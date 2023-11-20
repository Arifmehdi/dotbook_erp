<?php

namespace App\Utils;

use App\Models\Purchase;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseOrderUtil
{
    public function addPurchaseOrder($request, $codeGenerationService)
    {
        $poId = $codeGenerationService->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: 3, prefix: 'PO', splitter: '-', suffixSeparator: '-');
        $addOrder = new Purchase();
        $addOrder->invoice_id = $poId;
        $addOrder->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $addOrder->requisition_id = $request->requisition_id;
        $addOrder->supplier_account_id = $request->supplier_account_id;
        $addOrder->purchase_account_id = $request->purchase_account_id;
        $addOrder->admin_id = auth()->user()->id;
        $addOrder->total_item = $request->total_item;
        $addOrder->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addOrder->order_discount_type = $request->order_discount_type;
        $addOrder->order_discount_amount = $request->order_discount_amount;
        $addOrder->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $addOrder->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addOrder->net_total_amount = $request->net_total_amount;
        $addOrder->total_purchase_amount = $request->total_invoice_amount;
        // $addOrder->paid = $request->paying_amount;
        // $addOrder->due = $request->purchase_due;
        $addOrder->shipment_details = $request->shipment_details;
        $addOrder->purchase_note = $request->purchase_note;
        $addOrder->purchase_status = 3;
        $addOrder->is_purchased = 0;
        $addOrder->po_qty = $request->total_qty;
        $addOrder->po_pending_qty = $request->total_qty;
        $addOrder->po_receiving_status = 'Pending';
        $addOrder->date = $request->date;
        $addOrder->delivery_date = $request->delivery_date;
        $addOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addOrder->time = date('h:i:s a');
        $addOrder->is_last_created = 1;
        $addOrder->save();

        return $addOrder;
    }

    public function updatePurchaseOrder($updatePurchaseOrder, $request)
    {
        $updatePurchaseOrder->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;
        $updatePurchaseOrder->purchase_account_id = $request->purchase_account_id;
        $updatePurchaseOrder->requisition_id = $request->requisition_id;
        $updatePurchaseOrder->total_item = $request->total_item;
        $updatePurchaseOrder->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $updatePurchaseOrder->order_discount_type = $request->order_discount_type;
        $updatePurchaseOrder->order_discount_amount = $request->order_discount_amount;
        $updatePurchaseOrder->purchase_tax_percent = $request->purchase_tax ? $request->purchase_tax : 0.00;
        $updatePurchaseOrder->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $updatePurchaseOrder->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updatePurchaseOrder->net_total_amount = $request->net_total_amount;
        $updatePurchaseOrder->total_purchase_amount = $request->total_invoice_amount;
        $updatePurchaseOrder->shipment_details = $request->shipment_details;
        $updatePurchaseOrder->purchase_note = $request->purchase_note;
        $updatePurchaseOrder->purchase_status = $request->purchase_status;
        $updatePurchaseOrder->date = $request->date;
        $updatePurchaseOrder->delivery_date = $request->delivery_date;
        $time = date(' H:i:s', strtotime($updatePurchaseOrder->report_date));
        $updatePurchaseOrder->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));
        $updatePurchaseOrder->save();

        return $updatePurchaseOrder;
    }

    public function updatePoQtyAndStatusPortion($purchaseOrder)
    {
        $purchaseOrderProducts = DB::table('purchase_order_products')->where('purchase_order_products.purchase_id', $purchaseOrder->id)
            ->select(
                DB::raw('sum(order_quantity) as ordered_qty'),
                DB::raw('sum(pending_quantity) as pending_quantity'),
                DB::raw('sum(received_quantity) as received_quantity')
            )->groupBy('purchase_order_products.purchase_id')->get();

        $purchaseOrder->po_qty = $purchaseOrderProducts->sum('ordered_qty');
        $purchaseOrder->po_pending_qty = $purchaseOrderProducts->sum('pending_quantity');
        $purchaseOrder->po_received_qty = $purchaseOrderProducts->sum('received_quantity');

        if ($purchaseOrderProducts->sum('pending_quantity') == 0) {

            $purchaseOrder->po_receiving_status = 'Completed';
        } elseif ($purchaseOrderProducts->sum('ordered_qty') == $purchaseOrderProducts->sum('pending_quantity')) {

            $purchaseOrder->po_receiving_status = 'Pending';
        } elseif ($purchaseOrderProducts->sum('received_quantity') > 0) {

            $purchaseOrder->po_receiving_status = 'Partial';
        }

        $purchaseOrder->save();
    }

    public function poListTable($request, $supplierAccountId)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();
        $purchases = '';
        $query = DB::table('purchases')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('purchase_requisitions', 'purchases.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->leftJoin('users as created_by', 'purchases.admin_id', 'created_by.id');

        if ($supplierAccountId) {

            $query->where('purchases.supplier_account_id', $supplierAccountId);
        }

        if (! empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->receive_status) {

            $query->where('purchases.po_receiving_status', $request->receive_status);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $query->select(
            'purchases.id',
            'purchases.warehouse_id',
            'purchases.date',
            'purchases.invoice_id',
            'purchases.is_return_available',
            'purchases.total_purchase_amount',
            'purchases.purchase_return_amount',
            'purchases.purchase_return_due',
            'purchases.due',
            'purchases.paid',
            'purchases.purchase_status',
            'purchases.po_receiving_status',
            'purchase_requisitions.requisition_no',
            'warehouses.warehouse_name',
            'warehouses.warehouse_code',
            'suppliers.name as supplier_name',
            'created_by.prefix as created_prefix',
            'created_by.name as created_name',
            'created_by.last_name as created_last_name',
        );

        $purchases = $query->where('purchases.purchase_status', 3)
            ->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseOrderAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return $html;
            })
            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="'.$row->total_purchase_amount.'">'.\App\Utils\Converter::format_in_bdt($row->total_purchase_amount).'</span>')

            // ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="' . $row->paid . '">' . $this->converter->format_in_bdt($row->paid) . '</span>')

            // ->editColumn('due', fn ($row) => '<span class="due text-danger" data-value="' . $row->due . '">' . $this->converter->format_in_bdt($row->due) . '</span>')

            ->editColumn('status', function ($row) {

                if ($row->po_receiving_status == 'Completed') {

                    return '<span class="text-success"><b>Completed</b></span>';
                } elseif ($row->po_receiving_status == 'Pending') {

                    return '<span class="text-danger"><b>Pending</b></span>';
                } elseif ($row->po_receiving_status == 'Partial') {

                    return '<span class="text-primary"><b>Partial</b></span>';
                }
            })
            // ->editColumn('payment_status', function ($row) {

            //     $payable = $row->total_purchase_amount - $row->purchase_return_amount;
            //     if ($row->due <= 0) {

            //         return '<span class="text-success"><b>Paid</b></span>';
            //     } elseif ($row->due > 0 && $row->due < $payable) {

            //         return '<span class="text-primary"><b>Partial</b></span>';
            //     } elseif ($payable == $row->due) {

            //         return '<span class="text-danger"><b>Due</b></span>';
            //     }
            // })
            ->editColumn('created_by', function ($row) {

                return $row->created_prefix.' '.$row->created_name.' '.$row->created_last_name;
            })
            ->rawColumns(['action', 'date', 'invoice_id', 'total_purchase_amount', 'paid', 'due', 'purchase_return_amount', 'purchase_return_due', 'payment_status', 'status', 'created_by'])
            ->make(true);
    }

    private function createPurchaseOrderAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
        $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.show.order', [$row->id]).'"> View</a>';

        if (auth()->user()->can('edit_po')) {

            $html .= '<a class="dropdown-item" href="'.route('purchases.order.edit', [$row->id, 'ordered']).' "> Edit</a>';
        }

        if (auth()->user()->can('delete_po')) {

            $html .= '<a class="dropdown-item" id="delete" href="'.route('purchases.order.delete', $row->id).'"> Delete</a>';
        }

        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
