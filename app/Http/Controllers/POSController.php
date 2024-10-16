<?php

namespace App\Http\Controllers;

use App\Jobs\SaleMailJob;
use App\Models\CashRegister;
use App\Models\CashRegisterTransaction;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\ProductBranch;
use App\Models\ProductBranchVariant;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\User;
use App\Utils\AccountUtil;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SaleUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    protected $saleUtil;

    protected $customerUtil;

    protected $accountUtil;

    protected $productStockUtil;

    protected $invoiceVoucherRefIdUtil;

    protected $userActivityLogUtil;

    protected $util;

    public function __construct(
        SaleUtil $saleUtil,
        CustomerUtil $customerUtil,
        AccountUtil $accountUtil,
        ProductStockUtil $productStockUtil,
        InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        UserActivityLogUtil $userActivityLogUtil,
        Util $util,
    ) {
        $this->saleUtil = $saleUtil;
        $this->customerUtil = $customerUtil;
        $this->accountUtil = $accountUtil;
        $this->productStockUtil = $productStockUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->util = $util;

    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('pos_all')) {
            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->saleUtil->posSaleTable($request);
        }

        $customers = DB::table('customers')->get(['id', 'name', 'phone']);

        return view('sales_app.pos.index', compact('customers'));
    }

    public function show($saleId)
    {
        $sale = Sale::with([
            'customer',
            'admin',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.product.warranty',
            'saleProducts.variant',
            'sale_payments',
            'sale_payments.paymentMethod:id,name',
        ])->where('id', $saleId)->first();

        return view('sales_app.pos.ajax_view.show', compact('sale'));
    }

    // Create pos view
    public function create()
    {
        if (! auth()->user()->can('pos_add')) {
            abort(403, 'Access Forbidden.');
        }

        $openedCashRegister = CashRegister::with('admin', 'cash_counter')
            ->where('admin_id', auth()->user()->id)
            ->where('status', 1)
            ->first();

        if ($openedCashRegister) {
            $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);
            $brands = DB::table('brands')->get(['id', 'name']);
            $customers = DB::table('customers')->where('status', 1)->get(['id', 'name', 'phone']);
            $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

            $accounts = DB::table('accounts')
                ->whereIn('accounts.account_type', [1, 2])
                ->orderBy('accounts.account_type', 'asc')
                ->get([
                    'accounts.id',
                    'accounts.name',
                    'accounts.account_number',
                    'accounts.account_type',
                    'accounts.balance',
                ]);

            $methods = PaymentMethod::with(['methodAccount'])->select('id', 'name')->get();

            return view('sales_app.pos.create', compact(
                'openedCashRegister',
                'categories',
                'brands',
                'customers',
                'price_groups',
                'accounts',
                'methods',
            ));
        } else {

            return redirect()->route('sales.cash.register.create');
        }
    }

    // Store pos sale
    public function store(Request $request)
    {
        //return $request->all();
        $this->validate($request, [
            'payment_method_id' => 'required',
            'account_id' => 'required',
            'sale_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'A Sale A/c is required sale',
            'account_id.required' => 'A debit A/c is required for sale',
            'payment_method_id.required' => 'A payment method is required for sale',
        ]);

        $settings = DB::table('general_settings')
            ->select(['id', 'business', 'prefix', 'reward_poing_settings', 'send_es_settings'])->first();

        $invoicePrefix = json_decode($settings->prefix, true)['sale_invoice'];

        $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        $branchInvoiceSchema = DB::table('invoice_schemas')
            ->select(
                'invoice_schemas.id as schema_id',
                'invoice_schemas.prefix',
                'invoice_schemas.format',
                'invoice_schemas.start_from',
            )->first();

        $invoicePrefix = '';
        if ($branchInvoiceSchema && $branchInvoiceSchema->prefix !== null) {

            $invoicePrefix = $branchInvoiceSchema->format == 2 ? date('Y').$branchInvoiceSchema->start_from : $branchInvoiceSchema->prefix.$branchInvoiceSchema->start_from;
        } else {

            $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
            $invoicePrefix = $defaultSchemas->format == 2 ? date('Y').$defaultSchemas->start_from : $defaultSchemas->prefix.$defaultSchemas->start_from;
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($request->action == 2 || $request->action == 4) {

            if (! $request->customer_id) {

                return response()->json(['errorMsg' => 'Listed customer is required for draft or quotation.']);
            }
        }

        if ($request->action == 1) {

            if ($request->paying_amount < $request->total_payable_amount && ! $request->customer_id) {

                return response()->json(['errorMsg' => 'Listed customer is required when sale is due or partial.']);
            }
        }

        if ($request->button_type == 1 && $request->paying_amount == 0) {

            return response()->json(['errorMsg' => 'If you want to sale in full credit, so click credit sale button.']);
        }

        // generate invoice ID
        $invoiceId = $invoiceId = str_pad($this->invoiceVoucherRefIdUtil->getLastId('sales'), 5, '0', STR_PAD_LEFT);

        $addSale = new Sale();
        $addSale->invoice_id = $invoicePrefix.$invoiceId;
        $addSale->admin_id = auth()->user()->id;
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->customer_id = $request->customer_id != 0 ? $request->customer_id : null;
        $addSale->status = $request->action;

        if ($request->action == 1) {

            $addSale->is_fixed_challen = 1;
        }

        if ($request->action == 5) {

            $holdInvoice = Sale::where('status', 5)->where('admin_id', auth()->user()->id)->get();

            if ($holdInvoice->count() == 5) {

                return response()->json(['errorMsg' => 'You can hold only 5 invoices.']);
            }
        }

        $addSale->date = date('d-m-Y');
        $addSale->sale_account_id = $request->sale_account_id;
        $addSale->time = date('h:i:s a');
        $addSale->report_date = date('Y-m-d H:i:s');
        $addSale->month = date('F');
        $addSale->year = date('Y');
        $addSale->total_item = $request->total_item;
        $addSale->net_total_amount = $request->net_total_amount;
        $addSale->order_discount_type = $request->order_discount_type;
        $addSale->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $addSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $addSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $addSale->shipment_charge = 0.00;
        $addSale->created_by = 2;
        //
        $changedAmount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
        $paidAmount = $request->paying_amount - $changedAmount;
        //
        $customer = Customer::where('id', $request->customer_id)->first();

        $invoicePayable = 0;
        if ($request->action == 1) {

            $changedAmount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
            $paidAmount = $request->paying_amount - $changedAmount;

            if ($request->previous_due != 0) {

                $invoicePayable = $request->total_invoice_payable;
                $addSale->total_payable_amount = $request->total_invoice_payable;
                if ($paidAmount >= $request->total_invoice_payable) {

                    $addSale->paid = $request->total_invoice_payable;
                    $addSale->due = 0.00;
                } elseif ($request->paying_amount == 0 && $request->total_payable_amount < 0) {

                    $addSale->paid = $request->paying_amount;
                    $calcDue = $request->total_payable_amount;
                    $addSale->due = $request->total_payable_amount;
                } elseif ($paidAmount < $request->total_invoice_payable) {

                    $addSale->paid = $request->paying_amount;
                    $calcDue = $request->total_invoice_payable - $request->paying_amount;
                    $addSale->due = $calcDue;
                }
            } else {

                $invoicePayable = $request->total_payable_amount;
                $addSale->total_payable_amount = $request->total_payable_amount;
                // $addSale->paid = $request->paying_amount - $changedAmount;
                $addSale->paid = $paidAmount;
                $addSale->change_amount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
                $addSale->due = $request->total_due >= 0 ? $request->total_due : 0.00;
            }

            $addSale->save();

            // Add sales A/c ledger
            $this->accountUtil->addAccountLedger(
                voucher_type_id: 1,
                date: $request->date,
                account_id: $request->sale_account_id,
                trans_id: $addSale->id,
                amount: $invoicePayable,
                balance_type: 'credit'
            );

            if ($customer) {

                if (json_decode($settings->reward_poing_settings, true)['enable_cus_point'] == '1') {

                    $customer->point = $customer->point - $request->pre_redeemed;
                    $customer->point = $customer->point + $this->calculateCustomerPoint($settings, $request->total_invoice_payable);
                    $customer->save();
                }
            }
        } else {

            $addSale->total_payable_amount = $request->total_invoice_payable;
            $addSale->save();
        }
        $addSale->save();

        // update product quantity and add sale product

        $__index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
            $addSaleProduct = new SaleProduct();
            $addSaleProduct->sale_id = $addSale->id;
            $addSaleProduct->product_id = $product_id;
            $addSaleProduct->product_variant_id = $variant_id;
            $addSaleProduct->quantity = $request->quantities[$__index];
            $addSaleProduct->unit_discount_type = $request->unit_discount_types[$__index];
            $addSaleProduct->unit_discount = $request->unit_discounts[$__index];
            $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
            $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
            $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
            $addSaleProduct->unit = $request->units[$__index];
            $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
            $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
            $addSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$__index];
            $addSaleProduct->description = $request->descriptions[$__index] ? $request->descriptions[$__index] : null;
            $addSaleProduct->subtotal = $request->subtotals[$__index];
            $addSaleProduct->save();
            $__index++;
        }

        if ($request->action == 1) {

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
                $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                $__index++;
            }
        }

        $sale = Sale::with([
            'customer',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.product.warranty',
            'saleProducts.variant',
            'admin',
        ])->where('id', $addSale->id)->first();

        if ($request->action == 1) {

            $this->saleUtil->addPurchaseSaleProductChain($sale, $stockAccountingMethod);

            $this->saleUtil->__getSalePaymentForAddSaleStore($request, $sale, $paymentInvoicePrefix);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 7, data_obj: $sale);
        }

        // Add cash register transaction
        $addCashRegisterTransaction = new CashRegisterTransaction();
        $addCashRegisterTransaction->cash_register_id = $request->cash_register_id;
        $addCashRegisterTransaction->sale_id = $sale->id;
        $addCashRegisterTransaction->save();
        // Add cash register transaction end..

        $previous_due = $request->previous_due;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        if (
            \App\Models\GeneralSetting::isEmailActive() &&
            json_decode($settings->send_es_settings, true)['send_inv_via_email'] == '1'
        ) {
            if ($customer && $customer->email) {

                dispatch(new SaleMailJob($customer->email, $sale));
            }
        }

        if (
            \App\Models\GeneralSetting::isSmsActive() &&
            json_decode($settings->send_es_settings, true)['send_notice_via_sms'] == '1'
        ) {

            if ($customer && $customer->phone) {

                $this->smsUtil->sendSaleSms($sale);
            }
        }

        if ($request->action == 1) {
            return view('sales_app.save_and_print_template.pos_sale_print', compact(
                'sale',
                'previous_due',
                'total_payable_amount',
                'paying_amount',
                'total_due',
                'change_amount'
            ));
        } elseif ($request->action == 2) {

            return view('sales_app.save_and_print_template.draft_print', compact('sale'));
        } elseif ($request->action == 4) {

            return view('sales_app.save_and_print_template.quotation_print', compact('sale'));
        } elseif ($request->action == 5) {

            return response()->json(['holdInvoiceMsg' => 'Invoice is holded.']);
        } elseif ($request->action == 6) {

            return response()->json(['suspendMsg' => 'Invoice is suspended.']);
        }
    }

    // Pick Hold invoice **requested by ajax**
    public function pickHoldInvoice()
    {
        $holdInvoices = Sale::where('status', 5)->where('admin_id', auth()->user()->id)->get();

        return view('sales_app.pos.ajax_view.hold_invoice_list', compact('holdInvoices'));
    }

    // Get invoice info by edit invoice method
    public function edit($saleId)
    {
        $sale = Sale::with('sale_products', 'customer', 'admin')->where('id', $saleId)->first();
        $categories = DB::table('categories')->where('parent_category_id', null)->get(['id', 'name']);
        $brands = DB::table('brands')->get(['id', 'name']);
        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $accounts = DB::table('accounts')
            ->whereIn('accounts.account_type', [1, 2])
            ->orderBy('accounts.account_type', 'asc')
            ->get([
                'accounts.id',
                'accounts.name',
                'accounts.account_number',
                'accounts.account_type',
                'accounts.balance',
            ]);

        $methods = DB::table('payment_methods')->select('id', 'name')->get();

        return view('sales_app.pos.edit', compact('sale', 'categories', 'brands', 'price_groups', 'accounts', 'methods'));
    }

    // Get invoice products **requested by ajax**
    public function invoiceProducts($saleId)
    {
        $invoiceProducts = SaleProduct::with(['sale', 'product', 'variant'])->where('sale_id', $saleId)->get();
        $qty_limits = [];

        foreach ($invoiceProducts as $sale_product) {

            if ($sale_product->product->is_manage_stock == 0) {

                $qty_limits[] = PHP_INT_MAX;
            } else {

                $productBranch = DB::table('product_branches')
                    ->where('product_id', $sale_product->product_id)->first();

                if ($sale_product->product->type == 2) {

                    $qty_limits[] = 500000;
                } elseif ($sale_product->product_variant_id) {

                    $productBranchVariant = DB::table('product_branch_variants')
                        ->where('product_branch_id', $productBranch->id)
                        ->where('product_id', $sale_product->product_id)
                        ->where('product_variant_id', $sale_product->product_variant_id)
                        ->first();
                    $qty_limits[] = $productBranchVariant->variant_quantity;
                } else {

                    $qty_limits[] = $productBranch->product_quantity;
                }
            }
        }

        return view('sales_app.pos.ajax_view.invoice_product_list', compact('invoiceProducts', 'qty_limits'));
    }

    // update pos sale
    public function update(Request $request)
    {
        $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();

        $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];

        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        $updateSale = Sale::with([
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.product.comboProducts',
        ])
            ->where('id', $request->sale_id)->first();

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'product table is empty']);
        }

        if ($updateSale->status == 1 && $request->action != 1) {

            return response()->json(['errorMsg' => 'Final sale you can not update to quotation, draft, hold invoice or Suspend.']);
        }

        if ($request->action == 1) {

            if ($request->paying_amount < $request->total_payable_amount && ! $updateSale->customer_id) {

                return response()->json(['errorMsg' => 'Listed Customer is required when sale is credit or partial payment.']);
            }
        }

        foreach ($updateSale->sale_payments as $sale_payment) {

            $storedAccountId = $sale_payment->account_id;
            $sale_payment->delete();
        }

        $updateSale->status = $request->action;
        $updateSale->sale_account_id = $request->sale_account_id;
        $updateSale->total_item = $request->total_item;
        $updateSale->net_total_amount = $request->net_total_amount;
        $updateSale->order_discount_type = 1;
        $updateSale->order_discount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $updateSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        $updateSale->shipment_charge = 0.00;
        $updateSale->total_payable_amount = $request->total_payable_amount;
        $updateSale->change_amount = $request->change_amount >= 0 ? $request->change_amount : 0.00;
        $updateSale->save();

        if ($updateSale->status == 1) {

            if ($request->sale_account_id) {

                // Update Sales A/c Ledger
                $this->accountUtil->updateAccountLedger(
                    voucher_type_id: 1,
                    date: date('Y-m-d'),
                    account_id: $request->sale_account_id,
                    trans_id: $updateSale->id,
                    amount: $request->total_payable_amount,
                    balance_type: 'credit'
                );
            }

            if ($updateSale->customer_id) {

                // Update Customer Ledger
                $this->customerUtil->updateCustomerLedger(
                    voucher_type_id: 1,
                    customer_id: $updateSale->customer_id,
                    date: date('Y-m-d', strtotime($updateSale->date)),
                    trans_id: $updateSale->id,
                    amount: $request->total_payable_amount
                );
            }
        }

        // Add product quantity for adjustment
        foreach ($updateSale->saleProducts as $saleProduct) {

            $saleProduct->delete_in_update = 1;
            $saleProduct->save();
        }

        $__index = 0;
        foreach ($request->product_ids as $product_id) {

            $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;

            $saleProduct = SaleProduct::where('sale_id', $updateSale->id)
                ->where('product_id', $product_id)
                ->where('product_variant_id', $variant_id)
                ->first();

            if ($saleProduct) {

                $saleProduct->quantity = $request->quantities[$__index];
                $saleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
                $saleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
                $saleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$__index];
                $saleProduct->unit_discount_type = $request->unit_discount_types[$__index];
                $saleProduct->unit_discount = $request->unit_discounts[$__index];
                $saleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
                $saleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
                $saleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
                $saleProduct->unit = $request->units[$__index];
                $saleProduct->subtotal = $request->subtotals[$__index];
                $saleProduct->description = $request->descriptions[$__index];
                $saleProduct->delete_in_update = 0;
                $saleProduct->save();
            } else {

                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $updateSale->id;
                $addSaleProduct->product_id = $product_id;
                $addSaleProduct->product_variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;
                $addSaleProduct->quantity = $request->quantities[$__index];
                $addSaleProduct->unit_cost_inc_tax = $request->unit_costs_inc_tax[$__index];
                $addSaleProduct->unit_price_exc_tax = $request->unit_prices_exc_tax[$__index];
                $addSaleProduct->unit_price_inc_tax = $request->unit_prices_inc_tax[$__index];
                $addSaleProduct->unit_discount_type = $request->unit_discount_types[$__index];
                $addSaleProduct->unit_discount = $request->unit_discounts[$__index];
                $addSaleProduct->unit_discount_amount = $request->unit_discount_amounts[$__index];
                $addSaleProduct->unit_tax_percent = $request->unit_tax_percents[$__index];
                $addSaleProduct->unit_tax_amount = $request->unit_tax_amounts[$__index];
                $addSaleProduct->unit = $request->units[$__index];
                $addSaleProduct->subtotal = $request->subtotals[$__index];
                $addSaleProduct->description = $request->descriptions[$__index];
                $addSaleProduct->save();
            }

            $__index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::where('sale_id', $updateSale->id)
            ->where('delete_in_update', 1)->get();

        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

            $storedProductId = $deleteNotFoundSaleProduct->product_id;
            $storedVariantId = $deleteNotFoundSaleProduct->product_variant_id;
            $deleteNotFoundSaleProduct->delete();
            $this->productStockUtil->adjustMainProductAndVariantStock($storedProductId, $storedVariantId);
            $this->productStockUtil->adjustBranchStock($storedProductId, $storedVariantId);
        }

        $saleProducts = DB::table('sale_products')->where('sale_id', $updateSale->id)->get();

        foreach ($saleProducts as $saleProduct) {

            $this->productStockUtil->adjustMainProductAndVariantStock($saleProduct->product_id, $saleProduct->product_variant_id);
            $this->productStockUtil->adjustBranchStock($saleProduct->product_id, $saleProduct->product_variant_id);
        }

        // Add new payment
        if ($request->paying_amount > 0) {

            $__paymentInvoicePrefix = $paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPV';

            $addPaymentGetId = $this->saleUtil->addPaymentGetId(
                invoicePrefix: $__paymentInvoicePrefix,
                request: $request,
                payingAmount: $request->paying_amount,
                invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                saleId: $updateSale->id,
                customerPaymentId: null
            );

            if ($request->account_id) {

                // Add bank/cash-in-hand A/c ledger
                $this->accountUtil->addAccountLedger(
                    voucher_type_id: 10,
                    date: date('Y-m-d', strtotime($updateSale->date)),
                    account_id: $request->account_id,
                    trans_id: $addPaymentGetId,
                    amount: $request->paying_amount,
                    balance_type: 'debit'
                );
            }
        }

        $sale = Sale::with(['customer', 'saleProducts', 'saleProducts.product', 'saleProducts.variant'])
            ->where('id', $updateSale->id)
            ->first();

        // Update customer due
        if ($request->action == 1) {

            $adjustedSale = $this->saleUtil->adjustSaleInvoiceAmounts($updateSale);

            $this->saleUtil->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 7, data_obj: $adjustedSale);
        }

        $previous_due = 0;
        $total_payable_amount = $request->total_payable_amount;
        $paying_amount = $request->paying_amount;
        $total_due = $request->total_due;
        $change_amount = $request->change_amount;

        if ($request->action == 1) {

            return view('sales_app.save_and_print_template.pos_sale_print', compact(
                'sale',
                'previous_due',
                'total_payable_amount',
                'paying_amount',
                'total_due',
                'change_amount'
            ));
        } elseif ($request->action == 2) {

            $sale = Sale::with(['customer', 'saleProducts', 'saleProducts.product', 'saleProducts.variant'])->where('id', $updateSale->id)->first();

            return view('sales_app.save_and_print_template.draft_print', compact('sale'));
        } elseif ($request->action == 4) {

            $sale = Sale::with(['customer', 'saleProducts', 'saleProducts.product', 'saleProducts.variant'])->where('id', $updateSale->id)->first();

            return view('sales_app.save_and_print_template.quotation_print', compact('sale'));
        } elseif ($request->action == 5) {

            return response()->json(['holdInvoiceMsg' => 'Holded Invoice is updated successfully.']);
        } elseif ($request->action == 6) {

            return response()->json(['suspendMsg' => 'Suspended invoice is updated.']);
        }
    }

    // Get all suspended sales ** requested by ajax **
    public function suspendedList()
    {
        $sales = Sale::with('customer')
            ->where('admin_id', auth()->user()->id)
            ->where('status', 6)
            ->orderBy('id', 'desc')
            ->limit(20)
            ->get();

        return view('sales_app.pos.ajax_view.suspended_sale_list', compact('sales'));
    }

    // Get recent added product which has been added from pos
    public function getRecentProduct($product_id)
    {
        $product = ProductBranch::with(['product', 'product.tax', 'product.unit'])
            ->where('product_id', $product_id)
            ->first();

        if ($product->product_quantity > 0) {

            return view('sales_app.pos.ajax_view.recent_product_view', compact('product'));
        } else {

            return response()->json([
                'errorMsg' => 'Product is not added in the sale table, cause you did not add any number of opening stock in this Location/Shop.',
            ]);
        }
    }

    public function addQuickCustomerModal()
    {
        $customerGroups = DB::table('customer_groups')->select('id', 'group_name')->get();
        $users = User::where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.ajax_view.quick_add_customer', compact('customerGroups', 'users'));
    }

    //Add customer from pos
    public function addCustomer(Request $request)
    {
        return $this->util->storeQuickCustomer($request);
    }

    // Get pos product list
    public function posProductList(Request $request)
    {
        $products = '';
        $query = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('accounts as taxes', 'products.tax_ac_id', 'taxes.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->where('products.is_for_sale', 1)
            ->where('products.status', 1);

        if ($request->category_id) {

            $query->where('products.category_id', $request->category_id);
        }

        if ($request->brand_id) {

            $query->where('products.brand_id', $request->brand_id);
        }

        if (! $request->category_id && ! $request->brand_id) {

            $query->orderBy('products.id', 'DESC')->limit(90);
        }

        $products = $query->select(
            'products.id',
            'products.name',
            'products.product_code',
            'products.is_combo',
            'products.is_manage_stock',
            'products.is_purchased',
            'products.is_show_emi_on_pos',
            'products.is_variant',
            'products.product_cost',
            'products.product_cost_with_tax',
            'products.product_price',
            'products.profit',
            'products.quantity',
            'products.tax_ac_id',
            'products.tax_type',
            'products.thumbnail_photo',
            'products.type',
            'products.unit_id',
            'taxes.id as tax_ac_id',
            'taxes.tax_name',
            'taxes.tax_percent',
            'product_variants.id as variant_id',
            'product_variants.variant_name',
            'product_variants.variant_code',
            'product_variants.variant_cost',
            'product_variants.variant_cost_with_tax',
            'product_variants.variant_price',
            'units.id as unit_id',
            'units.name as unit_name',
        )->get();

        return view('sales_app.pos.ajax_view.select_product_list', compact('products'));
    }

    public function branchStock(Request $request)
    {
        $products = '';
        $products = DB::table('product_branches')
            ->leftJoin('product_branch_variants', 'product_branches.id', 'product_branch_variants.product_branch_id')
            ->leftJoin('product_variants', 'product_branch_variants.product_variant_id', 'product_variants.id')
            ->leftJoin('products', 'product_branches.product_id', 'products.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->select(
                'product_branches.product_quantity',
                'products.name as pro_name',
                'products.product_code as pro_code',
                'product_variants.variant_name as var_name',
                'product_variants.variant_code as var_code',
                'product_branch_variants.variant_quantity',
                'units.code_name as u_code',
            )->get();

        return view('sales_app.pos.ajax_view.stock', compact('products'));
    }

    public function searchExchangeableInv(Request $request)
    {
        $sale = Sale::with([
            'customer',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
            'sale_payments',
        ])->where('invoice_id', $request->invoice_id)->first();

        if ($sale) {

            return view('sales_app.pos.ajax_view.exchange_able_invoice', compact('sale'));
        } else {

            return response()->json(['errorMsg' => 'Invoice Not Fount']);
        }
    }

    public function prepareExchange(Request $request)
    {
        //return $request->all();
        $sale_id = $request->sale_id;
        $sale = Sale::where('id', $sale_id)->first();
        $ex_quantities = $request->ex_quantities;
        $product_row_ids = $request->product_row_ids;
        $sold_prices_inc_tax = $request->sold_prices_inc_tax;
        $sold_quantities = $request->sold_quantities;

        $index = 0;
        foreach ($ex_quantities as $ex_quantity) {

            $__ex_qty = $ex_quantity ? $ex_quantity : 0;
            $soldProduct = SaleProduct::where('id', $product_row_ids[$index])->first();

            if ($__ex_qty != 0) {

                $soldProduct->ex_quantity = $__ex_qty;
                $soldProduct->ex_status = 1;
                $soldProduct->save();
            } else {

                $soldProduct->ex_status = 0;
                $soldProduct->save();
            }
            $index++;
        }

        $ex_items = SaleProduct::with('product', 'variant', 'sale')
            ->where('sale_id', $sale->id)
            ->where('ex_status', 1)->get();

        $qty_limits = [];
        foreach ($ex_items as $sale_product) {

            $productBranch = ProductBranch::where('product_id', $sale_product->product_id)->first();

            if ($sale_product->product->type == 2) {

                $qty_limits[] = 500000;
            } elseif ($sale_product->product_variant_id) {

                $productBranchVariant = ProductBranchVariant::where('product_branch_id', $productBranch->id)->where('product_id', $sale_product->product_id)
                    ->where('product_variant_id', $sale_product->product_variant_id)
                    ->first();

                $qty_limits[] = $productBranchVariant->variant_quantity;
            } else {

                $qty_limits[] = $productBranch->product_quantity;
            }
        }

        return response()->json([
            'sale' => $sale,
            'ex_items' => $ex_items,
            'qty_limits' => $qty_limits,
        ]);
    }

    public function exchangeConfirm(Request $request)
    {
        if ($request->action != 1) {

            return response()->json(['errorMsg' => 'Can not create another entry when item exchange in going on.']);
        }

        $updateSale = Sale::with('customer')->where('id', $request->ex_sale_id)->first();

        if (
            $request->total_due > 0 &&
            $updateSale->customer_id == null
        ) {

            return response()->json([
                'errorMsg' => 'Listed Customer is required when exchange is due or partial.',
            ]);
        }

        if (
            $request->total_due > 0 &&
            $request->button_type != 0 &&
            $request->paying_amount == 0
        ) {

            return response()->json(['errorMsg' => 'If you want to sale in full credit, so click credit sale button.']);
        }

        $change = $request->change_amount > 0 ? $request->change_amount : 0;
        $updateSale->net_total_amount = $updateSale->net_total_amount + $request->net_total_amount;
        $updateSale->total_payable_amount = $updateSale->total_payable_amount + $request->total_payable_amount;
        // $updateSale->order_discount_type = 1;
        // $updateSale->order_discount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        // $updateSale->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0.00;
        // $updateSale->order_tax_percent = $request->order_tax ? $request->order_tax : 0.00;
        // $updateSale->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0.00;
        //$updateSale->paid = $updateSale->paid + $request->paying_amount - $change;
        //$updateSale->due = $updateSale->due + $request->total_due;
        $updateSale->change_amount = $updateSale->change_amount + $change;
        $updateSale->ex_status = 1;
        $updateSale->save();

        if ($updateSale->sale_account_id) {

            // Update Sales A/c Ledger
            $this->accountUtil->updateAccountLedger(
                voucher_type_id: 1,
                date: $updateSale->date,
                account_id: $updateSale->sale_account_id,
                trans_id: $updateSale->id,
                amount: $updateSale->total_payable_amount,
                balance_type: 'credit'
            );
        }

        if ($updateSale->customer_id) {

            // Update customer ledger
            $this->customerUtil->updateCustomerLedger(
                voucher_type_id: 1,
                customer_id: $updateSale->customer_id,
                date: $updateSale->date,
                trans_id: $updateSale->id,
                amount: $updateSale->total_payable_amount
            );
        }

        $product_ids = $request->product_ids;
        $variant_ids = $request->variant_ids;
        $descriptions = $request->descriptions;
        $quantities = $request->quantities;
        $unit_costs_inc_tax = $request->unit_costs_inc_tax;
        $subtotals = $request->subtotals;
        $unit_discount_types = $request->unit_discount_types;
        $unit_discounts = $request->unit_discounts;
        $unit_discount_amounts = $request->unit_discount_amounts;
        $unit_tax_percents = $request->unit_tax_percents;
        $unit_tax_amounts = $request->unit_tax_amounts;
        $unit_prices_inc_tax = $request->unit_prices_inc_tax;
        $units = $request->units;

        $index = 0;
        foreach ($product_ids as $product_id) {

            $variant_id = $variant_ids[$index] != 'noid' ? $variant_ids[$index] : null;
            $saleProduct = SaleProduct::where('sale_id', $request->ex_sale_id)
                ->where('product_id', $product_id)->where('product_variant_id', $variant_id)->first();

            if ($saleProduct) {

                if ($saleProduct->ex_status == 1) {

                    //$saleProduct->quantity = $saleProduct->quantity + $quantities[$index];
                    $saleProduct->quantity = $quantities[$index];
                    $saleProduct->ex_quantity = $quantities[$index];
                    $saleProduct->description = $descriptions[$index];
                    //$saleProduct->subtotal = $saleProduct->subtotal + $subtotals[$index];
                    $saleProduct->subtotal = $subtotals[$index];
                    $saleProduct->ex_status = 2;
                    $saleProduct->save();
                } else {

                    $saleProduct->sale_id = $request->ex_sale_id;
                    $saleProduct->product_id = $product_ids[$index];
                    $saleProduct->product_variant_id = $variant_id;
                    $saleProduct->quantity = $quantities[$index];
                    $saleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                    $saleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                    $saleProduct->unit_discount_type = $unit_discount_types[$index];
                    $saleProduct->unit_discount = $unit_discounts[$index];
                    $saleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                    $saleProduct->unit_tax_percent = $unit_tax_percents[$index];
                    $saleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                    $saleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                    $saleProduct->unit = $units[$index];
                    $saleProduct->description = $descriptions[$index];
                    $saleProduct->subtotal = $subtotals[$index];
                    $saleProduct->save();
                }
            } else {

                $addSaleProduct = new SaleProduct();
                $addSaleProduct->sale_id = $request->ex_sale_id;
                $addSaleProduct->product_id = $product_ids[$index];
                $addSaleProduct->product_variant_id = $variant_id;
                $addSaleProduct->quantity = $quantities[$index];
                $addSaleProduct->unit_cost_inc_tax = $unit_costs_inc_tax[$index];
                $addSaleProduct->unit_price_inc_tax = $unit_prices_inc_tax[$index];
                $addSaleProduct->unit_discount_type = $unit_discount_types[$index];
                $addSaleProduct->unit_discount = $unit_discounts[$index];
                $addSaleProduct->unit_discount_amount = $unit_discount_amounts[$index];
                $addSaleProduct->unit_tax_percent = $unit_tax_percents[$index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $addSaleProduct->unit_tax_amount = $unit_tax_amounts[$index];
                $addSaleProduct->unit = $units[$index];
                $addSaleProduct->description = $descriptions[$index];
                $addSaleProduct->subtotal = $subtotals[$index];
                $addSaleProduct->save();
            }

            $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);
            $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
            $index++;
        }

        $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($settings->prefix, true)['sale_payment'];
        $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

        // Add new payment
        if ($request->paying_amount > 0) {

            $__paymentInvoicePrefix = $paymentInvoicePrefix != null ? $paymentInvoicePrefix : 'SPV';

            $addPaymentGetId = $this->saleUtil->addPaymentGetId(
                invoicePrefix: $__paymentInvoicePrefix,
                request: $request,
                payingAmount: $request->paying_amount,
                invoiceId: $this->invoiceVoucherRefIdUtil->getLastId('sale_payments'),
                saleId: $updateSale->id,
                customerPaymentId: null
            );

            if ($updateSale->customer_id) {

                // add customer ledger
                $this->customerUtil->updateCustomerLedger(
                    voucher_type_id: 3,
                    customer_id: $updateSale->customer_id,
                    date: date('Y-m-d'),
                    trans_id: $addPaymentGetId,
                    amount: $request->paying_amount
                );
            }
        }

        $this->saleUtil->adjustSaleInvoiceAmounts($updateSale);

        $sale = Sale::with([
            'customer',
            'saleProducts',
            'saleProducts.product',
            'saleProducts.variant',
        ])->where('id', $request->ex_sale_id)->first();

        $previous_due = 0;
        $total_payable_amount = $sale->total_payable_amount;
        $paying_amount = $sale->paid;
        $total_due = $sale->due;
        $change_amount = $change;

        $this->saleUtil->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);

        return view('sales_app.save_and_print_template.pos_sale_print', compact(
            'sale',
            'previous_due',
            'total_payable_amount',
            'paying_amount',
            'total_due',
            'change_amount'
        ));
    }

    private function calculateCustomerPoint($point_settings, $total_amount)
    {
        $enable_cus_point = json_decode($point_settings->reward_poing_settings, true)['enable_cus_point'];

        (int) $amount_for_unit_rp = json_decode($point_settings->reward_poing_settings, true)['amount_for_unit_rp'];

        (int) $min_order_total_for_rp = json_decode($point_settings->reward_poing_settings, true)['min_order_total_for_rp'];

        (int) $max_rp_per_order = json_decode($point_settings->reward_poing_settings, true)['max_rp_per_order'];

        if ($enable_cus_point == '1') {

            if ($min_order_total_for_rp && $total_amount >= $min_order_total_for_rp) {

                if ($amount_for_unit_rp != 0) {

                    $calc_point = $total_amount / $amount_for_unit_rp;
                    $__net_point = (int) $calc_point;

                    if ($max_rp_per_order && $__net_point > $max_rp_per_order) {

                        return $max_rp_per_order;
                    } else {

                        return $__net_point;
                    }
                } else {

                    return 0;
                }
            } else {

                return 0;
            }
        } else {

            return 0;
        }
    }
}
