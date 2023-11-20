@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp

<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog four-col-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('menu.income_details') (@lang('menu.voucher_no') : <strong><span>{{ $income->voucher_no }}</span></strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 text-start">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.business_location') : </strong>
                                {{ json_decode($generalSettings->business, true)['shop_name'] }}</b>
                            </li>
                            <li><strong>@lang('menu.address') : </strong>{{ json_decode($generalSettings->business, true)['address'] }}</li>
                            <li><strong>@lang('menu.phone') : </strong>{{ json_decode($generalSettings->business, true)['phone'] }}</li>
                        </ul>
                    </div>

                    <div class="col-md-6 text-start">
                        <ul class="list-unstyled">
                            <li>
                                <strong> @lang('menu.voucher_no') : </strong> {{ $income->voucher_no }}
                            </li>

                            <li>
                                <strong>@lang('menu.date') : </strong> {{ date('d/m/Y', strtotime($income->report_date)) }}
                            </li>

                            <li><strong>@lang('menu.receive_status') : </strong>
                                @php
                                    $receivable = $income->total_amount - $income->received;
                                @endphp
                                @if ($income->due <= 0)

                                    <span class="badge bg-success"> @lang('menu.received') </span>
                                @elseif ($income->due > 0 && $income->due < $receivable)

                                    <span class="badge bg-primary text-white">@lang('menu.partial')</span>
                                @elseif ($receivable == $income->due)

                                    <span class="badge bg-danger text-white">@lang('menu.due')</span>
                                @endif
                            </li>

                            <li><strong>@lang('menu.created_by') : </strong>
                                {{ $income->createdBy ? $income->createdBy->prefix.' '.$income->createdBy->name.' '.$income->createdBy->last_name : 'N/A' }}
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="table-responsive">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">@lang('menu.serial')</th>
                                    <th class="text-start">@lang('menu.description')</th>
                                    <th class="text-start">@lang('menu.amount')</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @foreach ($income->incomeDescriptions as $incomeDescription)
                                    @php
                                        $accountType = '';
                                        if ($incomeDescription->account->account_type == 24) {

                                            $accountType = 'Direct Income : ';
                                        } elseif ($incomeDescription->account->account_type == 25) {

                                            $accountType = 'Indirect Income : ';
                                        } else {

                                            $accountType = 'Misc. Income A/c : ';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="text-start">{{ $loop->index + 1 }}</td>
                                        <td class="text-start">{{ $accountType.$incomeDescription->account->name }}</td>
                                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($incomeDescription->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <p><strong>@lang('menu.receipt_list')</strong></p>
                    <div class="col-md-7">
                        <table id="" class="table modal-table table-sm table-striped">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th class="text-start">@lang('menu.date')</th>
                                    <th class="text-start">@lang('menu.voucher_no')</th>
                                    <th class="text-start">@lang('menu.method')</th>
                                    <th class="text-start">@lang('menu.account')</th>
                                    <th class="text-start">@lang('menu.amount')</th>
                                </tr>
                            </thead>
                            <tbody class="sale_product_list">
                                @foreach ($income->incomeReceipts as $receipt)
                                    <tr>
                                        <td class="text-start">{{ $receipt->date }}</td>
                                        <td class="text-start">{{ $receipt->voucher_no }}</td>
                                        <td class="text-start">{{ $receipt->method ? $receipt->method->name : 'N/A' }}</td>
                                        <td class="text-start">{{ $receipt->account ? $receipt->account->name.' (A/c: '. $receipt->account->account_number : 'N/A' }}</td>
                                        <td class="text-start">{{ App\Utils\Converter::format_in_bdt($receipt->amount) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-5">
                        <div class="table-responsive">
                            <table class="table modal-table table-sm">
                                <tr>
                                    <th class="text-end">@lang('menu.total_amount') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->total_amount) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">@lang('menu.received') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->received) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-end">@lang('menu.due') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($income->due) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="details_area">
                            <p><strong>@lang('menu.income_note')</strong> : </p>
                            <p>{{ $income->note ? $income->note : 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="footer_btn btn btn-sm btn-primary print_btn" id="print_income_btn">@lang('menu.print')  </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->

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

<div class="income_details_print_area d-none">
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

    <div class="row">
        <div class="col-md-12">
            <table class="table modal-table table-sm">
                <tbody>
                    <tr>
                        <th class="text-start">@lang('menu.description'):</th>
                        <th class="text-start">@lang('menu.amount')</th>
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
                        <th class="text-end">@lang('menu.received') :</th>
                        <th class="text-start">
                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $income->received }}</b>
                        </th>
                    </tr>

                    <tr>
                        <th class="text-end">@lang('menu.due') :</th>
                        <th class="text-start">
                            <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                            {{ $income->due }}</b>
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p><strong>@lang('menu.inword') :</strong> {{ $income->note }}</p>
        </div>

        <div class="col-md-12">
            <p><strong>@lang('menu.note') :</strong> {{ $income->note }}</p>
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


<script>
  var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b= ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function inWords (num) {
        if ((num = num.toString()).length > 9) return 'overflow';
        n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
        if (!n) return; var str = '';
        str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
        str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
        str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
        str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
        str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + ' ' : '';
        return str;
    }
    var inWordDiv = document.getElementById('inword');
    if(inWordDiv != null) {
        document.getElementById('inword').innerHTML = inWords(parseInt("{{ $income->total_amount }}"));
    }
</script>
