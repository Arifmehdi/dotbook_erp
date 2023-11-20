@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp

<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto, font-size:10px; }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    @page { size:a4; margin-top: 0.8cm; margin-bottom: 35px; margin-left: 15px;margin-right: 15px; }

    th { font-size:11px!important; font-weight: 550!important;}
    td { font-size:8px;}
</style>

<div class="sale_payment_print_area">
    <div class="col-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            @else

                <span style="font-family: 'Anton', sans-serif;font-size:17px;color:gray;font-weight: 550; letter-spacing:1px;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
            @endif

            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.income_voucher') </strong></h6>
    </div>
    <div class="reference_area pt-3">
        <div class="row">
            <div class="col-md-4">
                <p><strong> @lang('menu.voucher_no') :</strong> {{ $income->voucher_no }}</p>
            </div>

            <div class="col-md-4 text-end">
                <p><strong>@lang('menu.date') :</strong> {{ date('d/m/Y', strtotime($income->report_date)) }}</p>
            </div>

            <div class="col-md-4 text-end">
                <p><strong>@lang('menu.created_by') :</strong> {{ $income->createdBy ? $income->createdBy->prefix.' '.$income->createdBy->name.' '.$income->createdBy->last_name : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="total_amount_table_area pt-3">
        <div class="row">
            <div class="col-md-12">
                <table class="table modal-table table-sm">
                    <tbody>
                        <tr>
                            <th class="text-startx">@lang('menu.description'):</th>
                            <th class="text-endx">@lang('menu.amount')</th>
                        </tr>

                        @foreach ($income->incomeDescriptions as $incomeDescription)
                            <tr>
                                <td class="text-start">{{ $loop->index + 1 }}. {{ $incomeDescription->account->name }}</td>
                                <td class="text-start">{{ json_decode($generalSettings->business, true)['currency'] }} {{ $incomeDescription->amount }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <th class="text-end">@lang('menu.total') :</th>
                            <th class="text-start"><b>{{ json_decode($generalSettings->business, true)['currency'] }} {{ $income->total_amount }}</b></th>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('menu.received') :</th>
                            <th class="text-start">
                               <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $income->received }}</b>
                            </th>
                        </tr>

                        <tr>
                            <th class="text-start">@lang('menu.due') :</th>
                            <th class="text-start">
                               <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                {{ $income->due }}</b>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="reference_area pt-3">
        <div class="row">
            <div class="col-md-4">
                <p><strong>@lang('menu.note') :</strong> {{ $income->note }}</p>
            </div>
        </div>
    </div>

    <br><br>

    <div class="row">
        <div class="col-4 text-start">
            <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.prepared_by')</p>
        </div>

        <div class="col-4 text-center">
            <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.checked_by')</p>
        </div>

        <div class="col-4 text-end">
            <p style="display: inline; border-top: 1px solid black; padding:0px 10px; font-weight: 600;">@lang('menu.authorized_by')</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <img style="width:170px; height:30px;" class="mt-3" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($income->voucher_no, $generator::TYPE_CODE_128)) }}">
            <p>{{ $income->voucher_no }}</p>
        </div>
    </div>
</div>

<div style="position:fixed;bottom:0px;left:0px;width:100%;color: #000;" class="footer text-end">

    <div class="row">

        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
