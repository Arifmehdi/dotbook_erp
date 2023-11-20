@foreach ($purchases as $purchase)
    <tr>
        <td>{{ $purchase->vehicle_no }}</td>
        <td>{{ $purchase->invoice_id }}</td>
        <td><a href="{{ route('purchases.edit', $purchase->id) }}"><i class="far fa-edit text-primary"></i></a></td>
    </tr>
@endforeach
