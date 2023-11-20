@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="container-fluid p-0">
        <form id="add_user_form" action="{{ route('contacts.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="mt-5x">
                <div class="row g-0">
                    <div class="col-12 p-0">
                        <div class="form_elemen">
                            <div class="sec-name">
                                <h6>@lang('menu.import_customers')</h6>
                                <div>
                                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i>
                                        <br>@lang('menu.back')
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container-fluid p-0">
                    <div class="p-15">
                        <div class="row">

                            <div class="col-12">
                                <div class="form_element rounded m-0">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.file_to_import') </b> </label>
                                                    <div class="col-8">
                                                        <input type="file" name="import_file" class="form-control" required>
                                                        <span class="error" style="color: red;">
                                                            {{ $errors->first('import_file') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-8">
                                                        <button class="btn btn-sm btn-primary float-start mt-1">@lang('menu.upload')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.download_sample') </b> </label>
                                                    <div class="col-8">
                                                        <a href="{{ asset('import_template/contct_import.xlsx') }}" class="btn btn-sm btn-success" download>@lang('menu.download_template_click')</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form_element rounded m-0 mt-2">
                                    <div class="element-body">
                                        <div class="heading">
                                            <h4>@lang('menu.instructions')</h4>
                                        </div>
                                        <div class="top_note">
                                            <p class="p-0 m-0"><b>@lang('menu.follow_instruct_import')</b></p>
                                            <p>@lang('menu.column_follow_order')</p>
                                        </div>

                                        <div class="instruction_table">
                                            <table class="table table-sm modal-table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th class="text-startx">@lang('menu.column_number')</th>
                                                        <th class="text-startx">@lang('menu.column_name')</th>
                                                        <th class="text-startx">@lang('menu.instruction')</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <tr>
                                                        <td class="text-start">1</td>
                                                        <td class="text-start">contact id</td>
                                                        <td class="text-start">@lang('menu.optional') (<small>@lang('menu.must_be_unique').</small>) </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">2</td>
                                                        <td class="text-start">contact type</td>
                                                        <td class="text-start text-danger">required</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">3</td>
                                                        <td class="text-start">contact related</td>
                                                        <td class="text-start text-danger">required</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">4</td>
                                                        <td class="text-start">name</td>
                                                        <td class="text-start text-danger">required</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">5</td>
                                                        <td class="text-start">phone</td>
                                                        <td class="text-start text-danger">required (<small>@lang('menu.must_be_unique').</small>)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">6</td>
                                                        <td class="text-start">nid_no</td>
                                                        <td class="text-start">@lang('menu.optional') (<small>@lang('menu.must_be_unique').</small>) </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">7</td>
                                                        <td class="text-start">trade_license_no</td>
                                                        <td class="text-start">@lang('menu.optional') (<small>@lang('menu.must_be_unique').</small>)</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">8</td>
                                                        <td class="text-start">email</td>
                                                        <td class="text-start">@lang('menu.optional')</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">9</td>
                                                        <td class="text-start">reference</td>
                                                        <td class="text-start">@lang('menu.optional')</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">10</td>
                                                        <td class="text-start">alternative phone</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">11</td>
                                                        <td class="text-start">landline</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">12</td>
                                                        <td class="text-start">date of birth</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">13</td>
                                                        <td class="text-start"></td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">14</td>
                                                        <td class="text-start">address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">15</td>
                                                        <td class="text-start">city</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">16</td>
                                                        <td class="text-start">zip_code</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">17</td>
                                                        <td class="text-start">country</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">18</td>
                                                        <td class="text-start">state</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">19</td>
                                                        <td class="text-start">description</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">20</td>
                                                        <td class="text-start">additional_information</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">21</td>
                                                        <td class="text-start">shipping_address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">22</td>
                                                        <td class="text-start">total employees</td>
                                                        <td class="text-start">@lang('menu.optional')</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">23</td>
                                                        <td class="text-start">business name</td>
                                                        <td class="text-start">@lang('menu.optional')</td>

                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">24</td>
                                                        <td class="text-start">known_person</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">25</td>
                                                        <td class="text-start">known_person_phone</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">26</td>
                                                        <td class="text-start">permanent_address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">27</td>
                                                        <td class="text-start">print_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">28</td>
                                                        <td class="text-start">print_ledger_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">29</td>
                                                        <td class="text-start">print_ledger_code</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">30</td>
                                                        <td class="text-start">billing_account</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">31</td>
                                                        <td class="text-start">contact_status</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">32</td>
                                                        <td class="text-start">contact_mailing_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">33</td>
                                                        <td class="text-start">contact_post_office</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">34</td>
                                                        <td class="text-start">contact_police_station</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">35</td>
                                                        <td class="text-start">contact_currency</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">36</td>
                                                        <td class="text-start">contact_fax</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">37</td>
                                                        <td class="text-start">primary_mobile</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">38</td>
                                                        <td class="text-start">contact_send_sms</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">39</td>
                                                        <td class="text-start">contact_email</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">40</td>
                                                        <td class="text-start">mailing_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">41</td>
                                                        <td class="text-start">mailing_address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">42</td>
                                                        <td class="text-start">mailing_email</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">43</td>
                                                        <td class="text-start">shipping_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">44</td>
                                                        <td class="text-start">shipping_number</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">45</td>
                                                        <td class="text-start">shipping_email</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">46</td>
                                                        <td class="text-start">shipping_send_sms</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">47</td>
                                                        <td class="text-start">alternative_address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">48</td>
                                                        <td class="text-start">alternative_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">49</td>
                                                        <td class="text-start">alternative_post_office</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">50</td>
                                                        <td class="text-start">alternative_zip_code</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">51</td>
                                                        <td class="text-start">alternative_police_station</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">52</td>
                                                        <td class="text-start">alternative_state</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">53</td>
                                                        <td class="text-start">alternative_city</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">54</td>
                                                        <td class="text-start">alternative_fax</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">55</td>
                                                        <td class="text-start">alternative_send_sms</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">56</td>
                                                        <td class="text-start">alternative_email</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">57</td>
                                                        <td class="text-start">tin_number</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">58</td>
                                                        <td class="text-start">tax_number</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">59</td>
                                                        <td class="text-start">tax_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">60</td>
                                                        <td class="text-start">tax_category</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">61</td>
                                                        <td class="text-start">tax_address</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">62</td>
                                                        <td class="text-start">bank_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">63</td>
                                                        <td class="text-start">bank_A_C_number</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">64</td>
                                                        <td class="text-start">bank_currency</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">65</td>
                                                        <td class="text-start">bank_branch</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">66</td>
                                                        <td class="text-start">partner_name</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">67</td>
                                                        <td class="text-start">percentage</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">68</td>
                                                        <td class="text-start">sales_team</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-start">69</td>
                                                        <td class="text-start">contact_telephone</td>
                                                        <td class="text-start">@lang('menu.optional') </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div>
</div>
@endsection
