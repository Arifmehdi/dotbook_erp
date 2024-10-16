@php $generator = new Picqer\Barcode\BarcodeGeneratorPNG(); @endphp
<style>
    @page {size:a4;margin-top: 0.8cm; /*margin-bottom: 35px;*/ margin-left: 4%;margin-right: 4%;}
</style>
 <!--Money Receipt design-->
 <div class="print_area">
    <div class="print_content">
        @if ($receipt->is_header_less == 0)
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
                    <p><strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b></p>
                    <p><strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b></p>
                </div>
            </div>
            <br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 class="text-uppercase"><strong>@lang('menu.money_receipt')</strong></h6>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <p><strong>@lang('menu.voucher_no')</strong> : {{ $receipt->invoice_id }}</p>
            </div>

            <div class="col-6 text-end">
                <p> <strong>@lang('menu.date')</strong> : {{ $receipt->is_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($receipt->date)) : '.......................................' }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <strong> Received With Thanks From </strong> : {{ $receipt->is_customer_name ? $receipt->cus_name : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Amount Of Money</strong> : {{ $receipt->amount > 0 ? json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($receipt->amount) : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>In Words</strong> :
                            @if ($receipt->amount > 0)
                                <span style="text-transform: uppercase;" id="inWord2"></span>.
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <strong>Paid To</strong>  : {{ $receipt->receiver }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>On Account Of</strong>  : {{ $receipt->ac_details }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <p><strong>Pay Method </strong> : Cash/Card/Bank-Transfer/Cheque/Advanced</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-center">
                <p><strong>{{ $receipt->note }}</strong></p>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6 class="borderTop">@lang('menu.customers_signature') </h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h6 class="borderTop">@lang('menu.signature_of_authority')</h6>
                </div>
            </div>
        </div>

        <div class="row page_break">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                @if (config('company.print_on_sale'))
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                @endif
            </div>
        </div>
    </div>

    <div class="print_content">
        @if ($receipt->is_header_less == 0)
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
                    <p><strong>@lang('menu.email') : </strong> <b>{{ json_decode($generalSettings->business, true)['email'] }}</b></p>
                    <p><strong>@lang('menu.phone') : </strong> <b>{{ json_decode($generalSettings->business, true)['phone'] }}</b></p>
                </div>
            </div>
            <br>
        @endif

        @if ($receipt->is_header_less == 1)
            @for ($i = 0; $i < $receipt->gap_from_top; $i++)
                <br>
            @endfor
        @endif

        <div class="row mt-2">
            <div class="col-12 text-center">
                <h6 class="text-uppercase"><strong>@lang('menu.money_receipt')</strong></h6>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <p><strong>@lang('menu.voucher_no')</strong> : {{ $receipt->invoice_id }}</p>
            </div>

            <div class="col-6 text-end">
                <p><strong>@lang('menu.date')</strong> : {{ $receipt->is_date ? date(json_decode($generalSettings->business, true)['date_format'] ,strtotime($receipt->date)) : '.......................................' }}</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p> <strong> Received With Thanks From </strong> :
                            {{ $receipt->is_customer_name ? $receipt->cus_name : ''}}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Amount Of Money</strong> : {{ $receipt->amount > 0 ? json_decode($generalSettings->business, true)['currency'].' '.App\Utils\Converter::format_in_bdt($receipt->amount) : ''}}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>In Words</strong> :
                            @if ($receipt->amount > 0)
                                <span style="text-transform: uppercase;" id="inWord1"></span>.
                            @endif
                        </p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>Paid To</strong> : {{ $receipt->receiver }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>

            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="row">
                    <div class="col-md-12">
                        <p><strong>On Account Of</strong>  : {{ $receipt->ac_details }}</p>
                    </div>
                    <div class="col-md-12">
                        <h6 class="borderTop d-block"></h6>
                    </div>
                </div>
            </div><br>
        </div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <p><strong>Pay Method</strong>  : Cash/Card/Bank-Transfer/Cheque/Advanced</p>
            </div>
        </div><br>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12 text-center">
                <p><strong>{{ $receipt->note }}</strong></p>
            </div>
        </div><br><br>

        <div class="row">
            <div class="col-md-6">
                <div class="details_area">
                    <h6 class="borderTop">@lang('menu.customers_signature') </h6>
                </div>
            </div>
            <div class="col-md-6">
                <div class="details_area text-end">
                    <h6 class="borderTop">@lang('menu.signature_of_authority') </h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-center">
                <img style="width: 170px; height:30px; margin-top:3px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($receipt->invoice_id, $generator::TYPE_CODE_128)) }}">
                @if (config('company.print_on_sale'))
                    <small class="d-block">@lang('menu.software_by') <b>@lang('menu.speedDigit_pvt_ltd') .</b></small>
                @endif
            </div>
        </div>
    </div>
</div>
<!--Money Receipt design end-->

<script>
    var a = ['','one ','two ','three ','four ', 'five ','six ','seven ','eight ','nine ','ten ','eleven ','twelve ','thirteen ','fourteen ','fifteen ','sixteen ','seventeen ','eighteen ','nineteen '];
    var b = ['', '', 'twenty','thirty','forty','fifty', 'sixty','seventy','eighty','ninety'];

    function inWords (num) {
          if ((num = num.toString()).length > 9) return 'overflow';
          n = ('000000000' + num).substr(-9).match(/^(\d{2})(\d{2})(\d{2})(\d{1})(\d{2})$/);
          if (!n) return; var str = '';
          str += (n[1] != 0) ? (a[Number(n[1])] || b[n[1][0]] + ' ' + a[n[1][1]]) + 'crore ' : '';
          str += (n[2] != 0) ? (a[Number(n[2])] || b[n[2][0]] + ' ' + a[n[2][1]]) + 'lakh ' : '';
          str += (n[3] != 0) ? (a[Number(n[3])] || b[n[3][0]] + ' ' + a[n[3][1]]) + 'thousand ' : '';
          str += (n[4] != 0) ? (a[Number(n[4])] || b[n[4][0]] + ' ' + a[n[4][1]]) + 'hundred ' : '';
          str += (n[5] != 0) ? ((str != '') ? 'and ' : '') + (a[Number(n[5])] || b[n[5][0]] + ' ' + a[n[5][1]]) + 'only ' : '';
          return str;
    }
    document.getElementById('inWord1').innerHTML = inWords(parseInt("{{ $receipt->amount }}"));
    document.getElementById('inWord2').innerHTML = inWords(parseInt("{{ $receipt->amount }}"));
  </script>
