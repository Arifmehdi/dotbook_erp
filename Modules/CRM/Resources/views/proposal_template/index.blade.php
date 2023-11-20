@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- select 2 --}}
    

    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush
@section('title', 'CRM - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Proposal Template</h6>
                </div>
                <x-all-buttons>
                    <x-add-button />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card m-0">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table TemplateTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">Name</th>
                                            <th class="text-start">Type</th>
                                            <th class="text-start">@lang('crm.subject')</th>
                                            <th class="text-start">@lang('crm.body')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 12%;width: 90%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">New Proposal</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="proposal_template_send" action="{{ route('crm.proposal_template.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row" id="to_area">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>CC</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="cc" class="form-control" data-name="To"
                                            id="to" placeholder="CC" />
                                        <span><small>Comma separated values of emails</small></span>
                                        <span class="error error_to"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>BCC</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" name="bcc" class="form-control" data-name="To"
                                            id="bcc" placeholder="BCC" />
                                        <span><small>Comma separated values of emails</small></span>
                                        <span class="error error_to"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>Subject<span
                                                class="text-danger">*</span></strong> </label>
                                    <div class="col-md-9">
                                        <input required type="text" name="subject" class="form-control"
                                            data-name="Subject" id="subject" placeholder="Subject" />
                                        <span class="error error_subject"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="rel_type"><strong>Related</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select name="rel_type" id="rel_type" class="form-control "
                                            data-none-selected-text="Nothing selected">
                                            <option value=""></option>
                                            <option value="lead">Lead</option>
                                            <option value="customer">Customer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="customer_leads"><strong
                                            class="customer_leads_label">Customer/Leads</strong> </label>
                                    <div class="col-md-9">
                                        <select name="customer_leads" id="customer_leads" class="form-control"
                                            data-none-selected-text="Nothing selected">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="proposal_date"><strong>Date <i
                                                class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="proposal_date" name="date" class="form-control"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="oprn_till_date"><strong>Open Till
                                            <i class="fa-regular fa-calendar calendar-icon"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="oprn_till_date" name="open_till" class="form-control"
                                            autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"
                                        for="currency"><strong>currency</strong> </label>
                                    <div class="col-md-9">
                                        <select name="currency" id="currency" class="form-control "
                                            data-show-subtext="1" data-base="1" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value=""></option>
                                            <option value="1" selected data-subtext="$">USD</option>
                                            <option value="2" data-subtext="€">EUR</option>
                                            <option value="3" data-subtext="DH">MAD</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"
                                        for="discount_before_after_tax"><strong>Discount Type</strong> </label>
                                    <div class="col-md-9">
                                        <select name="discount_before_after_tax" id="discount_before_after_tax"
                                            class="form-control " data-show-subtext="1" data-base="1" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value="after_tax">After Tax</option>
                                            <option value="before_tax">Before Tax</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong><i class="fa fa-tag"
                                                aria-hidden="true"></i></strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" class="selectize_tags form-control" id="tags"
                                            name="tags" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="status"><strong>Status</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select name="status" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected">
                                            <option value="1">Open</option>
                                            <option value="2">Declined</option>
                                            <option value="3">Accepted</option>
                                            <option value="4">Sent</option>
                                            <option value="5">Revised</option>
                                            <option value="6">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"
                                        for="assigned"><strong>Assigned</strong> </label>
                                    <div class="col-md-9">
                                        <select id="assigned" name="assigned" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value=""></option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>Attachment</strong> </label>
                                    <div class="col-md-9">
                                        <input type="file" name="proposal_file[]" class="form-control"
                                            data-name="file" id="file" placeholder="Attachments" multiple />
                                        <span class="d-block" style="line-height: 20px;font-size: 10px;">Max File Size :
                                            5Mb</span>
                                        <span class="d-block" style="line-height: 12px;font-size: 10px;">Allowed File:
                                            .pdf, .csv,
                                            .zip, .doc, .docx, .jpeg, .jpg, .png</span>
                                        <span class="error error_file"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="status"><strong>Item</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <div class="input-group">
                                            <select name="item_id" id="item_id" class="form-control form-select">
                                                <option value="">Select Item</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-text add_button" id="addMaterialItem"><i
                                                    class="fas fa-plus-square text-dark"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="proposal_to"><strong>Name</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="proposal_to" name="proposal_to" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="email"><strong>Email</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="email" name="email" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="phone"><strong>Phone</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="address"><strong>Address</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="address" name="address" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="city"><strong>City</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="city" name="city" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="state"><strong>State</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <input type="text" id="state" name="state" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="zip"><strong>Zip
                                            Code</strong> </label>
                                    <div class="col-md-9">
                                        <input type="text" id="zip" name="zip" class="form-control"
                                            value="">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end" for="country"><strong>country</strong>
                                    </label>
                                    <div class="col-md-9">
                                        <select id="country" name="country" class=" form-control" data-width="100%"
                                            data-none-selected-text="Nothing selected" data-live-search="true">
                                            <option value="0">Select Country</option>
                                            <option value="1" data-subtext="BN">Bangladesh</option>
                                            <option value="2" data-subtext="IN">India</option>
                                        </select>
                                        {{-- <select id="country" name="country" class=" form-control form-select" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true">
                                        <option value=""></option>
                                        <option value="1" data-subtext="AF">Afghanistan</option>
                                        <option value="2" data-subtext="AX">Aland Islands</option>
                                        <option value="3" data-subtext="AL">Albania</option>
                                        <option value="4" data-subtext="DZ">Algeria</option>
                                        <option value="5" data-subtext="AS">American Samoa</option>
                                        <option value="6" data-subtext="AD">Andorra</option>
                                        <option value="7" data-subtext="AO">Angola</option>
                                        <option value="8" data-subtext="AI">Anguilla</option>
                                        <option value="9" data-subtext="AQ">Antarctica</option>
                                        <option value="10" data-subtext="AG">Antigua and Barbuda</option>
                                        <option value="11" data-subtext="AR">Argentina</option>
                                        <option value="12" data-subtext="AM">Armenia</option>
                                        <option value="13" data-subtext="AW">Aruba</option>
                                        <option value="14" data-subtext="AU">Australia</option>
                                        <option value="15" data-subtext="AT">Austria</option>
                                        <option value="16" data-subtext="AZ">Azerbaijan</option>
                                        <option value="17" data-subtext="BS">Bahamas</option>
                                        <option value="18" data-subtext="BH">Bahrain</option>
                                        <option value="19" data-subtext="BD">Bangladesh</option>
                                        <option value="20" data-subtext="BB">Barbados</option>
                                        <option value="21" data-subtext="BY">Belarus</option>
                                        <option value="22" data-subtext="BE">Belgium</option>
                                        <option value="23" data-subtext="BZ">Belize</option>
                                        <option value="24" data-subtext="BJ">Benin</option>
                                        <option value="25" data-subtext="BM">Bermuda</option>
                                        <option value="26" data-subtext="BT">Bhutan</option>
                                        <option value="27" data-subtext="BO">Bolivia</option>
                                        <option value="28" data-subtext="BQ">Bonaire, Sint Eustatius and Saba</option>
                                        <option value="29" data-subtext="BA">Bosnia and Herzegovina</option>
                                        <option value="30" data-subtext="BW">Botswana</option>
                                        <option value="31" data-subtext="BV">Bouvet Island</option>
                                        <option value="32" data-subtext="BR">Brazil</option>
                                        <option value="33" data-subtext="IO">British Indian Ocean Territory</option>
                                        <option value="34" data-subtext="BN">Brunei</option>
                                        <option value="35" data-subtext="BG">Bulgaria</option>
                                        <option value="36" data-subtext="BF">Burkina Faso</option>
                                        <option value="37" data-subtext="BI">Burundi</option>
                                        <option value="38" data-subtext="KH">Cambodia</option>
                                        <option value="39" data-subtext="CM">Cameroon</option>
                                        <option value="40" data-subtext="CA">Canada</option>
                                        <option value="41" data-subtext="CV">Cape Verde</option>
                                        <option value="42" data-subtext="KY">Cayman Islands</option>
                                        <option value="43" data-subtext="CF">Central African Republic</option>
                                        <option value="44" data-subtext="TD">Chad</option>
                                        <option value="45" data-subtext="CL">Chile</option>
                                        <option value="46" data-subtext="CN">China</option>
                                        <option value="47" data-subtext="CX">Christmas Island</option>
                                        <option value="48" data-subtext="CC">Cocos (Keeling) Islands</option>
                                        <option value="49" data-subtext="CO">Colombia</option>
                                        <option value="50" data-subtext="KM">Comoros</option>
                                        <option value="51" data-subtext="CG">Congo</option>
                                        <option value="52" data-subtext="CK">Cook Islands</option>
                                        <option value="53" data-subtext="CR">Costa Rica</option>
                                        <option value="54" data-subtext="CI">Cote d'ivoire (Ivory Coast)</option>
                                        <option value="55" data-subtext="HR">Croatia</option>
                                        <option value="56" data-subtext="CU">Cuba</option>
                                        <option value="57" data-subtext="CW">Curacao</option>
                                        <option value="58" data-subtext="CY">Cyprus</option>
                                        <option value="59" data-subtext="CZ">Czech Republic</option>
                                        <option value="60" data-subtext="CD">Democratic Republic of the Congo</option>
                                        <option value="61" data-subtext="DK">Denmark</option>
                                        <option value="62" data-subtext="DJ">Djibouti</option>
                                        <option value="63" data-subtext="DM">Dominica</option>
                                        <option value="64" data-subtext="DO">Dominican Republic</option>
                                        <option value="65" data-subtext="EC">Ecuador</option>
                                        <option value="66" data-subtext="EG">Egypt</option>
                                        <option value="67" data-subtext="SV">El Salvador</option>
                                        <option value="68" data-subtext="GQ">Equatorial Guinea</option>
                                        <option value="69" data-subtext="ER">Eritrea</option>
                                        <option value="70" data-subtext="EE">Estonia</option>
                                        <option value="71" data-subtext="ET">Ethiopia</option>
                                        <option value="72" data-subtext="FK">Falkland Islands (Malvinas)</option>
                                        <option value="73" data-subtext="FO">Faroe Islands</option>
                                        <option value="74" data-subtext="FJ">Fiji</option>
                                        <option value="75" data-subtext="FI">Finland</option>
                                        <option value="76" data-subtext="FR">France</option>
                                        <option value="77" data-subtext="GF">French Guiana</option>
                                        <option value="78" data-subtext="PF">French Polynesia</option>
                                        <option value="79" data-subtext="TF">French Southern Territories</option>
                                        <option value="80" data-subtext="GA">Gabon</option>
                                        <option value="81" data-subtext="GM">Gambia</option>
                                        <option value="82" data-subtext="GE">Georgia</option>
                                        <option value="83" data-subtext="DE">Germany</option>
                                        <option value="84" data-subtext="GH">Ghana</option>
                                        <option value="85" data-subtext="GI">Gibraltar</option>
                                        <option value="86" data-subtext="GR">Greece</option>
                                        <option value="87" data-subtext="GL">Greenland</option>
                                        <option value="88" data-subtext="GD">Grenada</option>
                                        <option value="89" data-subtext="GP">Guadaloupe</option>
                                        <option value="90" data-subtext="GU">Guam</option>
                                        <option value="91" data-subtext="GT">Guatemala</option>
                                        <option value="92" data-subtext="GG">Guernsey</option>
                                        <option value="93" data-subtext="GN">Guinea</option>
                                        <option value="94" data-subtext="GW">Guinea-Bissau</option>
                                        <option value="95" data-subtext="GY">Guyana</option>
                                        <option value="96" data-subtext="HT">Haiti</option>
                                        <option value="97" data-subtext="HM">Heard Island and McDonald Islands
                                        </option>
                                        <option value="98" data-subtext="HN">Honduras</option>
                                        <option value="99" data-subtext="HK">Hong Kong</option>
                                        <option value="100" data-subtext="HU">Hungary</option>
                                        <option value="101" data-subtext="IS">Iceland</option>
                                        <option value="102" data-subtext="IN">India</option>
                                        <option value="103" data-subtext="ID">Indonesia</option>
                                        <option value="104" data-subtext="IR">Iran</option>
                                        <option value="105" data-subtext="IQ">Iraq</option>
                                        <option value="106" data-subtext="IE">Ireland</option>
                                        <option value="107" data-subtext="IM">Isle of Man</option>
                                        <option value="108" data-subtext="IL">Israel</option>
                                        <option value="109" data-subtext="IT">Italy</option>
                                        <option value="110" data-subtext="JM">Jamaica</option>
                                        <option value="111" data-subtext="JP">Japan</option>
                                        <option value="112" data-subtext="JE">Jersey</option>
                                        <option value="113" data-subtext="JO">Jordan</option>
                                        <option value="114" data-subtext="KZ">Kazakhstan</option>
                                        <option value="115" data-subtext="KE">Kenya</option>
                                        <option value="116" data-subtext="KI">Kiribati</option>
                                        <option value="117" data-subtext="XK">Kosovo</option>
                                        <option value="118" data-subtext="KW">Kuwait</option>
                                        <option value="119" data-subtext="KG">Kyrgyzstan</option>
                                        <option value="120" data-subtext="LA">Laos</option>
                                        <option value="121" data-subtext="LV">Latvia</option>
                                        <option value="122" data-subtext="LB">Lebanon</option>
                                        <option value="123" data-subtext="LS">Lesotho</option>
                                        <option value="124" data-subtext="LR">Liberia</option>
                                        <option value="125" data-subtext="LY">Libya</option>
                                        <option value="126" data-subtext="LI">Liechtenstein</option>
                                        <option value="127" data-subtext="LT">Lithuania</option>
                                        <option value="128" data-subtext="LU">Luxembourg</option>
                                        <option value="129" data-subtext="MO">Macao</option>
                                        <option value="131" data-subtext="MG">Madagascar</option>
                                        <option value="132" data-subtext="MW">Malawi</option>
                                        <option value="133" data-subtext="MY">Malaysia</option>
                                        <option value="134" data-subtext="MV">Maldives</option>
                                        <option value="135" data-subtext="ML">Mali</option>
                                        <option value="136" data-subtext="MT">Malta</option>
                                        <option value="137" data-subtext="MH">Marshall Islands</option>
                                        <option value="138" data-subtext="MQ">Martinique</option>
                                        <option value="139" data-subtext="MR">Mauritania</option>
                                        <option value="140" data-subtext="MU">Mauritius</option>
                                        <option value="141" data-subtext="YT">Mayotte</option>
                                        <option value="142" data-subtext="MX">Mexico</option>
                                        <option value="143" data-subtext="FM">Micronesia</option>
                                        <option value="144" data-subtext="MD">Moldava</option>
                                        <option value="145" data-subtext="MC">Monaco</option>
                                        <option value="146" data-subtext="MN">Mongolia</option>
                                        <option value="147" data-subtext="ME">Montenegro</option>
                                        <option value="148" data-subtext="MS">Montserrat</option>
                                        <option value="149" data-subtext="MA">Morocco</option>
                                        <option value="150" data-subtext="MZ">Mozambique</option>
                                        <option value="151" data-subtext="MM">Myanmar (Burma)</option>
                                        <option value="152" data-subtext="NA">Namibia</option>
                                        <option value="153" data-subtext="NR">Nauru</option>
                                        <option value="154" data-subtext="NP">Nepal</option>
                                        <option value="155" data-subtext="NL">Netherlands</option>
                                        <option value="156" data-subtext="NC">New Caledonia</option>
                                        <option value="157" data-subtext="NZ">New Zealand</option>
                                        <option value="158" data-subtext="NI">Nicaragua</option>
                                        <option value="159" data-subtext="NE">Niger</option>
                                        <option value="160" data-subtext="NG">Nigeria</option>
                                        <option value="161" data-subtext="NU">Niue</option>
                                        <option value="162" data-subtext="NF">Norfolk Island</option>
                                        <option value="163" data-subtext="KP">North Korea</option>
                                        <option value="130" data-subtext="MK">North Macedonia</option>
                                        <option value="164" data-subtext="MP">Northern Mariana Islands</option>
                                        <option value="165" data-subtext="NO">Norway</option>
                                        <option value="166" data-subtext="OM">Oman</option>
                                        <option value="167" data-subtext="PK">Pakistan</option>
                                        <option value="168" data-subtext="PW">Palau</option>
                                        <option value="169" data-subtext="PS">Palestine</option>
                                        <option value="170" data-subtext="PA">Panama</option>
                                        <option value="171" data-subtext="PG">Papua New Guinea</option>
                                        <option value="172" data-subtext="PY">Paraguay</option>
                                        <option value="173" data-subtext="PE">Peru</option>
                                        <option value="174" data-subtext="PH">Philippines</option>
                                        <option value="175" data-subtext="PN">Pitcairn</option>
                                        <option value="176" data-subtext="PL">Poland</option>
                                        <option value="177" data-subtext="PT">Portugal</option>
                                        <option value="178" data-subtext="PR">Puerto Rico</option>
                                        <option value="179" data-subtext="QA">Qatar</option>
                                        <option value="180" data-subtext="RE">Reunion</option>
                                        <option value="181" data-subtext="RO">Romania</option>
                                        <option value="182" data-subtext="RU">Russia</option>
                                        <option value="183" data-subtext="RW">Rwanda</option>
                                        <option value="184" data-subtext="BL">Saint Barthelemy</option>
                                        <option value="185" data-subtext="SH">Saint Helena</option>
                                        <option value="186" data-subtext="KN">Saint Kitts and Nevis</option>
                                        <option value="187" data-subtext="LC">Saint Lucia</option>
                                        <option value="188" data-subtext="MF">Saint Martin</option>
                                        <option value="189" data-subtext="PM">Saint Pierre and Miquelon</option>
                                        <option value="190" data-subtext="VC">Saint Vincent and the Grenadines</option>
                                        <option value="191" data-subtext="WS">Samoa</option>
                                        <option value="192" data-subtext="SM">San Marino</option>
                                        <option value="193" data-subtext="ST">Sao Tome and Principe</option>
                                        <option value="194" data-subtext="SA">Saudi Arabia</option>
                                        <option value="195" data-subtext="SN">Senegal</option>
                                        <option value="196" data-subtext="RS">Serbia</option>
                                        <option value="197" data-subtext="SC">Seychelles</option>
                                        <option value="198" data-subtext="SL">Sierra Leone</option>
                                        <option value="199" data-subtext="SG">Singapore</option>
                                        <option value="200" data-subtext="SX">Sint Maarten</option>
                                        <option value="201" data-subtext="SK">Slovakia</option>
                                        <option value="202" data-subtext="SI">Slovenia</option>
                                        <option value="203" data-subtext="SB">Solomon Islands</option>
                                        <option value="204" data-subtext="SO">Somalia</option>
                                        <option value="205" data-subtext="ZA">South Africa</option>
                                        <option value="206" data-subtext="GS">South Georgia and the South Sandwich Islands</option>
                                        <option value="207" data-subtext="KR">South Korea</option>
                                        <option value="208" data-subtext="SS">South Sudan</option>
                                        <option value="209" data-subtext="ES">Spain</option>
                                        <option value="210" data-subtext="LK">Sri Lanka</option>
                                        <option value="211" data-subtext="SD">Sudan</option>
                                        <option value="212" data-subtext="SR">Suriname</option>
                                        <option value="213" data-subtext="SJ">Svalbard and Jan Mayen</option>
                                        <option value="214" data-subtext="SZ">Swaziland</option>
                                        <option value="215" data-subtext="SE">Sweden</option>
                                        <option value="216" data-subtext="CH">Switzerland</option>
                                        <option value="217" data-subtext="SY">Syria</option>
                                        <option value="218" data-subtext="TW">Taiwan</option>
                                        <option value="219" data-subtext="TJ">Tajikistan</option>
                                        <option value="220" data-subtext="TZ">Tanzania</option>
                                        <option value="221" data-subtext="TH">Thailand</option>
                                        <option value="222" data-subtext="TL">Timor-Leste (East Timor)</option>
                                        <option value="223" data-subtext="TG">Togo</option>
                                        <option value="224" data-subtext="TK">Tokelau</option>
                                        <option value="225" data-subtext="TO">Tonga</option>
                                        <option value="226" data-subtext="TT">Trinidad and Tobago</option>
                                        <option value="227" data-subtext="TN">Tunisia</option>
                                        <option value="228" data-subtext="TR">Turkey</option>
                                        <option value="229" data-subtext="TM">Turkmenistan</option>
                                        <option value="230" data-subtext="TC">Turks and Caicos Islands</option>
                                        <option value="231" data-subtext="TV">Tuvalu</option>
                                        <option value="232" data-subtext="UG">Uganda</option>
                                        <option value="233" data-subtext="UA">Ukraine</option>
                                        <option value="234" data-subtext="AE">United Arab Emirates</option>
                                        <option value="235" data-subtext="GB">United Kingdom</option>
                                        <option value="236" data-subtext="US">United States</option>
                                        <option value="237" data-subtext="UM">United States Minor Outlying Islands</option>
                                        <option value="238" data-subtext="UY">Uruguay</option>
                                        <option value="239" data-subtext="UZ">Uzbekistan</option>
                                        <option value="240" data-subtext="VU">Vanuatu</option>
                                        <option value="241" data-subtext="VA">Vatican City</option>
                                        <option value="242" data-subtext="VE">Venezuela</option>
                                        <option value="243" data-subtext="VN">Vietnam</option>
                                        <option value="244" data-subtext="VG">Virgin Islands, British</option>
                                        <option value="245" data-subtext="VI">Virgin Islands, US</option>
                                        <option value="246" data-subtext="WF">Wallis and Futuna</option>
                                        <option value="247" data-subtext="EH">Western Sahara</option>
                                        <option value="248" data-subtext="YE">Yemen</option>
                                        <option value="249" data-subtext="ZM">Zambia</option>
                                        <option value="250" data-subtext="ZW">Zimbabwe</option>
                                    </select> --}}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="control-label col-md-3 text-end"><strong>Body </strong></label>
                                    <div class="col-md-9">
                                        <textarea id="editor" name="description" id="description"></textarea>
                                        <span class="error error_description"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Short Description</th>
                                            <th>Long Description</th>
                                            <th>Qty</th>
                                            <th>Exc. Rate</th>
                                            <th>Exc./Inc. </th>
                                            <th>Tax</th>
                                            <th>Discount</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th><i class="fa fa-cog" aria-hidden="true"></i></th>
                                            {{-- <th><button type="button" class="btn btn-primary btn-sm  mb-2" id="addMoreButton"><i class="fas fa-plus px-1"></i></button></th> --}}
                                        </tr>
                                    </thead>
                                    <tbody id="dup_tr"></tbody>
                                    <tfoot>
                                        <tr rowspan="3">
                                            <td colspan="7"></td>
                                            <td>Sub-total</td>
                                            <td><input type="text" name="sub_total" class="form-control"
                                                    id="sub_total" readonly></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>Discount</td>
                                            <td><input type="text" name="discount_sub_total" class="form-control"
                                                    id="discount"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7"></td>
                                            <td>Total</td>
                                            <td id=""><input type="text" name="total_calculate"
                                                    class="form-control" id="total_calculate" readonly></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i
                                                    class="fas fa-spinner"></i> </button>
                                            <button type="submit" id="save"
                                                class="w-auto btn btn-sm btn-success me-2 submit_button" data-status="4"
                                                value="save">@lang('menu.save')</button>
                                            <button type="submit" class="w-auto btn btn-sm btn-danger submit_button"
                                                data-status="1" value="save">@lang('menu.reset_data')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Material Item Modal -->
    <div class="modal fade" id="addMaterialItemModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop"
        aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Item</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="add_material_modal_body"></div>
            </div>
        </div>
    </div>
    <!-- Add Material Item Modal End-->

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
        integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.onkeyup = function(e) {
            //var e = e || window.event; // for IE to cover IEs window event-object
            if (e.ctrlKey && e.which == 13) {
                $('#save_and_sent').click();
                return false;
            } else if (e.shiftKey && e.which == 13) {
                $('#save').click();
                return false;
            }
        }

        ClassicEditor.create(document.querySelector('#editor')).then(editor => {

            })
            .catch(error => {
                console.error(error);
            });

        $('.select2').select2({
            placeholder: "Select Leads",
            allowClear: true
        });

        $('#addMaterialItem').on('click', function() {
            $.get("{{ route('crm.proposal_template.add.product.modal.view') }}", function(data) {
                $('#add_material_modal_body').html(data);
                $('#addMaterialItemModal').modal('show');
            });
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('oprn_till_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 2,
                maxYear: new Date().getFullYear() + 10,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('proposal_date'),
            dropdowns: {
                minYear: new Date().getFullYear() - 2,
                maxYear: new Date().getFullYear() + 10,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        // $(function() {
        //     $("selectize_tags").selectize(options);
        // });

        // $(document).ready(function() {
        //     const addButton = document.getElementById('addMoreButton');
        //     var index = 0;
        //     addButton.addEventListener('click', function(e) {
        //         e.preventDefault();
        //         index += 1;
        //         const container = document.getElementById('dup_tr');
        //         const child = ` <tr class="dup_tr">
    //                             <td><textarea name="name[]" class="form-control ckEditor" id="name_short_description_moor_${index}" cols="30" rows="3"></textarea></td>
    //                             <td><textarea name="details[]" class="form-control ckEditor" id="long_description_moor_${index}" cols="30" rows="3"></textarea></td>
    //                             <td><input type="number" name="qty[]" class="form-control" id="qty_moor_${index}" onkeyup="handleKeyUpAddMoor(${index})"></td>
    //                             <td><input type="text" name="rate[]" class="form-control" id="rate_moor_${index}" onkeyup="handleKeyUpAddMoor(${index})"></td>
    //                             <td>
    //                                 <input type="hidden" name="item_id[]">
    //                                 <select name="tax_type[]" id="tax_type_moor_${index}" class="form-control form-select" tabindex="-1" onchange="handleKeyUpAddMoor(${index})">
    //                                     <option value="1">@lang('menu.exclusive')</option>
    //                                     <option value="2">@lang('menu.inclusive')</option>
    //                                 </select>
    //                             </td>
    //                             <td>
    //                                 <select class="form-control form-select" name="tax[]" id="tax_moor_${index}" onchange="handleKeyUpAddMoor(${index})">
    //                                     <option value=""> N/A</option>
    //                                     <option value="">Tax 2%</option>
    //                                     <option value="">Tax 2.5%</option>
    //                                     <option value="">Tax 3%</option>
    //                                 </select>
    //                             </td>
    //                             <td>
    //                                 <input type="number" step="any" class="form-control" name="discount[]" id="discount_moor_${index}" placeholder="@lang('menu.discount')" value="0.00" onkeyup="handleKeyUpAddMoor(${index})">
    //                             </td>

    //                            <td>
    //                                 <select class="form-control form-select" name="discount_type[]" id="discount_type_moor_${index}" onchange="handleKeyUpAddMoor(${index})">
    //                                     <option value="1">@lang('menu.fixed')(0.00)</option>
    //                                     <option value="2">@lang('menu.percentage')(%)</option>
    //                                 </select>
    //                             </td>

    //                             <td><input type="text" name="amount[]" class="form-control sub_total_amount" id="amount_moor_${index}"></td>
    //                             <td>
    //                                 <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.remove()"><i class="fa fa-trash" aria-hidden="true"></i></button>
    //                             </td>
    //                         </tr>`;
        //         container.insertAdjacentHTML('beforeend', child);
        //         amount = calculateItemWiseAmountInMoor(index);
        //         totalAmountCalculations(amount);
        //     });
        // });

        // function handleKeyUpAddMoor(id) {
        //     amount = calculateItemWiseAmountInMoor(id);
        //     document.getElementById('amount_moor_' + id).value = amount;
        //     totalAmountCalculations(amount);
        // }

        // function calculateItemWiseAmountInMoor(id) {
        //     var amount = 0;
        //     var tax_type = document.getElementById('tax_type_moor_' + id).value;
        //     var tax = document.getElementById('tax_moor_' + id).value;
        //     var discount_amount = document.getElementById('discount_moor_' + id).value;
        //     var discount_type = document.getElementById('discount_type_moor_' + id).value;
        //     var qty = document.getElementById('qty_moor_' + id).value;
        //     var rate = document.getElementById('rate_moor_' + id).value;
        //     amount = rate * qty;
        //     if (discount_amount > 0) {
        //         if (discount_type == 1) {
        //             amount -= discount_amount;
        //         } else {
        //             amount -= (amount / 100) * discount_amount;
        //         }
        //     }

        //     if (tax != 0) {
        //         if (tax_type == 1) {
        //             amount += amount / 100 * tax;
        //         } else {
        //             amount -= amount / 100 * tax;
        //         }
        //     }
        //     return amount;
        // }

        $(document).on('change', '#item_id', function(e) {
            var item_id = $(this).val();
            var url = "{{ route('crm.proposal_template.product-info', ['item_id' => ':item_id']) }}";
            url = url.replace(':item_id', item_id);
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    if (data.id != null) {
                        const container = document.getElementById('dup_tr');
                        const child = ` <tr class="dup_tr">
                                        <td><textarea name="name[]" class="form-control ckEditor" id="name_short_description" cols="30" rows="3">${data.name}</textarea></td>
                                        <td><textarea name="details[]" class="form-control ckEditor" id="long_description" cols="30" rows="3">${data.product_details}</textarea></td>
                                        <td>
                                            <input type="number" name="qty[]" class="form-control" id="qty_${data.id}" value="1" onkeyup="handleKeyUp(${data.id})">
                                            <input type="hidden" name="item_id[]" value="${data.id}" >
                                        </td>
                                        <td><input type="text" name="rate[]" class="form-control" id="rate_${data.id}" value="${data.product_price}" onkeyup="handleKeyUp(${data.id})"></td>
                                        <td>
                                            <select name="tax_type[]" id="tax_type_${data.id}" class="form-control form-select" tabindex="-1" onchange="handleKeyUp(${data.id})">
                                                <option value="1">@lang('menu.exclusive')</option>
                                                <option value="2">@lang('menu.inclusive')</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-select" name="tax[]" id="tax_${data.id}" onchange="handleKeyUp(${data.id})">
                                                <option value="0"> N/A</option>
                                                <option value="2">Tax 2%</option>
                                                <option value="2.5">Tax 2.5%</option>
                                                <option value="3">Tax 3%</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="any" class="form-control" name="discount[]" id="discount_${data.id}" placeholder="@lang('menu.discount')" value="0.00" onkeyup="handleKeyUp(${data.id})">
                                        </td>
                                        <td>
                                            <select class="form-control form-select" name="discount_type[]" id="discount_type_${data.id}" onchange="handleKeyUp(${data.id})">
                                                <option value="1">@lang('menu.fixed')(0.00)</option>
                                                <option value="2">@lang('menu.percentage')(%)</option>
                                            </select>
                                        </td>

                                        <td><input type="text" name="amount[]" class="form-control sub_total_amount" id="amount_${data.id}" value="${data.product_price * 1}" ></td>
                                        <td>
                                            <button class="btn btn-sm btn-danger px-2" type="button" onclick="this.parentElement.parentElement.remove()"><i class="fa fa-trash" aria-hidden="true"></i></button>
                                        </td>
                                    </tr>`;
                        container.insertAdjacentHTML('beforeend', child);
                        amount = calculateItemWiseAmount(data.id);
                        totalAmountCalculations(amount);
                    }

                },
                error: function(data) {
                    toastr.error(data.responseJSON)
                }
            });
        });
        var in_amount = 0;

        function totalAmountCalculations(amount) {
            var request = $('#proposal_template_send').serialize();
            $.ajax({
                type: 'POST',
                url: "{{ route('crm.proposal_template.total') }}",
                data: request,
                success: function(data) {

                    $('#sub_total').val(data);
                    $('#total_calculate').val(data);
                }
            });
        }

        $('#discount').on('keyup', function() {
            in_discount = $('#sub_total').val() - $(this).val();
            $('#total_calculate').val(in_discount);
        });

        function handleKeyUp(id) {
            amount = calculateItemWiseAmount(id);
            document.getElementById('amount_' + id).value = amount;
            totalAmountCalculations(amount);
        }


        function calculateItemWiseAmount(id) {
            var amount = 0;
            var tax_type = document.getElementById('tax_type_' + id).value;
            var tax = document.getElementById('tax_' + id).value;
            var discount_amount = document.getElementById('discount_' + id).value;
            var discount_type = document.getElementById('discount_type_' + id).value;
            var qty = document.getElementById('qty_' + id).value;
            var rate = document.getElementById('rate_' + id).value;
            amount = rate * qty;
            if (discount_amount > 0) {
                if (discount_type == 1) {
                    amount -= discount_amount;
                } else {
                    amount -= (amount / 100) * discount_amount;
                }
            }

            if (tax != 0) {
                if (tax_type == 1) {
                    amount += amount / 100 * tax;
                } else {
                    amount -= amount / 100 * tax;
                }
            }
            return amount;
        }

        $(document).on('change', '#rel_type', function(e) {
            var rel_type = $(this).val();
            var url = "{{ route('crm.proposal_template.leads-customers', ['rel_type' => ':rel_type']) }}";
            url = url.replace(':rel_type', rel_type);
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#customer_leads').empty();
                    $('.customer_leads_label').text("");
                    $('.customer_leads_label').text(data.type);
                    $.each(data.leadsUser, function(key, value) {
                        $('#customer_leads').append("<option value=" + value.id + ">" + value
                            .name + "</option>");
                    });
                },
                error: function(data) {
                    toastr.error(data.responseJSON)
                }
            });
        });

        $(document).on('change', '#customer_leads', function(e) {
            var customer_leads_val = $(this).val();
            var url =
                "{{ route('crm.proposal_template.leads-address', ['customer_leads_val' => ':customer_leads_val', 'rel_type' => ':rel_type']) }}";
            url = url.replace(':customer_leads_val', customer_leads_val);
            url = url.replace(':rel_type', $('.customer_leads_label').text());
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#proposal_to').val("");
                    $('#email').val("");
                    $('#phone').val("");
                    $('#address').val("");
                    $('#city').val("");
                    $('#state').val("");
                    $('#zip').val("");
                    $('#country').empty();
                    if (data.type == "lead") {
                        $('#proposal_to').val(data.address.name);
                        $('#email').val(data.address.email_addresses);
                        $('#phone').val(data.address.phone_numbers);
                        $('#address').val(data.address.address);
                    } else {
                        $('#proposal_to').val(data.address.name);
                        $('#email').val(data.address.email);
                        $('#phone').val(data.address.phone);
                        $('#address').val(data.address.address);
                        $('#city').val(data.address.city);
                        $('#zip').val(data.address.zip_code);
                        $('#country').append("<option value=" + data.address.country + ">" + data
                            .address.country + "</option>");
                        $('#state').val(data.address.state);
                    }
                },
                error: function(data) {
                    toastr.error(data.responseJSON)
                }
            });
        });

        $(document).on('click', '#country', function(event) {
            var countries = `<option value=""></option>
                        <option value="1" data-subtext="BN">Bangladesh</option>
                        <option value="2" data-subtext="IN">India</option>`;
            $('#country').empty().append(countries);
        })

        $(document).ready(function() {
            var table = $('.TemplateTable').DataTable({
                processing: true,
                dom: "lBfrtip",
                serverSide: true,
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>Save as Excel',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, ],
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('crm.proposal_template.index') }}",
                columns: [{
                    data: 'action',
                    name: 'action'
                }, {
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'type',
                    name: 'type'
                }, {
                    data: 'subject',
                    name: 'subject'
                }, {
                    data: 'body',
                    name: 'body'
                }, ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            $('#proposal_template_send').on('submit', function(e) {
                e.preventDefault();
                var d = $(this);
                $('.loading_button').show();
                var url = $(this).attr('action');
                // var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#addModal').modal('hide');
                        $('#proposal_template_send')[0].reset();
                        $('.loading_button').hide();
                        $('.TemplateTable').DataTable().ajax.reload();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');
                        $('.submit_button').prop('type', 'submit');

                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');
                $.get(url, function(data) {
                    $('#editModal').html(data);
                    $('#editModal').modal('show');
                    $('.data_preloader').hide();

                });
            });

            // delete part
            $(document).on('click', '#delete', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $('#deleted_form').attr('action', url);
                $.confirm({
                    'title': 'Delete Confirmation',
                    'content': 'Are you sure?',
                    'buttons': {
                        'Yes': {
                            'class': 'yes btn-primary',
                            'action': function() {
                                $('#deleted_form').submit();
                            }
                        },
                        'No': {
                            'class': 'no btn-danger',
                            'action': function() {


                            }
                        }
                    }
                });
            });

            //data delete by ajax
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        table.ajax.reload();
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                            return;
                        }
                        toastr.error(data.responseJSON);
                    },
                    error: function(data) {
                        toastr.error(data.responseJSON)
                        asset_table.ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
