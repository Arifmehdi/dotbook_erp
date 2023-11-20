@extends('layout.master')
@push('css')
    <style>
        b {
            font-weight: 500;
            font-family: Arial, Helvetica, sans-serif;
        }

        p.checkbox_input_wrap {
            font-weight: 600;
            font-size: 10px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .row1 {}

        .my_border {
            border: 1px solid rgb(99, 97, 97) !important;
        }

        p.checkbox_input_wrap {
            display: flex;
            gap: 5px;
            line-height: 1.8;
            position: relative;
        }

        .customers:checked {
            background-color: #3770eb;
        }

        .text-info {
            display: flex;
            gap: 5px;
            align-items: center;
        }
    </style>
@endpush
@section('content')
    <div class="body-woaper">
        <div class="sec-name">
            <h6>@lang('role.edit_role')</h6>
            <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                    class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
        </div>
        <div class="container-fluid p-0">
            <form id="edit_role_form" action="{{ route('users.role.update', $role->id) }}" method="POST">
                @csrf
                <section class="p-15" id="accordion">
                    <div class="container-fluid p-0">
                        <div class="row1">
                            <div class="col-md-12">
                                <div class="form_element rounded mt-0 mb-1">
                                    <div class="element-body">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="input-group align-items-center gap-2">
                                                    <label for="inputEmail3"> <b>@lang('menu.role_name') </b> <span
                                                            class="text-danger">*</span></label>
                                                    <div class="w-input">
                                                        <input required type="text" name="role_name" required
                                                            class="form-control add_input" id="role_name"
                                                            placeholder="@lang('menu.role_name')" value="{{ $role->name }}">
                                                        <span
                                                            class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="input-group align-items-center gap-2">
                                                    <label for="inputEmail3"> <b> @lang('menu.select_all') </b> </label>
                                                    <div class="d-flex align-items-center">
                                                        <input type="checkbox" class="select_all super_select_all"
                                                            data-target="super_select_all" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form_element rounded mt-0 mb-1">
                                <div class="accordion-header">
                                    <input id="customers" type="checkbox"
                                        class=" sale_checkbox select_all super_select_all sales_app_permission"
                                        data-target="sales_app_permission" autocomplete="off">
                                    <a data-bs-toggle="collapse" class="sale_role" href="#collapseOne" href="">
                                        @lang('menu.sales_app_permissions')
                                    </a>
                                </div>
                                <div id="collapseOne" class="collapse show" data-bs-parent="#accordion">
                                    <div class="element-body border-top">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info">
                                                    <input id="customers" type="checkbox"
                                                        class="select_all super_select_all sales_app_permission super_select_all"
                                                        data-target="customers" autocomplete="off">
                                                    <strong> @lang('menu.customers')</strong>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_all"
                                                        {{ $role->hasPermissionTo('customer_all') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.view_all_customer')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_add"
                                                        {{ $role->hasPermissionTo('customer_add') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.add_customer')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_import"
                                                        {{ $role->hasPermissionTo('customer_import') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.import_customer')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_edit"
                                                        {{ $role->hasPermissionTo('customer_edit') ? 'checked' : '' }}
                                                        class=" customers sales_app_permission super_select_all">
                                                    @lang('menu.edit_customer')
                                                </p>


                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_delete"
                                                        {{ $role->hasPermissionTo('customer_delete') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.delete_customer')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_group"
                                                        {{ $role->hasPermissionTo('customer_group') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.customer_group')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_report"
                                                        {{ $role->hasPermissionTo('customer_report') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    Customer
                                                    report
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_manage"
                                                        {{ $role->hasPermissionTo('customer_manage') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.customer_manage')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_payment_receive_voucher"
                                                        {{ $role->hasPermissionTo('customer_payment_receive_voucher') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    @lang('menu.customer') @lang('menu.payment_receive_voucher')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="customer_status_change"
                                                        {{ $role->hasPermissionTo('customer_status_change') ? 'checked' : '' }}
                                                        class="customers sales_app_permission super_select_all">
                                                    Customer status change
                                                </p>
                                            </div>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info">
                                                    <input id="pos" type="checkbox"
                                                        class="select_all super_select_all sales_app_permission super_select_all"
                                                        data-target="pos" autocomplete="off"><strong>
                                                        @lang('menu.pos_sales')</strong>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pos_all"
                                                        {{ $role->hasPermissionTo('pos_all') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">
                                                    @lang('menu.manage') pos
                                                    sale
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pos_add"
                                                        {{ $role->hasPermissionTo('pos_add') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">@lang('menu.add_pos_sale')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pos_edit"
                                                        {{ $role->hasPermissionTo('pos_edit') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all"> Edit
                                                    pos
                                                    sale
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pos_delete"
                                                        {{ $role->hasPermissionTo('pos_delete') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">
                                                    @lang('role.delete')
                                                    pos sale
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pos_sale_settings"
                                                        {{ $role->hasPermissionTo('pos_sale_settings') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">@lang('menu.pos_sale_settings')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_price_pos_screen"
                                                        {{ $role->hasPermissionTo('edit_price_pos_screen') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">
                                                    Edit item price from pos screen
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_discount_pos_screen"
                                                        {{ $role->hasPermissionTo('edit_discount_pos_screen') ? 'checked' : '' }}
                                                        class="pos sales_app_permission super_select_all">
                                                    @lang('menu.edit_item_discount_from_pos_screen')
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info">
                                                    <input type="checkbox"
                                                        class="select_all sales_app_permission super_select_all"
                                                        data-target="sales_report" autocomplete="off"> <strong>
                                                        @lang('menu.sales_report')</strong>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sales_report"
                                                        {{ $role->hasPermissionTo('sales_report') ? 'checked' : '' }}
                                                        class="sales_report sales_app_permission super_select_all">@lang('menu.sales_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="pro_sale_report"
                                                        {{ $role->hasPermissionTo('pro_sale_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.sold_items_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sales_order_report"
                                                        {{ $role->hasPermissionTo('sales_order_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.sales_order_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="ordered_item_report"
                                                        {{ $role->hasPermissionTo('ordered_item_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.sales_ordered_items_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sr_wise_order_report"
                                                        {{ $role->hasPermissionTo('sr_wise_order_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.sr_wise_sales_order_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="ordered_item_qty_report"
                                                        {{ $role->hasPermissionTo('ordered_item_qty_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.ordered_item_qty_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_report"
                                                        {{ $role->hasPermissionTo('do_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">
                                                    @lang('menu.do_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_vs_sales_report"
                                                        {{ $role->hasPermissionTo('do_vs_sales_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission">@lang('menu.do_vs_sale')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sales_return_report"
                                                        {{ $role->hasPermissionTo('sales_return_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sales_return_report')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sales_returned_items_report"
                                                        {{ $role->hasPermissionTo('sales_returned_items_report') ? 'checked' : '' }}
                                                        class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sales_returned_items_report')
                                                </p>

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_payment_report" {{ $role->hasPermissionTo('sale_payment_report') ? 'checked' : '' }} class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.receive_payment') @lang('menu.report')
                                                </p> --}}

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="c_register_report" {{ $role->hasPermissionTo('c_register_report') ? 'checked' : '' }} class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.cash_register_reports')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_representative_report" {{ $role->hasPermissionTo('sale_representative_report') ? 'checked' : '' }} class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.sales_representative_report')
                                                </p> --}}
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info">
                                                    <input type="checkbox"
                                                        class="select_all super_select_all sales_app_permission super_select_all"
                                                        data-target="sales_return" autocomplete="off">
                                                    <strong> @lang('menu.sales_return')</strong>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="view_sales_return"
                                                        {{ $role->hasPermissionTo('view_sales_return') ? 'checked' : '' }}
                                                        class="sales_return sales_app_permission super_select_all">@lang('menu.view_all_sale_return')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="add_sales_return"
                                                        {{ $role->hasPermissionTo('add_sales_return') ? 'checked' : '' }}
                                                        class="sales_return sales_app_permission super_select_all">
                                                    @lang('menu.add') @lang('menu.sales_return')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_sales_return"
                                                        {{ $role->hasPermissionTo('edit_sales_return') ? 'checked' : '' }}
                                                        class="sales_return sales_app_permission super_select_all">
                                                    @lang('menu.edit') @lang('menu.sales_return')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="delete_sales_return"
                                                        {{ $role->hasPermissionTo('delete_sales_return') ? 'checked' : '' }}
                                                        class=" sales_return sales_app_permission super_select_all">
                                                    @lang('menu.delete') @lang('menu.sales_return')
                                                </p>

                                                <div class="mt-3">
                                                    <p class="text-info">
                                                        <input type="checkbox"
                                                            class="select_all super_select_all sales_app_permission"
                                                            data-target="recent_prices" autocomplete="off">
                                                        <strong>@lang('menu.recent_price')</strong>
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="add_new_recent_price"
                                                            {{ $role->hasPermissionTo('add_new_recent_price') ? 'checked' : '' }}
                                                            class="recent_prices super_select_all sales_app_permission">@lang('menu.add_new_price')
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="all_previous_recent_price"
                                                            {{ $role->hasPermissionTo('all_previous_recent_price') ? 'checked' : '' }}
                                                            class="recent_prices super_select_all sales_app_permission">
                                                        @lang('menu.all_pre_price')
                                                    </p>

                                                    <p class="checkbox_input_wrap mt-1">
                                                        <input type="checkbox" name="today_recent_price"
                                                            {{ $role->hasPermissionTo('today_recent_price') ? 'checked' : '' }}
                                                            class="recent_prices super_select_all sales_app_permission">
                                                        @lang('menu.today_price')
                                                    </p>
                                                </div>
                                            </div>

                                            <hr class="my-2">

                                            <div class="col-lg-3 col-sm-6">

                                                <p class="text-info">
                                                    <input type="checkbox"
                                                        class="select_all super_select_all sales_app_permission super_select_all"
                                                        data-target="sale" autocomplete="off">
                                                    <strong>Sales</strong>
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="create_add_sale"
                                                        {{ $role->hasPermissionTo('create_add_sale') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.create_add_sale')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="view_sales"
                                                        {{ $role->hasPermissionTo('view_sales') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.view_sales')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_sale"
                                                        {{ $role->hasPermissionTo('edit_sale') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.edit_sale')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="delete_sale"
                                                        {{ $role->hasPermissionTo('delete_sale') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.delete_sale')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_settings"
                                                        {{ $role->hasPermissionTo('sale_settings') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.sale_settings')
                                                </p>

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_index" {{ $role->hasPermissionTo('receive_payment_index') ? 'checked' : '' }} class="sale sales_app_permission super_select_all">@lang('menu.view_all_receive_payments')
                                                </p> --}}

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_create" {{ $role->hasPermissionTo('receive_payment_create') ? 'checked' : '' }} class="sale sales_app_permission super_select_all">@lang('menu.create_receive_payment')
                                                </p> --}}

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_view" {{ $role->hasPermissionTo('receive_payment_view') ? 'checked' : '' }} class="sale sales_app_permission super_select_all">@lang('menu.single_receive_payment_view')
                                                </p> --}}

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_update" {{ $role->hasPermissionTo('receive_payment_update') ? 'checked' : '' }} class="sale sales_app_permission super_select_all">@lang('menu.update_receive_payment')
                                                </p> --}}

                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_delete" {{ $role->hasPermissionTo('receive_payment_delete') ? 'checked' : '' }} class="sale sales_app_permission super_select_all"> @lang('menu.delete') @lang('menu.receive_payment')
                                                </p> --}}
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="hidden">
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="add_quotation"
                                                        {{ $role->hasPermissionTo('add_quotation') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.create_quotation')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_quotation_list"
                                                        {{ $role->hasPermissionTo('sale_quotation_list') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.manage') @lang('role.quotation')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_quotation_edit"
                                                        {{ $role->hasPermissionTo('sale_quotation_edit') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('role.edit_quotation')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_quotation_delete"
                                                        {{ $role->hasPermissionTo('sale_quotation_delete') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('role.delete_quotation')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_order_add"
                                                        {{ $role->hasPermissionTo('sale_order_add') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.create') @lang('menu.sales_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_order_all"
                                                        {{ $role->hasPermissionTo('sale_order_all') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.manage') @lang('menu.sales_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_order_edit"
                                                        {{ $role->hasPermissionTo('sale_order_edit') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.edit') @lang('menu.sales_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_order_do_approval"
                                                        {{ $role->hasPermissionTo('sale_order_do_approval') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('menu.do_approval')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_order_delete"
                                                        {{ $role->hasPermissionTo('sale_order_delete') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.delete') @lang('menu.sales_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_add"
                                                        {{ $role->hasPermissionTo('do_add') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.create') @lang('menu.delivery_order')
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="hidden">
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_all"
                                                        {{ $role->hasPermissionTo('do_all') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.manage') @lang('menu.delivery_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_edit"
                                                        {{ $role->hasPermissionTo('do_edit') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.edit')
                                                    @lang('menu.delivery_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_delete"
                                                        {{ $role->hasPermissionTo('do_delete') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.delete')
                                                    @lang('menu.delivery_order')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="change_expire_date"
                                                        {{ $role->hasPermissionTo('change_expire_date') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">

                                                    @lang('role.change_expire_date')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_to_final"
                                                        {{ $role->hasPermissionTo('do_to_final') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('role.do_to_final')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="quotation_notification"
                                                        {{ $role->hasPermissionTo('quotation_notification') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('role.get_notification_after_creating_the_quotation')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sales_order_notification"
                                                        {{ $role->hasPermissionTo('sales_order_notification') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    Get notification after createing the sales orderdo
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_notification"
                                                        {{ $role->hasPermissionTo('do_notification') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    Get notification after createing the do
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="price_update_notification"
                                                        {{ $role->hasPermissionTo('price_update_notification') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('role.notification_about_price_update')
                                                </p>
                                            </div>

                                            <div class="col-lg-3 col-sm-6">
                                                <p class="checkbox_input_wrap mt-1"><input type="hidden"></p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="do_approval_notification"
                                                        {{ $role->hasPermissionTo('do_approval_notification') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('role.get_notification_after_do_approval')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_price_sale_screen"
                                                        {{ $role->hasPermissionTo('edit_price_sale_screen') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('role.edit_product_price_from_sales_screen')
                                                </p>
                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="edit_discount_sale_screen"
                                                        {{ $role->hasPermissionTo('edit_discount_sale_screen') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('role.edit_product_discount_in_sale_scr').
                                                </p>
                                                {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="shipment_access" {{ $role->hasPermissionTo('shipment_access') ? 'checked' : '' }} class="sale sales_app_permission super_select_all">@lang('role.access_shipments')
                                                </p> --}}

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="view_product_cost_is_sale_screed"
                                                        {{ $role->hasPermissionTo('view_product_cost_is_sale_screed') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">@lang('role.view_item_cost_in_sale_screen')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox"
                                                        {{ $role->hasPermissionTo('view_own_sale') ? 'checked' : '' }}
                                                        name="view_own_sale"
                                                        class="sale sales_app_permission super_select_all">@lang('role.view_only_own_data')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="discounts"
                                                        {{ $role->hasPermissionTo('discounts') ? 'checked' : '' }}
                                                        class="sale sales_app_permission super_select_all">
                                                    @lang('menu.manage')
                                                    Offers
                                                </p>
                                            </div>

                                            <hr>
                                            <div class="col-lg-3 col-sm-6">
                                                <p class="text-info">
                                                    <input id="manage_sr" type="checkbox"
                                                        class="select_all super_select_all sales_app_permission super_select_all"
                                                        data-target="manage_sr" autocomplete="off">
                                                    <strong> @lang('menu.manage_sr')</strong>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="manage_sr_index"
                                                        {{ $role->hasPermissionTo('manage_sr_index') ? 'checked' : '' }}
                                                        class="manage_sr sales_app_permission super_select_all">
                                                    @lang('menu.sr_list')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="manage_sr_manage"
                                                        {{ $role->hasPermissionTo('manage_sr_manage') ? 'checked' : '' }}
                                                        class="manage_sr sales_app_permission super_select_all">
                                                    @lang('menu.manage_sr')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="manage_sr_create"
                                                        {{ $role->hasPermissionTo('manage_sr_create') ? 'checked' : '' }}
                                                        class="manage_sr sales_app_permission super_select_all">
                                                    @lang('menu.add_sr')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="manage_sr_edit"
                                                        {{ $role->hasPermissionTo('manage_sr_edit') ? 'checked' : '' }}
                                                        class="manage_sr sales_app_permission super_select_all">
                                                    @lang('menu.edit_sr')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="manage_sr_delete"
                                                        {{ $role->hasPermissionTo('manage_sr_delete') ? 'checked' : '' }}
                                                        class="manage_sr sales_app_permission super_select_all">
                                                    @lang('menu.delete_sr')
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox"
                                class="procur_check select_all super_select_all procurement_permission super_select_all"
                                data-target="procurement_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="procur_role" href="#collapseTwo" href="">
                                @lang('role.procurement_permissions')
                            </a>
                        </div>
                        <div id="collapseTwo" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">

                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission"
                                                    data-target="purchase" autocomplete="off"><strong>
                                                    @lang('menu.purchases')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_all"
                                                    {{ $role->hasPermissionTo('purchase_all') ? 'checked' : '' }}
                                                    class="purchase procurement_permission super_select_all">
                                                @lang('menu.manage') purchase
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_add"
                                                    {{ $role->hasPermissionTo('purchase_add') ? 'checked' : '' }}
                                                    class="purchase procurement_permission super_select_all"> Add purchase
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_edit"
                                                    {{ $role->hasPermissionTo('purchase_edit') ? 'checked' : '' }}
                                                    class="purchase procurement_permission super_select_all"> Edit purchase
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_delete"
                                                    {{ $role->hasPermissionTo('purchase_delete') ? 'checked' : '' }}
                                                    class="purchase procurement_permission super_select_all">
                                                @lang('role.delete') purchase
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_settings"
                                                    {{ $role->hasPermissionTo('purchase_settings') ? 'checked' : '' }}
                                                    class="purchase procurement_permission super_select_all">
                                                @lang('role.purchase') settings
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission"
                                                    data-target="requisition" autocomplete="off"><strong>
                                                    @lang('menu.requisitions')</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_requisition"
                                                    {{ $role->hasPermissionTo('create_requisition') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('menu.create_requisition')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="all_requisition"
                                                    {{ $role->hasPermissionTo('all_requisition') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('menu.manage') @lang('menu.requisitions')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_requisition"
                                                    {{ $role->hasPermissionTo('edit_requisition') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('menu.edit') @lang('menu.requisitions')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="approve_requisition"
                                                    {{ $role->hasPermissionTo('approve_requisition') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('menu.approved') @lang('menu.requisitions')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_requisition"
                                                    {{ $role->hasPermissionTo('delete_requisition') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('role.delete') @lang('menu.requisitions')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="requisition_notification"
                                                    {{ $role->hasPermissionTo('requisition_notification') ? 'checked' : '' }}
                                                    class="requisition procurement_permission super_select_all">
                                                @lang('role.get_notification_after_creating_requisition')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission"
                                                    data-target="purchase_order"
                                                    autocomplete="off"><strong>@lang('menu.purchase_order')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_po"
                                                    {{ $role->hasPermissionTo('create_po') ? 'checked' : '' }}
                                                    class="purchase_order procurement_permission super_select_all">
                                                @lang('menu.create_purchase_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="all_po"
                                                    {{ $role->hasPermissionTo('all_po') ? 'checked' : '' }}
                                                    class="purchase_order procurement_permission super_select_all">
                                                @lang('menu.manage') @lang('role.purchase') @lang('menu.order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_po"
                                                    {{ $role->hasPermissionTo('edit_po') ? 'checked' : '' }}
                                                    class="purchase_order procurement_permission super_select_all">
                                                @lang('menu.edit') @lang('menu.purchase_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_po"
                                                    {{ $role->hasPermissionTo('delete_po') ? 'checked' : '' }}
                                                    class="purchase_order procurement_permission super_select_all">
                                                @lang('menu.delete') @lang('menu.purchase_order')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="po_notification"
                                                    {{ $role->hasPermissionTo('po_notification') ? 'checked' : '' }}
                                                    class="purchase_order procurement_permission super_select_all">
                                                @lang('role.get_notification_after_creating') @lang('role.purchase') @lang('menu.order')
                                            </p>
                                        </div>

                                        {{-- <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="purchase_payment" autocomplete="off"><strong> @lang('role.purchase') @lang('menu.payments')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_index" {{ $role->hasPermissionTo('purchase_payment_index') ? 'checked' : '' }} class="purchase_payment procurement_permission super_select_all">
                                                @lang('role.view_all') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_create" {{ $role->hasPermissionTo('purchase_payment_create') ? 'checked' : '' }} class="purchase_payment procurement_permission super_select_all">
                                                @lang('role.create') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_view" {{ $role->hasPermissionTo('purchase_payment_view') ? 'checked' : '' }} class="purchase_payment procurement_permission super_select_all">
                                                @lang('role.single_purchase') @lang('menu.payments') @lang('role.view')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_update" {{ $role->hasPermissionTo('purchase_payment_update') ? 'checked' : '' }} class="purchase_payment procurement_permission super_select_all">
                                                @lang('menu.update') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_delete" {{ $role->hasPermissionTo('purchase_payment_delete') ? 'checked' : '' }} class="purchase_payment procurement_permission super_select_all">
                                                @lang('role.delete') @lang('role.purchase') @lang('menu.payments')
                                            </p>
                                        </div> --}}

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all procurement_permission"
                                                    data-target="receive_stocks" autocomplete="off">
                                                <strong>@lang('role.receive_stocks')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_stocks_index"
                                                    {{ $role->hasPermissionTo('receive_stocks_index') ? 'checked' : '' }}
                                                    class="receive_stocks procurement_permission super_select_all">
                                                @lang('role.receive_stocks_list')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_stocks_view"
                                                    {{ $role->hasPermissionTo('receive_stocks_view') ? 'checked' : '' }}
                                                    class="receive_stocks procurement_permission super_select_all">
                                                @lang('role.stock_issue_details')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_stocks_create"
                                                    {{ $role->hasPermissionTo('receive_stocks_create') ? 'checked' : '' }}
                                                    class="receive_stocks procurement_permission super_select_all">
                                                @lang('role.receive_stock_create')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_stocks_update"
                                                    {{ $role->hasPermissionTo('receive_stocks_update') ? 'checked' : '' }}
                                                    class="receive_stocks procurement_permission super_select_all">
                                                @lang('role.receive_stock_update')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="receive_stocks_delete"
                                                    {{ $role->hasPermissionTo('receive_stocks_delete') ? 'checked' : '' }}
                                                    class="receive_stocks procurement_permission super_select_all">
                                                @lang('role.receive_stock_delete')
                                            </p>
                                        </div>
                                    </div>

                                    <hr class="my-2">

                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission super_select_all"
                                                    data-target="suppliers" autocomplete="off"> <strong>
                                                    @lang('menu.suppliers')</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_all"
                                                    {{ $role->hasPermissionTo('supplier_all') ? 'checked' : '' }}
                                                    class="suppliers procurement_permission super_select_all">
                                                @lang('menu.view_all_supplier')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_add"
                                                    {{ $role->hasPermissionTo('supplier_add') ? 'checked' : '' }}
                                                    class="suppliers procurement_permission super_select_all">@lang('menu.add_supplier')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_import"
                                                    {{ $role->hasPermissionTo('supplier_import') ? 'checked' : '' }}
                                                    class="suppliers procurement_permission super_select_all">
                                                @lang('role.import') supplier
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_edit"
                                                    {{ $role->hasPermissionTo('supplier_edit') ? 'checked' : '' }}
                                                    class="suppliers procurement_permission super_select_all">
                                                @lang('menu.edit_supplier')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_delete"
                                                    {{ $role->hasPermissionTo('supplier_delete') ? 'checked' : '' }}
                                                    class="suppliers procurement_permission super_select_all">
                                                @lang('role.delete') supplier
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission super_select_all"
                                                    data-target="purchase_by_scale" autocomplete="off"> <strong>
                                                    @lang('role.purchase') By @lang('role.scale')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_by_scale_index"
                                                    {{ $role->hasPermissionTo('purchase_by_scale_index') ? 'checked' : '' }}
                                                    class="purchase_by_scale procurement_permission super_select_all">
                                                @lang('role.view_all') @lang('role.purchase') By @lang('role.scale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_by_scale_view"
                                                    {{ $role->hasPermissionTo('purchase_by_scale_index') ? 'checked' : '' }}
                                                    class="purchase_by_scale procurement_permission super_select_all">
                                                Single View @lang('role.purchase') By @lang('role.scale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_by_scale_create"
                                                    {{ $role->hasPermissionTo('purchase_by_scale_create') ? 'checked' : '' }}
                                                    class="purchase_by_scale procurement_permission super_select_all"> Add
                                                @lang('role.purchase') By @lang('role.scale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_by_scale_delete"
                                                    {{ $role->hasPermissionTo('purchase_by_scale_delete') ? 'checked' : '' }}
                                                    class="purchase_by_scale procurement_permission super_select_all">
                                                @lang('role.delete') @lang('role.purchase') By @lang('role.scale')
                                            </p>
                                        </div>

                                        <div class="col-md-3">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission super_select_all"
                                                    data-target="purchase_return" autocomplete="off"> <strong>
                                                    @lang('menu.purchase_return')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_purchase_return"
                                                    {{ $role->hasPermissionTo('view_purchase_return') ? 'checked' : '' }}
                                                    class="purchase_return procurement_permission super_select_all">
                                                @lang('role.view_all') purchase return
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_purchase_return"
                                                    {{ $role->hasPermissionTo('add_purchase_return') ? 'checked' : '' }}
                                                    class="purchase_return procurement_permission super_select_all"> Add
                                                purchase return
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_purchase_return"
                                                    {{ $role->hasPermissionTo('edit_purchase_return') ? 'checked' : '' }}
                                                    class="purchase_return procurement_permission super_select_all"> Edit
                                                purchase return
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_purchase_return"
                                                    {{ $role->hasPermissionTo('delete_purchase_return') ? 'checked' : '' }}
                                                    class="purchase_return procurement_permission super_select_all">
                                                @lang('role.delete') purchase return
                                            </p>
                                        </div>

                                        <div class="col-md-3">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all procurement_permission"
                                                    data-target="stock_issue" autocomplete="off">
                                                <strong>@lang('role.stock_issue')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue"
                                                    {{ $role->hasPermissionTo('stock_issue') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_index"
                                                    {{ $role->hasPermissionTo('stock_issue_index') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue') @lang('role.list')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_create"
                                                    {{ $role->hasPermissionTo('stock_issue_create') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue') @lang('role.create')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_view"
                                                    {{ $role->hasPermissionTo('stock_issue_view') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue') @lang('role.details')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_update"
                                                    {{ $role->hasPermissionTo('stock_issue_update') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue') @lang('role.update')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_delete"
                                                    {{ $role->hasPermissionTo('stock_issue_delete') ? 'checked' : '' }}
                                                    class="stock_issue procurement_permission super_select_all">
                                                @lang('role.stock_issue') @lang('role.delete')
                                            </p>
                                        </div>
                                    </div>

                                    <hr class="my-2">

                                    <div class="row">
                                        <div class="col-md-3 ">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all procurement_permission super_select_all"
                                                    data-target="procurement_report" autocomplete="off"><strong>
                                                    @lang('menu.procurement_reports')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="requested_product_report"
                                                    {{ $role->hasPermissionTo('requested_product_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('menu.requested_item_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="weighted_product_report"
                                                    {{ $role->hasPermissionTo('weighted_product_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('menu.weighted_item_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="received_stocks_report"
                                                    {{ $role->hasPermissionTo('received_stocks_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">@lang('menu.received_stocks_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_report"
                                                    {{ $role->hasPermissionTo('purchase_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('menu.purchase_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_sale_report"
                                                    {{ $role->hasPermissionTo('purchase_sale_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('role.purchase') & @lang('role.sale_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pro_purchase_report"
                                                    {{ $role->hasPermissionTo('pro_purchase_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('role.purchased_items_report')
                                            </p>

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_report" {{ $role->hasPermissionTo('purchase_payment_report') ? 'checked' : '' }} class="procurement_report procurement_permission super_select_all">  @lang('menu.purchase_payment_report')
                                            </p> --}}

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issue_report"
                                                    {{ $role->hasPermissionTo('stock_issue_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('role.stock_issue_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="stock_issued_items_report"
                                                    {{ $role->hasPermissionTo('stock_issued_items_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('role.stock_issued_items_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_return_report"
                                                    {{ $role->hasPermissionTo('purchase_return_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('menu.purchase_return_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_returned_items_report"
                                                    {{ $role->hasPermissionTo('purchase_returned_items_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('menu.purchase_returned_items_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="supplier_report"
                                                    {{ $role->hasPermissionTo('supplier_report') ? 'checked' : '' }}
                                                    class="procurement_report procurement_permission super_select_all">
                                                @lang('role.supplier_report')
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="inven_check select_all inventory_permission super_select_all"
                                data-target="inventory_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="inven_role" href="#collapseThree"
                                href="">@lang('role.inventory_permissions')</a>
                        </div>
                        <div id="collapseThree" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all inventory_permission"
                                                data-target="product" autocomplete="off">
                                            <strong>@lang('menu.items')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_all"
                                                {{ $role->hasPermissionTo('product_all') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('menu.view_all_item')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_add"
                                                {{ $role->hasPermissionTo('product_add') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all">@lang('menu.add_item')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_edit"
                                                {{ $role->hasPermissionTo('product_edit') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('role.edit_item')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="openingStock_add"
                                                {{ $role->hasPermissionTo('openingStock_add') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all">
                                            @lang('menu.add')/@lang('menu.edit') @lang('menu.opening_stock')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_delete"
                                                {{ $role->hasPermissionTo('product_delete') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('role.delete')
                                            @lang('role.item')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="product_settings"
                                                {{ $role->hasPermissionTo('product_settings') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all">@lang('menu.item_settings')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="hidden">
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="categories"
                                                {{ $role->hasPermissionTo('categories') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('role.categories')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="brand"
                                                {{ $role->hasPermissionTo('brand') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('menu.brand')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="units"
                                                {{ $role->hasPermissionTo('units') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('role.unit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="variant"
                                                {{ $role->hasPermissionTo('variant') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('role.variants')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="warranties"
                                                {{ $role->hasPermissionTo('warranties') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('menu.warranties')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="selling_price_group"
                                                {{ $role->hasPermissionTo('selling_price_group') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all"> @lang('menu.selling_price_group')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="generate_barcode"
                                                {{ $role->hasPermissionTo('generate_barcode') ? 'checked' : '' }}
                                                class="product inventory_permission super_select_all">@lang('menu.generate_barcode')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all inventory_permission super_select_all"
                                                data-target="stock_adjustments" autocomplete="off"> <strong>
                                                @lang('role.stock_adjustments')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_adjustments_all"
                                                {{ $role->hasPermissionTo('stock_adjustments_all') ? 'checked' : '' }}
                                                class="stock_adjustments inventory_permission super_select_all">
                                            @lang('role.view_all') @lang('role.adjustment')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_adjustments_add"
                                                {{ $role->hasPermissionTo('stock_adjustments_add') ? 'checked' : '' }}
                                                class="stock_adjustments inventory_permission super_select_all">
                                            @lang('role.add_stock_adjustment')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_adjustments_delete"
                                                {{ $role->hasPermissionTo('stock_adjustments_delete') ? 'checked' : '' }}
                                                class="stock_adjustments inventory_permission super_select_all">@lang('role.delete_stock_adjustment')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all inventory_permission "
                                                data-target="daily_stock" autocomplete="off">
                                            <strong>@lang('role.daily_stock')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock"
                                                {{ $role->hasPermissionTo('daily_stock') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_index"
                                                {{ $role->hasPermissionTo('daily_stock_index') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_create"
                                                {{ $role->hasPermissionTo('daily_stock_create') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_view"
                                                {{ $role->hasPermissionTo('daily_stock_view') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock') @lang('role.details')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_update"
                                                {{ $role->hasPermissionTo('daily_stock_update') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock') @lang('role.update')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_delete"
                                                {{ $role->hasPermissionTo('daily_stock_delete') ? 'checked' : '' }}
                                                class="daily_stock inventory_permission super_select_all">
                                            @lang('role.daily_stock') @lang('role.delete')
                                        </p>

                                        {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_out_report" {{ $role->hasPermissionTo('daily_stock_delete') ? 'checked' : '' }} class="daily_stock inventory_permission super_select_all">
                                            Stock out report
                                        </p> --}}
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all inventory_permission super_select_all"
                                                data-target="transfer_stock" autocomplete="off"><strong>
                                                @lang('role.transfer_stock')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="transfer_wh_to_bl"
                                                {{ $role->hasPermissionTo('transfer_wh_to_bl') ? 'checked' : '' }}
                                                class="transfer_stock inventory_permission super_select_all">
                                            @lang('role.transfer_stock') @lang('role.wh_to_b_location')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="transfer_bl_wh"
                                                {{ $role->hasPermissionTo('transfer_bl_wh') ? 'checked' : '' }}
                                                class="transfer_stock inventory_permission super_select_all">
                                            @lang('role.transfer_stock') @lang('role.s_location_to_wh')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all inventory_permission super_select_all"
                                                data-target="inventory_report"
                                                autocomplete="off"><strong>@lang('role.inventory_report')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_adjustment_report"
                                                {{ $role->hasPermissionTo('stock_adjustment_report') ? 'checked' : '' }}
                                                class="inventory_report inventory_permission super_select_all">@lang('menu.stock_adjustment_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_report"
                                                {{ $role->hasPermissionTo('stock_report') ? 'checked' : '' }}
                                                class="inventory_report inventory_permission super_select_all">@lang('menu.stock_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_stock_report"
                                                {{ $role->hasPermissionTo('daily_stock_report') ? 'checked' : '' }}
                                                class="inventory_report inventory_permission super_select_all">@lang('role.daily_stock_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_in_out_report"
                                                {{ $role->hasPermissionTo('stock_in_out_report') ? 'checked' : '' }}
                                                class="inventory_report inventory_permission super_select_all">@lang('menu.stock_in_out_report')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="finance_check select_all finance_permission super_select_all"
                                data-target="finance_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="finance_role" href="#collapseFour" href="">
                                @lang('role.finance_permissions')
                            </a>
                        </div>
                        <div id="collapseFour" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="banks" autocomplete="off"><strong>
                                                @lang('role.banks')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="banks_index"
                                                {{ $role->hasPermissionTo('banks_index') ? 'checked' : '' }}
                                                class="banks finance_permission super_select_all"> @lang('role.bank_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="banks_add"
                                                {{ $role->hasPermissionTo('banks_add') ? 'checked' : '' }}
                                                class="banks finance_permission super_select_all"> @lang('role.bank_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="banks_edit"
                                                {{ $role->hasPermissionTo('banks_edit') ? 'checked' : '' }}
                                                class="banks finance_permission super_select_all"> @lang('role.bank_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="banks_delete"
                                                {{ $role->hasPermissionTo('banks_delete') ? 'checked' : '' }}
                                                class="banks finance_permission super_select_all"> @lang('role.bank_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="account_groups" autocomplete="off"><strong>
                                                @lang('menu.account_groups')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="account_groups_index"
                                                {{ $role->hasPermissionTo('account_groups_index') ? 'checked' : '' }}
                                                class="account_groups finance_permission super_select_all">
                                            @lang('role.account_group_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="account_groups_add"
                                                {{ $role->hasPermissionTo('account_groups_add') ? 'checked' : '' }}
                                                class="account_groups finance_permission super_select_all">
                                            @lang('role.account_group_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="account_groups_edit"
                                                {{ $role->hasPermissionTo('account_groups_edit') ? 'checked' : '' }}
                                                class="account_groups finance_permission super_select_all">
                                            @lang('role.account_group_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="account_groups_delete"
                                                {{ $role->hasPermissionTo('account_groups_delete') ? 'checked' : '' }}
                                                class="account_groups finance_permission super_select_all">
                                            @lang('role.account_groups_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="accounts" autocomplete="off"><strong>
                                                @lang('menu.accounts')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="accounts_index"
                                                {{ $role->hasPermissionTo('accounts_index') ? 'checked' : '' }}
                                                class="accounts finance_permission super_select_all"> @lang('role.account_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="accounts_ledger"
                                                {{ $role->hasPermissionTo('accounts_ledger') ? 'checked' : '' }}
                                                class="accounts finance_permission super_select_all"> @lang('role.account_ledger')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="accounts_add"
                                                {{ $role->hasPermissionTo('accounts_add') ? 'checked' : '' }}
                                                class="accounts finance_permission super_select_all"> @lang('role.account_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="accounts_edit"
                                                {{ $role->hasPermissionTo('accounts_edit') ? 'checked' : '' }}
                                                class="accounts finance_permission super_select_all"> @lang('role.account_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="accounts_delete"
                                                {{ $role->hasPermissionTo('accounts_delete') ? 'checked' : '' }}
                                                class="accounts finance_permission super_select_all"> @lang('role.account_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="cost_centres" autocomplete="off"><strong>
                                                @lang('menu.cost_centres')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centres_index"
                                                {{ $role->hasPermissionTo('cost_centres_index') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centres_add"
                                                {{ $role->hasPermissionTo('cost_centres_add') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centres_edit"
                                                {{ $role->hasPermissionTo('cost_centres_edit') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centres_delete"
                                                {{ $role->hasPermissionTo('cost_centres_delete') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_delete')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centre_categories_add"
                                                {{ $role->hasPermissionTo('cost_centre_categories_add') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_category_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centre_categories_edit"
                                                {{ $role->hasPermissionTo('cost_centre_categories_edit') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_category_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cost_centre_categories_delete"
                                                {{ $role->hasPermissionTo('cost_centre_categories_delete') ? 'checked' : '' }}
                                                class="cost_centres finance_permission super_select_all">
                                            @lang('role.cost_centre_category_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="chart_of_accounts" autocomplete="off"><strong>
                                                @lang('menu.chart_of_accounts')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="chart_of_accounts_index"
                                                {{ $role->hasPermissionTo('chart_of_accounts_index') ? 'checked' : '' }}
                                                class="chart_of_accounts finance_permission super_select_all">
                                            @lang('role.view_chart_of_accounts')
                                        </p>
                                    </div>
                                </div>

                                <div class="row mt-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="receipts" autocomplete="off"><strong>
                                                @lang('role.receipts')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="receipts_index"
                                                {{ $role->hasPermissionTo('receipts_index') ? 'checked' : '' }}
                                                class="receipts finance_permission super_select_all"> @lang('role.receipt_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="receipts_add"
                                                {{ $role->hasPermissionTo('receipts_add') ? 'checked' : '' }}
                                                class="receipts finance_permission super_select_all"> @lang('role.receipt_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="receipts_edit"
                                                {{ $role->hasPermissionTo('receipts_edit') ? 'checked' : '' }}
                                                class="receipts finance_permission super_select_all"> @lang('role.receipt_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="receipts_delete"
                                                {{ $role->hasPermissionTo('receipts_delete') ? 'checked' : '' }}
                                                class="receipts finance_permission super_select_all"> @lang('role.receipt_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="payments" autocomplete="off"><strong>
                                                @lang('menu.payments')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="payments_index"
                                                {{ $role->hasPermissionTo('payments_index') ? 'checked' : '' }}
                                                class="payments finance_permission super_select_all"> @lang('role.payment_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="payments_add"
                                                {{ $role->hasPermissionTo('payments_add') ? 'checked' : '' }}
                                                class="payments finance_permission super_select_all"> @lang('role.payment_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="payments_edit"
                                                {{ $role->hasPermissionTo('payments_edit') ? 'checked' : '' }}
                                                class="payments finance_permission super_select_all"> @lang('role.payment_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="payments_delete"
                                                {{ $role->hasPermissionTo('payments_delete') ? 'checked' : '' }}
                                                class="payments finance_permission super_select_all"> @lang('role.payment_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all finance_permission"
                                                data-target="journals" autocomplete="off"><strong>
                                                @lang('menu.journals')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="journals_index"
                                                {{ $role->hasPermissionTo('journals_index') ? 'checked' : '' }}
                                                class="journals finance_permission super_select_all"> @lang('role.journal_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="journals_add"
                                                {{ $role->hasPermissionTo('journals_add') ? 'checked' : '' }}
                                                class="journals finance_permission super_select_all"> @lang('role.journal_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="journals_edit"
                                                {{ $role->hasPermissionTo('journals_edit') ? 'checked' : '' }}
                                                class="journals finance_permission super_select_all"> @lang('role.journal_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="journals_delete"
                                                {{ $role->hasPermissionTo('journals_delete') ? 'checked' : '' }}
                                                class="journals finance_permission super_select_all"> @lang('role.journal_delete')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all finance_permission"
                                                data-target="contras" autocomplete="off"><strong>
                                                @lang('role.contras')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="contras_index"
                                                {{ $role->hasPermissionTo('contras_index') ? 'checked' : '' }}
                                                class="contras finance_permission super_select_all"> @lang('role.contra_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="contras_add"
                                                {{ $role->hasPermissionTo('contras_add') ? 'checked' : '' }}
                                                class="contras finance_permission super_select_all"> @lang('role.contra_add')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="contras_edit"
                                                {{ $role->hasPermissionTo('contras_edit') ? 'checked' : '' }}
                                                class="contras finance_permission super_select_all"> @lang('role.contra_edit')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="contras_delete"
                                                {{ $role->hasPermissionTo('contras_delete') ? 'checked' : '' }}
                                                class="contras finance_permission super_select_all"> @lang('role.contra_delete')
                                        </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all finance_permission"
                                                data-target="expenses" autocomplete="off"><strong>
                                                @lang('menu.expense')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="view_expense"
                                                {{ $role->hasPermissionTo('view_expense') ? 'checked' : '' }}
                                                class="expenses finance_permission super_select_all"> @lang('role.view_expense')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="add_expense"
                                                {{ $role->hasPermissionTo('add_expense') ? 'checked' : '' }}
                                                class="expenses finance_permission super_select_all"> Add expense
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_expense"
                                                {{ $role->hasPermissionTo('edit_expense') ? 'checked' : '' }}
                                                class="expenses finance_permission super_select_all"> @lang('menu.edit_expense')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_expense"
                                                {{ $role->hasPermissionTo('delete_expense') ? 'checked' : '' }}
                                                class="expenses finance_permission super_select_all"> @lang('role.delete')
                                            expense
                                        </p>

                                        {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="expense_category" {{ $role->hasPermissionTo('expense_category') ? 'checked' : '' }} class="expenses finance_permission super_select_all">Expense categories
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="category_wise_expense" {{ $role->hasPermissionTo('category_wise_expense') ? 'checked' : '' }} class="expenses finance_permission super_select_all"> View category wise expense
                                        </p> --}}
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all finance_permission"
                                                data-target="incomes" autocomplete="off"><strong>
                                                @lang('menu.incomes')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="incomes_index"
                                                {{ $role->hasPermissionTo('incomes_index') ? 'checked' : '' }}
                                                class="incomes finance_permission super_select_all">
                                            @lang('menu.income_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="incomes_show"
                                                {{ $role->hasPermissionTo('incomes_show') ? 'checked' : '' }}
                                                class="incomes finance_permission super_select_all">
                                            @lang('menu.income_single_view')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="incomes_create"
                                                {{ $role->hasPermissionTo('incomes_create') ? 'checked' : '' }}
                                                class="incomes finance_permission super_select_all">
                                            @lang('menu.add_income')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="incomes_edit"
                                                {{ $role->hasPermissionTo('incomes_edit') ? 'checked' : '' }}
                                                class="incomes finance_permission super_select_all">
                                            @lang('menu.edit_income')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="incomes_delete"
                                                {{ $role->hasPermissionTo('incomes_delete') ? 'checked' : '' }}
                                                class="incomes finance_permission super_select_all">
                                            @lang('role.delete') @lang('menu.income')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all finance_permission"
                                                data-target="finance_report" autocomplete="off"><strong>
                                                @lang('menu.finance_report')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="balance_sheet"
                                                {{ $role->hasPermissionTo('balance_sheet') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.balance_sheet')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="trial_balance"
                                                {{ $role->hasPermissionTo('trial_balance') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.trial_balance')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cash_flow"
                                                {{ $role->hasPermissionTo('cash_flow') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.cash_flow')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="fund_flow"
                                                {{ $role->hasPermissionTo('fund_flow') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.fund_flow')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="day_book"
                                                {{ $role->hasPermissionTo('day_book') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.day_book')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="outstanding_receivables"
                                                {{ $role->hasPermissionTo('outstanding_receivables') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.outstanding_receivables')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="outstanding_payables"
                                                {{ $role->hasPermissionTo('outstanding_payables') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.outstanding_payables')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="profit_loss_ac"
                                                {{ $role->hasPermissionTo('profit_loss_ac') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.profit_loss_account')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="daily_profit_loss"
                                                {{ $role->hasPermissionTo('daily_profit_loss') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">@lang('menu.daily_profit_loss')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="expanse_report"
                                                {{ $role->hasPermissionTo('expanse_report') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.expanse_report')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="income_report"
                                                {{ $role->hasPermissionTo('income_report') ? 'checked' : '' }}
                                                class="finance_report finance_permission super_select_all">
                                            @lang('menu.income_report')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($addons->manufacturing == 1)
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="accordion-header">
                                <input type="checkbox"
                                    class="manufacturing_check select_all super_select_all manufacturing_permission "
                                    data-target="manufacturing_permission" autocomplete="off">
                                <a data-bs-toggle="collapse" class="manufacturing_role" href="#collapseFive"
                                    href="">
                                    @lang('menu.manufacturing_permissions')
                                </a>
                            </div>
                            <div id="collapseFive" class="collapse" data-bs-parent="#accordion">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all manufacturing_permission"
                                                    data-target="manage_production" autocomplete="off"><strong>
                                                    @lang('menu.manage') production</strong></strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_view"
                                                    {{ $role->hasPermissionTo('process_view') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">@lang('menu.view_process')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_add"
                                                    {{ $role->hasPermissionTo('process_add') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">@lang('menu.add_process')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_edit"
                                                    {{ $role->hasPermissionTo('process_edit') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">@lang('menu.edit_process')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_delete"
                                                    {{ $role->hasPermissionTo('process_delete') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">
                                                @lang('role.delete') @lang('menu.process')
                                            </p>


                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_view"
                                                    {{ $role->hasPermissionTo('production_view') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">
                                                @lang('menu.view_production')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_add"
                                                    {{ $role->hasPermissionTo('production_add') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">@lang('menu.add_production')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_edit"
                                                    {{ $role->hasPermissionTo('production_edit') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">@lang('menu.edit_production')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="production_delete"
                                                    {{ $role->hasPermissionTo('production_delete') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">
                                                @lang('role.delete') @lang('menu.production')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manuf_settings"
                                                    {{ $role->hasPermissionTo('manuf_settings') ? 'checked' : '' }}
                                                    class="manage_production manufacturing_permission super_select_all">
                                                @lang('menu.manufacturing_settings')
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"> <input type="checkbox"
                                                    class="select_all super_select_all manufacturing_permission"
                                                    data-target="menufacturing_report" autocomplete="off"><strong>
                                                    @lang('menu.manufacturing_report')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="process_report"
                                                    {{ $role->hasPermissionTo('process_report') ? 'checked' : '' }}
                                                    class="menufacturing_report manufacturing_permission super_select_all">
                                                @lang('menu.process_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manuf_report"
                                                    {{ $role->hasPermissionTo('manuf_report') ? 'checked' : '' }}
                                                    class="menufacturing_report manufacturing_permission super_select_all">
                                                @lang('menu.manufacturing_report')
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox"
                                class="communication_check select_all super_select_all communication_permission"
                                data-target="communication_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="communication_role" href="#collapseSix"
                                href="">
                                @lang('menu.communication_permissions')
                            </a>
                        </div>
                        <div id="collapseSix" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all communication_permission"
                                                data-target="communication" autocomplete="off"><strong>
                                                @lang('menu.communication')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="notice_board"
                                                {{ $role->hasPermissionTo('notice_board') ? 'checked' : '' }}
                                                class="communication super_select_all communication_permission">@lang('menu.notice_board')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="email"
                                                {{ $role->hasPermissionTo('email') ? 'checked' : '' }}
                                                class="communication super_select_all communication_permission">
                                            @lang('menu.email')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="email_settings"
                                                {{ $role->hasPermissionTo('email_settings') ? 'checked' : '' }}
                                                class="communication super_select_all communication_permission">
                                            @lang('menu.email') @lang('menu.settings')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sms"
                                                {{ $role->hasPermissionTo('sms') ? 'checked' : '' }}
                                                class="communication super_select_all communication_permission">
                                            @lang('menu.sms')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="sms_settings"
                                                {{ $role->hasPermissionTo('sms_settings') ? 'checked' : '' }}
                                                class="communication super_select_all communication_permission">
                                            @lang('menu.sms_settings')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox"
                                class="utilities_check select_all super_select_all utilities_permission "
                                data-target="utilities_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="utilities_role" href="#collapseSeven"
                                href="">
                                @lang('menu.utilities_permissions')
                            </a>
                        </div>
                        <div id="collapseSeven" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox"
                                                class="select_all super_select_all utilities_permission"
                                                data-target="utilities" autocomplete="off"><strong>
                                                @lang('menu.utilities')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="media"
                                                {{ $role->hasPermissionTo('media') ? 'checked' : '' }}
                                                class="utilities utilities_permission super_select_all">
                                            @lang('menu.media')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="calender"
                                                {{ $role->hasPermissionTo('calender') ? 'checked' : '' }}
                                                class="utilities utilities_permission super_select_all">
                                            @lang('menu.calender')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="announcement"
                                                {{ $role->hasPermissionTo('announcement') ? 'checked' : '' }}
                                                class="utilities utilities_permission super_select_all">
                                            @lang('menu.announcement')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="activity_log"
                                                {{ $role->hasPermissionTo('activity_log') ? 'checked' : '' }}
                                                class="utilities utilities_permission super_select_all">
                                            @lang('menu.activity_log')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="database_backup"
                                                {{ $role->hasPermissionTo('database_backup') ? 'checked' : '' }}
                                                class="utilities utilities_permission super_select_all">
                                            @lang('menu.database_backup')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="asset_check select_all super_select_all asset_permission"
                                data-target="asset_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="asset_role" href="#collapseEight" href="">
                                @lang('menu.asset_permissions')
                            </a>
                        </div>
                        <div id="collapseEight" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all asset_permission super_select_all" data-target="asset"
                                                autocomplete="off">
                                            <strong>@lang('menu.asset')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_index"
                                                {{ $role->hasPermissionTo('asset_index') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_create"
                                                {{ $role->hasPermissionTo('asset_create') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_view"
                                                {{ $role->hasPermissionTo('asset_view') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_update"
                                                {{ $role->hasPermissionTo('asset_update') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_delete"
                                                {{ $role->hasPermissionTo('asset_delete') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.delete')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_settings"
                                                {{ $role->hasPermissionTo('asset_settings') ? 'checked' : '' }}
                                                class="asset asset_permission super_select_all"> @lang('menu.asset')
                                            settings
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission"
                                                data-target="asset_allocation" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.allocation')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_allocation_index"
                                                {{ $role->hasPermissionTo('asset_allocation_index') ? 'checked' : '' }}
                                                class="asset_allocation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.allocation') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_allocation_create"
                                                {{ $role->hasPermissionTo('asset_allocation_create') ? 'checked' : '' }}
                                                class="asset_allocation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.allocation') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_allocation_view"
                                                {{ $role->hasPermissionTo('asset_allocation_view') ? 'checked' : '' }}
                                                class="asset_allocation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.allocation') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_allocation_update"
                                                {{ $role->hasPermissionTo('asset_allocation_update') ? 'checked' : '' }}
                                                class="asset_allocation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.allocation') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_allocation_delete"
                                                {{ $role->hasPermissionTo('asset_allocation_delete') ? 'checked' : '' }}
                                                class="asset_allocation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.allocation') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_depreciation" autocomplete="off">
                                            <strong>@lang('menu.asset')
                                                @lang('role.depreciation')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_depreciation_index"
                                                {{ $role->hasPermissionTo('asset_depreciation_index') ? 'checked' : '' }}
                                                class="asset_depreciation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.depreciation') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_depreciation_create"
                                                {{ $role->hasPermissionTo('asset_depreciation_create') ? 'checked' : '' }}
                                                class="asset_depreciation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.depreciation') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_depreciation_view"
                                                {{ $role->hasPermissionTo('asset_depreciation_view') ? 'checked' : '' }}
                                                class="asset_depreciation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.depreciation') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_depreciation_update"
                                                {{ $role->hasPermissionTo('asset_depreciation_update') ? 'checked' : '' }}
                                                class="asset_depreciation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.depreciation') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_depreciation_delete"
                                                {{ $role->hasPermissionTo('asset_depreciation_delete') ? 'checked' : '' }}
                                                class="asset_depreciation asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.depreciation') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_licenses" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.licenses')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_index"
                                                {{ $role->hasPermissionTo('asset_licenses_index') ? 'checked' : '' }}
                                                class="asset_licenses asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_create"
                                                {{ $role->hasPermissionTo('asset_licenses_create') ? 'checked' : '' }}
                                                class="asset_licenses asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_view"
                                                {{ $role->hasPermissionTo('asset_licenses_view') ? 'checked' : '' }}
                                                class="asset_licenses asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_update"
                                                {{ $role->hasPermissionTo('asset_licenses_update') ? 'checked' : '' }}
                                                class="asset_licenses asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_delete"
                                                {{ $role->hasPermissionTo('asset_licenses_delete') ? 'checked' : '' }}
                                                class="asset_licenses asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.delete')
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_manufacturer" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.manufacturer')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_manufacturer_index"
                                                {{ $role->hasPermissionTo('asset_manufacturer_index') ? 'checked' : '' }}
                                                class="asset_manufacturer asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.manufacturer') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_manufacturer_create"
                                                {{ $role->hasPermissionTo('asset_manufacturer_create') ? 'checked' : '' }}
                                                class="asset_manufacturer asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.manufacturer') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_manufacturer_view"
                                                {{ $role->hasPermissionTo('asset_manufacturer_view') ? 'checked' : '' }}
                                                class="asset_manufacturer asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.manufacturer') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_manufacturer_update"
                                                {{ $role->hasPermissionTo('asset_manufacturer_update') ? 'checked' : '' }}
                                                class="asset_manufacturer asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.manufacturer') @lang('role.update')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_manufacturer_delete"
                                                {{ $role->hasPermissionTo('asset_manufacturer_delete') ? 'checked' : '' }}
                                                class="asset_manufacturer asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.manufacturer') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_categories" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.categories')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_categories_index"
                                                {{ $role->hasPermissionTo('asset_categories_index') ? 'checked' : '' }}
                                                class="asset_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.categories') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_categories_create"
                                                {{ $role->hasPermissionTo('asset_categories_create') ? 'checked' : '' }}
                                                class="asset_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.categories') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_categories_view"
                                                {{ $role->hasPermissionTo('asset_categories_view') ? 'checked' : '' }}
                                                class="asset_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.categories') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_categories_update"
                                                {{ $role->hasPermissionTo('asset_categories_update') ? 'checked' : '' }}
                                                class="asset_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.categories') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_categories_delete"
                                                {{ $role->hasPermissionTo('asset_categories_delete') ? 'checked' : '' }}
                                                class="asset_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.categories') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_locations" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.locations')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_locations_index"
                                                {{ $role->hasPermissionTo('asset_locations_index') ? 'checked' : '' }}
                                                class="asset_locations asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.locations') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_locations_create"
                                                {{ $role->hasPermissionTo('asset_locations_create') ? 'checked' : '' }}
                                                class="asset_locations asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.locations') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_locations_view"
                                                {{ $role->hasPermissionTo('asset_locations_view') ? 'checked' : '' }}
                                                class="asset_locations asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.locations') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_locations_update"
                                                {{ $role->hasPermissionTo('asset_locations_update') ? 'checked' : '' }}
                                                class="asset_locations asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.locations') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_locations_delete"
                                                {{ $role->hasPermissionTo('asset_locations_delete') ? 'checked' : '' }}
                                                class="asset_locations asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.locations') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_units" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.units')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_units_index"
                                                {{ $role->hasPermissionTo('asset_units_index') ? 'checked' : '' }}
                                                class="asset_units asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.units') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_units_create"
                                                {{ $role->hasPermissionTo('asset_units_create') ? 'checked' : '' }}
                                                class="asset_units asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.units') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_units_view"
                                                {{ $role->hasPermissionTo('asset_units_view') ? 'checked' : '' }}
                                                class="asset_units asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.units') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_units_update"
                                                {{ $role->hasPermissionTo('asset_units_update') ? 'checked' : '' }}
                                                class="asset_units asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.units') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_units_delete"
                                                {{ $role->hasPermissionTo('asset_units_delete') ? 'checked' : '' }}
                                                class="asset_units asset_permission super_select_all"> @lang('menu.asset')
                                            @lang('role.units') @lang('role.delete')
                                        </p>
                                    </div>
                                </div>

                                <hr class="my-2">

                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_requests" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.requests')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_requests_index"
                                                {{ $role->hasPermissionTo('asset_requests_index') ? 'checked' : '' }}
                                                class="asset_requests asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.requests') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_requests_create"
                                                {{ $role->hasPermissionTo('asset_requests_create') ? 'checked' : '' }}
                                                class="asset_requests asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.requests') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_requests_view"
                                                {{ $role->hasPermissionTo('asset_requests_view') ? 'checked' : '' }}
                                                class="asset_requests asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.requests') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_requests_update"
                                                {{ $role->hasPermissionTo('asset_requests_update') ? 'checked' : '' }}
                                                class="asset_requests asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.requests') @lang('role.update')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_requests_delete"
                                                {{ $role->hasPermissionTo('asset_requests_delete') ? 'checked' : '' }}
                                                class="asset_requests asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.requests') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_warranties" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.warranties')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_warranties_index"
                                                {{ $role->hasPermissionTo('asset_warranties_index') ? 'checked' : '' }}
                                                class="asset_warranties asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.warranties') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_warranties_create"
                                                {{ $role->hasPermissionTo('asset_warranties_create') ? 'checked' : '' }}
                                                class="asset_warranties asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.warranties') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_warranties_view"
                                                {{ $role->hasPermissionTo('asset_warranties_view') ? 'checked' : '' }}
                                                class="asset_warranties asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.warranties') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_warranties_update"
                                                {{ $role->hasPermissionTo('asset_warranties_update') ? 'checked' : '' }}
                                                class="asset_warranties asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.warranties') @lang('role.update')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_warranties_delete"
                                                {{ $role->hasPermissionTo('asset_warranties_delete') ? 'checked' : '' }}
                                                class="asset_warranties asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.warranties') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_audits" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.audits')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_audits_index"
                                                {{ $role->hasPermissionTo('asset_audits_index') ? 'checked' : '' }}
                                                class="asset_audits asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.audits') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_audits_create"
                                                {{ $role->hasPermissionTo('asset_audits_create') ? 'checked' : '' }}
                                                class="asset_audits asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.audits') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_audits_view"
                                                {{ $role->hasPermissionTo('asset_audits_view') ? 'checked' : '' }}
                                                class="asset_audits asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.audits') @lang('role.detail')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_audits_update"
                                                {{ $role->hasPermissionTo('asset_audits_update') ? 'checked' : '' }}
                                                class="asset_audits asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.audits') @lang('role.update')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_audits_delete"
                                                {{ $role->hasPermissionTo('asset_audits_delete') ? 'checked' : '' }}
                                                class="asset_audits asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.audits') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_revokes" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.revokes')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_revokes_index"
                                                {{ $role->hasPermissionTo('asset_revokes_index') ? 'checked' : '' }}
                                                class="asset_revokes asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.revokes') @lang('role.list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_revokes_create"
                                                {{ $role->hasPermissionTo('asset_revokes_create') ? 'checked' : '' }}
                                                class="asset_revokes asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.revokes') @lang('role.create')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_revokes_view"
                                                {{ $role->hasPermissionTo('asset_revokes_view') ? 'checked' : '' }}
                                                class="asset_revokes asset_permission super_select_all">
                                            @lang('menu.asset')
                                            @lang('role.revokes') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_revokes_update"
                                                {{ $role->hasPermissionTo('asset_revokes_update') ? 'checked' : '' }}
                                                class="asset_revokes asset_permission super_select_all">
                                            @lang('menu.asset')
                                            @lang('role.revokes') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_revokes_delete"
                                                {{ $role->hasPermissionTo('asset_revokes_delete') ? 'checked' : '' }}
                                                class="asset_revokes asset_permission super_select_all">
                                            @lang('menu.asset')
                                            @lang('role.revokes') @lang('role.delete')
                                        </p>
                                    </div>
                                </div>
                                <hr class="my-2">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_components" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.components')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_components_index"
                                                {{ $role->hasPermissionTo('asset_components_index') ? 'checked' : '' }}
                                                class="asset_components asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.components') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_components_create"
                                                {{ $role->hasPermissionTo('asset_components_create') ? 'checked' : '' }}
                                                class="asset_components asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.components') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_components_view"
                                                {{ $role->hasPermissionTo('asset_components_view') ? 'checked' : '' }}
                                                class="asset_components asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.components') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_components_update"
                                                {{ $role->hasPermissionTo('asset_components_update') ? 'checked' : '' }}
                                                class="asset_components asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.components') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_components_delete"
                                                {{ $role->hasPermissionTo('asset_components_delete') ? 'checked' : '' }}
                                                class="asset_components asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.components') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_licenses_categories" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.licenses') @lang('role.categories')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_categories_index"
                                                {{ $role->hasPermissionTo('asset_licenses_categories_index') ? 'checked' : '' }}
                                                class="asset_licenses_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.categories') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_categories_create"
                                                {{ $role->hasPermissionTo('asset_licenses_categories_create') ? 'checked' : '' }}
                                                class="asset_licenses_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.categories') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_categories_view"
                                                {{ $role->hasPermissionTo('asset_licenses_categories_view') ? 'checked' : '' }}
                                                class="asset_licenses_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.categories') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_categories_update"
                                                {{ $role->hasPermissionTo('asset_licenses_categories_update') ? 'checked' : '' }}
                                                class="asset_licenses_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.categories') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_licenses_categories_delete"
                                                {{ $role->hasPermissionTo('asset_licenses_categories_delete') ? 'checked' : '' }}
                                                class="asset_licenses_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.licenses') @lang('role.categories') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_terms_and_conditions" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.terms_and_condition')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_terms_and_conditions_index"
                                                {{ $role->hasPermissionTo('asset_terms_and_conditions_index') ? 'checked' : '' }}
                                                class="asset_terms_and_conditions asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_terms_and_conditions_create"
                                                {{ $role->hasPermissionTo('asset_terms_and_conditions_create') ? 'checked' : '' }}
                                                class="asset_terms_and_conditions asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_terms_and_conditions_view"
                                                {{ $role->hasPermissionTo('asset_terms_and_conditions_view') ? 'checked' : '' }}
                                                class="asset_terms_and_conditions asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_terms_and_conditions_update"
                                                {{ $role->hasPermissionTo('asset_terms_and_conditions_update') ? 'checked' : '' }}
                                                class="asset_terms_and_conditions asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_terms_and_conditions_delete"
                                                {{ $role->hasPermissionTo('asset_terms_and_conditions_delete') ? 'checked' : '' }}
                                                class="asset_terms_and_conditions asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all asset_permission "
                                                data-target="asset_term_condition_categories" autocomplete="off">
                                            <strong>@lang('menu.asset') @lang('role.terms_and_condition')
                                                @lang('role.category')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_term_condition_categories_index"
                                                {{ $role->hasPermissionTo('asset_term_condition_categories_index') ? 'checked' : '' }}
                                                class="asset_term_condition_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_term_condition_categories_create"
                                                {{ $role->hasPermissionTo('asset_term_condition_categories_create') ? 'checked' : '' }}
                                                class="asset_term_condition_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_term_condition_categories_view"
                                                {{ $role->hasPermissionTo('asset_term_condition_categories_view') ? 'checked' : '' }}
                                                class="asset_term_condition_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_term_condition_categories_update"
                                                {{ $role->hasPermissionTo('asset_term_condition_categories_update') ? 'checked' : '' }}
                                                class="asset_term_condition_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="asset_term_condition_categories_delete"
                                                {{ $role->hasPermissionTo('asset_term_condition_categories_delete') ? 'checked' : '' }}
                                                class="asset_term_condition_categories asset_permission super_select_all">
                                            @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- LC Permission start --}}
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="lc_check select_all super_select_all lc_permission"
                                data-target="lc_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="lc_role" href="#collapseLC" href="">
                                @lang('role.lc_permissions')
                            </a>
                        </div>
                        <div id="collapseLC" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-md-3">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all lc_permission "
                                                data-target="opening_lc" autocomplete="off">
                                            <strong>@lang('role.opening_lc')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc"
                                                {{ $role->hasPermissionTo('opening_lc') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all">
                                            @lang('role.opening_lc')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc_index"
                                                {{ $role->hasPermissionTo('opening_lc_index') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all">
                                            @lang('role.opening_lc') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc_create"
                                                {{ $role->hasPermissionTo('opening_lc_create') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all">
                                            @lang('role.opening_lc') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc_view"
                                                {{ $role->hasPermissionTo('opening_lc_view') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all"> @lang('role.opening_lc')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc_update"
                                                {{ $role->hasPermissionTo('opening_lc_update') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all">
                                            @lang('role.opening_lc') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="opening_lc_delete"
                                                {{ $role->hasPermissionTo('opening_lc_delete') ? 'checked' : '' }}
                                                class="opening_lc lc_permission super_select_all">
                                            @lang('role.opening_lc') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all lc_permission "
                                                data-target="import_purchase_order" autocomplete="off">
                                            <strong>@lang('role.import') @lang('role.purchase_order')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order"
                                                {{ $role->hasPermissionTo('import_purchase_order') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order_index"
                                                {{ $role->hasPermissionTo('import_purchase_order_index') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order_create"
                                                {{ $role->hasPermissionTo('import_purchase_order_create') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order_view"
                                                {{ $role->hasPermissionTo('import_purchase_order_view') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order_update"
                                                {{ $role->hasPermissionTo('import_purchase_order_update') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="import_purchase_order_delete"
                                                {{ $role->hasPermissionTo('import_purchase_order_delete') ? 'checked' : '' }}
                                                class="import_purchase_order lc_permission super_select_all">
                                            @lang('role.import') @lang('role.purchase_order') @lang('role.delete')
                                        </p>
                                    </div>

                                    <hr class="my-2">

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all lc_permission "
                                                data-target="exporters" autocomplete="off">
                                            <strong>@lang('menu.exporters')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters"
                                                {{ $role->hasPermissionTo('exporters') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all">
                                            @lang('menu.exporters')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters_index"
                                                {{ $role->hasPermissionTo('exporters_index') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all">
                                            @lang('menu.exporters') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters_create"
                                                {{ $role->hasPermissionTo('exporters_create') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all">
                                            @lang('menu.exporters') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters_view"
                                                {{ $role->hasPermissionTo('exporters_view') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all"> @lang('menu.exporters')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters_update"
                                                {{ $role->hasPermissionTo('exporters_update') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all">
                                            @lang('menu.exporters') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="exporters_delete"
                                                {{ $role->hasPermissionTo('exporters_delete') ? 'checked' : '' }}
                                                class="exporters lc_permission super_select_all">
                                            @lang('menu.exporters') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all lc_permission "
                                                data-target="insurance_companies" autocomplete="off">
                                            <strong>@lang('role.insurance_companies')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies"
                                                {{ $role->hasPermissionTo('insurance_companies') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies_index"
                                                {{ $role->hasPermissionTo('insurance_companies_index') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies_create"
                                                {{ $role->hasPermissionTo('insurance_companies_create') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies_view"
                                                {{ $role->hasPermissionTo('insurance_companies_view') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies_update"
                                                {{ $role->hasPermissionTo('insurance_companies_update') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="insurance_companies_delete"
                                                {{ $role->hasPermissionTo('insurance_companies_delete') ? 'checked' : '' }}
                                                class="insurance_companies lc_permission super_select_all">
                                            @lang('role.insurance_companies') @lang('role.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all lc_permission"
                                                data-target="cnf_agents" autocomplete="off">
                                            <strong>@lang('role.cnf_agent')</strong>
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents"
                                                {{ $role->hasPermissionTo('cnf_agents') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all">
                                            @lang('role.cnf_agent')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents_index"
                                                {{ $role->hasPermissionTo('cnf_agents_index') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all">
                                            @lang('role.cnf_agent') @lang('role.list')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents_create"
                                                {{ $role->hasPermissionTo('cnf_agents_create') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all">
                                            @lang('role.cnf_agent') @lang('role.create')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents_view"
                                                {{ $role->hasPermissionTo('cnf_agents_view') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all"> @lang('role.cnf_agent')
                                            @lang('role.detail')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents_update"
                                                {{ $role->hasPermissionTo('cnf_agents_update') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all">
                                            @lang('role.cnf_agent') @lang('role.update')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cnf_agents_delete"
                                                {{ $role->hasPermissionTo('cnf_agents_delete') ? 'checked' : '' }}
                                                class="cnf_agents lc_permission super_select_all">
                                            @lang('role.cnf_agent') @lang('role.delete')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- LC Permission end --}}
                    @if ($addons->todo == 1)
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="accordion-header">
                                <input type="checkbox"
                                    class="project_check select_all super_select_all project_permission"
                                    data-target="project_permission" autocomplete="off">
                                <a data-bs-toggle="collapse" class="project_role" href="#collapseNine"
                                    href="">
                                    @lang('role.project_management_permissions')
                                </a>
                            </div>
                            <div id="collapseNine" class="collapse" data-bs-parent="#accordion">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all project_permission"
                                                    data-target="manage_task" autocomplete="off"><strong>
                                                    @lang('menu.manage') @lang('menu.task')</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="assign_todo"
                                                    {{ $role->hasPermissionTo('assign_todo') ? 'checked' : '' }}
                                                    class="manage_task project_permission super_select_all">

                                                @lang('menu.todo')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="work_space"
                                                    {{ $role->hasPermissionTo('work_space') ? 'checked' : '' }}
                                                    class="manage_task project_permission super_select_all">

                                                @lang('role.work_space')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="memo"
                                                    {{ $role->hasPermissionTo('memo') ? 'checked' : '' }}
                                                    class="manage_task project_permission super_select_all">
                                                @lang('menu.memo')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="msg"
                                                    {{ $role->hasPermissionTo('msg') ? 'checked' : '' }}
                                                    class="manage_task project_permission super_select_all">

                                                @lang('menu.message')
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="setup_check select_all super_select_all setup_permission"
                                data-target="setup_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="setup_role" href="#collapseTen" href="">
                                @lang('menu.set_up_permissions')
                            </a>
                        </div>
                        <div id="collapseTen" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all setup_permission"
                                                data-target="settings" autocomplete="off">
                                            <strong>@lang('menu.settings')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="g_settings"
                                                {{ $role->hasPermissionTo('g_settings') ? 'checked' : '' }}
                                                class="settings setup_permission super_select_all">
                                            @lang('menu.general')
                                            @lang('menu.settings')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="p_settings"
                                                {{ $role->hasPermissionTo('p_settings') ? 'checked' : '' }}
                                                class="settings setup_permission super_select_all">
                                            @lang('menu.payment')
                                            @lang('menu.settings')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="barcode_settings"
                                                {{ $role->hasPermissionTo('barcode_settings') ? 'checked' : '' }}
                                                class="settings setup_permission super_select_all">

                                            @lang('menu.barcode') @lang('menu.settings')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="reset"
                                                {{ $role->hasPermissionTo('reset') ? 'checked' : '' }}
                                                class="settings setup_permission super_select_all">
                                            @lang('menu.reset')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info">
                                            <input type="checkbox" class="select_all super_select_all setup_permission "
                                                data-target="app_setup" autocomplete="off"> <strong>
                                                @lang('role.app_set_up')</strong>
                                        </p>

                                        {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="tax" {{ $role->hasPermissionTo('tax') ? 'checked' : '' }} class="app_setup setup_permission super_select_all">
                                            Tax
                                        </p> --}}

                                        {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="branch" {{ $role->hasPermissionTo('branch') ? 'checked' : '' }} class="app_setup setup_permission super_select_all">
                                            @lang('role.business_location')
                                        </p> --}}

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="warehouse"
                                                {{ $role->hasPermissionTo('warehouse') ? 'checked' : '' }}
                                                class="app_setup setup_permission super_select_all">
                                            @lang('role.warehouse')
                                        </p>


                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="inv_sc"
                                                {{ $role->hasPermissionTo('inv_sc') ? 'checked' : '' }}
                                                class="app_setup setup_permission super_select_all">
                                            @lang('role.invoice_schemas')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="inv_lay"
                                                {{ $role->hasPermissionTo('inv_lay') ? 'checked' : '' }}
                                                class="app_setup setup_permission super_select_all">
                                            @lang('role.invoice_layout')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="cash_counters"
                                                {{ $role->hasPermissionTo('cash_counters') ? 'checked' : '' }}
                                                class="app_setup setup_permission super_select_all">
                                            @lang('role.cash_counters')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all setup_permission "
                                                data-target="users" autocomplete="off"><strong>
                                                @lang('menu.users')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_view"
                                                {{ $role->hasPermissionTo('user_view') ? 'checked' : '' }}
                                                class="users setup_permission super_select_all">
                                            @lang('menu.view_user')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_add"
                                                {{ $role->hasPermissionTo('user_add') ? 'checked' : '' }}
                                                class="users setup_permission super_select_all" autocomplete="off">
                                            @lang('role.add_user')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_edit"
                                                {{ $role->hasPermissionTo('user_edit') ? 'checked' : '' }}
                                                class="users setup_permission super_select_all" autocomplete="off">
                                            @lang('menu.edit_user')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="user_delete"
                                                {{ $role->hasPermissionTo('user_delete') ? 'checked' : '' }}
                                                class="users setup_permission super_select_all" autocomplete="off">
                                            @lang('role.delete') @lang('menu.user')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all setup_permission "
                                                data-target="roles" autocomplete="off"><strong> Roles</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="role_view"
                                                {{ $role->hasPermissionTo('role_view') ? 'checked' : '' }}
                                                class="roles setup_permission super_select_all">
                                            @lang('role.view_role')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="role_add"
                                                {{ $role->hasPermissionTo('role_add') ? 'checked' : '' }}
                                                class="roles setup_permission super_select_all">
                                            @lang('role.add_role')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="role_edit"
                                                {{ $role->hasPermissionTo('role_edit') ? 'checked' : '' }}
                                                class="roles setup_permission super_select_all">
                                            @lang('role.edit_role')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="role_delete"
                                                {{ $role->hasPermissionTo('role_delete') ? 'checked' : '' }}
                                                class="roles setup_permission super_select_all">
                                            @lang('role.delete') @lang('role.role')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="cash_check select_all super_select_all cash_permission"
                                data-target="cash_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="cash_role" href="#collapseEleven" href="">
                                @lang('role.cash_register_permissions')
                            </a>
                        </div>
                        <div id="collapseEleven" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all cash_permission"
                                                data-target="cash_register" autocomplete="off"><strong>
                                                @lang('role.cash_register')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="register_view"
                                                {{ $role->hasPermissionTo('register_view') ? 'checked' : '' }}
                                                class="cash_register cash_permission super_select_all">

                                            @lang('role.view') @lang('role.cash_register')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="register_close"
                                                {{ $role->hasPermissionTo('register_close') ? 'checked' : '' }}
                                                class="cash_register cash_permission super_select_all">
                                            @lang('menu.close') @lang('role.cash_register')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="another_register_close"
                                                {{ $role->hasPermissionTo('another_register_close') ? 'checked' : '' }}
                                                class="cash_register cash_permission super_select_all">
                                            @lang('role.close_another') @lang('role.cash_register')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="dash_chek select_all super_select_all dashboard_permission"
                                data-target="dashboard_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="dash_role" href="#collapseTwelve" href="">
                                @lang('role.dashboard_permissions')
                            </a>
                        </div>
                        <div id="collapseTwelve" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><input type="checkbox"
                                                class="select_all super_select_all dashboard_permission"
                                                data-target="dashboard" autocomplete="off"><strong>
                                                @lang('menu.dashboard')</strong>
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="dash_data"
                                                {{ $role->hasPermissionTo('dash_data') ? 'checked' : '' }}
                                                class="dashboard dashboard_permission super_select_all">
                                            @lang('role.view_dashboard_data')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if ($addons->hrm == 1)
                        <div class="form_element rounded mt-0 mb-1">
                            <div class="accordion-header">
                                <input type="checkbox" class="hr_chek select_all super_select_all human_permission"
                                    data-target="human_permission" autocomplete="off">
                                <a data-bs-toggle="collapse" class="hr_role" href="#collapseThirteen"
                                    href="">
                                    @lang('role.human_resource_permissions')
                                </a>
                            </div>
                            <div id="collapseThirteen" class="collapse" data-bs-parent="#accordion">
                                <div class="element-body border-top">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all human_permission"
                                                    data-target="hrm" autocomplete="off"><strong> HRM</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_dashboard"
                                                    {{ $role->hasPermissionTo('hrm_dashboard') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all ">
                                                @lang('role.hrm_dashboard')
                                            </p>

                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="attendance"
                                                    {{ $role->hasPermissionTo('attendance') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all">

                                                @lang('role.attendance')
                                            </p>

                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll"
                                                    {{ $role->hasPermissionTo('payroll') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all">
                                                @lang('role.payroll')
                                            </p>

                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll_report"
                                                    {{ $role->hasPermissionTo('payroll_report') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all">

                                                @lang('role.payroll_report')
                                            </p>

                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="payroll_payment_report"
                                                    {{ $role->hasPermissionTo('payroll_payment_report') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all">
                                                @lang('role.payroll_payment_report')
                                            </p>

                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="attendance_report"
                                                    {{ $role->hasPermissionTo('attendance_report') ? 'checked' : '' }}
                                                    class="hrm human_permission super_select_all">
                                                @lang('role.attendance_report')
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_others" autocomplete="off"><strong>
                                                    @lang('role.others')</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="leave_type"
                                                    {{ $role->hasPermissionTo('leave_type') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">

                                                Leave type
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="leave_assign"
                                                    {{ $role->hasPermissionTo('leave_assign') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">

                                                Leave assign
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="shift"
                                                    {{ $role->hasPermissionTo('shift') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">
                                                Shift
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="view_allowance_and_deduction"
                                                    {{ $role->hasPermissionTo('view_allowance_and_deduction') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all"> Allowance and
                                                deduction
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="holiday"
                                                    {{ $role->hasPermissionTo('holiday') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">

                                                Holiday
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="department"
                                                    {{ $role->hasPermissionTo('department') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">

                                                @lang('menu.departments')
                                            </p>
                                            <p class="checkbox_input_wrap">
                                                <input type="checkbox" name="designation"
                                                    {{ $role->hasPermissionTo('designation') ? 'checked' : '' }}
                                                    class="hrm_others human_permission super_select_all">

                                                Designation
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_employee" autocomplete="off"><strong>
                                                    {{ __('Employee') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_index"
                                                    {{ $role->hasPermissionTo('hrm_employees_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_create"
                                                    {{ $role->hasPermissionTo('hrm_employees_create') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_view"
                                                    {{ $role->hasPermissionTo('hrm_employees_view') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_update"
                                                    {{ $role->hasPermissionTo('hrm_employees_update') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_delete"
                                                    {{ $role->hasPermissionTo('hrm_employees_delete') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee delete') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_master_list_index"
                                                    {{ $role->hasPermissionTo('hrm_master_list_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee master list') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_bulk_import_index"
                                                    {{ $role->hasPermissionTo('hrm_employees_bulk_import_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee bulk import index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employees_bulk_import_store"
                                                    {{ $role->hasPermissionTo('hrm_employees_bulk_import_store') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee bulk import store') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_id_card_print_index"
                                                    {{ $role->hasPermissionTo('hrm_id_card_print_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee Id card index') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_id_card_print"
                                                    {{ $role->hasPermissionTo('hrm_id_card_print') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee Id card print') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_appointment_with_select_letter_index"
                                                    {{ $role->hasPermissionTo('hrm_appointment_with_select_letter_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee appointment letter with selection') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_appointment_with_select_letter_print"
                                                    {{ $role->hasPermissionTo('hrm_appointment_with_select_letter_print') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee appointment letter print with selection') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_promotion_index"
                                                    {{ $role->hasPermissionTo('hrm_promotion_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee appointment letter index') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_appointment_letter_print"
                                                    {{ $role->hasPermissionTo('hrm_appointment_letter_print') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee appointment letter print') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_daily_attendance_update"
                                                    {{ $role->hasPermissionTo('hrm_daily_attendance_update') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee promotion Index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_promotion_create"
                                                    {{ $role->hasPermissionTo('hrm_promotion_create') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee promotion create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_promotion_view"
                                                    {{ $role->hasPermissionTo('hrm_promotion_view') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee promotion view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_promotion_update"
                                                    {{ $role->hasPermissionTo('hrm_promotion_update') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee promotion update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_promotion_delete"
                                                    {{ $role->hasPermissionTo('hrm_promotion_delete') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee promotion delete') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_change_index"
                                                    {{ $role->hasPermissionTo('hrm_shift_change_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Employee shift change index') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_change_action"
                                                    {{ $role->hasPermissionTo('hrm_shift_change_action') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">

                                                {{ __('Employee shift change action') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_resigned_employee_index"
                                                    {{ $role->hasPermissionTo('hrm_resigned_employee_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Resigned employee index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_left_employee_index"
                                                    {{ $role->hasPermissionTo('hrm_left_employee_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Left employee index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_trashed_employee_index"
                                                    {{ $role->hasPermissionTo('hrm_trashed_employee_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Trash employee index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_final_settlement_index"
                                                    {{ $role->hasPermissionTo('hrm_final_settlement_index') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Final settlement employee index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_final_settlement_action"
                                                    {{ $role->hasPermissionTo('hrm_final_settlement_action') ? 'checked' : '' }}
                                                    class="hrm_employee human_permission super_select_all">
                                                {{ __('Final settlement employee action') }}
                                            </p>

                                        </div>

                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_attendance" autocomplete="off"><strong>
                                                    {{ __('Attendances') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_index"
                                                    {{ $role->hasPermissionTo('hrm_attendance_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_create"
                                                    {{ $role->hasPermissionTo('hrm_attendance_create') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_view"
                                                    {{ $role->hasPermissionTo('hrm_attendance_view') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_update"
                                                    {{ $role->hasPermissionTo('hrm_attendance_update') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Attendance update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_delete"
                                                    {{ $role->hasPermissionTo('hrm_attendance_delete') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Attendance delete') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_person_wise_attendance_index"
                                                    {{ $role->hasPermissionTo('hrm_person_wise_attendance_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Person wise attendance index') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_section_wise_attendance"
                                                    {{ $role->hasPermissionTo('hrm_section_wise_attendance') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Section wise attendance index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_section_wise_attendance_store"
                                                    {{ $role->hasPermissionTo('hrm_section_wise_attendance_store') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Section wise attendance store') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_log_index"
                                                    {{ $role->hasPermissionTo('hrm_attendance_log_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance log list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_log_update"
                                                    {{ $role->hasPermissionTo('hrm_attendance_log_update') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance log update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_log_view"
                                                    {{ $role->hasPermissionTo('hrm_attendance_log_view') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance log view') }}
                                            </p>
                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_daily_attendance_index"
                                                    {{ $role->hasPermissionTo('hrm_daily_attendance_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Daily attendance list') }}
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_daily_attendance_view"
                                                    {{ $role->hasPermissionTo('hrm_daily_attendance_view') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Daily attendance view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_daily_attendance_update"
                                                    {{ $role->hasPermissionTo('hrm_daily_attendance_update') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Daily attendance edit') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_rapid_update"
                                                    {{ $role->hasPermissionTo('hrm_attendance_rapid_update') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Attendance rapid view') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_rapid_update_employee_wise"
                                                    {{ $role->hasPermissionTo('hrm_attendance_rapid_update_employee_wise') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Employee wise attendance rapid update') }}
                                            </p>



                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_rapid_update_date_wise"
                                                    {{ $role->hasPermissionTo('hrm_attendance_rapid_update_date_wise') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Daily attendance rapid update') }}
                                            </p>

                                        </div>
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_bulk_attendance_import_index"
                                                    {{ $role->hasPermissionTo('hrm_bulk_attendance_import_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Bulk attendance import index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_bulk_attendance_import_text_file"
                                                    {{ $role->hasPermissionTo('hrm_bulk_attendance_import_text_file') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">
                                                {{ __('Bulk attendance import text') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_bulk_attendance_import_index"
                                                    {{ $role->hasPermissionTo('hrm_bulk_attendance_import_index') ? 'checked' : '' }}
                                                    class="hrm_attendance human_permission super_select_all">

                                                {{ __('Bulk import attendance') }}
                                            </p>

                                        </div>

                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all human_permission"
                                                    data-target="hrm_department" autocomplete="off"><strong>
                                                    {{ __('Departments') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_departments_index"
                                                    {{ $role->hasPermissionTo('hrm_departments_index') ? 'checked' : '' }}
                                                    class="hrm_department human_permission super_select_all">

                                                {{ __('Department List') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_departments_create"
                                                    {{ $role->hasPermissionTo('hrm_departments_create') ? 'checked' : '' }}
                                                    class="hrm_department human_permission super_select_all">

                                                {{ __('Department create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_departments_view"
                                                    {{ $role->hasPermissionTo('hrm_departments_view') ? 'checked' : '' }}
                                                    class="hrm_department human_permission super_select_all">
                                                {{ __('Department view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_departments_update"
                                                    {{ $role->hasPermissionTo('hrm_departments_update') ? 'checked' : '' }}
                                                    class="hrm_department human_permission super_select_all">
                                                {{ __('Department update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_departments_delete"
                                                    {{ $role->hasPermissionTo('hrm_departments_delete') ? 'checked' : '' }}
                                                    class="hrm_department human_permission super_select_all">
                                                {{ __('Department delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_section" autocomplete="off"><strong>
                                                    {{ __('Sections') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sections_index"
                                                    {{ $role->hasPermissionTo('hrm_sections_index') ? 'checked' : '' }}
                                                    class="hrm_section human_permission super_select_all">

                                                {{ __('Section list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sections_create"
                                                    {{ $role->hasPermissionTo('hrm_sections_create') ? 'checked' : '' }}
                                                    class="hrm_section human_permission super_select_all">

                                                {{ __('Section create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sections_view"
                                                    {{ $role->hasPermissionTo('hrm_sections_view') ? 'checked' : '' }}
                                                    class="hrm_section human_permission super_select_all">
                                                {{ __('Section view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sections_update"
                                                    {{ $role->hasPermissionTo('hrm_sections_update') ? 'checked' : '' }}
                                                    class="hrm_section human_permission super_select_all">
                                                {{ __('Section update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sections_delete"
                                                    {{ $role->hasPermissionTo('hrm_sections_delete') ? 'checked' : '' }}
                                                    class="hrm_section human_permission super_select_all">
                                                {{ __('Section delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_sub_section" autocomplete="off"><strong>
                                                    {{ __('Sub Sections') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sub_sections_index"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_index') ? 'checked' : '' }}
                                                    class="hrm_sub_section human_permission super_select_all">

                                                {{ __('Subsection list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sub_sections_create"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_create') ? 'checked' : '' }}
                                                    class="hrm_sub_section human_permission super_select_all">

                                                {{ __('Subsection create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sub_sections_view"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_view') ? 'checked' : '' }}
                                                    class="hrm_sub_section human_permission super_select_all">

                                                {{ __('Subsection view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sub_sections_update"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_update') ? 'checked' : '' }}
                                                    class="hrm_sub_section human_permission super_select_all">
                                                {{ __('Subsection update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_sub_sections_delete"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_delete') ? 'checked' : '' }}
                                                    class="hrm_sub_section human_permission super_select_all">
                                                {{ __('Subsection delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_designation" autocomplete="off"><strong>
                                                    {{ __('Designations') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_designations_index"
                                                    {{ $role->hasPermissionTo('hrm_sub_sections_delete') ? 'checked' : '' }}
                                                    class="hrm_designation human_permission super_select_all">

                                                {{ __('Designation list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_designations_create"
                                                    {{ $role->hasPermissionTo('hrm_designations_create') ? 'checked' : '' }}
                                                    class="hrm_designation human_permission super_select_all">

                                                {{ __('Designation create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_designations_view"
                                                    {{ $role->hasPermissionTo('hrm_designations_view') ? 'checked' : '' }}
                                                    class="hrm_designation human_permission super_select_all">

                                                {{ __('Designation view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_designations_update"
                                                    {{ $role->hasPermissionTo('hrm_designations_update') ? 'checked' : '' }}
                                                    class="hrm_designation human_permission super_select_all">
                                                {{ __('Designation update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_designations_delete"
                                                    {{ $role->hasPermissionTo('hrm_designations_delete') ? 'checked' : '' }}
                                                    class="hrm_designation human_permission super_select_all">
                                                {{ __('Designation delete') }}
                                            </p>


                                        </div>



                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all human_permission"
                                                    data-target="hrm_shift" autocomplete="off"><strong>
                                                    {{ __('Shifts') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shifts_index"
                                                    {{ $role->hasPermissionTo('hrm_shifts_index') ? 'checked' : '' }}
                                                    class="hrm_shift human_permission super_select_all">

                                                {{ __('Shift List') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shifts_create"
                                                    {{ $role->hasPermissionTo('hrm_shifts_create') ? 'checked' : '' }}
                                                    class="hrm_shift human_permission super_select_all">

                                                {{ __('Shift create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shifts_view"
                                                    {{ $role->hasPermissionTo('hrm_shifts_view') ? 'checked' : '' }}
                                                    class="hrm_shift human_permission super_select_all">
                                                {{ __('Shift view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shifts_update"
                                                    {{ $role->hasPermissionTo('hrm_shifts_update') ? 'checked' : '' }}
                                                    class="hrm_shift human_permission super_select_all">
                                                {{ __('Shift update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shifts_delete"
                                                    {{ $role->hasPermissionTo('hrm_shifts_delete') ? 'checked' : '' }}
                                                    class="hrm_shift human_permission super_select_all">
                                                {{ __('Shift delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_shift_adjustment " autocomplete="off"><strong>
                                                    {{ __('Shift adjustment') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_adjustments_index"
                                                    {{ $role->hasPermissionTo('hrm_shift_adjustments_index') ? 'checked' : '' }}
                                                    class="hrm_shift_adjustment  human_permission super_select_all">

                                                {{ __('Shift adjustment list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_adjustments_create"
                                                    {{ $role->hasPermissionTo('hrm_shift_adjustments_create') ? 'checked' : '' }}
                                                    class="hrm_shift_adjustment  human_permission super_select_all">

                                                {{ __('Shift adjustment create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_adjustments_view"
                                                    {{ $role->hasPermissionTo('hrm_shift_adjustments_view') ? 'checked' : '' }}
                                                    class="hrm_shift_adjustment  human_permission super_select_all">
                                                {{ __('Shift adjustment view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_adjustments_update"
                                                    {{ $role->hasPermissionTo('hrm_shift_adjustments_update') ? 'checked' : '' }}
                                                    class="hrm_shift_adjustment  human_permission super_select_all">
                                                {{ __('Shift adjustment update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_shift_adjustments_delete"
                                                    {{ $role->hasPermissionTo('hrm_shift_adjustments_delete') ? 'checked' : '' }}
                                                    class="hrm_shift_adjustment  human_permission super_select_all">
                                                {{ __('Shift adjustment delete') }}
                                            </p>
                                        </div>





                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_grade" autocomplete="off"><strong>
                                                    {{ __('Grades') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_grades_index"
                                                    {{ $role->hasPermissionTo('hrm_grades_index') ? 'checked' : '' }}
                                                    class="hrm_grade human_permission super_select_all">

                                                {{ __('Grade list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_grades_create"
                                                    {{ $role->hasPermissionTo('hrm_grades_create') ? 'checked' : '' }}
                                                    class="hrm_grade human_permission super_select_all">

                                                {{ __('Grade create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_grades_view"
                                                    {{ $role->hasPermissionTo('hrm_grades_view') ? 'checked' : '' }}
                                                    class="hrm_grade human_permission super_select_all">

                                                {{ __('Grade view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_grades_update"
                                                    {{ $role->hasPermissionTo('hrm_grades_update') ? 'checked' : '' }}
                                                    class="hrm_grade human_permission super_select_all">
                                                {{ __('Grade update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_grades_delete"
                                                    {{ $role->hasPermissionTo('hrm_grades_delete') ? 'checked' : '' }}
                                                    class="hrm_grade human_permission super_select_all">
                                                {{ __('Grade delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all human_permission"
                                                    data-target="hrm_holidays" autocomplete="off"><strong>
                                                    {{ __('Holidays') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_index"
                                                    {{ $role->hasPermissionTo('hrm_holidays_index') ? 'checked' : '' }}
                                                    class="hrm_holidays human_permission super_select_all">

                                                {{ __('Holidays List') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_create"
                                                    {{ $role->hasPermissionTo('hrm_holidays_create') ? 'checked' : '' }}
                                                    class="hrm_holidays human_permission super_select_all">

                                                {{ __('Holidays create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_view"
                                                    {{ $role->hasPermissionTo('hrm_holidays_view') ? 'checked' : '' }}
                                                    class="hrm_holidays human_permission super_select_all">
                                                {{ __('Holidays view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_update"
                                                    {{ $role->hasPermissionTo('hrm_holidays_update') ? 'checked' : '' }}
                                                    class="hrm_holidays human_permission super_select_all">
                                                {{ __('Holidays update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_delete"
                                                    {{ $role->hasPermissionTo('hrm_holidays_delete') ? 'checked' : '' }}
                                                    class="hrm_holidays human_permission super_select_all">
                                                {{ __('Holidays delete') }}
                                            </p>
                                        </div>


                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_holiday_calendar" autocomplete="off"><strong>
                                                    {{ __('Holiday calendar') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_calendar_index"
                                                    {{ $role->hasPermissionTo('hrm_holidays_calendar_index') ? 'checked' : '' }}
                                                    class="hrm_holiday_calendar human_permission super_select_all">

                                                {{ __('Holiday calendar list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_calendar_create"
                                                    {{ $role->hasPermissionTo('hrm_holidays_calendar_create') ? 'checked' : '' }}
                                                    class="hrm_holiday_calendar human_permission super_select_all">

                                                {{ __('Holiday calendar create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_calendar_view"
                                                    {{ $role->hasPermissionTo('hrm_holidays_calendar_view') ? 'checked' : '' }}
                                                    class="hrm_holiday_calendar human_permission super_select_all">

                                                {{ __('Holiday calendar view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_calendar_update"
                                                    {{ $role->hasPermissionTo('hrm_holidays_calendar_update') ? 'checked' : '' }}
                                                    class="hrm_holiday_calendar human_permission super_select_all">
                                                {{ __('Holiday calendar update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_holidays_calendar_delete"
                                                    {{ $role->hasPermissionTo('hrm_holidays_calendar_delete') ? 'checked' : '' }}
                                                    class="hrm_holiday_calendar human_permission super_select_all">
                                                {{ __('Holiday calendar delete') }}
                                            </p>

                                        </div>


                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_leave_application" autocomplete="off"><strong>
                                                    {{ __('Leave applications') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_applications_index"
                                                    {{ $role->hasPermissionTo('hrm_leave_applications_index') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">

                                                {{ __('Leave application list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_applications_create"
                                                    {{ $role->hasPermissionTo('hrm_leave_applications_create') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">

                                                {{ __('Leave application create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_applications_view"
                                                    {{ $role->hasPermissionTo('hrm_leave_applications_view') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">
                                                {{ __('Leave application view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_applications_update"
                                                    {{ $role->hasPermissionTo('hrm_leave_applications_update') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">
                                                {{ __('Leave application update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_applications_delete"
                                                    {{ $role->hasPermissionTo('hrm_leave_applications_delete') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">
                                                {{ __('Leave application delete') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_view"
                                                    {{ $role->hasPermissionTo('hrm_leave_view') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">

                                                {{ __('Leave register view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_register_view"
                                                    {{ $role->hasPermissionTo('hrm_leave_register_view') ? 'checked' : '' }}
                                                    class="hrm_leave_application human_permission super_select_all">

                                                {{ __('Leave register list') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_leave_type" autocomplete="off"><strong>
                                                    {{ __('Leave types') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_types_index"
                                                    {{ $role->hasPermissionTo('hrm_leave_types_index') ? 'checked' : '' }}
                                                    class="hrm_leave_type human_permission super_select_all">

                                                {{ __('Leave type list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_types_create"
                                                    {{ $role->hasPermissionTo('hrm_leave_types_create') ? 'checked' : '' }}
                                                    class="hrm_leave_type human_permission super_select_all">

                                                {{ __('Leave type create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_types_view"
                                                    {{ $role->hasPermissionTo('hrm_leave_types_view') ? 'checked' : '' }}
                                                    class="hrm_leave_type human_permission super_select_all">

                                                {{ __('Leave type view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_types_update"
                                                    {{ $role->hasPermissionTo('hrm_leave_types_update') ? 'checked' : '' }}
                                                    class="hrm_leave_type human_permission super_select_all">
                                                {{ __('Leave type update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_types_delete"
                                                    {{ $role->hasPermissionTo('hrm_leave_types_delete') ? 'checked' : '' }}
                                                    class="hrm_leave_type human_permission super_select_all">
                                                {{ __('Leave type delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_salary_settlement" autocomplete="off"><strong>
                                                    {{ __('Salary settlement') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_settlement_index"
                                                    {{ $role->hasPermissionTo('hrm_salary_settlement_index') ? 'checked' : '' }}
                                                    class="hrm_salary_settlement human_permission super_select_all">

                                                {{ __('Salary settlement list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_settlement_create"
                                                    {{ $role->hasPermissionTo('hrm_salary_settlement_create') ? 'checked' : '' }}
                                                    class="hrm_salary_settlement human_permission super_select_all">

                                                {{ __('Salary settlement create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_settlement_view"
                                                    {{ $role->hasPermissionTo('hrm_salary_settlement_view') ? 'checked' : '' }}
                                                    class="hrm_salary_settlement human_permission super_select_all">

                                                {{ __('Salary settlement view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_settlement"
                                                    {{ $role->hasPermissionTo('hrm_salary_settlement') ? 'checked' : '' }}
                                                    class="hrm_salary_settlement human_permission super_select_all">
                                                {{ __('Salary settlement settlement') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_settlement_delete"
                                                    {{ $role->hasPermissionTo('hrm_salary_settlement_delete') ? 'checked' : '' }}
                                                    class="hrm_salary_settlement human_permission super_select_all">
                                                {{ __('Salary settlement delete') }}
                                            </p>
                                        </div>
                                        {{-- kdjgklsjklds ojksd;lfjks;dlfj lskdrflwefkwel piweoprkowepk pwp[rkwepwep pwprqprqpir[pq [pqirpqp[riq[pri]]]]] --}}
                                        <hr class="my-2">
                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_amount_adjustmnet" autocomplete="off"><strong>
                                                    {{ __('Amount adjusments') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustments_index"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustments_index') ? 'checked' : '' }}
                                                    class="hrm_amount_adjustmnet human_permission super_select_all">

                                                {{ __('Amount adjusment list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustments_create"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustments_create') ? 'checked' : '' }}
                                                    class="hrm_amount_adjustmnet human_permission super_select_all">

                                                {{ __('Amount adjusment create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustments_view"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustments_view') ? 'checked' : '' }}
                                                    class="hrm_amount_adjustmnet human_permission super_select_all">

                                                {{ __('Amount adjusment view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustments_update"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustments_update') ? 'checked' : '' }}
                                                    class="hrm_amount_adjustmnet human_permission super_select_all">
                                                {{ __('Amount adjusment update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustments_delete"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustments_delete') ? 'checked' : '' }}
                                                    class="hrm_amount_adjustmnet human_permission super_select_all">
                                                {{ __('Amount adjusment delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_overtime_adjustmnet" autocomplete="off"><strong>
                                                    {{ __('Overtime adjusments') }}
                                                </strong>
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_overtimeAdjustments_index"
                                                    {{ $role->hasPermissionTo('hrm_overtimeAdjustments_index') ? 'checked' : '' }}
                                                    class="hrm_overtime_adjustmnet human_permission super_select_all">

                                                {{ __('Overtime adjusment list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_overtimeAdjustments_create"
                                                    {{ $role->hasPermissionTo('hrm_overtimeAdjustments_create') ? 'checked' : '' }}
                                                    class="hrm_overtime_adjustmnet human_permission super_select_all">

                                                {{ __('Overtime adjusment create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_overtimeAdjustments_view"
                                                    {{ $role->hasPermissionTo('hrm_overtimeAdjustments_view') ? 'checked' : '' }}
                                                    class="hrm_overtime_adjustmnet human_permission super_select_all">

                                                {{ __('Overtime adjusment view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_overtimeAdjustments_update"
                                                    {{ $role->hasPermissionTo('hrm_overtimeAdjustments_update') ? 'checked' : '' }}
                                                    class="hrm_overtime_adjustmnet human_permission super_select_all">
                                                {{ __('Overtime adjusment update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_overtimeAdjustments_delete"
                                                    {{ $role->hasPermissionTo('hrm_overtimeAdjustments_delete') ? 'checked' : '' }}
                                                    class="hrm_overtime_adjustmnet human_permission super_select_all">
                                                {{ __('Overtime adjusment delete') }}
                                            </p>
                                        </div>


                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_tax_adjustmnet" autocomplete="off"><strong>
                                                    {{ __('Tax adjusments') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employeeTaxAdjustments_index"
                                                    {{ $role->hasPermissionTo('hrm_employeeTaxAdjustments_index') ? 'checked' : '' }}
                                                    class="hrm_tax_adjustmnet human_permission super_select_all">

                                                {{ __('Tax adjusment list') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employeeTaxAdjustments_create"
                                                    {{ $role->hasPermissionTo('hrm_employeeTaxAdjustments_create') ? 'checked' : '' }}
                                                    class="hrm_tax_adjustmnet human_permission super_select_all">

                                                {{ __('Tax adjusment create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employeeTaxAdjustments_view"
                                                    {{ $role->hasPermissionTo('hrm_employeeTaxAdjustments_view') ? 'checked' : '' }}
                                                    class="hrm_tax_adjustmnet human_permission super_select_all">

                                                {{ __('Tax adjusment view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employeeTaxAdjustments_update"
                                                    {{ $role->hasPermissionTo('hrm_employeeTaxAdjustments_update') ? 'checked' : '' }}
                                                    class="hrm_tax_adjustmnet human_permission super_select_all">
                                                {{ __('Tax adjusment update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_employeeTaxAdjustments_delete"
                                                    {{ $role->hasPermissionTo('hrm_employeeTaxAdjustments_delete') ? 'checked' : '' }}
                                                    class="hrm_tax_adjustmnet human_permission super_select_all">
                                                {{ __('Tax adjusment delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_advances" autocomplete="off"><strong>
                                                    {{ __('Salary advances') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_advances_index"
                                                    {{ $role->hasPermissionTo('hrm_salary_advances_index') ? 'checked' : '' }}
                                                    class="hrm_advances human_permission super_select_all">

                                                {{ __('Salary advance list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_advances_create"
                                                    {{ $role->hasPermissionTo('hrm_salary_advances_create') ? 'checked' : '' }}
                                                    class="hrm_advances human_permission super_select_all">

                                                {{ __('Salary advance create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_advances_view"
                                                    {{ $role->hasPermissionTo('hrm_salary_advances_view') ? 'checked' : '' }}
                                                    class="hrm_advances human_permission super_select_all">

                                                {{ __('Salary advance view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_advances_update"
                                                    {{ $role->hasPermissionTo('hrm_salary_advances_update') ? 'checked' : '' }}
                                                    class="hrm_advances human_permission super_select_all">

                                                {{ __('Salary advance update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salary_advances_delete"
                                                    {{ $role->hasPermissionTo('hrm_salary_advances_delete') ? 'checked' : '' }}
                                                    class="hrm_advances human_permission super_select_all">
                                                {{ __('Salary advance delete') }}
                                            </p>
                                        </div>

                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info"><input type="checkbox"
                                                    class="select_all super_select_all human_permission"
                                                    data-target="hrm_payment_types" autocomplete="off"><strong>
                                                    {{ __('Payment types') }}</strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payments_types_index"
                                                    {{ $role->hasPermissionTo('hrm_payments_types_index') ? 'checked' : '' }}
                                                    class="hrm_payment_types human_permission super_select_all">

                                                {{ __('payment type List') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payments_types_create"
                                                    {{ $role->hasPermissionTo('hrm_payments_types_create') ? 'checked' : '' }}
                                                    class="hrm_payment_types human_permission super_select_all">

                                                {{ __('payment type create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payments_types_view"
                                                    {{ $role->hasPermissionTo('hrm_payments_types_view') ? 'checked' : '' }}
                                                    class="hrm_payment_types human_permission super_select_all">
                                                {{ __('payment type view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payments_types_update"
                                                    {{ $role->hasPermissionTo('hrm_payments_types_update') ? 'checked' : '' }}
                                                    class="hrm_payment_types human_permission super_select_all">
                                                {{ __('payment type update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payments_types_delete"
                                                    {{ $role->hasPermissionTo('hrm_payments_types_delete') ? 'checked' : '' }}
                                                    class="hrm_payment_types human_permission super_select_all">
                                                {{ __('payment type delete') }}
                                            </p>

                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_el_payment" autocomplete="off"><strong>
                                                    {{ __('Earned leave payments') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_payments_index"
                                                    {{ $role->hasPermissionTo('hrm_el_payments_index') ? 'checked' : '' }}
                                                    class="hrm_el_payment human_permission super_select_all">

                                                {{ __('Earned leave list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_payments_create"
                                                    {{ $role->hasPermissionTo('hrm_el_payments_create') ? 'checked' : '' }}
                                                    class="hrm_el_payment human_permission super_select_all">

                                                {{ __('Earned leave create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_payments_view"
                                                    {{ $role->hasPermissionTo('hrm_el_payments_view') ? 'checked' : '' }}
                                                    class="hrm_el_payment human_permission super_select_all">

                                                {{ __('Earned leave view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_payments_update"
                                                    {{ $role->hasPermissionTo('hrm_el_payments_update') ? 'checked' : '' }}
                                                    class="hrm_el_payment human_permission super_select_all">
                                                {{ __('Earned leave update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_payments_delete"
                                                    {{ $role->hasPermissionTo('hrm_el_payments_delete') ? 'checked' : '' }}
                                                    class="hrm_el_payment human_permission super_select_all">
                                                {{ __('Earned leave delete') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_el_calculation_index"
                                                    {{ $role->hasPermissionTo('hrm_el_calculation_index') ? 'checked' : '' }}
                                                    class="hrm_el_payment  human_permission super_select_all">

                                                {{ __('Earned leave calculation index') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_payroll" autocomplete="off"><strong>
                                                    {{ __('Payroll') }}
                                                </strong>
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payroll_index"
                                                    {{ $role->hasPermissionTo('hrm_payroll_index') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Payroll index') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payroll_salary_generate"
                                                    {{ $role->hasPermissionTo('hrm_payroll_salary_generate') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Salary generate') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payroll_payslip_generate"
                                                    {{ $role->hasPermissionTo('hrm_payroll_payslip_generate') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Payslip generate') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_payroll_custom_excel"
                                                    {{ $role->hasPermissionTo('hrm_payroll_custom_excel') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Payroll custom excel') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_calculation_checker_jobVsSalary"
                                                    {{ $role->hasPermissionTo('hrm_calculation_checker_jobVsSalary') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Job card vs salary calculation check') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_calculation_checker_summaryVsSalary"
                                                    {{ $role->hasPermissionTo('hrm_calculation_checker_summaryVsSalary') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('Summary vs salary calculation check') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_calculation_checker_allCalculation"
                                                    {{ $role->hasPermissionTo('hrm_calculation_checker_allCalculation') ? 'checked' : '' }}
                                                    class="hrm_payroll human_permission super_select_all">

                                                {{ __('All calculation check') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_job_card" autocomplete="off"><strong>
                                                    {{ __('Job card') }}
                                                </strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_job_card"
                                                    {{ $role->hasPermissionTo('hrm_attendance_job_card') ? 'checked' : '' }}
                                                    class="hrm_job_card human_permission super_select_all">
                                                {{ __('Job card index') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_job_card_print"
                                                    {{ $role->hasPermissionTo('hrm_attendance_job_card_print') ? 'checked' : '' }}
                                                    class="hrm_job_card human_permission super_select_all">
                                                {{ __('Employee wise job card print') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_attendance_job_summary_print"
                                                    {{ $role->hasPermissionTo('hrm_attendance_job_summary_print') ? 'checked' : '' }}
                                                    class="hrm_job_card human_permission super_select_all">
                                                {{ __('Job card summary print') }}
                                            </p>
                                        </div>



                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_awards" autocomplete="off"><strong>
                                                    {{ __('Awards') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_awards_index"
                                                    {{ $role->hasPermissionTo('hrm_awards_index') ? 'checked' : '' }}
                                                    class="hrm_awards human_permission super_select_all">

                                                {{ __('Awards list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_awards_create"
                                                    {{ $role->hasPermissionTo('hrm_awards_create') ? 'checked' : '' }}
                                                    class="hrm_awards human_permission super_select_all">

                                                {{ __('Awards create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_awards_view"
                                                    {{ $role->hasPermissionTo('hrm_awards_view') ? 'checked' : '' }}
                                                    class="hrm_awards human_permission super_select_all">
                                                {{ __('Awards view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_awards_update"
                                                    {{ $role->hasPermissionTo('hrm_awards_update') ? 'checked' : '' }}
                                                    class="hrm_awards human_permission super_select_all">
                                                {{ __('Awards update') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_awards_delete"
                                                    {{ $role->hasPermissionTo('hrm_awards_delete') ? 'checked' : '' }}
                                                    class="hrm_awards human_permission super_select_all">
                                                {{ __('Awards delete') }}
                                            </p>
                                        </div>


                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_notice" autocomplete="off"><strong>
                                                    {{ __('Notices') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_notice_index"
                                                    {{ $role->hasPermissionTo('hrm_notice_index') ? 'checked' : '' }}
                                                    class="hrm_notice human_permission super_select_all">

                                                {{ __('Notice list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_notice_create"
                                                    {{ $role->hasPermissionTo('hrm_notice_create') ? 'checked' : '' }}
                                                    class="hrm_notice human_permission super_select_all">

                                                {{ __('Notice create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_notice_view"
                                                    {{ $role->hasPermissionTo('hrm_notice_view') ? 'checked' : '' }}
                                                    class="hrm_notice human_permission super_select_all">

                                                {{ __('Notice view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_notice_update"
                                                    {{ $role->hasPermissionTo('hrm_notice_update') ? 'checked' : '' }}
                                                    class="hrm_notice human_permission super_select_all">
                                                {{ __('Notice update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_notice_delete"
                                                    {{ $role->hasPermissionTo('hrm_notice_delete') ? 'checked' : '' }}
                                                    class="hrm_notice human_permission super_select_all">
                                                {{ __('Notice delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_visits" autocomplete="off"><strong>
                                                    {{ __('Visits') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_visit_index"
                                                    {{ $role->hasPermissionTo('hrm_visit_index') ? 'checked' : '' }}
                                                    class="hrm_visits human_permission super_select_all">

                                                {{ __('Visit list') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_visit_create"
                                                    {{ $role->hasPermissionTo('hrm_visit_create') ? 'checked' : '' }}
                                                    class="hrm_visits human_permission super_select_all">

                                                {{ __('Visit create') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_visit_view"
                                                    {{ $role->hasPermissionTo('hrm_visit_view') ? 'checked' : '' }}
                                                    class="hrm_visits human_permission super_select_all">

                                                {{ __('Visit view') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_visit_update"
                                                    {{ $role->hasPermissionTo('hrm_visit_update') ? 'checked' : '' }}
                                                    class="hrm_visits human_permission super_select_all">
                                                {{ __('Visit update') }}
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_visit_delete"
                                                    {{ $role->hasPermissionTo('hrm_visit_delete') ? 'checked' : '' }}
                                                    class="hrm_visits human_permission super_select_all">
                                                {{ __('Visit delete') }}
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all human_permission "
                                                    data-target="hrm_others_report" autocomplete="off"><strong>
                                                    {{ __('Reports & others') }}
                                                </strong>
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_dashboard"
                                                    {{ $role->hasPermissionTo('hrm_dashboard') ? 'checked' : '' }}
                                                    class="hrm hrm_others_report human_permission super_select_all ">
                                                @lang('role.hrm_dashboard')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_leave_application_report"
                                                    {{ $role->hasPermissionTo('hrm_leave_application_report') ? 'checked' : '' }}
                                                    class="hrm_others_report human_permission super_select_all">

                                                {{ __('Leave application report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_salaryAdjustment_report"
                                                    {{ $role->hasPermissionTo('hrm_salaryAdjustment_report') ? 'checked' : '' }}
                                                    class="hrm_others_report human_permission super_select_all">

                                                {{ __('Salary adjustment report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_absent_report"
                                                    {{ $role->hasPermissionTo('hrm_absent_report') ? 'checked' : '' }}
                                                    class="hrm_others_report human_permission super_select_all">

                                                {{ __('Daily attendance report') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_date_range_absent_checker"
                                                    {{ $role->hasPermissionTo('hrm_date_range_absent_checker') ? 'checked' : '' }}
                                                    class="hrm_others_report human_permission super_select_all">

                                                {{ __('Date range absent checker') }}
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="hrm_organogram_index"
                                                    {{ $role->hasPermissionTo('hrm_organogram_index') ? 'checked' : '' }}
                                                    class="hrm_others_report human_permission super_select_all">

                                                {{ __('Company organogram') }}
                                            </p>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="other_check select_all super_select_all others_permission"
                                data-target="weight_scale_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="other_role" href="#collapsefourteen" href="">
                                Weight Scale Permissions
                            </a>
                        </div>

                        <div id="collapsefourteen" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all weight_scale_permission"
                                                    data-target="weight_scale"> Weight Scale</strong></p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="index_weight_scale"
                                                {{ $role->hasPermissionTo('index_weight_scale') ? 'checked' : '' }}
                                                class="weight_scale weight_scale_permission super_select_all">
                                            @lang('menu.weight_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="single_view_weight_scale"
                                                {{ $role->hasPermissionTo('single_view_weight_scale') ? 'checked' : '' }}
                                                class="weight_scale weight_scale_permission super_select_all">
                                            @lang('menu.weight_single_view')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="add_weight_scale"
                                                {{ $role->hasPermissionTo('add_weight_scale') ? 'checked' : '' }}
                                                class="weight_scale weight_scale_permission super_select_all">
                                            @lang('menu.add_weight')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_weight_scale"
                                                {{ $role->hasPermissionTo('delete_weight_scale') ? 'checked' : '' }}
                                                class="weight_scale weight_scale_permission super_select_all">
                                            @lang('menu.delete_weight')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all weight_scale_clients_permission"
                                                    data-target="weight_scale_clients"> Weight Scale Clients</strong></p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="index_weight_scale_client"
                                                {{ $role->hasPermissionTo('index_weight_scale_client') ? 'checked' : '' }}
                                                class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                            @lang('menu.weight_scale_client_list')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="add_weight_scale_client"
                                                {{ $role->hasPermissionTo('add_weight_scale_client') ? 'checked' : '' }}
                                                class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                            @lang('menu.add_weight_scale_client')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="edit_weight_scale_client"
                                                {{ $role->hasPermissionTo('edit_weight_scale_client') ? 'checked' : '' }}
                                                class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                            @lang('menu.edit_weight_scale_client')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="delete_weight_scale_client"
                                                {{ $role->hasPermissionTo('delete_weight_scale_client') ? 'checked' : '' }}
                                                class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                            @lang('menu.delete_weight_scale_client')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="other_check select_all super_select_all others_permission"
                                data-target="others_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="other_role" href="#collapsefifteen" href="">
                                Others Permissions
                            </a>
                        </div>
                        <div id="collapsefifteen" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all others_permission"
                                                    data-target="others">
                                                @lang('role.others')</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="print_invoice"
                                                {{ $role->hasPermissionTo('print_invoice') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            @lang('menu.print_invoice')
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="print_challan"
                                                {{ $role->hasPermissionTo('print_challan') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            @lang('menu.print_challan')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="print_weight"
                                                {{ $role->hasPermissionTo('print_weight') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            @lang('menu.print_weight')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="today_summery"
                                                {{ $role->hasPermissionTo('today_summery') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            Today summery
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="website_link"
                                                {{ $role->hasPermissionTo('website_link') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            Website link
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="hrm_menu"
                                                {{ $role->hasPermissionTo('hrm_menu') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            HRM Menus
                                        </p>

                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="modules_page"
                                                {{ $role->hasPermissionTo('modules_page') ? 'checked' : '' }}
                                                class="others others_permission super_select_all">
                                            Modules page
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form_element rounded mt-0 mb-1">
                        <div class="accordion-header">
                            <input type="checkbox" class="other_check select_all super_select_all website_permission"
                                data-target="website_permission" autocomplete="off">
                            <a data-bs-toggle="collapse" class="other_role" href="#collapsesixteen">
                                Website Permissions
                            </a>
                        </div>
                        <div id="collapsesixteen" class="collapse" data-bs-parent="#accordion">
                            <div class="element-body border-top">
                                <div class="row">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="client">
                                                Client</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_client"
                                                {{ $role->hasPermissionTo('web_manage_client') ? 'checked' : '' }}
                                                class="client website_permission super_select_all">
                                            @lang('menu.manage_clients')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_client"
                                                {{ $role->hasPermissionTo('web_add_client') ? 'checked' : '' }}
                                                class="client website_permission super_select_all">
                                            @lang('menu.add_client')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_client"
                                                {{ $role->hasPermissionTo('web_edit_client') ? 'checked' : '' }}
                                                class="client website_permission super_select_all">
                                            @lang('menu.edit_client')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_client"
                                                {{ $role->hasPermissionTo('web_delete_client') ? 'checked' : '' }}
                                                class="client website_permission super_select_all">
                                            @lang('menu.delete_client')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="buyer_requisition">
                                                Buyer Requisition</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_requisition_show"
                                                {{ $role->hasPermissionTo('web_requisition_show') ? 'checked' : '' }}
                                                class="buyer_requisition website_permission super_select_all">
                                            @lang('menu.show')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_requisition_delete"
                                                {{ $role->hasPermissionTo('web_requisition_delete') ? 'checked' : '' }}
                                                class="buyer_requisition website_permission super_select_all">
                                            @lang('menu.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="partners">
                                                Partners</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_partner"
                                                {{ $role->hasPermissionTo('web_manage_partner') ? 'checked' : '' }}
                                                class="partners website_permission super_select_all">
                                            @lang('menu.manage_partners')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_partner"
                                                {{ $role->hasPermissionTo('web_add_partner') ? 'checked' : '' }}
                                                class="partners website_permission super_select_all">
                                            @lang('menu.add_partner')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_partner"
                                                {{ $role->hasPermissionTo('web_edit_partner') ? 'checked' : '' }}
                                                class="partners website_permission super_select_all">
                                            @lang('menu.edit_partner')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_partner"
                                                {{ $role->hasPermissionTo('web_delete_partner') ? 'checked' : '' }}
                                                class="partners website_permission super_select_all">
                                            @lang('menu.delete_partner')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="team">
                                                Teams</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_team"
                                                {{ $role->hasPermissionTo('web_manage_team') ? 'checked' : '' }}
                                                class="team website_permission super_select_all">
                                            @lang('menu.manage_teams')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_team"
                                                {{ $role->hasPermissionTo('web_add_team') ? 'checked' : '' }}
                                                class="team website_permission super_select_all">
                                            @lang('menu.add_team')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_team"
                                                {{ $role->hasPermissionTo('web_edit_team') ? 'checked' : '' }}
                                                class="team website_permission super_select_all">
                                            @lang('menu.edit_team')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_team"
                                                {{ $role->hasPermissionTo('web_delete_team') ? 'checked' : '' }}
                                                class="team website_permission super_select_all">
                                            @lang('menu.delete_team')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="category">
                                                Category</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_category"
                                                {{ $role->hasPermissionTo('web_manage_category') ? 'checked' : '' }}
                                                class="category website_permission super_select_all">
                                            @lang('menu.manage_categorys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_category"
                                                {{ $role->hasPermissionTo('web_add_category') ? 'checked' : '' }}
                                                class="category website_permission super_select_all">
                                            @lang('menu.add_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_category"
                                                {{ $role->hasPermissionTo('web_edit_category') ? 'checked' : '' }}
                                                class="category website_permission super_select_all">
                                            @lang('menu.edit_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_category"
                                                {{ $role->hasPermissionTo('web_delete_category') ? 'checked' : '' }}
                                                class="category website_permission super_select_all">
                                            @lang('menu.delete_category')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="product">
                                                Product</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_product"
                                                {{ $role->hasPermissionTo('web_manage_product') ? 'checked' : '' }}
                                                class="product website_permission super_select_all">
                                            @lang('menu.manage_products')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_product"
                                                {{ $role->hasPermissionTo('web_add_product') ? 'checked' : '' }}
                                                class="product website_permission super_select_all">
                                            @lang('menu.add_product')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_product"
                                                {{ $role->hasPermissionTo('web_edit_product') ? 'checked' : '' }}
                                                class="product website_permission super_select_all">
                                            @lang('menu.edit_product')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_product"
                                                {{ $role->hasPermissionTo('web_delete_product') ? 'checked' : '' }}
                                                class="product website_permission super_select_all">
                                            @lang('menu.delete_product')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="job_category">
                                                Job Category</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_job_category"
                                                {{ $role->hasPermissionTo('web_manage_job_category') ? 'checked' : '' }}
                                                class="job_category website_permission super_select_all">
                                            @lang('menu.manage_job_categorys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_job_category"
                                                {{ $role->hasPermissionTo('web_add_job_category') ? 'checked' : '' }}
                                                class="job_category website_permission super_select_all">
                                            @lang('menu.add_job_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_job_category"
                                                {{ $role->hasPermissionTo('web_edit_job_category') ? 'checked' : '' }}
                                                class="job_category website_permission super_select_all">
                                            @lang('menu.edit_job_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_job_category"
                                                {{ $role->hasPermissionTo('web_delete_job_category') ? 'checked' : '' }}
                                                class="job_category website_permission super_select_all">
                                            @lang('menu.delete_job_category')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="job">
                                                Job</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_job"
                                                {{ $role->hasPermissionTo('web_manage_job') ? 'checked' : '' }}
                                                class="job website_permission super_select_all">
                                            @lang('menu.manage_jobs')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_job"
                                                {{ $role->hasPermissionTo('web_add_job') ? 'checked' : '' }}
                                                class="job website_permission super_select_all">
                                            @lang('menu.add_job')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_job"
                                                {{ $role->hasPermissionTo('web_edit_job') ? 'checked' : '' }}
                                                class="job website_permission super_select_all">
                                            @lang('menu.edit_job')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_job"
                                                {{ $role->hasPermissionTo('web_delete_job') ? 'checked' : '' }}
                                                class="job website_permission super_select_all">
                                            @lang('menu.delete_job')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="job_applied">
                                                Job Applied</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_job_applied_download"
                                                {{ $role->hasPermissionTo('web_job_applied_download') ? 'checked' : '' }}
                                                class="job_applied website_permission super_select_all">
                                            @lang('menu.download')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_job_applied_delete"
                                                {{ $role->hasPermissionTo('web_job_applied_delete') ? 'checked' : '' }}
                                                class="job_applied website_permission super_select_all">
                                            @lang('menu.delete')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="gallery_category">
                                                Gallery Category</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_gallery_category"
                                                {{ $role->hasPermissionTo('web_manage_gallery_category') ? 'checked' : '' }}
                                                class="gallery_category website_permission super_select_all">
                                            @lang('menu.manage_categorys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_gallery_category"
                                                {{ $role->hasPermissionTo('web_add_gallery_category') ? 'checked' : '' }}
                                                class="gallery_category website_permission super_select_all">
                                            @lang('menu.add_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_gallery_category"
                                                {{ $role->hasPermissionTo('web_edit_gallery_category') ? 'checked' : '' }}
                                                class="gallery_category website_permission super_select_all">
                                            @lang('menu.edit_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_gallery_category"
                                                {{ $role->hasPermissionTo('web_delete_gallery_category') ? 'checked' : '' }}
                                                class="gallery_category website_permission super_select_all">
                                            @lang('menu.delete_category')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="gallery">
                                                Gallery</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_gallery"
                                                {{ $role->hasPermissionTo('web_manage_gallery') ? 'checked' : '' }}
                                                class="gallery website_permission super_select_all">
                                            @lang('menu.manage_gallerys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_gallery"
                                                {{ $role->hasPermissionTo('web_add_gallery') ? 'checked' : '' }}
                                                class="gallery website_permission super_select_all">
                                            @lang('menu.add_gallery')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_gallery"
                                                {{ $role->hasPermissionTo('web_edit_gallery') ? 'checked' : '' }}
                                                class="gallery website_permission super_select_all">
                                            @lang('menu.edit_gallery')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_gallery"
                                                {{ $role->hasPermissionTo('web_delete_gallery') ? 'checked' : '' }}
                                                class="gallery website_permission super_select_all">
                                            @lang('menu.delete_gallery')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="blog_category">
                                                Blog Category</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_blog_category"
                                                {{ $role->hasPermissionTo('web_manage_blog_category') ? 'checked' : '' }}
                                                class="blog_category website_permission super_select_all">
                                            @lang('menu.manage_blog_categorys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_blog_category"
                                                {{ $role->hasPermissionTo('web_add_blog_category') ? 'checked' : '' }}
                                                class="blog_category website_permission super_select_all">
                                            @lang('menu.add_blog_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_blog_category"
                                                {{ $role->hasPermissionTo('web_edit_blog_category') ? 'checked' : '' }}
                                                class="blog_category website_permission super_select_all">
                                            @lang('menu.edit_blog_category')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_blog_category"
                                                {{ $role->hasPermissionTo('web_delete_blog_category') ? 'checked' : '' }}
                                                class="blog_category website_permission super_select_all">
                                            @lang('menu.delete_blog_category')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="blog">
                                                Blog</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_blog"
                                                {{ $role->hasPermissionTo('web_manage_blog') ? 'checked' : '' }}
                                                class="blog website_permission super_select_all">
                                            @lang('menu.manage_blogs')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_blog"
                                                {{ $role->hasPermissionTo('web_add_blog') ? 'checked' : '' }}
                                                class="blog website_permission super_select_all">
                                            @lang('menu.add_blog')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_blog"
                                                {{ $role->hasPermissionTo('web_edit_blog') ? 'checked' : '' }}
                                                class="blog website_permission super_select_all">
                                            @lang('menu.edit_blog')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_blog"
                                                {{ $role->hasPermissionTo('web_delete_blog') ? 'checked' : '' }}
                                                class="blog website_permission super_select_all">
                                            @lang('menu.delete_blog')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="comment">
                                                Comments</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_comment"
                                                {{ $role->hasPermissionTo('web_manage_comment') ? 'checked' : '' }}
                                                class="comment website_permission super_select_all">
                                            @lang('menu.manage_comments')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_comment"
                                                {{ $role->hasPermissionTo('web_edit_comment') ? 'checked' : '' }}
                                                class="comment website_permission super_select_all">
                                            @lang('menu.edit_comment')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_comment"
                                                {{ $role->hasPermissionTo('web_delete_comment') ? 'checked' : '' }}
                                                class="comment website_permission super_select_all">
                                            @lang('menu.delete_comment')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="page">
                                                Pages</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_page"
                                                {{ $role->hasPermissionTo('web_manage_page') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.manage_pages')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_page"
                                                {{ $role->hasPermissionTo('web_add_page') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.add_page')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_page"
                                                {{ $role->hasPermissionTo('web_edit_page') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.edit_page')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_page"
                                                {{ $role->hasPermissionTo('web_delete_page') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.delete_page')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_about_us"
                                                {{ $role->hasPermissionTo('web_about_us') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.about_us')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_history"
                                                {{ $role->hasPermissionTo('web_history') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.history')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_message_of_director"
                                                {{ $role->hasPermissionTo('web_message_of_director') ? 'checked' : '' }}
                                                class="page website_permission super_select_all">
                                            @lang('menu.message_of_director')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="testimonial">
                                                Testimonial</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_testimonial"
                                                {{ $role->hasPermissionTo('web_manage_testimonial') ? 'checked' : '' }}
                                                class="testimonial website_permission super_select_all">
                                            @lang('menu.manage_testimonials')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_testimonial"
                                                {{ $role->hasPermissionTo('web_add_testimonial') ? 'checked' : '' }}
                                                class="testimonial website_permission super_select_all">
                                            @lang('menu.add_testimonial')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_page"
                                                {{ $role->hasPermissionTo('web_edit_page') ? 'checked' : '' }}
                                                class="testimonial website_permission super_select_all">
                                            @lang('menu.edit_testimonial')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_page"
                                                {{ $role->hasPermissionTo('web_delete_page') ? 'checked' : '' }}
                                                class="testimonial website_permission super_select_all">
                                            @lang('menu.delete_testimonial')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="campaign">
                                                Campaign</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_campaign"
                                                {{ $role->hasPermissionTo('web_manage_campaign') ? 'checked' : '' }}
                                                class="campaign website_permission super_select_all">
                                            @lang('menu.manage_campaigns')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_campaign"
                                                {{ $role->hasPermissionTo('web_add_campaign') ? 'checked' : '' }}
                                                class="campaign website_permission super_select_all">
                                            @lang('menu.add_campaign')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_campaign"
                                                {{ $role->hasPermissionTo('web_edit_campaign') ? 'checked' : '' }}
                                                class="campaign website_permission super_select_all">
                                            @lang('menu.edit_campaign')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_campaign"
                                                {{ $role->hasPermissionTo('web_delete_campaign') ? 'checked' : '' }}
                                                class="campaign website_permission super_select_all">
                                            @lang('menu.delete_campaign')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="faq">
                                                FAQ</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_faq"
                                                {{ $role->hasPermissionTo('web_manage_faq') ? 'checked' : '' }}
                                                class="faq website_permission super_select_all">
                                            @lang('menu.manage_faqs')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_faq"
                                                {{ $role->hasPermissionTo('web_add_faq') ? 'checked' : '' }}
                                                class="faq website_permission super_select_all">
                                            @lang('menu.add_faq')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_faq"
                                                {{ $role->hasPermissionTo('web_edit_faq') ? 'checked' : '' }}
                                                class="faq website_permission super_select_all">
                                            @lang('menu.edit_faq')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_faq"
                                                {{ $role->hasPermissionTo('web_delete_faq') ? 'checked' : '' }}
                                                class="faq website_permission super_select_all">
                                            @lang('menu.delete_faq')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="buet_test">
                                                Buet Test</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_buet_test"
                                                {{ $role->hasPermissionTo('web_manage_buet_test') ? 'checked' : '' }}
                                                class="buet_test website_permission super_select_all">
                                            @lang('menu.manage_buet_tests')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_buet_test"
                                                {{ $role->hasPermissionTo('web_add_buet_test') ? 'checked' : '' }}
                                                class="buet_test website_permission super_select_all">
                                            @lang('menu.add_buet_test')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_buet_test"
                                                {{ $role->hasPermissionTo('web_edit_buet_test') ? 'checked' : '' }}
                                                class="buet_test website_permission super_select_all">
                                            @lang('menu.edit_buet_test')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_buet_test"
                                                {{ $role->hasPermissionTo('web_delete_buet_test') ? 'checked' : '' }}
                                                class="buet_test website_permission super_select_all">
                                            @lang('menu.delete_buet_test')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="dealership_request">
                                                Dealership Request</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_dealership_requests"
                                                {{ $role->hasPermissionTo('web_manage_dealership_requests') ? 'checked' : '' }}
                                                class="dealership_request website_permission super_select_all">
                                            @lang('menu.manage_dealership_requests')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_dealership_request"
                                                {{ $role->hasPermissionTo('web_delete_dealership_request') ? 'checked' : '' }}
                                                class="dealership_request website_permission super_select_all">
                                            @lang('menu.delete_dealership_request')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="slider">
                                                Slider</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_slider"
                                                {{ $role->hasPermissionTo('web_manage_slider') ? 'checked' : '' }}
                                                class="slider website_permission super_select_all">
                                            @lang('menu.manage_sliders')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_slider"
                                                {{ $role->hasPermissionTo('web_add_slider') ? 'checked' : '' }}
                                                class="slider website_permission super_select_all">
                                            @lang('menu.add_slider')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_slider"
                                                {{ $role->hasPermissionTo('web_edit_slider') ? 'checked' : '' }}
                                                class="slider website_permission super_select_all">
                                            @lang('menu.edit_slider')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_slider"
                                                {{ $role->hasPermissionTo('web_delete_slider') ? 'checked' : '' }}
                                                class="slider website_permission super_select_all">
                                            @lang('menu.delete_slider')
                                        </p>
                                    </div>

                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="video">
                                                Video</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_video"
                                                {{ $role->hasPermissionTo('web_manage_video') ? 'checked' : '' }}
                                                class="video website_permission super_select_all">
                                            @lang('menu.manage_videos')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_video"
                                                {{ $role->hasPermissionTo('web_add_video') ? 'checked' : '' }}
                                                class="video website_permission super_select_all">
                                            @lang('menu.add_video')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_video"
                                                {{ $role->hasPermissionTo('web_edit_video') ? 'checked' : '' }}
                                                class="video website_permission super_select_all">
                                            @lang('menu.edit_video')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_video"
                                                {{ $role->hasPermissionTo('web_delete_video') ? 'checked' : '' }}
                                                class="video website_permission super_select_all">
                                            @lang('menu.delete_video')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="country">
                                                Country</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_country"
                                                {{ $role->hasPermissionTo('web_manage_country') ? 'checked' : '' }}
                                                class="country website_permission super_select_all">
                                            @lang('menu.manage_countrys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_country"
                                                {{ $role->hasPermissionTo('web_add_country') ? 'checked' : '' }}
                                                class="country website_permission super_select_all">
                                            @lang('menu.add_video')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_country"
                                                {{ $role->hasPermissionTo('web_edit_country') ? 'checked' : '' }}
                                                class="country website_permission super_select_all">
                                            @lang('menu.edit_video')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_country"
                                                {{ $role->hasPermissionTo('web_delete_country') ? 'checked' : '' }}
                                                class="country website_permission super_select_all">
                                            @lang('menu.delete_video')
                                        </p>
                                    </div>
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="city">
                                                City</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_manage_city"
                                                {{ $role->hasPermissionTo('web_manage_city') ? 'checked' : '' }}
                                                class="city website_permission super_select_all">
                                            @lang('menu.manage_citys')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_add_city"
                                                {{ $role->hasPermissionTo('web_add_city') ? 'checked' : '' }}
                                                class="city website_permission super_select_all">
                                            @lang('menu.add_city')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_edit_city"
                                                {{ $role->hasPermissionTo('web_edit_city') ? 'checked' : '' }}
                                                class="city website_permission super_select_all">
                                            @lang('menu.edit_city')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="web_delete_city"
                                                {{ $role->hasPermissionTo('web_delete_city') ? 'checked' : '' }}
                                                class="city website_permission super_select_all">
                                            @lang('menu.delete_city')
                                        </p>
                                    </div>
                                    <hr class="my-2">
                                    <div class="col-lg-3 col-sm-6">
                                        <p class="text-info"><strong><input type="checkbox"
                                                    class="select_all super_select_all website_permission"
                                                    data-target="setting">
                                                Setting</strong></p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="general_setting"
                                                {{ $role->hasPermissionTo('general_setting') ? 'checked' : '' }}
                                                class="setting website_permission super_select_all">
                                            @lang('menu.general_settings')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="seo"
                                                {{ $role->hasPermissionTo('seo') ? 'checked' : '' }}
                                                class="setting website_permission super_select_all">
                                            @lang('menu.seo')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="social_link"
                                                {{ $role->hasPermissionTo('social_link') ? 'checked' : '' }}
                                                class="setting website_permission super_select_all">
                                            @lang('menu.social_link')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="banner"
                                                {{ $role->hasPermissionTo('banner') ? 'checked' : '' }}
                                                class="setting website_permission super_select_all">
                                            @lang('menu.banner')
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="contact"
                                                {{ $role->hasPermissionTo('contact') ? 'checked' : '' }}
                                                class="setting website_permission super_select_all">
                                            @lang('menu.contact')
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row1">
                        <div class="col-md-12 d-flex justify-content-end mt-2">
                            <div class="btn-box">
                                <button type="button" class="btn loading_button p-1 d-none"><i
                                        class="fas fa-spinner text-white"></i></button>
                                <button class="btn w-auto btn-success submit_button float-end"
                                    {{ $role->name === 'superadmin' ? 'disabled' : '' }}>
                                    @lang('menu.save')
                                </button>
                            </div>
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
        $(document).on('click', '.select_all', function() {
            var target = $(this).data('target');
            if ($(this).is(':checked', true)) {
                $('.' + target).prop('checked', true);
            } else {
                $('.' + target).prop('checked', false);
            }
        });
    </script>
@endpush
