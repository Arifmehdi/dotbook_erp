@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>@lang('menu.import_suppliers')</h6>
        <x-all-buttons/>
    </div>
    <div class="container-fluid p-0">
        <form action="{{ route('contacts.suppliers.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="mt-5x">
                <div class="container-fluid">
                    <div class="row">
                        <div class=" p-15 g-0 pb-0">
                            <div class="form_element rounded m-0 pb-0">
                                <div class="element-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label for="inputEmail3" class="col-4"><b>@lang('menu.file_to_import') :</b> </label>
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
                                                <label for="inputEmail3" class="col-4"><b>@lang('menu.download') Simple :</b> </label>
                                                <div class="col-8">
                                                    <a href="{{ asset('import_template/supplier_import_template.xlsx') }}" class="btn btn-sm btn-success" download>@lang('menu.download_template_file_click_here')</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-15">
                            <div class="form_element rounded m-0 pb-0">
                                <div class="element-body">
                                    <div class="heading">
                                        <h4>@lang('menu.instructions')</h4>
                                    </div>
                                    <div class="top_note">
                                        <p class="p-0 m-0"><b>@lang('menu.follow_instructions_importing_file').</b></p>
                                        <p>@lang('menu.column_follow_order').</p>
                                    </div>

                                    <div class="instruction_table">
                                        <table class="table table-sm modal-table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-startx">@lang('menu.column_number')</th>
                                                    <th class="text-startx">@lang('menu.column_name')</th>
                                                    <th class="text-startx">Instruction</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">1</td>
                                                    <td class="text-start"> @lang('menu.supplier_id') </td>
                                                    <td class="text-start"> @lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">2</td>
                                                    <td class="text-start"> @lang('menu.business_name') </td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">3</td>
                                                    <td class="text-start"> Name</td>
                                                    <td class="text-start text-danger"> Required</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">4</td>
                                                    <td class="text-start">@lang('menu.phone') </td>
                                                    <td class="text-start text-danger"> <b>Required</b> <br>
                                                        (<small>Must be unique.</small>)</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">5</td>
                                                    <td class="text-start"> @lang('menu.alternative_number')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">6</td>
                                                    <td class="text-start">@lang('menu.landline')</td>
                                                    <td class="text-start"> @lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">7</td>
                                                    <td class="text-start">@lang('menu.email')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">8</td>
                                                    <td class="text-start">@lang('menu.date_of_birth')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">9</td>
                                                    <td class="text-start">@lang('menu.tax_number')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">10</td>
                                                    <td class="text-start">@lang('menu.opening_balance')</td>
                                                    <td class="text-start">@lang('menu.optional') <br>
                                                        (<small>Opening Balance will be added in customer balance due.</small>)</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">12</td>
                                                    <td class="text-start">@lang('menu.address')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">13</td>
                                                    <td class="text-start">@lang('menu.city')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">13</td>
                                                    <td class="text-start">State</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">14</td>
                                                    <td class="text-start">@lang('menu.country')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">15</td>
                                                    <td class="text-start">@lang('menu.zip_code')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">16</td>
                                                    <td class="text-start">@lang('menu.shipping_address')</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">16</td>
                                                    <td class="text-start">@lang('menu.prefix')</td>
                                                    <td class="text-start">@lang('menu.optional')(If you leave this field blank, it will be generated automatically.)</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">17</td>
                                                    <td class="text-start">Pay term Number</td>
                                                    <td class="text-start">@lang('menu.optional')</td>
                                                </tr>

                                                <tr>
                                                    <td class="text-start">17</td>
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
            </section>
        </form>
    </div>
</div>
@endsection
