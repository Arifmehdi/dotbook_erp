@extends('layout.master')
@push('css')
    <style>
        p.checkbox_input_wrap {
            display: flex;
            gap: 5px;
            line-height: 1.8;
            position: relative;
        }
    </style>
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.add_user')</h6>
                <x-all-buttons />
            </div>
            <form id="add_user_form" action="{{ route('users.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="p-15">
                    <div class="row g-1">
                        <div class="col-12">
                            <div class="form_element rounded m-0">

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.prefix') </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="prefix" class="form-control"
                                                        placeholder="Mr / Mrs / Miss" autofocus>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.first_name') </b><span
                                                        class="text-danger">*</span> </label>

                                                <div class="col-8">
                                                    <input type="text" name="first_name" class="form-control"
                                                        placeholder="@lang('menu.first_name')" id="first_name" required>
                                                    <span class="error error_first_name"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.last_name') </b> </label>
                                                <div class="col-8">
                                                    <input type="text" name="last_name" class="form-control"
                                                        placeholder="@lang('menu.last_name')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"><b>@lang('menu.email') </b> <span
                                                        class="text-danger">*</span> </label>
                                                <div class="col-8">
                                                    <input type="text" name="email" id="email" class="form-control"
                                                        placeholder="exmple@email.com" required>
                                                    <span class="error error_email"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-4"> <b>@lang('menu.phone') </b> <span
                                                        class="text-danger">*</span></label>
                                                <div class="col-8">
                                                    <input type="text" name="phone" class="form-control"
                                                        autocomplete="off" placeholder="Phone number" required>
                                                    <span class="error error_phone"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="col-8 offset-4">
                                                <p class="checkbox_input_wrap mt-1 fw-bold">
                                                    @lang('menu.marketing_user') <input type="checkbox"
                                                        {{ isset($isSr) ? 'checked' : '' }} name="is_marketing_user">
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form_element rounded m-0">
                                <div class="heading_area">
                                    <p class="p-1 text-primary"><b>@lang('menu.role_permission')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-12">
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" checked name="allow_login" id="allow_login">
                                                <b>@lang('menu.allow_login')</b>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="auth_field_area">
                                        <div class="row g-2 mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.username') </b> <span
                                                            class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input type="text" name="username" id="username"
                                                            class="form-control " placeholder="@lang('menu.username')"
                                                            autocomplete="off">
                                                        <span class="error error_username"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.role') </b> <span
                                                            class="text-danger">*</span></label>
                                                    <div class="col-8">
                                                        <select name="role_id" id="role_id"
                                                            class="form-control form-select"></select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-4"><b>@lang('menu.password') </b> <span
                                                            class="text-danger">*</span> </label>
                                                    <div class="col-8">
                                                        <input type="password" name="password" id="password"
                                                            class="form-control" placeholder="@lang('menu.password')"
                                                            autocomplete="off">
                                                        <span class="error error_password"></span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="input-group">
                                                    <label class="col-sm-4 col-12"><b>@lang('menu.confirm_password') </b> <span
                                                            class="text-danger">*</span> </label>
                                                    <div class="col-sm-8 col-12">
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control" placeholder="@lang('menu.confirm_password')"
                                                            autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form_element rounded m-0">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.sales')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"> <b>@lang('menu.commission') (%) </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="number" name="sales_commission_percent"
                                                        class="form-control" placeholder="Sales Commission Percentage (%)"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>@lang('menu.max_discount')(%) </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="number" name="max_sales_discount_percent"
                                                        class="form-control" placeholder="Max sales discount percent"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form_element rounded m-0">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.more_information')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"> <b>@lang('menu.date_of_birth') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="date_of_birth" class="form-control"
                                                        autocomplete="off" placeholder="Date of birth">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.gender') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <select name="gender" class="form-control form-select">
                                                        <option value="">@lang('menu.select_gender')</option>
                                                        <option value="Male">@lang('menu.male')</option>
                                                        <option value="Female">@lang('menu.female')</option>
                                                        <option value="Others">@lang('menu.others')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.marital_status') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <select name="marital_status" class="form-control form-select">
                                                        <option value="">@lang('menu.marital_status')</option>
                                                        <option value="Married">@lang('menu.married')</option>
                                                        <option value="Unmarried">@lang('menu.unmarried')</option>
                                                        <option value="Divorced">@lang('menu.divorced')</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.blood_group') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="blood_group" class="form-control"
                                                        placeholder="@lang('menu.blood_group')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.facebook_link') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="facebook_link" class="form-control"
                                                        autocomplete="off" placeholder="Facebook link">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.twitter_link') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="twitter_link" class="form-control"
                                                        autocomplete="off" placeholder="@lang('menu.twitter_link')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.instagram_link')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="instagram_link" class="form-control"
                                                        autocomplete="off" placeholder="@lang('menu.instagram_link')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.guardian_name')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="guardian_name" class="form-control"
                                                        autocomplete="off" placeholder="@lang('menu.guardian_name')">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.id_proof_name') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="id_proof_name" class="form-control"
                                                        autocomplete="off" placeholder="@lang('menu.id_proof_name') ">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.id_proof_number') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="id_proof_number" class="form-control"
                                                        autocomplete="off" placeholder="@lang('menu.id_proof_number')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>@lang('menu.permanent_address') </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="text" name="permanent_address"
                                                        class="form-control form-control-sm" autocomplete="off"
                                                        placeholder="@lang('menu.permanent_address')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-2">
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-12"><b>@lang('menu.current_address') </b> </label>
                                                <div class="col-sm-8 col-12">
                                                    <input type="text" name="current_address"
                                                        class="form-control form-control-sm"
                                                        placeholder="@lang('menu.current_address')">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form_element rounded m-0">
                                <div class="heading_area">
                                    <p class="px-1 pt-1 pb-0 text-primary"><b>@lang('menu.bank_details')</b> </p>
                                </div>

                                <div class="element-body">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.account_name') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_ac_holder_name"
                                                        class="form-control " placeholder="Account holder's name"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.account_no') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_ac_no" class="form-control"
                                                        placeholder="@lang('menu.account_number')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.bank_name')</b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_name" class="form-control"
                                                        placeholder="@lang('menu.bank_name')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.identifier_code') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_identifier_code"
                                                        class="form-control" placeholder="Bank identifier code"
                                                        autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-2 mt-1">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.branch') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="bank_branch" class="form-control"
                                                        placeholder="@lang('menu.branch')" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <label class="col-sm-4 col-5"><b>@lang('menu.tax_payer_id') </b> </label>
                                                <div class="col-sm-8 col-7">
                                                    <input type="text" name="tax_payer_id" class="form-control"
                                                        placeholder="@lang('menu.tax_payer_id')" autocomplete="off">
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
                                <button class="btn btn-success submit_button">@lang('menu.save')</button>
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
        function setRoles() {
            $.ajax({
                url: "{{ route('users.all.roles') }}",
                success: function(roles) {
                    $.each(roles, function(key, val) {
                        $('#role_id').append('<option value="' + val.id + '">' + val.name +
                        '</option>');
                    });
                }
            });
        }
        setRoles();

        // Add user by ajax
        $(document).on('submit', '#add_user_form', function(e) {
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
                    window.location = "{{ route('users.index') }}";
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

        $('#allow_login').on('click', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.auth_field_area').show();
            } else {

                $('.auth_field_area').hide();
            }
        });
    </script>
@endpush
