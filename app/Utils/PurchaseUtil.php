<?php

namespace App\Utils;

use App\Models\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PurchaseUtil
{
    public $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function addPurchase($request, $codeGenerationService, $invoicePrefix)
    {
        $__invoicePrefix = $invoicePrefix != null ? $invoicePrefix : 'PI';
        $invoiceId = $codeGenerationService->generateMonthAndTypeWise(table: 'purchases', column: 'invoice_id', typeColName: 'purchase_status', typeValue: 1, prefix: $__invoicePrefix, splitter: '-', suffixSeparator: '-');

        $addPurchase = new Purchase();
        $addPurchase->invoice_id = $invoiceId;
        $addPurchase->warehouse_id = $request->warehouse_id ? $request->warehouse_id : null;
        $addPurchase->supplier_account_id = $request->supplier_account_id;
        $addPurchase->purchase_account_id = $request->purchase_account_id;
        $addPurchase->admin_id = auth()->user()->id;
        $addPurchase->total_item = $request->total_item;
        $addPurchase->total_qty = $request->total_qty;
        $addPurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $addPurchase->order_discount_type = $request->order_discount_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->tax_ac_id = $request->purchase_tax_ac_id;
        $addPurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $addPurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $addPurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $addPurchase->ait_deduction = $request->ait_deduction ? $request->ait_deduction : 0.00;
        $addPurchase->ait_deduction_amount = $request->ait_deduction_amount ? $request->ait_deduction_amount : 0.00;
        $addPurchase->ait_deduction_type = $request->ait_deduction_type;
        $addPurchase->order_discount_amount = $request->order_discount_amount;
        $addPurchase->net_total_amount = $request->net_total_amount;
        $addPurchase->total_purchase_amount = $request->total_invoice_amount;
        $addPurchase->purchase_note = $request->purchase_note;
        $addPurchase->purchase_status = $request->purchase_status;
        $addPurchase->is_purchased = 1;
        $addPurchase->requisition_id = $request->requisition_id;
        $addPurchase->receive_stock_id = $request->receive_stock_id;
        $addPurchase->purchase_by_scale_id = $request->purchase_by_scale_id;
        $addPurchase->challan_no = $request->challan_no;
        $addPurchase->challan_date = $request->challan_date;
        $addPurchase->carrier = $request->carrier;
        $addPurchase->vehicle_no = $request->vehicle_no;
        $addPurchase->net_weight = $request->net_weight;
        $addPurchase->date = $request->date;
        $addPurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date.date(' H:i:s')));
        $addPurchase->is_last_created = 1;

        // Additional expenses
        $addPurchase->labour_cost = $request->labour_cost ? $request->labour_cost : 0;
        $addPurchase->transport_cost = $request->transport_cost ? $request->transport_cost : 0;
        $addPurchase->scale_charge = $request->scale_charge ? $request->scale_charge : 0;
        $addPurchase->others = $request->others ? $request->others : 0;
        $addPurchase->total_additional_expense = $request->total_additional_expense ? $request->total_additional_expense : 0;
        $addPurchase->total_expense_with_item = $request->total_expense_with_item ? $request->total_expense_with_item : 0;
        $addPurchase->save();

        return $addPurchase;
    }

    public function updatePurchase($updatePurchase, $request)
    {
        foreach ($updatePurchase->purchaseProducts as $purchaseProduct) {

            $purchaseProduct->delete_in_update = 1;
            $purchaseProduct->save();
        }

        $updatePurchase->warehouse_id = isset($request->warehouse_count) ? $request->warehouse_id : null;

        // update purchase total information
        $updatePurchase->supplier_account_id = $request->supplier_account_id;
        $updatePurchase->purchase_account_id = $request->purchase_account_id;
        $updatePurchase->total_item = $request->total_item;
        $updatePurchase->total_qty = $request->total_qty;
        $updatePurchase->net_total_amount = $request->net_total_amount;
        $updatePurchase->order_discount = $request->order_discount ? $request->order_discount : 0.00;
        $updatePurchase->order_discount_type = $request->order_discount_type;
        $updatePurchase->order_discount_amount = $request->order_discount_amount;
        $updatePurchase->tax_ac_id = $request->purchase_tax_ac_id;
        $updatePurchase->purchase_tax_percent = $request->purchase_tax_percent ? $request->purchase_tax_percent : 0.00;
        $updatePurchase->purchase_tax_amount = $request->purchase_tax_amount ? $request->purchase_tax_amount : 0.00;
        $updatePurchase->shipment_charge = $request->shipment_charge ? $request->shipment_charge : 0.00;
        $updatePurchase->ait_deduction = $request->ait_deduction ? $request->ait_deduction : 0.00;
        $updatePurchase->ait_deduction_amount = $request->ait_deduction_amount ? $request->ait_deduction_amount : 0.00;
        $updatePurchase->ait_deduction_type = $request->ait_deduction_type;
        $updatePurchase->total_purchase_amount = $request->total_invoice_amount;
        $updatePurchase->purchase_note = $request->purchase_note;
        $updatePurchase->purchase_status = 1;
        $updatePurchase->date = $request->date;
        $updatePurchase->requisition_id = $request->requisition_id;
        $updatePurchase->receive_stock_id = $request->receive_stock_id;
        $updatePurchase->purchase_by_scale_id = $request->purchase_by_scale_id;
        $updatePurchase->challan_no = $request->challan_no;
        $updatePurchase->challan_date = $request->challan_date;
        $updatePurchase->carrier = $request->carrier;
        $updatePurchase->vehicle_no = $request->vehicle_no;
        $updatePurchase->net_weight = $request->net_weight;
        $time = date(' H:i:s', strtotime($updatePurchase->report_date));
        $updatePurchase->report_date = date('Y-m-d H:i:s', strtotime($request->date.$time));

        // Additional expenses
        $updatePurchase->labour_cost = $request->labour_cost ? $request->labour_cost : 0;
        $updatePurchase->transport_cost = $request->transport_cost ? $request->transport_cost : 0;
        $updatePurchase->scale_charge = $request->scale_charge ? $request->scale_charge : 0;
        $updatePurchase->others = $request->others ? $request->others : 0;
        $updatePurchase->total_additional_expense = $request->total_additional_expense ? $request->total_additional_expense : 0;
        $updatePurchase->total_expense_with_item = $request->total_expense_with_item ? $request->total_expense_with_item : 0;
        $updatePurchase->save();

        return $updatePurchase;
    }

    public function purchaseListTable($request, $supplierAccountId)
    {
        $generalSettings = DB::table('general_settings')->select('business')->first();

        $purchases = '';

        $query = DB::table('purchases')->where('purchases.is_purchased', 1);

        if ($supplierAccountId) {

            $query->where('purchases.supplier_account_id', $supplierAccountId);
        }

        if (! empty($request->warehouse_id)) {

            $query->where('purchases.warehouse_id', $request->warehouse_id);
        }

        if ($request->supplier_account_id) {

            $query->where('purchases.supplier_account_id', $request->supplier_account_id);
        }

        if ($request->purchase_account_id) {

            $query->where('purchases.purchase_account_id', $request->purchase_account_id);
        }

        if ($request->user_id) {

            $query->where('purchases.admin_id', $request->user_id);
        }

        if ($request->from_date) {

            $from_date = date('Y-m-d', strtotime($request->from_date));
            $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
            $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
            $query->whereBetween('purchases.report_date', $date_range); // Final
        }

        $purchases = $query->leftJoin('purchase_requisitions', 'purchases.requisition_id', 'purchase_requisitions.id')
            ->leftJoin('receive_stocks', 'purchases.receive_stock_id', 'receive_stocks.id')
            ->leftJoin('purchase_requisitions as rs_requisitions', 'receive_stocks.requisition_id', 'rs_requisitions.id')
            ->leftJoin('purchases as po', 'receive_stocks.purchase_order_id', 'po.id')
            ->leftJoin('departments as rs_req_departments', 'rs_requisitions.department_id', 'rs_req_departments.id')
            ->leftJoin('expanses', 'purchases.id', 'expanses.purchase_ref_id')
            ->leftJoin('departments', 'purchase_requisitions.department_id', 'departments.id')
            ->leftJoin('warehouses', 'purchases.warehouse_id', 'warehouses.id')
            ->leftJoin('accounts as suppliers', 'purchases.supplier_account_id', 'suppliers.id')
            ->select(
                'purchases.id',
                'purchases.warehouse_id',
                'purchases.date',
                'purchases.invoice_id',
                'purchases.is_return_available',
                'purchases.total_qty',
                'purchases.total_purchase_amount',
                'purchases.total_additional_expense',
                'purchases.due',
                'purchases.paid',
                'purchases.purchase_status',
                'purchases.purchase_note',
                'purchases.purchase_return_amount',
                'expanses.voucher_no as expense_voucher',
                'purchase_requisitions.id as pr_id',
                'purchase_requisitions.requisition_no',
                'receive_stocks.id as rs_id',
                'receive_stocks.voucher_no as rs_voucher_no',
                'rs_requisitions.id as rs_pr_id',
                'rs_requisitions.requisition_no as rs_requisition_no',
                'rs_req_departments.name as rs_req_department',
                'po.id as purchase_order_id',
                'po.invoice_id as po_id',
                'departments.name as dep_name',
                'warehouses.warehouse_name',
                'warehouses.warehouse_code',
                'suppliers.name as supplier_name',
            )->orderBy('purchases.report_date', 'desc');

        return DataTables::of($purchases)
            ->addColumn('action', fn ($row) => $this->createPurchaseAction($row))

            ->editColumn('date', function ($row) use ($generalSettings) {

                return date(json_decode($generalSettings->business, true)['date_format'], strtotime($row->date));
            })

            ->editColumn('supplier_name', function ($row) {

                return '<span title="'.$row->supplier_name.'">'.Str::limit($row->supplier_name, 17).'</span>';
            })

            ->editColumn('invoice_id', function ($row) {

                $html = '';
                $html .= $row->invoice_id;
                $html .= $row->is_return_available ? ' <span class="badge bg-danger p-1"><i class="fas fa-undo text-white"></i></span>' : '';

                return $html;
            })

            ->editColumn('requisition_no', function ($row) {

                if ($row->pr_id || $row->rs_pr_id) {

                    $id = $row->pr_id ? $row->pr_id : $row->rs_pr_id;

                    return '<strong><a href="'.route('purchases.requisition.show', [$id]).'" id="details_btn">'.$row->requisition_no.$row->rs_requisition_no.'</a></strong>';
                }
            })

            ->editColumn('po_id', function ($row) {

                if ($row->po_id) {

                    return '<strong><a href="'.route('purchases.show.order', [$row->purchase_order_id]).'" id="details_btn">'.$row->po_id.'</a></strong>';
                }
            })

            ->editColumn('rs_voucher_no', function ($row) {

                if ($row->rs_id) {

                    return '<strong><a href="'.route('purchases.receive.stocks.show', [$row->rs_id]).'" id="details_btn">'.$row->rs_voucher_no.'</a></strong>';
                }
            })

            ->editColumn('department', function ($row) {

                return $row->dep_name ? $row->dep_name.$row->rs_req_department : '...';
            })

            ->editColumn('total_purchase_amount', fn ($row) => '<span class="total_purchase_amount" data-value="'.$row->total_purchase_amount.'">'.$this->converter->format_in_bdt($row->total_purchase_amount).'</span>')

            ->editColumn('total_qty', fn ($row) => '<span class="total_qty" data-value="'.$row->total_qty.'">'.$this->converter->format_in_bdt($row->total_qty).'</span>')

            ->editColumn('total_additional_expense', fn ($row) => '<span class="total_additional_expense" data-value="'.$row->total_additional_expense.'">'.$this->converter->format_in_bdt($row->total_additional_expense).'</span>')

            ->editColumn('paid', fn ($row) => '<span class="paid text-success" data-value="'.$row->paid.'">'.$this->converter->format_in_bdt($row->paid).'</span>')

            ->editColumn('due', fn ($row) => '<span class="text-danger">'.'<span class="due" data-value="'.$row->due.'">'.$this->converter->format_in_bdt($row->due).'</span></span>')

            ->rawColumns(['action', 'date', 'invoice_id', 'supplier_name', 'requisition_no', 'po_id', 'rs_voucher_no', 'total_qty', 'total_purchase_amount', 'total_additional_expense', 'paid', 'due'])
            ->make(true);
    }

    private function createPurchaseAction($row)
    {
        $html = '<div class="btn-group" role="group">';
        $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
        $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

        $html .= '<a class="dropdown-item" id="details_btn" href="'.route('purchases.show', [$row->id]).'"> View</a>';

        if (auth()->user()->can('payments_add')) {

            $html .= '<a class="dropdown-item" id="add_purchase_payment" href="'.route('purchases.payments.create', $row->id).'"> Add Payment</a>';
        }

        if (auth()->user()->can('purchase_edit')) {

            $html .= '<a class="dropdown-item" href="'.route('purchases.edit', [$row->id]).' "> Edit</a>';
        }

        if (auth()->user()->can('purchase_delete')) {

            $html .= '<a class="dropdown-item" id="delete" href="'.route('purchase.delete', $row->id).'"> Delete</a>';
        }

        // $html .= '<a class="dropdown-item" id="items_notification" href="#"><i class="fas fa-envelope text-primary"></i> Items Received Notification</a>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    public function adjustPurchaseInvoiceAmounts($purchase)
    {
        $totalPurchasePaid = DB::table('payment_description_references')
            ->leftJoin('payment_descriptions', 'payment_description_references.payment_description_id', 'payment_descriptions.id')
            ->leftJoin('payments', 'payment_descriptions.payment_id', 'payments.id')
            ->where('payment_description_references.purchase_id', $purchase->id)->where('payments.payment_type', 2)
            ->select(DB::raw('sum(payment_description_references.amount) as total_paid'))
            ->groupBy('payment_description_references.purchase_id')
            ->get();

        $return = DB::table('purchase_returns')
            ->where('purchase_returns.purchase_id', $purchase->id)
            ->select(DB::raw('sum(total_return_amount) as total_return_amount'))
            ->groupBy('purchase_returns.purchase_id')
            ->get();

        $returnAmount = $return->sum('total_return_amount') ? $return->sum('total_return_amount') : 0;

        $due = $purchase->total_purchase_amount - $totalPurchasePaid->sum('total_paid') - $returnAmount;

        $purchase->paid = $totalPurchasePaid->sum('total_paid');
        $purchase->due = $due;
        $purchase->purchase_return_amount = $returnAmount;
        $purchase->save();

        return $purchase;
    }

    public function adjustPurchaseLeftQty($purchaseProduct)
    {
        $totalSold = DB::table('purchase_sale_product_chains')
            ->where('purchase_product_id', $purchaseProduct->id)
            ->select(DB::raw('SUM(sold_qty) as total_sold'))
            ->groupBy('purchase_product_id')->get();

        $leftQty = $purchaseProduct->quantity - $totalSold->sum('total_sold');
        $purchaseProduct->left_qty = $leftQty;
        $purchaseProduct->save();
    }
}
