<?php

namespace App\Utils;

use App\Models\PurchaseRequisition;
use App\Models\PurchaseRequisitionProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RequisitionUtil
{
    public $converter;

    public function __construct(
        Converter $converter
    ) {
        $this->converter = $converter;
    }

    public function requisitionListTable($request)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $requisitions = '';
        $query = DB::table('purchase_requisitions');

        if ($request->is_approved != '') {

            $query->where('purchase_requisitions.is_approved', $request->is_approved);
        }

        if ($request->requester_id) {

            $query->where('purchase_requisitions.requester_id', $request->requester_id);
        }

        if ($request->created_by_id) {

            $query->where('purchase_requisitions.created_by_id', $request->created_by_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            //$date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $requisitions = $query->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('users as created_by', 'purchase_requisitions.created_by_id', 'created_by.id')
            ->leftJoin('users as approved_by', 'purchase_requisitions.approved_by_id', 'approved_by.id')->select(
                'purchase_requisitions.*',
                'departments.name as dep_name',
                'created_by.prefix as created_prefix',
                'created_by.name as created_name',
                'created_by.last_name as created_last_name',
                'approved_by.prefix as approved_prefix',
                'approved_by.name as approved_name',
                'approved_by.last_name as approved_last_name',
            )->orderBy('purchase_requisitions.report_date', 'desc');

        return DataTables::of($requisitions)
            ->addColumn('action', function ($row) {

                $html = '<div class="btn-group" role="group">';
                $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.requisition.show', [$row->id]).'"> View</a>';

                if (auth()->user()->can('edit_requisition')) {

                    $html .= '<a class="dropdown-item" href="'.route('purchases.requisition.edit', [$row->id]).' "> Edit</a>';
                }

                if (auth()->user()->can('delete_requisition')) {

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('purchases.requisition.delete', $row->id).'"> Delete</a>';
                }

                if (auth()->user()->can('approve_requisition')) {

                    $html .= '<a class="dropdown-item" id="requisition_approval" href="'.route('purchases.requisition.approval', [$row->id]).'"> Requisition Approval</a>';
                }

                $html .= '</div>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })

            ->editColumn('department', function ($row) {

                return $row->dep_name ? $row->dep_name : '...';
            })

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.$this->converter->format_in_bdt($row->total_item).'</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$this->converter->format_in_bdt($row->total_qty).'</span>')

            ->editColumn('total_purchase_order', fn ($row) => '<span class="total_purchase_order" data-value="'.$row->total_purchase_order.'">'.$this->converter->format_in_bdt($row->total_purchase_order).'</span>')

            ->editColumn('total_purchase', fn ($row) => '<span class="total_purchase" data-value="'.$row->total_purchase.'">'.$this->converter->format_in_bdt($row->total_purchase).'</span>')

            ->editColumn('total_received', fn ($row) => '<span class="total_received" data-value="'.$row->total_received.'">'.$this->converter->format_in_bdt($row->total_received).'</span>')

            ->editColumn('is_approved', function ($row) {

                if ($row->is_approved == 0) {

                    return '<span class="text-danger"><b>Pending</b></span>';
                } else {

                    return '<span class="text-success"><b>Approved</b></span>';
                }
            })

            ->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })

            ->rawColumns(['action', 'date', 'department', 'is_approved', 'total_item', 'total_qty', 'total_purchase_order', 'total_purchase', 'total_received', 'created_by'])
            ->make(true);
    }

    public function addRequisition($request, $codeGenerationService)
    {
        $requisitionNo = $codeGenerationService->generateMonthWise(table: 'purchase_requisitions', column: 'requisition_no', prefix: 'PR', digits: 4, size: 13, splitter: '-', suffixSeparator: '-');

        $addRequisition = new PurchaseRequisition();
        $addRequisition->requisition_no = $requisitionNo;
        $addRequisition->department_id = $request->department_id;
        $addRequisition->requester_id = $request->requester_id;
        $addRequisition->total_item = $request->total_item;
        $addRequisition->total_qty = $request->total_qty;
        $addRequisition->created_by_id = auth()->user()->id;
        $addRequisition->note = $request->note;
        $addRequisition->date = $request->date;
        $addRequisition->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addRequisition->save();

        return $addRequisition;
    }

    public function updateRequisition($updateRequisition, $request)
    {
        $updateRequisition->requisition_no = $request->requisition_no ? $request->requisition_no : 'PR'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('purchase_requisitions'), 5, '0', STR_PAD_LEFT);
        $updateRequisition->department_id = $request->department_id;
        $updateRequisition->requester_id = $request->requester_id;
        $updateRequisition->total_item = $request->total_item;
        $updateRequisition->total_qty = $request->total_qty;
        $updateRequisition->note = $request->note;
        $updateRequisition->date = $request->date;
        $time = date(' H:i:s', strtotime($updateRequisition->report_date));
        $updateRequisition->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $updateRequisition->save();

        return $updateRequisition;
    }

    public function updateRequisitionOrderPurchaseAndReceivedCount($requisitionId)
    {
        $countRequisitionOrder = DB::table('purchases')
            ->where('purchase_status', 3)->where('requisition_id', $requisitionId)
            ->count();

        $countRequisitionPurchase = DB::table('purchases')
            ->where('receive_stock_id', null)
            ->where('purchase_status', 1)
            ->where('requisition_id', $requisitionId)->count();

        $countRequisitionReceive = DB::table('receive_stocks')->where('requisition_id', $requisitionId)->count();

        $updateRequisition = PurchaseRequisition::where('id', $requisitionId)->first();
        $updateRequisition->total_purchase_order = $countRequisitionOrder;
        $updateRequisition->total_purchase = $countRequisitionPurchase;
        $updateRequisition->total_received = $countRequisitionReceive;
        $updateRequisition->save();
    }

    public function updateRequisitionLeftQty($requisitionId)
    {
        $requisitionProducts = PurchaseRequisitionProduct::where('requisition_id', $requisitionId)->get();

        foreach ($requisitionProducts as $requisitionProduct) {

            $purchaseProducts = DB::table('purchase_products')
                ->leftJoin('purchases', 'purchase_products.purchase_id', 'purchases.id')
                ->where('purchases.receive_stock_id', null)
                ->where('purchases.requisition_id', $requisitionId)
                ->where('purchase_products.product_id', $requisitionProduct->product_id)
                ->where('purchase_products.product_variant_id', $requisitionProduct->variant_id)
                ->select(DB::raw('SUM(purchase_products.quantity) as purchased_qty'))
                ->groupBy('purchase_products.product_id')
                ->groupBy('purchase_products.product_variant_id')
                ->get();

            $receivedProducts = DB::table('receive_stock_products')
                ->leftJoin('receive_stocks', 'receive_stock_products.receive_stock_id', 'receive_stocks.id')
                ->where('receive_stocks.requisition_id', $requisitionId)
                ->where('receive_stock_products.product_id', $requisitionProduct->product_id)
                ->where('receive_stock_products.variant_id', $requisitionProduct->variant_id)
                ->select(DB::raw('SUM(receive_stock_products.quantity) as received_qty'))
                ->groupBy('receive_stock_products.product_id')
                ->groupBy('receive_stock_products.variant_id')
                ->get();

            $purchaseOrReceivedQty = $purchaseProducts->sum('purchased_qty') + $receivedProducts->sum('received_qty');
            $leftQty = $requisitionProduct->quantity - $purchaseOrReceivedQty;

            $requisitionProduct->purchase_qty = $purchaseProducts->sum('purchased_qty');
            $requisitionProduct->received_qty = $receivedProducts->sum('received_qty');
            $requisitionProduct->left_qty = $leftQty;
            $requisitionProduct->save();
        }
    }
}
