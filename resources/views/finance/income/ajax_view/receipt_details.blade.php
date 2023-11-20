@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
@endphp
<div class="modal-dialog col-55-modal" role="document">
    <div class="modal-content payment_details_contant">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.receipt_details') </h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <div class="sale_payment_print_area">
                <div class="header_area">
                    <div class="company_name text-center">
                        <h3><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></h3>
                        <p>{{ json_decode($generalSettings->business, true)['address'] }}</p>
                        <h6><b>@lang('menu.income_receipt') @lang('menu.voucher')</b></h6>
                    </div>
                </div>

                <div class="reference_area pt-3">
                    <div class="row">
                        <div class="col-md-6">
                            <p><b>@lang('menu.income') @lang('menu.voucher_no') :</b> {{ $receipt->income->voucher_no }}</p>
                            <p><b>@lang('menu.receipt') @lang('menu.voucher_no') :</b> {{ $receipt->voucher_no }}</p>
                        </div>

                        <div class="col-md-6 text-end">
                            <p><b>@lang('menu.date') :</b> {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($receipt->report_date))  }}</p>
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

                                    <tr>
                                        <th class="text-startx">@lang('menu.received_amount') :</th>
                                        <td class="text-endx">
                                           <b>{{ json_decode($generalSettings->business, true)['currency'] }}
                                            {{ $receipt->amount }}</b>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-startx">@lang('menu.in_word') :</th>
                                        <td class="text-endx"><span id="inword"></span></td>
                                    </tr>

                                    <tr>
                                        <th class="text-startx">@lang('menu.method') :</th>
                                        <td class="text-endx">
                                            {{ $receipt->method ? $receipt->method->name : '' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <th class="text-startx">@lang('menu.note') :</th>
                                        <td class="text-endx">{{ $receipt->note }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="signature_area pt-5 mt-5 d-none">
                    <table class="w-100 mt-5">
                        <tbody>
                            <tr>
                                <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('menu.receiver')</p> </th>
                                <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('menu.made_by')</p></th>
                                <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('menu.account_manager')</p></th>
                                <th class="text-center"><p style="width: 70%; border-top:1px solid black;">@lang('menu.authority')</p></th>
                            </tr>

                            <tr class="text-center">
                                <td colspan="4" class="text-center">
                                    <img style="width: 170px; height:40px;" class="mt-3" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->voucher_no, $generator::TYPE_CODE_128)) }}">
                                </td>
                            </tr>

                            <tr class="text-center">
                                <td colspan="4" class="text-center">
                                    {{ $receipt->voucher_no }}
                                </td>
                            </tr>

                            @if (config('company.print_on_payment'))
                                <tr>
                                    <td colspan="4" class="text-navy-blue text-center"><small>@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small> </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 text-end">
                    <ul class="list-unstyled">
                        <li class="mt-1">
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                            <button type="submit" id="print_receipt" class="btn btn-sm btn-success">@lang('menu.print')</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // actual  conversion code starts here
    var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
    var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
    var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen',
        'nineteen'
    ];

    function convert_millions(num) {
        if (num >= 100000) {
            return convert_millions(Math.floor(num / 100000)) + " Lack " + convert_thousands(num % 1000000);
        } else {
            return convert_thousands(num);
        }
    }

    function convert_thousands(num) {
        if (num >= 1000) {
            return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
        } else {
            return convert_hundreds(num);
        }
    }

    function convert_hundreds(num) {
        if (num > 99) {
            return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
        } else {
            return convert_tens(num);
        }
    }

    function convert_tens(num) {
        if (num < 10) return ones[num];
        else if (num >= 10 && num < 20) return teens[num - 10];
        else {
            return tens[Math.floor(num / 10)] + " " + ones[num % 10];
        }
    }

    function convert(num) {
        if (num == 0) return "zero";
        else return convert_millions(num);
    }

    document.getElementById('inword').innerHTML = convert(parseInt("{{ $receipt->amount }}")).replace(
        'undefined', '(some Penny)').toUpperCase() + ' ONLY.';
</script>
