@extends('layout.master')
@push('css')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.add_invoice_layout')</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                        class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                </a>
            </div>
            <form id="add_layout_form" action="{{ route('invoices.layouts.store') }}" method="POST">
                @csrf
                <section class="p-15">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form_element m-0 rounded">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-3 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.name') </b> </label>
                                                    <div class="col-sm-9 col-12">
                                                        <input type="text" name="name" class="form-control"
                                                            placeholder="@lang('menu.layout_name')" required>
                                                        <span class="error error_name"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-3 col-12"><b>@lang('menu.design')
                                                        </b></label>

                                                    <div class="col-sm-9 col-12">
                                                        <select name="design" id="design"
                                                            class="form-control form-select">
                                                            <option value="1">@lang('menu.classic_for_normal_printer')</option>
                                                            <option value="2">@lang('menu.slim_for_pos_printer')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap bordered">
                                                                <input type="checkbox" checked name="show_shop_logo"> &nbsp;
                                                                @lang('menu.show_business_shop_logo')
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap">
                                                                <input type="checkbox" checked name="show_seller_info">
                                                                &nbsp; @lang('menu.show_seller_info')
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <div class="col-12">
                                                        <div class="row">
                                                            <p class="checkbox_input_wrap">
                                                                <input type="checkbox" checked name="show_total_in_word">
                                                                &nbsp; @lang('menu.show_total_word')
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 py-1">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.header_option')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <p class="checkbox_input_wrap">
                                                    <input type="checkbox" name="is_header_less" id="is_header_less">
                                                    &nbsp;<b>@lang('menu.is_leaderless') ?</b> <i data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="If you check this option then print header info will not come in the print preview. Use case, When the print page is pre-generated Like Pad.Where header info previously exists."
                                                        class="fas fa-info-circle tp"></i>
                                                </p>
                                            </div>

                                            <div class="col-md-9 hideable_field d-none">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-4 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.gap_from_top') (inc) </b>
                                                    </label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="number" name="gap_from_top" id="gap_from_top"
                                                            class="form-control" placeholder="@lang('menu.gap_from_top')">
                                                        <span class="error error_gap_from_top"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-4 col-12"><b>@lang('menu.sub_heading') 1
                                                        </b> </label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="text" name="sub_heading_1" id="sub_heading_1"
                                                            class="form-control" placeholder="@lang('menu.sub_heading') 1">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-4 col-12"><b>@lang('menu.sub_heading')
                                                            2 </b> </label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="text" name="sub_heading_2" id="sub_heading_2"
                                                            class="form-control" placeholder="@lang('menu.sub_heading') 2">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-12">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-2 col-12"><b>@lang('menu.header_text')
                                                        </b> </label>
                                                    <div class="col-sm-10 col-12">
                                                        <input type="text" name="header_text"
                                                            class="form-control form-control-sm"
                                                            placeholder="@lang('menu.header_text')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.invoice_heading')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-5 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.invoice_heading') </b>
                                                    </label>
                                                    <div class="col-sm-7 col-12">
                                                        <input type="text" name="invoice_heading" class="form-control"
                                                            id="invoice_heading" placeholder="@lang('menu.invoice_heading')"
                                                            required>
                                                        <span class="error error_invoice_heading"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-5 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.quotation_heading') </b>
                                                    </label>
                                                    <div class="col-sm-7 col-12">
                                                        <input type="text" name="quotation_heading"
                                                            id="quotation_heading" class="form-control"
                                                            placeholder="@lang('menu.quotation_heading')" required>
                                                        <span class="error error_quotation_heading"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-5 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.draft_heading') </b>
                                                    </label>
                                                    <div class="col-sm-7 col-12">
                                                        <input type="text" name="draft_heading" id="draft_heading"
                                                            class="form-control" placeholder="@lang('menu.draft_heading')"
                                                            required>
                                                        <span class="error error_draft_heading"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-sm-5 col-12"><span
                                                            class="text-danger">*</span> <b>@lang('menu.challan_heading') </b>
                                                    </label>
                                                    <div class="col-sm-7 col-12">
                                                        <input type="text" name="challan_heading" id="challan_heading"
                                                            class="form-control" placeholder="@lang('menu.challan_heading')"
                                                            required>
                                                        <span class="error error_challan_heading"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 py-1">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.field_for_branch')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                            <input type="checkbox" name="branch_landmark"> &nbsp;
                                                            <b>@lang('menu.landmark')</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_city">
                                                        &nbsp;<b>@lang('menu.city')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_state"> &nbsp;
                                                        <b>@lang('menu.state')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_zipcode"> &nbsp;
                                                        <b>@lang('menu.zip_code')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_phone"> &nbsp;
                                                        <b>@lang('menu.phone')</b>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_alternate_number">
                                                        &nbsp; <b>@lang('menu.alternative_number')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="branch_email"> &nbsp;
                                                        <b>@lang('menu.email')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.field_for_customer')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="input-group">
                                                    <div class="row">
                                                        <p class="checkbox_input_wrap">
                                                            <input type="checkbox" checked name="customer_name">
                                                            &nbsp;<b>@lang('menu.name')</b>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap ">
                                                        <input type="checkbox" checked name="customer_tax_no"> &nbsp;
                                                        <b>@lang('menu.tax_number')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="customer_address">
                                                        &nbsp;<b>@lang('menu.address')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="customer_phone">
                                                        &nbsp;<b>@lang('menu.phone')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 py-1">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.field_for_product')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" checked name="product_w_type">
                                                        &nbsp;<b>@lang('menu.product_warranty_type')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="product_w_duration"> &nbsp;
                                                        <b>@lang('menu.product_warranty_duration')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="product_discount"> &nbsp;
                                                        <b>@lang('menu.product_discount')</b>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap">
                                                        <input type="checkbox" checked name="product_tax"> &nbsp;
                                                        <b>@lang('menu.product_tax')</b>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="row">
                                                    <p class="checkbox_input_wrap ">
                                                        <input type="checkbox" name="product_imei"><b>&nbsp;
                                                            @lang('menu.show_sale_description')</b>
                                                    </p>
                                                </div>
                                                <small class="text-muted">(@lang('menu.product_serial_number'))</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.bank_details')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.account_no')
                                                            :</b></label>
                                                    <div class="col-8">
                                                        <input type="text" name="account_no" class="form-control"
                                                            placeholder="@lang('menu.account_number')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.account_name')</b>
                                                    </label>
                                                    <div class="col-8">
                                                        <input type="text" name="account_name" class="form-control"
                                                            placeholder="@lang('menu.account_name')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.bank_name'):</b>
                                                    </label>
                                                    <div class="col-8">
                                                        <input type="text" name="bank_name" class="form-control"
                                                            placeholder="@lang('menu.bank_name')">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.bank_branch')
                                                            :</b></label>

                                                    <div class="col-8">
                                                        <input type="text" name="bank_branch" class="form-control"
                                                            placeholder="@lang('menu.bank_branch')">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 py-1">
                                <div class="form_element m-0 rounded">
                                    <div class="heading_area">
                                        <p class="p-1 text-primary"><b>@lang('menu.footer_text')</b></p>
                                    </div>

                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>Invoice Notice
                                                            :</b></label>
                                                    <div class="col-8">
                                                        <textarea name="invoice_notice" class="form-control ckEditor" cols="10" rows="3"
                                                            placeholder="Invoice Notice"></textarea>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label for="inputEmail3" class="col-4"><b>@lang('menu.footer_text'):</b>
                                                    </label>
                                                    <div class="col-8">
                                                        <textarea name="footer_text" class="form-control ckEditor" cols="10" rows="3" placeholder="Footer text"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button class="btn btn-success submit_button float-end">@lang('menu.save')</button>
                                </div>
                            </div>
                        </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Add Invoice layout by ajax
        $(document).on('submit', '#add_layout_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    toastr.success(data);
                    $('.loading_button').hide();
                    window.location = "{{ route('invoices.layouts.index') }}";
                },
                error: function(err) {
                    $('.loading_button').hide();
                    toastr.error('Please check again all form fields.', 'Some thing went wrong.');
                    $('.error').html('');
                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#is_header_less').on('change', function() {
            if ($(this).is(':CHECKED', true)) {
                $('.hideable_field').show();
            } else {
                $('.hideable_field').hide();
            }
        });
    </script>
@endpush
