@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s A';
@endphp
<div class="gate_pass_print_template">
    <style>
        @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 10px;margin-right: 10px;}

        h6 { font-size: 16px; }
        p {  font-size: 14px; }
        td {  color: black; }
    </style>
    <div class="details_area">
        <div class="row" style="border-bottom: 1px solid black;">
            <div class="col-4">
                @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                    <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                @else

                    <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                @endif
            </div>

            <div class="col-8 text-end">
                <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
                <p><b>{{ json_decode($generalSettings->business, true)['address'] }}</b></p>
                <p>
                    <strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b>,
                    <strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b>
                </p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 class="fw-bold text-uppercase">@lang('menu.gate_pass')</h6>
                <p class="fw-bold text-uppercase">@lang('menu.for_sold_items')</p>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong>@lang('menu.customer') : </strong> {{ $sale?->customer?->name  }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.address') : </strong> {{ $sale?->customer?->address }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.phone') : </strong> {{ $sale?->customer?->phone }}</li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong> @lang('menu.date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($sale->gatePass->created_at)) }}</li>
                    <li style="font-size:11px!important;"><strong> @lang('menu.gp_voucher_no') : </strong>{{ $sale->gatePass->voucher_no }}</li>
                    <li style="font-size:11px!important;"><strong> @lang('menu.user') : </strong> {{ $sale->gatePass->createdBy ? $sale->gatePass->createdBy->prefix . ' ' . $sale->gatePass->createdBy->name . ' ' . $sale->gatePass->createdBy->last_name : 'N/A' }} </li>
                </ul>
            </div>

            <div class="col-4">
                <ul class="list-unstyled">
                    <li style="font-size:11px!important;"><strong> @lang('menu.do_id') : </strong> {{ $sale?->do?->do_id }}</li>
                    <li style="font-size:11px!important;"><strong>@lang('menu.invoice_id') : </strong> {{ $sale->invoice_id }}</li>
                    <li style="font-size:11px!important;">
                        <strong>@lang('menu.invoice_date') : </strong> {{ date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($sale->date))  }}
                    </li>
                </ul>
            </div>
        </div>

        <div class="sale_product_table mt-2">
            <table class="table report-table table-sm table-bordered print_table">
                <thead>
                    <tr>
                        <th class="text-start" style="font-size:10px!important;">@lang('menu.sl')</th>
                        <th class="text-start" style="font-size:10px!important;">@lang('menu.item')</th>
                        <th class="text-start" style="font-size:10px!important;">@lang('menu.quantity')</th>
                        <th class="text-start" style="font-size:10px!important;">@lang('menu.unit')</th>
                    </tr>
                </thead>
                <tbody class="sale_print_product_list">
                    @foreach ($customerCopySaleProducts as $saleProduct)
                        <tr>
                            <td class="text-start" style="font-size:10px!important;">{{ $loop->index + 1 }}</td>
                            <td class="text-start" style="font-size:10px!important;">
                                {{ $saleProduct->p_name }}
                                @if ($saleProduct->product_variant_id)
                                    -{{ $saleProduct->variant_name }}
                                @endif
                                {!! '<br><small class="text-muted">' . $saleProduct->description . '</small>' !!}
                            </td>
                            <td class="text-start" style="font-size:10px!important;">{{ $saleProduct->quantity }} </td>
                            <td class="text-start" style="font-size:10px!important;">{{ $saleProduct->unit_code_name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <table class="modal-table">
            <tr>
                <td style="font-size:11px!important;">@lang('menu.vehicle_no')</td>
                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_car_number }}</td>
            </tr>

            <tr>
                <td style="font-size:11px!important;">@lang('menu.driver_name')</td>
                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_driver_name }}</td>
            </tr>

            <tr>
                <td style="font-size:11px!important;">@lang('menu.driver_phone') </td>
                <td style="font-size:11px!important;"> : {{ $sale?->weight?->do_driver_phone }}</td>
            </tr>
        </table>

        <br><br>
        <div class="row">
            <div class="col-3 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.receiver')</p>
            </div>

            <div class="col-3 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.main_gate')</p>
            </div>

            <div class="col-3 text-start">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.store_department')</p>
            </div>

            <div class="col-3 text-end">
                <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
            </div>
        </div>

        <div class="row mt-1">
            <div class="col-4 text-start">
                <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
            </div>

            <div class="col-4 text-center">
                @if (config('company.print_on_sale'))
                    <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                @endif
            </div>

            <div class="col-4 text-end">
                <small>@lang('menu.print_time') : {{ date($timeFormat) }}</small>
            </div>
        </div>
    </div>
</div>
