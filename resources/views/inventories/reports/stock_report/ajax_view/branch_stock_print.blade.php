<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page {size:a4;margin-top: 0.8cm;margin-bottom: 35px; margin-left: 20px;margin-right: 20px;}
    th { font-size:10px!important; font-weight: 550!important;}
    td { font-size:9px;}
</style>
@php
    $totalExpense = 0;
    $totalPaid = 0;
    $totalDue = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        <h5>{{ json_decode($generalSettings->business, true)['shop_name'] }} </h5>
        <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
        <h6 style="margin-top: 10px;"><b>@lang('menu.business_location_stock_report') </b></h6>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-startx">@lang('menu.p_code')</th>
                    <th class="text-startx">@lang('menu.item')</th>
                    <th class="text-startx">@lang('menu.business_location')</th>
                    <th class="text-endx">@lang('menu.unit')</th>
                    <th class="text-endx">@lang('menu.current_stock')</th>
                    <th class="text-endx">@lang('menu.stock_value') <b><small>(@lang('menu.by_nit_cost'))</small></b></th>
                    <th class="text-endx">@lang('menu.total_sold')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @foreach ($branch_stock as $row)
                    @if ($row->variant_name)
                        <tr>
                            <td class="text-start">{{ $row->variant_code }}</td>
                            <td class="text-start">{{ $row->name.'-'.$row->variant_name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] . '  ' !!}</td>
                            <td class="text-endx">{{ App\Utils\Converter::format_in_bdt($row->variant_price) }}</td>
                            <td class="text-endx">{{ $row->variant_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-endx">
                                @php
                                    $currentStockValue = $row->variant_cost_with_tax * $row->variant_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td>
                            <td class="text-endx">{{ $row->v_total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td class="text-start">{{ $row->product_code }}</td>
                            <td class="text-start">{{ $row->name }}</td>
                            <td class="text-start">{!! $row->b_name ? $row->b_name.'/'.$row->branch_code.'<b>(BL)<b/>' : json_decode($generalSettings->business, true)['shop_name'] . '  ' !!}</td>
                            <td class="text-endx">{{ App\Utils\Converter::format_in_bdt($row->product_price) }}</td>
                            <td class="text-endx">{{ $row->product_quantity.'('.$row->code_name.')' }}</td>
                            <td class="text-endx">
                                @php
                                    $currentStockValue = $row->product_cost_with_tax * $row->product_quantity;
                                @endphp
                                {{ App\Utils\Converter::format_in_bdt($currentStockValue) }}
                            </td class="text-end">
                            <td class="text-endx">{{ $row->total_sale.'('.$row->code_name.')' }}</td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@if (config('company.print_on_others'))
    <div class="row">
        <div class="col-md-12 text-center">
            <small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
        </div>
    </div>
@endif

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">
    <small style="font-size: 5px;" class="text-end">
        @lang('menu.print_date'): {{ date('d-m-Y , h:iA') }}
    </small>
</div>
