<?php

namespace App\Utils;

use App\Models\ReceiveStock;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ReceiveStockUtil
{
    public function receiveListTable($request, $converter)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();

        $receiveStocks = '';

        $query = DB::table('receive_stocks')->leftJoin('purchase_requisitions', 'receive_stocks.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('purchases', 'receive_stocks.id', 'purchases.receive_stock_id')
            ->leftJoin('purchases as po', 'receive_stocks.purchase_order_id', 'po.id')
            ->leftJoin('warehouses', 'receive_stocks.warehouse_id', 'warehouses.id')
            ->leftJoin('accounts as suppliers', 'receive_stocks.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'receive_stocks.created_by_id', 'created_by.id');

        if ($request->supplier_account_id) {

            $query->where('receive_stocks.supplier_account_id', $request->supplier_account_id);
        }

        if (! empty($request->status)) {

            if ($request->status == 'purchased') {

                $query->where('purchases.receive_stock_id', '!=', null);
            } elseif ($request->status == 'not-purchased') {

                $query->where('purchases.invoice_id', null);
            }
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('receive_stocks.date_ts', $date_range); // Final
        }

        $receiveStocks = $query->select(
            'receive_stocks.id',
            'receive_stocks.requisition_id',
            'receive_stocks.warehouse_id',
            'receive_stocks.date',
            'receive_stocks.date_ts',
            'receive_stocks.voucher_no',
            'receive_stocks.total_item',
            'receive_stocks.total_qty',
            'receive_stocks.status',
            'receive_stocks.note',
            'receive_stocks.challan_no',
            'receive_stocks.challan_date',
            'purchase_requisitions.requisition_no',
            'purchases.id as purchase_id',
            'purchases.invoice_id as p_invoice_id',
            'po.id as purchase_order_id',
            'po.invoice_id as po_id',
            'departments.name as dep_name',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        )->orderBy('receive_stocks.date_ts', 'desc');

        return DataTables::of($receiveStocks)
            ->addColumn('action', fn ($row) => $this->receiveStockActionBtn($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })

            ->editColumn('requisition_no', function ($row) {

                if ($row->requisition_id) {

                    return '<strong><a href="'.route('purchases.requisition.show', [$row->requisition_id]).'" id="details_btn">'.$row->requisition_no.'</a></strong>';
                }
            })

            ->editColumn('po_id', function ($row) {

                if ($row->po_id) {

                    return '<strong><a href="'.route('purchases.show.order', [$row->purchase_order_id]).'" id="details_btn">'.$row->po_id.'</a></strong>';
                }
            })

            ->editColumn('p_invoice_id', function ($row) {

                if ($row->purchase_id) {

                    return '<strong><a href="'.route('purchases.show', [$row->purchase_id]).'" id="details_btn">'.$row->p_invoice_id.'</a></strong>';
                }
            })

            ->editColumn('stored_location', function ($row) use ($generalSettings) {

                if ($row->warehouse_name) {

                    return $row->warehouse_name.'/'.$row->warehouse_code.'<b>(WH)</b>';
                } else {

                    return json_decode($generalSettings->business, true)['shop_name'];
                }
            })

            ->editColumn('department', function ($row) {

                return $row->dep_name ? $row->dep_name : '...';
            })

            ->editColumn('total_item', fn ($row) => '<span class="total_item" data-value="'.$row->total_item.'">'.$converter->format_in_bdt($row->total_item).'</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$converter->format_in_bdt($row->total_qty).'</span>')

            ->editColumn('status', function ($row) {

                if ($row->p_invoice_id == null) {

                    return '<span class="text-danger"><strong>Not-Purchased</strong></span>';
                } elseif ($row->p_invoice_id) {

                    return '<span class="text-success"><strong>Purchased</strong></span>';
                }
            })->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })
            ->rawColumns(['action', 'requisition_no', 'po_id', 'p_invoice_id', 'date', 'total_item', 'total_qty', 'due', 'stored_location', 'status', 'created_by'])
            ->make(true);
    }

    public function receiveStockStore($request, $codeGenerationService)
    {
        $addReceiveStock = new ReceiveStock();

        $addReceiveStock->voucher_no = $codeGenerationService->generateMonthWise('receive_stocks', 'voucher_no', 'RS', 4, 13, '-', '-');

        $addReceiveStock->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $addReceiveStock->supplier_account_id = $request->supplier_account_id;
        $addReceiveStock->created_by_id = auth()->user()->id;
        $addReceiveStock->total_item = $request->total_item;
        $addReceiveStock->total_qty = $request->total_qty;
        $addReceiveStock->note = $request->note;
        $addReceiveStock->requisition_id = $request->requisition_id;
        $addReceiveStock->purchase_order_id = $request->purchase_order_id;
        $addReceiveStock->challan_no = $request->challan_no;
        $addReceiveStock->challan_date = $request->challan_date;
        $addReceiveStock->net_weight = $request->net_weight;
        $addReceiveStock->vehicle_no = $request->vehicle_no;
        $addReceiveStock->date = $request->date;
        $addReceiveStock->date_ts = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addReceiveStock->save();

        return $addReceiveStock;
    }

    public function receiveStockUpdate($request, $receiveStock)
    {
        $receiveStock->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $receiveStock->supplier_account_id = $request->supplier_account_id;
        $receiveStock->total_item = $request->total_item;
        $receiveStock->total_qty = $request->total_qty;
        $receiveStock->note = $request->note;
        $receiveStock->requisition_id = $request->requisition_id;
        $receiveStock->purchase_order_id = $request->purchase_order_id;
        $receiveStock->challan_no = $request->challan_no;
        $receiveStock->challan_date = $request->challan_date;
        $receiveStock->net_weight = $request->net_weight;
        $receiveStock->vehicle_no = $request->vehicle_no;
        $receiveStock->date = $request->date;
        $time = date(' H:i:s', strtotime($receiveStock->date_ts));
        $receiveStock->date_ts = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $receiveStock->save();

        return $receiveStock;
    }

    public function deleteReceiveStock($requisitionId, $requisitionUtil, $productStockUtil, $userActivityLogUtil)
    {
        $deleteReceiveStock = ReceiveStock::with(['purchase', 'receiveStockProducts'])->where('id', $requisitionId)->first();

        if ($deleteReceiveStock->purchase) {

            return response()->json(['errorMsg' => 'The receive stock voucher can\'t be deleted, Associated with purchase']);
        }

        // Add user Log
        $userActivityLogUtil->addLog(action: 3, subject_type: 36, data_obj: $deleteReceiveStock);

        $deleteReceiveStock->delete();

        if ($deleteReceiveStock->requisition_id) {

            $requisitionUtil->updateRequisitionOrderPurchaseAndReceivedCount($deleteReceiveStock->requisition_id);
            $requisitionUtil->updateRequisitionLeftQty($deleteReceiveStock->requisition_id);
        }

        foreach ($deleteReceiveStock->receiveStockProducts as $receiveStockProduct) {

            $productStockUtil->adjustMainProductAndVariantStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id);

            if ($deleteReceiveStock->warehouse_id) {

                $productStockUtil->adjustWarehouseStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id, $deleteReceiveStock->warehouse_id);
            } else {

                $productStockUtil->adjustBranchStock($receiveStockProduct->product_id, $receiveStockProduct->variant_id, $deleteReceiveStock->warehouse_id);
            }
        }

        return 'Successfully received stock is deleted';
    }

    private function receiveStockActionBtn($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

        if (auth()->user()->can('receive_stocks_view')) {

            $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.receive.stocks.show', [$row->id]).'"> View</a>';
        }

        if (auth()->user()->can('receive_stocks_update')) {

            $html .= '<a class="dropdown-item" href="'.route('purchases.receive.stocks.edit', [$row->id]).' "> Edit</a>';
        }

        if (auth()->user()->can('receive_stocks_delete')) {

            $html .= '<a class="dropdown-item" id="delete" href="'.route('purchases.receive.stocks.delete', $row->id).'"> Delete</a>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
