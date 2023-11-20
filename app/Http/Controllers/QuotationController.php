<?php

namespace App\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Jobs\SaleMailJob;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Utils\AccountUtil;
use App\Utils\QuotationProductUtil;
use App\Utils\QuotationUtil;
use App\Utils\SaleUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    protected $saleUtil;

    protected $quotationUtil;

    protected $quotationProductUtil;

    protected $userActivityLogUtil;

    protected $accountUtil;

    public function __construct(
        SaleUtil $saleUtil,
        QuotationUtil $quotationUtil,
        QuotationProductUtil $quotationProductUtil,
        UserActivityLogUtil $userActivityLogUtil,
        AccountUtil $accountUtil,
    ) {
        $this->saleUtil = $saleUtil;
        $this->quotationUtil = $quotationUtil;
        $this->quotationProductUtil = $quotationProductUtil;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->accountUtil = $accountUtil;
    }

    // Quotations list view
    public function index(Request $request)
    {
        if ($request->ajax()) {

            return $this->quotationUtil->saleQuotationTable($request);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('sales_app.quotations.index', compact('customerAccounts', 'users'));
    }

    // Quotation Details
    public function show($quotationId)
    {
        $quotation = Sale::with([
            'customer',
            'quotationBy:id,prefix,name,last_name',
            'sr:id,prefix,name,last_name',
            'saleProducts',
            'saleProducts.warehouse',
            'saleProducts.product:id,name,product_code',
            'saleProducts.variant:id,variant_name,variant_code',
            'saleProducts.saleUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.saleUnit.baseUnit:id,code_name',
        ])->where('id', $quotationId)->first();

        $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($quotation->id);

        return view('sales_app.quotations.ajax_view.show', compact('quotation', 'customerCopySaleProducts'));
    }

    public function create()
    {
        if (! auth()->user()->can('add_quotation')) {

            abort(403, 'Access Forbidden.');
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get(['id', 'name']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.quotations.create', compact(
            'customerAccounts',
            'price_groups',
            'users',
            'taxAccounts'
        ));
    }

    public function store(Request $request, CodeGenerationServiceInterface $generator)
    {
        $this->validate($request, [
            'customer_account_id' => 'required',
            'date' => 'required|date',
        ], [
            'customer_account_id.required' => 'Customer is required',
        ]);

        if ($request->expire_date) {

            $this->validate($request, [
                'expire_time' => 'required',
            ]);
        }

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'item table is empty']);
        }

        try {

            DB::beginTransaction();
            $settings = DB::table('general_settings')
                ->select(['id', 'business', 'prefix', 'send_es_settings'])
                ->first();

            $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();

            $invoicePrefix = 'Q'.auth()->user()->user_id;

            $srUserId = isset($request->user_count) ? $request->user_id : auth()->user()->id;

            $addQuotation = $this->quotationUtil->addQuotation($request, $srUserId, $generator, $invoicePrefix);

            $index = 0;
            foreach ($request->product_ids as $product_id) {

                $this->quotationProductUtil->addQuotationProduct(quotationId: $addQuotation->id, request: $request, index: $index);
                $index++;
            }

            $quotation = Sale::with([
                'customer',
                'saleProducts',
                'saleProducts.product:id,name,product_code,is_manage_stock',
                'saleProducts.variant:id,variant_name,variant_code',
                'saleProducts.saleUnit:id,code_name,base_unit_id,base_unit_multiplier',
                'saleProducts.saleUnit.baseUnit:id,code_name',
                'sr:id,prefix,name,last_name',
                'quotationBy:id,prefix,name,last_name',
            ])->where('id', $addQuotation->id)->first();

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 29, data_obj: $quotation);

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

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        if ($request->action == 'save_and_print') {

            return view('sales_app.save_and_print_template.quotation_print', compact('quotation'));
        } else {

            return response()->json(['quotationMsg' => 'Quotation created successfully']);
        }
    }

    public function edit($saleId)
    {
        if (! auth()->user()->can('sale_quotation_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $price_groups = DB::table('price_groups')->where('status', 'Active')->get();

        $quotation = Sale::with([
            'saleProducts',
            'customer',
            'saleProducts.warehouse',
            'saleProducts.product',
            'saleProducts.variant',
            'saleProducts.product.unit:id,name,code_name',
            'saleProducts.product.unit.childUnits:id,name,code_name,base_unit_id,base_unit_multiplier',
            'saleProducts.product.comboProducts',
            'saleProducts.product.comboProducts.parentProduct',
            'saleProducts.product.comboProducts.product_variant',
        ])->where('id', $saleId)->first();

        $qty_limits = $this->saleUtil->getStockLimitProducts($quotation);

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $taxAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.is_default_tax_calculator', 1)
            ->get(['accounts.id', 'accounts.name', 'accounts.tax_percent']);

        $saleAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_group_number', 15)
            ->get(['accounts.id', 'accounts.name']);

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.quotations.edit', compact('quotation', 'price_groups', 'saleAccounts', 'taxAccounts', 'qty_limits', 'users', 'customerAccounts'));
    }

    public function update(Request $request, $quotationId, CodeGenerationServiceInterface $generator)
    {
        if (! auth()->user()->can('sale_quotation_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'status' => 'required',
            'date' => 'required|date',
            'sale_account_id' => 'required',
        ], [
            'sale_account_id.required' => 'Sale A/c is required',
        ]);

        if (isset($request->user_count)) {

            $this->validate($request, ['user_id' => 'required'], ['user_id.required' => 'Sr is required']);
        }

        if ($request->expire_date) {

            $this->validate($request, [
                'expire_time' => 'required',
            ]);
        }

        $settings = DB::table('general_settings')->select(['id', 'business', 'prefix'])->first();

        if ($request->product_ids == null) {

            return response()->json(['errorMsg' => 'product table is empty']);
        }

        $updateQuotation = Sale::with(['saleProducts', 'saleProducts.product', 'saleProducts.variant', 'saleProducts.product.comboProducts'])
            ->where('id', $quotationId)->first();

        $srUserId = isset($request->user_count) ? $request->user_id : $updateQuotation->sr_user_id;

        if ($updateQuotation->status == 4 && $request->status == 3) {

            if ($request->expire_date) {

                $__date = date('Y-m-d H:i:s', strtotime($request->expire_date.$request->expire_time));

                if (strtotime(date('Y-m-d H:i:s')) > strtotime($__date)) {

                    return response()->json(['errorMsg' => 'Date expired. Quotation can not create sales order!']);
                }
            }
        }

        foreach ($updateQuotation->saleProducts as $saleProduct) {

            $saleProduct->delete_in_update = 1;
            $saleProduct->save();
        }

        if ($request->status == 3) {

            $updateQuotation->order_id = $updateQuotation->order_id == null ? $generator->generateMonthWise(table: 'sales', column: 'order_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-') : $updateQuotation->order_id;
            $updateQuotation->order_status = 1;
            $updateQuotation->order_by_id = auth()->user()->id;
            $updateQuotation->order_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        } else {

            $updateQuotation->order_id = null;
            $updateQuotation->order_status = 0;
            $updateQuotation->order_by_id = null;
            $updateQuotation->order_date = null;
        }

        $updateQuotation->sr_user_id = $srUserId;
        $updateQuotation->sale_account_id = $request->sale_account_id;
        $updateQuotation->date = $request->date;
        $updateQuotation->total_item = $request->total_item ? $request->total_item : 0;
        $updateQuotation->net_total_amount = $request->net_total_amount ? $request->net_total_amount : 0;
        $updateQuotation->order_discount_type = $request->order_discount_type;
        $updateQuotation->order_discount = $request->order_discount ? $request->order_discount : 0;
        $updateQuotation->order_discount_amount = $request->order_discount_amount ? $request->order_discount_amount : 0;
        $updateQuotation->tax_ac_id = $request->order_tax_ac_id;
        $updateQuotation->order_tax_percent = $request->order_tax_percent ? $request->order_tax_percent : 0;
        $updateQuotation->order_tax_amount = $request->order_tax_amount ? $request->order_tax_amount : 0;
        $updateQuotation->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0;
        $updateQuotation->total_payable_amount = $request->total_invoice_amount ? $request->total_invoice_amount : 0;
        $updateQuotation->all_price_type = $request->all_price_type;
        $updateQuotation->sale_note = $request->sale_note;

        if ($request->status == 4) {

            $updateQuotation->quotation_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        }

        $updateQuotation->expire_date = $request->expire_date ? date('Y-m-d H:i:s', strtotime($request->expire_date.$request->expire_time)) : null;
        $updateQuotation->save();

        // Update/Add sale product rows
        $index = 0;
        foreach ($request->product_ids as $product_id) {

            $this->quotationProductUtil->updateQuotationProduct(quotationId: $updateQuotation->id, request: $request, index: $index);
            $index++;
        }

        $deleteNotFoundSaleProducts = SaleProduct::with('purchaseSaleProductChains', 'purchaseSaleProductChains.purchaseProduct')
            ->where('sale_id', $updateQuotation->id)
            ->where('delete_in_update', 1)->get();

        foreach ($deleteNotFoundSaleProducts as $deleteNotFoundSaleProduct) {

            $purchaseSaleProductChains = $deleteNotFoundSaleProduct->purchaseSaleProductChains;
            $deleteNotFoundSaleProduct->delete();
        }

        session()->flash('successMsg', 'Sale quotation updated successfully');

        return response()->json(['successMsg' => 'Sale quotation updated successfully']);
    }
}
