@foreach ($purchaseOrders as $po)
    <li>
        <a href="#" id="selected_po" class="name" data-purchase_order_id="{{ $po->purchase_order_id }}" data-po_id="{{ $po->po_id }}" data-supplier_account_id="{{ $po->supplier_account_id }}" data-warehouse_id="{{ $po->warehouse_id }}" data-supplier_account_id="" data-requisition_no="{{ $po->requisition_no }}" data-requisition_id="{{ $po->requisition_id }}">
            {{ $po->po_id }}
        </a>
    </li>
@endforeach