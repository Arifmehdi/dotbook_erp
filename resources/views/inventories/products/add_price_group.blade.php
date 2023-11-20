@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="container-fluid p-0">
        <div class="sec-name">
            <h6>@lang('menu.add_edit_price_group') </h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <form id="add_product_price_group_form" action="{{ route('products.save.price.groups') }}" method="POST">
                @csrf
                <input type="hidden" name="action_type" id="action_type" value="">
                <section>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="element-body">
                                    <div class="form_part">
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="heading_area">
                                                    <p><strong>@lang('menu.item') : {{ $product_name->name.' ('.$product_name->product_code.')' }}</strong> </p>
                                                    <small class="text-danger">@lang('menu.tax_will_added_price_group')</small>
                                                </div>
                                                <div class="table-responsive mt-1">
                                                    <table class="table modal-table table-sm">
                                                        <thead>
                                                            <tr class="bg-primary">
                                                                @if ($type == 1)
                                                                <th class="text-white text-start" scope="col">@lang('menu.variant')</th>
                                                                @endif
                                                                <th class="text-white text-center" scope="col">
                                                                    @lang('menu.default_selling_price') Exc.Tax
                                                                </th>
                                                                @foreach ($priceGroups as $pg)
                                                                <th class="text-white text-start" scope="col">
                                                                    {{ $pg->name }}
                                                                </th>
                                                                @endforeach
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($products as $item)
                                                            @if ($item->is_variant == 1)
                                                            <tr>
                                                                <td class="text-start">
                                                                    <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                                                                    <input type="hidden" name="variant_ids[]" value="{{ $item->v_id }}">
                                                                    {{ $item->variant_name }}
                                                                </td>
                                                                <td class="text-center">

                                                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $item->variant_price}}</b>
                                                                </td>
                                                                @foreach ($priceGroups as $pg)
                                                                <td class="text-start">
                                                                    @php
                                                                    $existsPrice = DB::table('price_group_products')
                                                                    ->where('price_group_id', $pg->id)
                                                                    ->where('product_id', $item->p_id)
                                                                    ->where('variant_id', $item->v_id)->first(['price']);
                                                                    @endphp
                                                                    @if ($existsPrice)
                                                                    <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][{{ $item->v_id }}]" type="number" step="any" class="form-control" value="{{ ($existsPrice->price) }}">
                                                                    @else
                                                                    <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][{{ $item->v_id }}]" type="number" step="any" class="form-control" value="0.00">
                                                                    @endif
                                                                </td>
                                                                @endforeach
                                                            </tr>
                                                            @else
                                                            <tr>
                                                                <td class="text-center">
                                                                    <input type="hidden" name="product_ids[]" value="{{ $item->p_id }}">
                                                                    <input type="hidden" name="variant_ids[]" value="noid">
                                                                    <b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $item->product_price }}</b>
                                                                </td>
                                                                @foreach ($priceGroups as $pg)
                                                                <td>
                                                                    @php
                                                                    $existsPrice = DB::table('price_group_products')
                                                                    ->where('price_group_id', $pg->id)
                                                                    ->where('product_id', $item->p_id)->first(['price']);
                                                                    @endphp
                                                                    @if ($existsPrice)
                                                                    <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][noid]" type="number" step="any" class="form-control" value="{{ $existsPrice->price }}">
                                                                    @else
                                                                    <input name="group_prices[{{ $pg->id }}][{{ $item->p_id }}][noid]" type="number" step="any" class="form-control" value="0.00">
                                                                    @endif
                                                                </td>
                                                                @endforeach
                                                            </tr>
                                                            @endif
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="btn-box">
                                <button type="button" class="btn loading_button btn-sm d-none"><i class="fas fa-spinner"></i></button>
                                <button type="submit" name="action" value="save_and_new" class="btn btn-success submit_button w-auto">@lang('menu.save_and_add_another')</button>
                                <button type="submit" name="action" value="save" class="btn btn-success submit_button w-auto">@lang('menu.save')</button>
                            </div>
                        </div>
                    </div>
                </section>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Add or edit product price group by ajax
    $('#add_product_price_group_form').on('submit', function(e) {
        e.preventDefault();
        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        $.ajax({
            url: url
            , type: 'post'
            , data: request
            , success: function(data) {
                $('.loading_button').hide();
                if (!$.isEmptyObject(data.saveMessage)) {
                    toastr.success(data.saveMessage);
                    window.location = "{{ route('products.all.product') }}";
                } else if (!$.isEmptyObject(data.saveAndAnotherMsg)) {
                    toastr.success(data.saveAndAnotherMsg);
                    window.location = "{{ route('products.add.view') }}";
                }
            }
        });
    });

    $(document).on('click', '.submit_button', function(e) {
        var value = $(this).val();
        $('#action_type').val(value);
    });

</script>
@endpush
