@foreach ($requisitions as $requisition)
    <li>
        <a href="#" id="selected_requisition" class="name" data-id="{{ $requisition->id }}" data-is_approved="{{ $requisition->is_approved }}">
            {{ $requisition->requisition_no }}
        </a>
    </li>
@endforeach