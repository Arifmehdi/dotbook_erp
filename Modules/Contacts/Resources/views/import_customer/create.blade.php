@extends('layout.master')
@push('css')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="add_user_form" action="{{ route('contacts.customers.import.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5x">
                    <div class="row g-0">
                        <div class="col-12 p-0">
                            <div class="form_elemen">
                                <div class="sec-name">
                                    <h6>@lang('menu.import_customers')</h6>
                                    <x-all-buttons/>
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
                                                            <a href="{{ asset('import_template/customer_import_template.xlsx') }}" class="btn btn-sm btn-success" download>@lang('menu.download_template_click')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_element rounded m-0 mt-1">
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
                                                            <td class="text-start">@lang('menu.one')</td>
                                                            <td class="text-start">@lang('menu.customer_id')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.two')</td>
                                                            <td class="text-start">@lang('menu.business_name')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.three')</td>
                                                            <td class="text-start">@lang('menu.name')</td>
                                                            <td class="text-start text-danger">@lang('menu.required')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.four')</td>
                                                            <td class="text-start"> @lang('menu.phone') </td>
                                                            <td class="text-start text-danger"> <b>@lang('menu.required')</b> <br>
                                                                (<small>@lang('menu.must_be_unique').</small>)
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.five')</td>
                                                            <td class="text-start">@lang('menu.alternative_number')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.six')</td>
                                                            <td class="text-start">@lang('menu.landline')</td>
                                                            <td class="text-start"> @lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.seven')</td>
                                                            <td class="text-start">@lang('menu.email')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.eight')</td>
                                                            <td class="text-start">@lang('menu.date_of_birth')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.nine')</td>
                                                            <td class="text-start">@lang('menu.tax_number')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.ten')</td>
                                                            <td class="text-start">@lang('menu.opening_balance') </td>
                                                            <td class="text-start">@lang('menu.optional') <br>
                                                                (<small>Opening Balance will be added in customer balance due.</small>)</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.eleven')</td>
                                                            <td class="text-start">@lang('menu.address')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.twelve')</td>
                                                            <td class="text-start">@lang('menu.city')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.thirteen')</td>
                                                            <td class="text-start">@lang('menu.state')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.fourteen')</td>
                                                            <td class="text-start">@lang('menu.country')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.fifteen')</td>
                                                            <td class="text-start">@lang('menu.zip_code')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.sixteen')</td>
                                                            <td class="text-start">@lang('menu.shipping_address')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>


                                                        <tr>
                                                            <td class="text-start">@lang('menu.seventeen')</td>
                                                            <td class="text-start">@lang('menu.pay_term_number')</td>
                                                            <td class="text-start">@lang('menu.optional')</td>
                                                        </tr>

                                                        <tr>
                                                            <td class="text-start">@lang('menu.eighteen')</td>
                                                            <td class="text-start">@lang('menu.pay_term')</td>
                                                            <td class="text-start">@lang('menu.optional') (If exists 1=Day,2=Month)</td>
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
