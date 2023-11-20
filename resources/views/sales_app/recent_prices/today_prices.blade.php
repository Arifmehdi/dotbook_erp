@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Today Price - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.today_price')</h6>
                </div>
                <x-all-buttons/>
            </div>
        </div>

        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.created_at')</th>
                                    <th class="text-start">@lang('menu.created_by')</th>
                                    <th class="text-start">@lang('menu.item_name')</th>
                                    <th class="text-start">@lang('menu.previous_price')</th>
                                    <th class="text-start">@lang('menu.new_price')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prices as $price)
                                    <tr>
                                        <td>{{ date('d-m-Y h:m:s a', strtotime($price->created_at)) }}</td>
                                        <td>{{ $price->prefix . ' ' . $price->name . ' ' . $price->last_name }}</td>
                                        <td>{{ $price->p_name.($price->v_name ? ' - '.$price->v_name : '') }}</td>
                                        <td>{{ $price->previous_price }}</td>
                                        <td>{{ $price->new_price }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
