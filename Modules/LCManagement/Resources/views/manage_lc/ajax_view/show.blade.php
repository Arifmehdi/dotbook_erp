 <!-- Details Modal -->
 <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog col-65-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    LC Details (LC. No : <strong>{{ $lc->lc_no }}</strong>)
                </h5>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-unstyled">
                            <li><strong>LC No : </strong>{{ $lc->lc_no }}</li>

                            <li><strong>Opening Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->opening_date)) }}
                            </li>

                            <li><strong>Last Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->last_date)) }}
                            </li>

                            <li><strong>@lang('menu.expire_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->expire_date)) }}
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>Issuing Bank : </strong>{{ $lc->issuingBank ? $lc->issuingBank->name : '' }}</li>
                            <li><strong>Opening Bank : </strong>{{ $lc->openingBank ? $lc->openingBank->name : '' }}</li>
                            <li><strong>Advising Bank : </strong>{{ $lc->advisingBank ? $lc->advisingBank->name : '' }}</li>
                        </ul>
                    </div>

                    <div class="col-md-4 text-left">
                        <ul class="list-unstyled">
                            <li><strong>@lang('menu.supplier') : </strong> {{ $lc->supplier ? $lc->supplier->name.'('.$lc->supplier->phone.')' : '' }}</li>
                            <li><strong>Insurance Company: </strong> {{ $lc->insurance_company }}</li>
                            <li><strong>@lang('menu.created_by'): </strong> {{ $lc->createdBy ? $lc->createdBy->prefix.' '.$lc->createdBy->name.' '.$lc->createdBy->last_name : '' }}</li>
                        </ul>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="" class="table modal-table table-sm table-striped">
                                <tbody class="purchase_product_list">
                                       <tr>
                                           <th class="text-start">LC Amount : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->lc_amount) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Currency : </th>
                                           <td class="text-end">{{ $lc->currency ? $lc->currency->code : 'N/A' }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Currency Rate : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->currency_rate) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Total LC Amount : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->total_amount) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">LC Margin Amount : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->lc_margin_amount) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Insurance Payable Amt : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->insurance_payable_amt) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Mode Of Shipment Amount : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->mode_of_amount) }}</td>
                                       </tr>

                                       <tr>
                                           <th class="text-start">Total LC Payable Amount : </th>
                                           <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->total_payable_amt) }}</td>
                                       </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-success print_btn m-0 me-2">@lang('menu.print')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
<!-- Details Modal End-->
<style>
   @media print
   {
       table { page-break-after:auto }
       tr    { page-break-inside:avoid; page-break-after:auto }
       td    { page-break-inside:avoid; page-break-after:auto }
       thead { display:table-header-group }
       tfoot { display:table-footer-group }
   }

   @page {size:a4;margin-top: 0.8cm;margin-bottom: 33px; margin-left: 20px;margin-right: 20px;}
   .header, .header-space,
   .footer, .footer-space {height: 20px;}
   .header {position: fixed;top: 0;}
   .footer {position: fixed;bottom: 0;}
   .noBorder {border: 0px !important;}
   tr.noBorder td {border: 0px !important;}
   tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}
</style>
@php
    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
@endphp
<!-- Lc print templete-->
   <div class="lc_print_template d-none">
       <div class="details_area">
           <div class="heading_area">
               <div class="row">
                   <div class="col-md-4 col-sm-4 col-lg-4">
                        @if (json_decode($generalSettings->business, true)['business_logo'] != null)

                            <img src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
                        @else

                            <span style="font-family: 'Anton', sans-serif;font-size:15px;color:gray;">{{ json_decode($generalSettings->business, true)['shop_name'] }}</span>
                        @endif
                   </div>
                   <div class="col-md-4 col-sm-4 col-lg-4">
                       <div class="heading text-center">
                           <h3 class="bill_name">LC</h3>
                       </div>
                   </div>
                   <div class="col-md-4 col-sm-4 col-lg-4">

                   </div>
               </div>
           </div>

           <div class="purchase_and_deal_info pt-3">
               <div class="row">
                   <div class="col-4">
                       <ul class="list-unstyled">
                            <li><strong>LC No : </strong>{{ $lc->lc_no }}</li>

                            <li><strong>Opening Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->opening_date)) }}
                            </li>

                            <li><strong>Last Date : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->last_date)) }}
                            </li>

                            <li><strong>@lang('menu.expire_date') : </strong>
                                {{ date(json_decode($generalSettings->business, true)['date_format'], strtotime($lc->expire_date)) }}
                            </li>
                       </ul>
                   </div>

                   <div class="col-4">
                       <ul class="list-unstyled">
                            <li><strong>Issuing Bank : </strong>{{ $lc->issuingBank ? $lc->issuingBank->name : '' }}</li>
                            <li><strong>Opening Bank : </strong>{{ $lc->openingBank ? $lc->openingBank->name : '' }}</li>
                            <li><strong>Advising Bank : </strong>{{ $lc->advisingBank ? $lc->advisingBank->name : '' }}</li>
                       </ul>
                   </div>

                   <div class="col-4">
                       <ul class="list-unstyled">
                            <li><strong>@lang('menu.supplier') : </strong> {{ $lc->supplier ? $lc->supplier->name.'('.$lc->supplier->phone.')' : '' }}</li>
                            <li><strong>Insurance Company : </strong> {{ $lc->insurance_company }}</li>
                            <li><strong>@lang('menu.created_by') : </strong> {{ $lc->createdBy ? $lc->createdBy->prefix.' '.$lc->createdBy->name.' '.$lc->createdBy->last_name : '' }}</li>
                       </ul>
                   </div>
               </div>
           </div>

           <div class="purchase_product_table pt-3 pb-3">
                <table id="" class="table report-table table-sm table-bordered print_table">
                    <tbody class="purchase_product_list">
                        <tr>
                            <th class="text-start">LC Amount : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->lc_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Currency : </th>
                            <td class="text-end">{{ $lc->currency ? $lc->currency->code : 'N/A' }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Currency Rate : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->currency_rate) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total LC Amount : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->total_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">LC Margin Amount : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->lc_margin_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Insurance Payable Amt : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->insurance_payable_amt) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Mode Of Shipment Amount : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->mode_of_amount) }}</td>
                        </tr>

                        <tr>
                            <th class="text-start">Total LC Payable Amount : </th>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($lc->total_payable_amt) }}</td>
                        </tr>
                    </tbody>
                </table>
           </div>

           <br>
           <div class="row">
               <div class="col-md-6">
                   <h6>@lang('menu.checked_by') : </h6>
               </div>

               <div class="col-md-6 text-end">
                   <h6>@lang('menu.approved_by') : </h6>
               </div>
           </div>

           <div class="row">
               <div class="col-md-12 text-center">
                   <img style="width: 170px; height:25px;" src="data:image/png;base64,{{ base64_encode($generator->getBarcode($lc->lc_no, $generator::TYPE_CODE_128)) }}">
                   <p>{{ $lc->lc_no }}</p>
               </div>
           </div>

            <div id="footer">
                <div class="row mt-1">
                    <div class="col-4 text-center">
                        <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
                    </div>

                    <div class="col-4 text-center">
                        @if (config('company.print_on_sale'))
                            <small class="d-block">@lang('menu.software_by') <strong>@lang('menu.speedDigit_pvt_ltd') .</strong></small>
                        @endif
                    </div>

                    <div class="col-4 text-center">
                        <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
                    </div>
                </div>
            </div>
       </div>
   </div>
<!-- Lc print templete end-->
