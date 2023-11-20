@extends('layout.master')
@push('css')

@endpush
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <h6>@lang('menu.import_item')</h6>
        <x-back-button/>
    </div>
    <div class="container-fluid p-0">
        <form id="add_user_form" action="{{ route('product.import.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <section class="p-15">
                <div class="row">
                    <div class="col-md-12">
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
                                            <label for="inputEmail3" class="col-4"><b>@lang('menu.download') @lang('menu.simple') </b> </label>
                                            <div class="col-8">
                                                <a href="{{ asset('import_template/product_import_template.csv') }}" class="btn btn-sm btn-success" download>@lang('menu.download_template_file_click_here')</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form_element rounded m-0 mt-1">
                            <div class="element-body">
                                <div class="heading">
                                    <h4>@lang('menu.instructions')</h4>
                                </div>
                                <div class="top_note">
                                    <p class="p-0 m-0"><b>@lang('menu.follow_instructions_importing_file')</b></p>
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
                                                <td class="text-start">@lang('menu.item_name') (@lang('menu.required'))</td>
                                                <td class="text-start"> @lang('menu.name_of_the_item')</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">2</td>
                                                <td class="text-start"> @lang('menu.item_code')(SKU) (@lang('menu.optional'))</td>
                                                <td class="text-start">@lang('menu.item_code')(SKU). @lang('menu.if_automatically_generated')</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">3</td>
                                                <td class="text-start"> @lang('menu.unit') (@lang('menu.required'))</td>
                                                <td class="text-start">@lang('menu.name_of_the_unit')</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">4</td>
                                                <td class="text-start"> @lang('menu.category') (@lang('menu.required'))</td>
                                                <td class="text-start"> <b>@lang('menu.name_of_the_category')</b> <br>
                                                    (<small>@lang('menu.if__category_with_created')</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">5</td>
                                                <td class="text-start"> @lang('menu.child_category') (@lang('menu.optional'))</td>
                                                <td class="text-start"> <b>Name of the Sub-Category</b> <br>
                                                    (<small>If not found new sub-category with the given name under the
                                                        parent Category will be created</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">6</td>
                                                <td class="text-start">@lang('menu.brand') (@lang('menu.optional'))</td>
                                                <td class="text-start"> <b>Name of the brand</b> <br>
                                                    (<small>If not found new brand with the given name will be created</small>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">7</td>
                                                <td class="text-start">@lang('menu.barcode_type') (@lang('menu.optional'), Default: C128)</td>
                                                <td class="text-start"> Barcode Type for the product. <br>
                                                    (<span><b>Currently supported: C128, C39, EAN-13, EAN-8, UPC-A, UPC-E, ITF-14</b> </span>)</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">8</td>
                                                <td class="text-start">@lang('menu.alert_quantity') (@lang('menu.optional'))</td>
                                                <td class="text-start"> @lang('menu.alert_quantity')</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">9</td>
                                                <td class="text-start">Expiry Date (@lang('menu.optional'))</td>
                                                <td class="text-start">Stock Expiry Date <br>
                                                    (<span><b>Format: mm-dd-yyyy; Ex: 11-25-2018</b> </span>)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">10</td>
                                                <td class="text-start">@lang('menu.warranty')</td>
                                                <td class="text-start">Name of the Warranty </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">11</td>
                                                <td class="text-start">@lang('menu.description') (@lang('menu.optional'))</td>
                                                <td class="text-start">Description of product </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">12</td>
                                                <td class="text-start">Tax (@lang('menu.optional'))</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">UNIT COST Excluding Tax (@lang('menu.required'))</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">13</td>
                                                <td class="text-start">UNIT COST Including Tax (@lang('menu.optional'))</td>
                                                <td class="text-start">Only in numbers</td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">14</td>
                                                <td class="text-start">@lang('menu.profit_margin') % (@lang('menu.optional'))</td>
                                                <td class="text-start">
                                                    @lang('menu.profit_margin') (Only in numbers)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">15</td>
                                                <td class="text-start"> @lang('menu.opening_stock') (Only in numbers)</td>
                                                <td class="text-start">
                                                    Selling Price (Only in numbers)
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="text-start">16</td>
                                                <td class="text-start"> @lang('menu.opening_stock')  @lang('menu.branch') (@lang('menu.optional')) <br>
                                                    (<small>If blank first Branch will be used</small>) </td>
                                                <td class="text-start">
                                                    Only Branch Code
                                                </td>
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
