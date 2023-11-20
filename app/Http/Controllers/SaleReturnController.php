<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleReturn;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\ProductStockUtil;
use App\Utils\SaleUtil;
use App\Utils\UserActivityLogUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SaleReturnController extends Controller
{
    public function __construct(
        private ProductStockUtil $productStockUtil,
        private SaleUtil $saleUtil,
        private AccountUtil $accountUtil,
        private CustomerUtil $customerUtil,
        private Converter $converter,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil
    ) {
    }

    // Sale return index view
    public function index(Request $request)
    {
        if (! auth()->user()->can('view_sales_return')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $returns = '';
            $generalSettings = DB::table('general_settings')->first();
            $query = DB::table('sale_returns')
                ->leftJoin('sales', 'sale_returns.sale_id', 'sales.id')
                ->leftJoin('warehouses', 'sale_returns.warehouse_id', 'warehouses.id')
                ->leftJoin('accounts as customers', 'sale_returns.customer_account_id', 'customers.id')
                ->leftJoin('users as sr', 'sale_returns.sr_user_id', 'sr.id')
                ->leftJoin('users as created_by', 'sale_returns.created_by_id', 'created_by.id');

            if (auth()->user()->can('view_own_sale')) {

                $query->where('sale_returns.created_by_id', auth()->user()->id);
            }

            $this->filteredQuery($request, $query);

            $returns = $query->select(
                'sale_returns.*',
                'sales.invoice_id as parent_invoice_id',
                'warehouses.warehouse_name as w_name',
                'warehouses.warehouse_code as w_code',
                'customers.name as cus_name',
                'sr.prefix as sr_prefix',
                'sr.name as sr_name',
                'sr.last_name as sr_last_name',
                'created_by.prefix as created_by_prefix',
                'created_by.name as created_by_name',
                'created_by.last_name as created_by_last_name',
            )->orderBy('report_date', 'desc');

            return DataTables::of($returns)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="details_btn" href="'.route('sales.returns.show', $row->id).'"> View</a>';

                    if (auth()->user()->can('edit_sales_return')) {

                        $html .= '<a class="dropdown-item" href="'.route('sale.return.random.edit', [$row->id]).'"> Edit</a>';
                    }

                    if (auth()->user()->can('delete_sales_return')) {

                        $html .= '<a class="dropdown-item" id="delete" href="'.route('sales.returns.delete', [$row->id]).'"> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('date', function ($row) {

                    return date('d/m/Y', strtotime($row->date));
                })
                ->editColumn('sr', function ($row) {

                    return $row->sr_prefix.' '.$row->sr_name.' '.$row->sr_last_name;
                })
                ->editColumn('createdBy', function ($row) {

                    return $row->created_by_prefix.' '.$row->created_by_name.' '.$row->created_by_last_name;
                })
                ->editColumn('stored_location', function ($row) use ($generalSettings) {

                    if ($row->w_name) {

                        return $row->w_name.'/'.$row->w_code.'(WH)';
                    } else {

                        return $row->branch_name != null ? ($row->branch_name.'/'.$row->branch_code).'<b>(BL)</b>' : json_decode($generalSettings->business, true)['shop_name'].'';
                    }
                })
                ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$this->converter->format_in_bdt($row->total_qty).'</span>')
                ->editColumn('total_return_amount', fn ($row) => '<span class="total_return_amount text-danger" data-value="'.$row->total_return_amount.'">'.$this->converter->format_in_bdt($row->total_return_amount).'</span>')
                ->rawColumns(['action', 'date', 'stored_location', 'sr', 'createdBy', 'total_qty', 'total_return_amount'])
                ->make(true);
        }

        $customerAccounts = DB::table('accounts')
            ->leftJoin('account_groups', 'accounts.account_group_id', 'account_groups.id')
            ->where('account_groups.sub_sub_group_number', 6)
            ->get(['accounts.id', 'accounts.name', 'accounts.phone']);

        $users = [];
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('sales_app.sale_return.index', compact('customerAccounts', 'users'));
    }

    // Show Sale return details
    public function show($returnId)
    {
        $saleReturn = SaleReturn::with([
            'sale',
            'customer',
            'sr:id,prefix,name,last_name',
            'returnProducts',
            'returnProducts.product',
            'returnProducts.variant',
            'returnProducts.returnUnit:id,code_name,base_unit_id,base_unit_multiplier',
            'returnProducts.returnUnit.baseUnit:id,code_name',
        ])->where('id', $returnId)->first();

        return view('sales_app.sale_return.ajax_view.show', compact('saleReturn'));
    }

    //Deleted sale return
    public function delete($saleReturnId)
    {
        if (! auth()->user()->can('delete_sales_return')) {

            return response()->json('Access Denied.');
        }

        try {

            DB::beginTransaction();

            $saleReturn = SaleReturn::with(['sale', 'customer', 'returnProducts'])->where('id', $saleReturnId)->first();

            $storedReturnedProducts = $saleReturn->returnProducts;
            $storedReturnAccountId = $saleReturn->sale_account_id;

            // Add User Activity Log
            $this->userActivityLogUtil->addLog(action: 3, subject_type: 9, data_obj: $saleReturn);

            $saleReturn->delete();

            foreach ($storedReturnedProducts as $returnProduct) {

                $this->productStockUtil->adjustMainProductAndVariantStock($returnProduct->product_id, $returnProduct->product_variant_id);
                $this->productStockUtil->adjustBranchStock($returnProduct->product_id, $returnProduct->product_variant_id);
            }

            if ($saleReturn->sale) {

                $saleReturn->sale->is_return_available = 0;
                $this->saleUtil->adjustSaleInvoiceAmounts($saleReturn->sale);
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Sale return voucher is deleted successfully');
    }

    private function filteredQuery($request, $query)
    {
        if ($request->user_id) {

            $query->where('sale_returns.sr_user_id', $request->user_id);
        }

        if ($request->customer_account_id) {

            $query->where('sale_returns.customer_account_id', $request->customer_account_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('sale_returns.report_date', $date_range); // Final
        }

        return $query;
    }
}
