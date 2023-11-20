<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\PaymentMethod;
use App\Models\Purchase;
use App\Models\PurchaseOrderProduct;
use App\Models\PurchaseOrderProductReceive;
use App\Models\PurchaseProduct;
use App\Utils\AccountLedgerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\PaymentDescriptionReferenceUtil;
use App\Utils\PaymentDescriptionUtil;
use App\Utils\PaymentUtil;
use App\Utils\ProductStockUtil;
use App\Utils\ProductUtil;
use App\Utils\PurchaseOrderUtil;
use App\Utils\PurchaseUtil;
use App\Utils\RequisitionUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderReceiveController extends Controller
{
    protected $paymentUtil;

    protected $paymentDescriptionUtil;

    protected $paymentDescriptionReferenceUtil;

    protected $accountLedgerUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $productStockUtil;

    protected $purchaseOrderUtil;

    protected $purchaseUtil;

    protected $productUtil;

    protected $requisitionUtil;

    protected $userActivityLogUtil;

    public function __construct(
        PaymentUtil $paymentUtil,
        PaymentDescriptionUtil $paymentDescriptionUtil,
        PaymentDescriptionReferenceUtil $paymentDescriptionReferenceUtil,
        AccountLedgerUtil $accountLedgerUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        ProductStockUtil $productStockUtil,
        PurchaseOrderUtil $purchaseOrderUtil,
        PurchaseUtil $purchaseUtil,
        ProductUtil $productUtil,
        RequisitionUtil $requisitionUtil,
        UserActivityLogUtil $userActivityLogUtil,
    ) {
        $this->paymentUtil = $paymentUtil;
        $this->paymentDescriptionUtil = $paymentDescriptionUtil;
        $this->paymentDescriptionReferenceUtil = $paymentDescriptionReferenceUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->productStockUtil = $productStockUtil;
        $this->purchaseOrderUtil = $purchaseOrderUtil;
        $this->purchaseUtil = $purchaseUtil;
        $this->productUtil = $productUtil;
        $this->requisitionUtil = $requisitionUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function processReceive($purchaseId)
    {
        $purchase = Purchase::with([
            'supplier:id,name,phone',
            'purchase_order_products',
            'purchase_order_products.receives',
            'purchase_order_products.product',
            'purchase_order_products.variant',
        ])->where('id', $purchaseId)->first();

        $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

        $accounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->leftJoin('banks', 'accounts.bank_id', 'banks.id')
            ->whereIn('account_groups.sub_sub_group_number', [1, 2, 11])
            ->select('accounts.id', 'accounts.name', 'accounts.account_number', 'accounts.account_type', 'accounts.balance', 'banks.name as bank')
            ->orderBy('account_groups.sub_sub_group_number', 'asc')->get();

        $warehouses = DB::table('warehouses')->get();

        return view('procurement.orders.receive.process_to_receive', compact('purchase', 'warehouses', 'accounts', 'methods'));
    }

    public function processReceiveStore(Request $request, $purchaseId, CodeGenerationServiceInterface $codeGenerationService)
    {
        if (isset($request->paying_amount) && $request->paying_amount > 0) {

            $this->validate($request, ['account_id' => 'required'], ['account_id.required' => 'Credit A/c is required.']);
        }

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly
            $settings = DB::table('general_settings')->select(['id', 'prefix', 'purchase'])->first();
            $paymentVoucherPrefix = json_decode($settings->prefix, true)['purchase_payment'];
            $isEditProductPrice = json_decode($settings->purchase, true)['is_edit_pro_price'];

            $order = Purchase::where('id', $purchaseId)->first();
            $order->po_pending_qty = $request->total_pending;
            $order->po_received_qty = $request->total_received;
            $order->is_purchased = $request->total_received > 0 ? 1 : $order->is_purchased;
            $order->save();

            // Update Purchase order Product
            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$index] != 'noid' ? $request->variant_ids[$index] : null;
                $purchaseOrderProduct = PurchaseOrderProduct::where('purchase_id', $order->id)
                    ->where('product_id', $product_id)
                    ->where('product_variant_id', $variant_id)->first();

                if ($purchaseOrderProduct) {

                    $purchaseOrderProduct->pending_quantity = (float) $request->pending_quantities[$index];
                    $purchaseOrderProduct->received_quantity = (float) $request->received_quantities[$index];
                    $purchaseOrderProduct->save();
                }

                $index++;
            }

            if (isset($request->or_receive_rows)) {

                foreach ($request->or_receive_rows as $id => $value) {

                    $valueIndex = 0;
                    foreach ($value['purchase_challan'] as $challan) {

                        $updateReceiveRow = PurchaseOrderProductReceive::where('id', $value['receive_id'][$valueIndex])->first();

                        if ($updateReceiveRow) {

                            $updateReceiveRow->purchase_challan = $challan;
                            $updateReceiveRow->lot_number = $value['lot_number'][$valueIndex];
                            $updateReceiveRow->received_date = $value['received_date'][$valueIndex];
                            $updateReceiveRow->report_date = date('Y-m-d H:i:s', strtotime($value['received_date'][$valueIndex].date(' H:i:s')));
                            $updateReceiveRow->qty_received = $value['qty_received'][$valueIndex];
                            $updateReceiveRow->save();
                        } else {

                            if ($value['qty_received'][$valueIndex] && $value['qty_received'][$valueIndex] > 0) {

                                $addReceiveRow = new PurchaseOrderProductReceive();
                                $addReceiveRow->order_product_id = $id;
                                $addReceiveRow->purchase_challan = $challan;
                                $addReceiveRow->lot_number = $value['lot_number'][$valueIndex];
                                $addReceiveRow->received_date = $value['received_date'][$valueIndex];
                                $addReceiveRow->report_date = date('Y-m-d H:i:s', strtotime($value['received_date'][$valueIndex].date(' H:i:s')));
                                $addReceiveRow->qty_received = $value['qty_received'][$valueIndex];
                                $addReceiveRow->save();
                            }
                        }

                        $valueIndex++;
                    }
                }
            }

            // Add received product to purchase products table
            $purchase_order_products = DB::table('purchase_order_products')->where('purchase_id', $order->id)->get();

            foreach ($purchase_order_products as $purchase_order_product) {

                $purchaseProduct = PurchaseProduct::where('purchase_id', $order->id)->where('product_order_product_id', $purchase_order_product->id)->first();

                if ($purchaseProduct) {

                    $purchaseProduct->quantity = $purchase_order_product->received_quantity;
                    $purchaseProduct->unit = $purchase_order_product->unit;
                    $purchaseProduct->unit_cost = $purchase_order_product->unit_cost;
                    $purchaseProduct->unit_discount = $purchase_order_product->unit_discount;
                    $purchaseProduct->unit_cost_with_discount = $purchase_order_product->unit_cost_with_discount;
                    $purchaseProduct->unit_tax_percent = $purchase_order_product->unit_tax_percent;
                    $purchaseProduct->unit_tax = $purchase_order_product->unit_tax;
                    $purchaseProduct->net_unit_cost = $purchase_order_product->net_unit_cost;
                    $purchaseProduct->subtotal = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost_with_discount;
                    $purchaseProduct->line_total = $purchase_order_product->received_quantity * $purchase_order_product->net_unit_cost;
                    $purchaseProduct->profit_margin = $purchase_order_product->profit_margin;
                    $purchaseProduct->selling_price = $purchase_order_product->selling_price;
                    $purchaseProduct->lot_no = $purchase_order_product->lot_no;
                    $purchaseProduct->save();

                    // update product and variant Price & quantity
                    if ($order->is_last_created == 1) {

                        $this->productUtil->updateProductAndVariantPrice($purchase_order_product->product_id, $purchase_order_product->product_variant_id, $purchase_order_product->unit_cost_with_discount, $purchase_order_product->net_unit_cost, $purchase_order_product->profit_margin, $purchase_order_product->selling_price, $isEditProductPrice, $order->is_last_created);
                    }

                    $this->purchaseUtil->adjustPurchaseLeftQty($purchaseProduct);
                } else {

                    if ($purchase_order_product->received_quantity != 0) {

                        $addPurchaseProduct = new PurchaseProduct();
                        $addPurchaseProduct->purchase_id = $order->id;
                        $addPurchaseProduct->product_order_product_id = $purchase_order_product->id;
                        $addPurchaseProduct->product_id = $purchase_order_product->product_id;
                        $addPurchaseProduct->product_variant_id = $purchase_order_product->product_variant_id;
                        $addPurchaseProduct->quantity = $purchase_order_product->received_quantity;
                        $addPurchaseProduct->left_qty = $purchase_order_product->received_quantity;
                        $addPurchaseProduct->unit = $purchase_order_product->unit;
                        $addPurchaseProduct->unit_cost = $purchase_order_product->unit_cost;
                        $addPurchaseProduct->unit_discount = $purchase_order_product->unit_discount;
                        $addPurchaseProduct->unit_cost_with_discount = $purchase_order_product->unit_cost_with_discount;
                        $addPurchaseProduct->unit_tax_percent = $purchase_order_product->unit_tax_percent;
                        $addPurchaseProduct->unit_tax = $purchase_order_product->unit_tax;
                        $addPurchaseProduct->net_unit_cost = $purchase_order_product->net_unit_cost;
                        $addPurchaseProduct->subtotal = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost_with_discount;
                        $addPurchaseProduct->line_total = $purchase_order_product->received_quantity * $purchase_order_product->unit_cost;
                        $addPurchaseProduct->profit_margin = $purchase_order_product->profit_margin;
                        $addPurchaseProduct->selling_price = $purchase_order_product->selling_price;
                        $addPurchaseProduct->lot_no = $purchase_order_product->lot_no;
                        $addPurchaseProduct->description = $purchase_order_product->description;
                        $addPurchaseProduct->save();

                        $this->productUtil->updateProductAndVariantPrice($purchase_order_product->product_id, $purchase_order_product->product_variant_id, $purchase_order_product->unit_cost_with_discount, $purchase_order_product->net_unit_cost, $purchase_order_product->profit_margin, $purchase_order_product->selling_price, $isEditProductPrice, $order->is_last_created);
                    }
                }
            }

            if ($order->requisition_id) {

                $this->requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($order->requisition_id);
                $this->requisitionUtil->updateRequisitionLeftQty($order->requisition_id);
            }

            // Add purchase payment
            if ($request->paying_amount > 0) {

                $addPayment = $this->paymentUtil->addPayment(date: $request->date, remarks: $request->payment_note, paymentType: 2, voucherGenerator: $codeGenerationService, voucherPrefix: $paymentVoucherPrefix, debitTotal: $request->paying_amount, creditTotal: $request->paying_amount, purchaseRefId: $order->id);

                // Add Payment Description Debit Entry
                $addPaymentDebitDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->supplier_account_id, paymentMethodId: $request->payment_method_id, amountType: 'dr', amount: $request->paying_amount);

                $this->paymentDescriptionReferenceUtil->addPaymentDescriptionReferences(paymentDescriptionId: $addPaymentDebitDescription->id, refIdColNames: ['purchase_id'], refIds: [$order->id], amounts: [$request->paying_amount]);

                //Add Debit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $order->supplier_account_id, trans_id: $addPaymentDebitDescription->id, amount: $request->paying_amount, amount_type: 'debit');

                // Add Payment Description Credit Entry
                $addPaymentCreditDescription = $this->paymentDescriptionUtil->addPaymentDescription(paymentId: $addPayment->id, accountId: $request->account_id, paymentMethodId: $request->payment_method_id, amountType: 'cr', amount: $request->paying_amount);

                //Add Credit Ledger Entry
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 9, date: $request->date, account_id: $request->account_id, trans_id: $addPaymentCreditDescription->id, amount: $request->paying_amount, amount_type: 'credit');

                // $orderPayment = DB::table('purchase_payments')
                //     ->where('purchase_payments.id', $addPurchasePaymentGetId)
                //     ->leftJoin('suppliers', 'purchase_payments.supplier_id', 'suppliers.id')
                //     ->leftJoin('payment_methods', 'purchase_payments.payment_method_id', 'payment_methods.id')
                //     ->leftJoin('purchases', 'purchase_payments.purchase_id', 'purchases.id')
                //     ->select(
                //         'purchase_payments.invoice_id as voucher_no',
                //         'purchase_payments.date',
                //         'purchase_payments.paid_amount',
                //         'suppliers.name as supplier',
                //         'suppliers.phone',
                //         'payment_methods.name as method',
                //         'purchases.invoice_id as agp',
                //     )->first();

                // $this->userActivityLogUtil->addLog(action: 1, subject_type: 28, data_obj: $orderPayment);
            }

            $purchaseProducts = DB::table('purchase_products')->where('purchase_id', $order->id)->get();

            if (count($purchaseProducts) > 0) {

                foreach ($purchaseProducts as $purchaseProduct) {

                    $this->productStockUtil->adjustMainProductAndVariantStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id);

                    if ($order->warehouse_id) {

                        $this->productStockUtil->addWarehouseProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                        $this->productStockUtil->adjustWarehouseStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id, $request->warehouse_id);
                    } else {

                        $this->productStockUtil->addBranchProduct($purchaseProduct->product_id, $purchaseProduct->product_variant_id);
                        $this->productStockUtil->adjustBranchStock($purchaseProduct->product_id, $purchaseProduct->product_variant_id);
                    }
                }
            }

            // $this->purchaseUtil->adjustPurchaseInvoiceAmounts($order);

            $this->purchaseOrderUtil->updatePoQtyAndStatusPortion($order);
            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully order receiving is modified.');
    }
}
