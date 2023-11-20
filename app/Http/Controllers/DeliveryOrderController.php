<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Models\GatePass;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Models\WeightScale;
use App\Utils\AccountLedgerUtil;
use App\Utils\DayBookUtil;
use App\Utils\DeliveryOrderProductUtil;
use App\Utils\DeliveryOrderUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SaleUtil;
use App\Utils\SmsUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function __construct(
        private SaleUtil $saleUtil,
        private DeliveryOrderUtil $deliveryOrderUtil,
        private DeliveryOrderProductUtil $deliveryOrderProductUtil,
        private SmsUtil $smsUtil,
        private ProductStockUtil $productStockUtil,
        private AccountLedgerUtil $accountLedgerUtil,
        private DayBookUtil $dayBookUtil,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->deliveryOrderUtil->doTable($request);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.delivery_order.index', compact('customerAccounts', 'users'));
    }

    public function show($doId)
    {
        $do = Sale::with([
            'customer:id,name,phone,address',
            'doBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.saleUnit:id,code_name,base_unit_id,base_unit_id,base_unit_multiplier',
            'saleProducts.saleUnit.baseUnit:id,code_name',
            'saleProducts.product:id,name,product_code',
            'saleProducts.product.warranty',
            'saleProducts.variant:id,variant_name,variant_code',

            'references:id,payment_description_id,sale_id,amount',
            'references.paymentDescription:id,payment_id',
            'references.paymentDescription.payment:id,voucher_no,date,payment_type',
            'references.paymentDescription.payment.descriptions:id,payment_id,account_id,payment_method_id',
            'references.paymentDescription.payment.descriptions.paymentMethod:id,name',
            'references.paymentDescription.payment.descriptions.account:id,name,account_number,account_group_id,bank_id,bank_branch',
            'references.paymentDescription.payment.descriptions.account.bank:id,name',
            'references.paymentDescription.payment.descriptions.account.group:id,sub_sub_group_number',
        ])->where('id', $doId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($doId);

        return view('sales_app.delivery_order.ajax_view.show', compact('do', 'customerCopySaleProducts'));
    }

    public function edit($saleId)
    {
        if (! auth()->user()->can('do_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();

        $do = Sale::with([
            'saleProducts',
            'customer',
            'saleProducts.warehouse',
            'saleProducts.product',
            'saleProducts.product.unit:id,name,code_name',
            'saleProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.variant',
            'saleProducts.saleUnit:id,name,base_unit_multiplier',
            'saleProducts.product.comboProducts',
            'saleProducts.product.comboProducts.parentProduct',
            'saleProducts.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        return view('sales_app.delivery_order.edit', compact('do', 'price_groups', 'saleAccounts', 'taxAccounts'));
    }

    public function update(Request $request, $saleId)
    {
        if (! auth()->user()->can('do_edit')) {

            return response()->json(['errorMsg' => 'Access Forbidden']);
        }

        $this->validate($request, [
            'date' => 'required|date',
            'sale_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if ($request->expire_date) {

            $this->validate($request, ['expire_time' => 'required']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();

            if ($request->product_ids == null) {

                return response()->json(['errorMsg' => 'Product table is empty']);
            }

            $do = Sale::with([
                'saleProducts',
                'saleProducts.product',
                'saleProducts.variant',
                'saleProducts.product.comboProducts',
            ])->where('id', $saleId)->first();

            foreach ($do->saleProducts as $saleProduct) {

                $saleProduct->delete_in_update = 1;
                $saleProduct->save();
            }

            $updateDo = $this->deliveryOrderUtil->updateDo($do, $request);

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $this->deliveryOrderProductUtil->updateDeliveryOrderProduct(doId: $updateDo->id, request: $request, index: $index);
                $index++;
            }

            $deleteNotFoundSaleProducts = SaleProduct::with('purchaseSaleProductChains', 'purchaseSaleProductChains.purchaseProduct')
                ->where('sale_id', $updateDo->id)->where('delete_in_update', 1)->get();

            foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

                $deleteNotFoundSaleProduct->delete();
            }

            $this->deliveryOrderUtil->calculateDoLeftQty($updateDo);

            $adjustedDo = $this->saleUtil->adjustSaleInvoiceAmounts($updateDo);

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 30, data_obj: $adjustedDo);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        session()->flash('doMsg', 'Delivery order updated successfully');

        return response()->json(['successMsg' => 'Delivery order updated successfully']);
    }

    public function toFinal()
    {
        if (! auth()->user()->can('do_to_final')) {

            abort(403, 'Access Forbidden.');
        }

        $warehouses = DB::table('warehouses')
            ->select(
                'warehouses.id',
                'warehouses.warehouse_name as name',
                'warehouses.warehouse_code as code',
            )->get();

        return view('sales_app.delivery_order.do_to_final', compact('warehouses'));
    }

    public function toFinalConfirm(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $this->validate($request, [
            'date' => 'required|date',
        ], [
            'customer_account_id.required' => 'Customer is required',
        ]);

        $weight = DB::table('weight_scales')->where('id', $request->weight_id)->first();

        if (! $weight) {

            return response()->json(['errorMsg' => 'Can not create invoice without weight.']);
        } else {

            if ($weight->second_weight == null || $weight->second_weight == 0) {

                return response()->json(['errorMsg' => 'Can not create invoice without vehicle second weight.']);
            }
        }

        $netWeight = $weight->second_weight - $weight->first_weight;

        if ($netWeight != $request->total_qty) {

            return response()->json(['errorMsg' => 'Net weight and total item quantity is mismatched.']);
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'Item table is empty']);
        }

        try {

            DB::beginTransaction();

            $settings = DB::table('general_settings')->select(['id', 'business', 'prefix', 'send_es_settings'])->first();

            $stockAccountingMethod = json_decode($settings->business, true)['stock_accounting_method'];

            $updateDo = Sale::where('id', $request->sale_id)->first();

            $updateDo->invoice_id = null;
            $updateDo->save();

            $addSale = $this->deliveryOrderUtil->addDoSalesInvoice(weight: $weight, do: $updateDo, request: $request);

            // Add Day Book entry for sales
            $this->dayBookUtil->addDayBook(voucherTypeId: 1, date: $request->date, accountId: $updateDo->customer_account_id, transId: $addSale->id, amount: $request->net_total_amount, amountType: 'debit');

            // Add sales A/c ledger
            $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $updateDo->sale_account_id, trans_id: $addSale->id, amount: $request->net_total_amount, amount_type: 'credit');

            if ($request->customer_account_id) {

                // Add customer ledger
                $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 1, date: $request->date, account_id: $updateDo->customer_account_id, trans_id: $addSale->id, amount: $request->net_total_amount, amount_type: 'debit', user_id: $updateDo->sr_user_id);
            }

            $updateDoCarWeight = WeightScale::where('id', $request->weight_id)->update(['sale_id' => $addSale->id, 'updated_at' => \DB::raw('updated_at')]);

            // update product quantity and add sale product

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $addDoSaleInvoiceProduct = $this->deliveryOrderProductUtil->addDoSaleInvoiceProduct(request: $request, saleId: $addSale->id, index: $__index);

                if ($addDoSaleInvoiceProduct->tax_ac_id) {

                    $this->accountLedgerUtil->addAccountLedger(voucher_type_id: 16, date: $request->date, account_id: $addDoSaleInvoiceProduct->tax_ac_id, trans_id: $addDoSaleInvoiceProduct->id, amount: $addDoSaleInvoiceProduct->unit_tax_amount, amount_type: 'credit');
                }
                $__index++;
            }

            $this->deliveryOrderUtil->calculateDoLeftQty($updateDo);

            // Add sale payment
            $sale = Sale::with([
                'customer:id,name,phone,address',
                'do:id,do_id,do_date,order_by_id,all_price_type',
                // 'do.orderBy:id,prefix,name,last_name',
                'saleProducts',
                'saleProducts.product:id,name,product_code,is_manage_stock',
                'saleProducts.variant:id,variant_name,variant_code',
                'saleProducts.warehouse:id,warehouse_name,warehouse_code',
                'saleBy:id,prefix,name,last_name',
                'sr:id,prefix,name,last_name',
            ])->where('delivery_order_id', $updateDo->id)->orderBy('id', 'desc')->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 7, data_obj: $sale);

            $__index = 0;
            foreach ($request->product_ids as $product_id) {

                $warehouse_id = $request->warehouse_ids[$__index];
                $variant_id = $request->variant_ids[$__index] != 'noid' ? $request->variant_ids[$__index] : null;

                $this->productStockUtil->adjustMainProductAndVariantStock($product_id, $variant_id);

                if ($warehouse_id) {

                    $this->productStockUtil->adjustWarehouseStock($product_id, $variant_id, $warehouse_id);
                } else {

                    $this->productStockUtil->adjustBranchStock($product_id, $variant_id);
                }

                $__index++;
            }

            $this->saleUtil->updatePurchaseSaleProductChain($sale, $stockAccountingMethod);

            // if (
            //     \App\Models\GeneralSetting::isEmailActive() &&
            //     json_decode($settings->send_es_settings, true)['send_inv_via_email'] == '1'
            // ) {

            //     if ($sale->customer && $sale->customer->email) {

            //         SaleMailJob::dispatch($sale->customer->email, $sale)
            //             ->delay(now()->addSeconds(5));
            //     }
            // }

            // if (
            //     \App\Models\GeneralSetting::isSmsActive() &&
            //     json_decode($settings->send_es_settings, true)['send_notice_via_sms'] == '1'
            // ) {

            //     if ($sale->customer && $sale->customer->phone) {

            //         $this->smsUtil->sendSaleSms($sale);
            //     }
            // }

            $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales_app.save_and_print_template.sale_print', compact('sale', 'customerCopySaleProducts'));
        } else {

            return response()->json(['finalMsg' => 'DO to final created successfully']);
        }
    }

    public function printInvoice(Request $request)
    {
        $weight = DB::table('weight_scales')->where('id', $request->weight_id)->first(['sale_id']);

        if (! $weight) {

            return response()->json(['errorMsg' => 'Invoice in not available yet']);
        }

        if (! $weight->sale_id) {

            return response()->json(['errorMsg' => 'Invoice in not available yet']);
        }

        $sale = Sale::with([
            'customer',
            'do:id,do_id,do_date,order_by_id,order_id,all_price_type',
            // 'do.orderBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.warehouse',
            'saleBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
            'weight',
        ])->where('id', $weight->sale_id)->orderBy('id', 'desc')->first();

        if (! $sale) {

            return response()->json(['errorMsg' => 'Invoice yet not to be created.']);
        }

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

        return view('sales_app.save_and_print_template.sale_print', compact('sale', 'customerCopySaleProducts'));
    }

    public function printChallan(Request $request)
    {
        $weight = DB::table('weight_scales')->where('id', $request->weight_id)->first(['sale_id']);

        if (! $weight) {

            return response()->json(['errorMsg' => 'Challan in not available yet']);
        }

        if (! $weight->sale_id) {

            return response()->json(['errorMsg' => 'Challan in not available yet']);
        }

        $sale = Sale::with([
            'customer',
            'do:id,do_id,do_date',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.warehouse',
            'saleBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
        ])->where('id', $weight->sale_id)->orderBy('id', 'desc')->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

        return view('sales_app.save_and_print_template.challan_print', compact('sale', 'customerCopySaleProducts'));
    }

    public function printGatePass(Request $request, CodeGenerationServiceInterface $codeGenerationService)
    {
        $weight = DB::table('weight_scales')->where('id', $request->weight_id)->first(['sale_id']);

        if (! $weight) {

            return response()->json(['errorMsg' => 'Gate pass in not available yet']);
        }

        if (! $weight->sale_id) {

            return response()->json(['errorMsg' => 'Gate pass in not available yet']);
        }

        $gatePass = DB::table('gate_passes')->where('sale_id', $weight->sale_id)->first();

        if (! $gatePass) {

            $addGatePass = new GatePass();
            $addGatePass->voucher_no = $codeGenerationService->generateMonthWise(table: 'gate_passes', column: 'voucher_no', prefix: 'SGP', splitter: '-', suffixSeparator: '-');
            $addGatePass->gp_for = 1;
            $addGatePass->sale_id = $weight->sale_id;
            $addGatePass->created_by_id = auth()->user()->id;
            $addGatePass->save();
        }

        $sale = Sale::with([
            'customer',
            'weight',
            'gatePass',
            'gatePass.createdBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
        ])->where('id', $weight->sale_id)->orderBy('id', 'desc')->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($weight->sale_id, true);

        return view('sales_app.save_and_print_template.gate_pass_print', compact('sale', 'customerCopySaleProducts'));
    }

    public function saveCar(Request $request, CodeGenerationServiceInterface $generator)
    {
        if ($request->sale_id == null) {

            return response()->json(['errorMsg' => 'Please select a DO']);
        }

        if ($request->do_car_number == null) {

            return response()->json(['errorMsg' => 'Vehicle number must not be empty.']);
        }

        if ($request->do_car_weight == null || $request->do_car_weight <= 0) {

            return response()->json(['errorMsg' => 'Weight 0 or empty is not acceptable.']);
        }

        $weightDetails = '';

        try {

            DB::beginTransaction();

            $userId = auth()->user()->user_id;

            // database queries here. Access any $var_N directly
            $sale = Sale::where('id', $request->sale_id)->first();

            // $updateWeightScale = WeightScale::where('id', $request->weight_id)->where('do_car_number', $request->do_car_number)
            // ->first();

            $updateWeightScale = WeightScale::where('id', $request->weight_id)->first();

            if ($updateWeightScale) {

                if ($updateWeightScale->first_weight == null || $updateWeightScale->first_weight == 0) {

                    $updateWeightScale->do_car_number = $request->do_car_number;
                    $updateWeightScale->do_driver_name = $request->do_driver_name;
                    $updateWeightScale->do_driver_phone = $request->do_driver_phone;
                    $updateWeightScale->do_car_last_weight = $request->do_car_weight;
                    $updateWeightScale->first_weight = $request->do_car_weight;
                    $updateWeightScale->first_weighted_by_id = auth()->user()->id;
                    $updateWeightScale->save();
                } else {

                    $updateWeightScale->do_car_number = $request->do_car_number;
                    $updateWeightScale->do_driver_name = $request->do_driver_name;
                    $updateWeightScale->do_driver_phone = $request->do_driver_phone;
                    $updateWeightScale->do_car_last_weight = $request->do_car_weight;
                    $updateWeightScale->second_weight = $request->do_car_weight;
                    $updateWeightScale->second_weighted_by_id = auth()->user()->id;
                    $updateWeightScale->save();
                }

                $weightDetails = $updateWeightScale;
            } else {

                $addWeightScale = new WeightScale();
                $addWeightScale->first_weighted_by_id = auth()->user()->id;
                $addWeightScale->delivery_order_id = $request->sale_id;
                $addWeightScale->do_car_number = $request->do_car_number;
                $addWeightScale->do_driver_name = $request->do_driver_name;
                $addWeightScale->do_driver_phone = $request->do_driver_phone;
                $addWeightScale->do_car_last_weight = $request->do_car_weight;
                $addWeightScale->first_weight = $request->do_car_weight;
                // $addWeightScale->reserve_invoice_id = $request->invoice_id;
                $addWeightScale->reserve_invoice_id = $generator->generateMonthWise(table: 'sales', column: 'invoice_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
                $addWeightScale->save();

                // $sale->invoice_id = $request->invoice_id;
                $sale->invoice_id = $addWeightScale->reserve_invoice_id;
                $sale->save();

                $weightDetails = $addWeightScale;
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $weightDetails;
    }

    public function getWeightDetails(Request $request)
    {
        if ($request->weight_id) {

            $weight = WeightScale::with(['sale', 'do', 'firstWeightedBy:id,prefix,name,last_name', 'secondWeightedBy:id,prefix,name,last_name'])->where('id', $request->weight_id)->first();
        } else {

            return response()->json(['errorMsg' => 'No weight available yet']);
        }

        return view('sales_app.delivery_order.ajax_view.weight_details_modal', compact('weight'));
    }

    public function printWeight(Request $request)
    {
        if ($request->weight_id) {

            $weight = WeightScale::with(['sale', 'do', 'firstWeightedBy:id,prefix,name,last_name', 'secondWeightedBy:id,prefix,name,last_name'])->where('id', $request->weight_id)->first();
        } else {

            return response()->json(['errorMsg' => 'No weight available yet']);
        }

        return view('sales_app.save_and_print_template.do_last_weight', compact('weight'));
    }

    public function doDone($id)
    {
        if (! $id) {

            return response()->json(['errorMsg' => 'Please select a vehicle.']);
        }

        $weight = WeightScale::where('id', $id)->first();

        if ($weight->sale_id) {

            $weight->is_vehicle_done = 1;
            $weight->save();
        } else {

            $weight->delete();
        }

        return 'Vehicle is done.';
    }

    public function editDoModal(Request $request)
    {
        if (! auth()->user()->can('do_edit')) {

            return response()->json(['errorMsg' => 'Access Forbidden']);
        }

        if ($request->sale_id == null) {

            return response()->json(['errorMsg' => 'Please select a DO']);
        }

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $do = Sale::with([
            'saleProducts',
            'customer',
            'saleProducts.warehouse',
            'saleProducts.product',
            'saleProducts.product.unit:id,name,code_name',
            'saleProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.variant',
            'saleProducts.saleUnit:id,name,base_unit_multiplier',
            'saleProducts.product.comboProducts',
            'saleProducts.product.comboProducts.parentProduct',
            'saleProducts.product.comboProducts.product_variant',
        ])->where('id', $request->sale_id)->first();

        return view('sales_app.delivery_order.ajax_view.edit_do', compact('do', 'taxAccounts'));
    }

    public function printDo(Request $request)
    {
        $do = Sale::with([
            'customer:id,name,phone,address',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.warehouse',
            'orderBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
        ])->where('id', $request->sale_id)->first();

        if (! $do) {

            return response()->json(['errorMsg' => 'Please select a DO']);
        }

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($do->id);

        return view('sales_app.save_and_print_template.do_print', compact('do', 'customerCopySaleProducts'));
    }

    public function printBillAgainstDo(Request $request)
    {
        if (! $request->sale_id) {

            return response()->json(['errorMsg' => 'Please select a DO']);
        }

        $do = Sale::with(['customer:id,name,phone,address'])->where('id', $request->sale_id)->first();

        $saleProducts = '';
        $query = DB::table('sale_products')
            ->leftJoin('sales', 'sale_products.sale_id', '=', 'sales.id')
            ->leftJoin('products', 'sale_products.product_id', 'products.id')
            ->leftJoin('product_variants', 'sale_products.product_variant_id', 'product_variants.id')
            ->leftJoin('units', 'products.unit_id', 'units.id')
            ->leftJoin('weight_scales', 'sales.id', 'weight_scales.sale_id')
            ->where('sales.status', 1)
            ->where('sale_products.quantity', '>', 0)
            ->where('sales.delivery_order_id', $request->sale_id);

        $saleProducts = $query->select(
            'sale_products.sale_id',
            'sale_products.product_id',
            'sale_products.product_variant_id',
            'sale_products.unit_price_inc_tax',
            'sale_products.quantity',
            'units.code_name as unit_code',
            'sale_products.subtotal',
            'sales.date',
            'sales.report_date',
            'sales.invoice_id',
            'weight_scales.do_car_number',
            'products.name',
            'products.product_code',
            'product_variants.variant_name',
            'product_variants.variant_code',
        )->orderBy('sales.report_date', 'desc')->get();

        return view('sales_app.delivery_order.ajax_view.print_do_bills', compact('do', 'saleProducts'));
    }

    public function previousInvoice(Request $request)
    {
        $sale = Sale::with([
            'customer',
            'do:id,do_id,do_date,order_by_id,order_id,all_price_type',
            // 'do.orderBy:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.product:id,name,product_code,is_manage_stock',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.warehouse',
            'saleBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
        ])->where('order_id', null)->where('do_id', null)->where('quotation_id', null)->where('invoice_id', $request->invoice_id)->first();

        if (! $sale) {

            return response()->json(['errorMsg' => 'Invoice Not Found.']);
        }

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($sale->id, true);

        return view('sales_app.save_and_print_template.sale_print', compact('sale', 'customerCopySaleProducts'));
    }

    public function previousWeight(Request $request)
    {
        $sale = Sale::with([
            'do:id,do_id,do_date',
            'weight',
            'weight.firstWeightedBy:id,prefix,name,last_name',
            'weight.secondWeightedBy:id,prefix,name,last_name',
        ])->where('order_id', null)->where('do_id', null)->where('quotation_id', null)->where('invoice_id', $request->invoice_id)->first();

        if (! $sale) {

            return response()->json(['errorMsg' => 'Weight Not Found.']);
        }

        if ($sale->weight == null) {

            return response()->json(['errorMsg' => 'Weight Not Found.']);
        }

        return view('sales_app.save_and_print_template.weight_print', compact('sale'));
    }
}
