<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Utils\Converter;
use App\Utils\SupplierUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SupplierReportController extends Controller
{
    protected $converter;

    protected $supplierUtil;

    public function __construct(Converter $converter, SupplierUtil $supplierUtil)
    {
        $this->converter = $converter;
        $this->supplierUtil = $supplierUtil;
    }

    // Index view of supplier report
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $supplierUtil = $this->supplierUtil;
            $generalSettings = DB::table('general_settings')->first();
            $suppliers = '';
            $query = DB::table('suppliers')->where('status', 1);

            if ($request->supplier_id) {

                $query->where('suppliers.id', $request->supplier_id);
            }

            $suppliers = $query->select(
                'suppliers.id',
                'suppliers.name',
                'suppliers.contact_id',
                'suppliers.phone',
                'suppliers.address',
                'suppliers.opening_balance_type',
            )->orderBy('suppliers.name', 'asc');

            return DataTables::of($suppliers)
                ->editColumn('name', function ($row) {

                    return $row->name.' (ID: '.$row->contact_id.')';
                })
                ->editColumn('opening_balance', function ($row) use ($supplierUtil) {

                    $amounts = $supplierUtil->supplierAmountSummery($row->id, $row);
                    $openingBalance = $amounts['opening_balance_type'] == 'credit'
                        ? $amounts['opening_balance']
                        : ($amounts['opening_balance'] > 0 ? -$amounts['opening_balance'] : $amounts['opening_balance']);

                    $formattedAmount = $this->converter->format_in_bdt($openingBalance);

                    $showAmount = $formattedAmount < 0 ? Str::of($formattedAmount)->replace('-', '')->wrap('(', ')') : $formattedAmount;

                    return '<span class="opening_balance" data-value="'.$openingBalance.'">'.$showAmount.'</span>';
                })

                ->editColumn('total_paid', function ($row) use ($supplierUtil) {

                    $amounts = $supplierUtil->supplierAmountSummery($row->id, $row);
                    $totalPaid = $amounts['total_paid'];

                    return '<span class="total_paid" data-value="'.$totalPaid.'">'.$this->converter->format_in_bdt($totalPaid).'</span>';
                })
                ->editColumn('total_return', function ($row) use ($supplierUtil) {

                    $amounts = $supplierUtil->supplierAmountSummery($row->id, $row);
                    $total_return = $amounts['total_return'];

                    return '<span class="total_return" data-value="'.$total_return.'">'.$this->converter->format_in_bdt($total_return).'</span>';
                })
                ->editColumn('total_purchase_due', function ($row) use ($supplierUtil) {

                    $amounts = $supplierUtil->supplierAmountSummery($row->id, $row);
                    $total_due = $amounts['total_due'];

                    $formattedAmount = $this->converter->format_in_bdt($total_due);
                    $showAmount = $formattedAmount < 0 ? Str::of($formattedAmount)->replace('-', '')->wrap('(', ')') : $formattedAmount;

                    return '<span class="total_purchase_due" data-value="'.$total_due.'">'.$showAmount.'</span>';
                })
                ->editColumn('total_purchase', function ($row) use ($supplierUtil) {

                    $amounts = $supplierUtil->supplierAmountSummery($row->id, $row);
                    $total_purchase = $amounts['total_purchase'];

                    return '<span class="total_purchase" data-value="'.$total_purchase.'">'.$this->converter->format_in_bdt($total_purchase).'</span>';
                })
                ->rawColumns(['name', 'opening_balance', 'total_paid', 'total_purchase', 'total_purchase_due', 'total_due', 'total_return'])
                ->make(true);
        }

        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();

        return view('reports.supplier_report.index', compact('suppliers'));
    }

    public function print(Request $request)
    {
        $supplierReports = '';
        $supplierId = $request->supplier_id;
        $query = DB::table('suppliers')->where('status', 1);

        if ($request->supplier_id) {

            $query->where('suppliers.id', $request->supplier_id);
        }

        $supplierReports = $query->select(
            'suppliers.id',
            'suppliers.name',
            'suppliers.contact_id',
            'suppliers.phone',
            'suppliers.address',
            'suppliers.opening_balance_type',
        )->orderBy('suppliers.name')->get();

        $supplierUtil = $this->supplierUtil;

        return view('reports.supplier_report.ajax_view.print', compact('supplierReports', 'supplierId', 'supplierUtil'));
    }
}
