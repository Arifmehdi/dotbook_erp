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
            <h6>@lang('menu.add_role')</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-0">
            <form id="add_role_form" action="{{ route('users.role.store') }}" method="POST">
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
                                                            placeholder="@lang('menu.role_name')">
                                                        <span
                                                            class="error error_role_name">{{ $errors->first('role_name') }}</span>
                                                    </div>
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
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.view_all_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_add"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.add_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_import"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.import_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_edit"
                                                    class=" customers sales_app_permission super_select_all">
                                                @lang('menu.edit_customer')
                                            </p>


                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_delete"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.delete_customer')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_group"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_group')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_report"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_manage"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_manage')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_payment_receive_voucher"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer') @lang('menu.payment_receive_voucher')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="customer_status_change"
                                                    class="customers sales_app_permission super_select_all">
                                                @lang('menu.customer_status_change')
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
                                                    class="pos sales_app_permission super_select_all">@lang('menu.manage_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_add"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.add_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_edit"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.edit_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_delete"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.delete_pos_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pos_sale_settings"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.pos_sale_settings')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_pos_screen"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.edit_item_price_from_pos_screen')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_pos_screen"
                                                    class="pos sales_app_permission super_select_all">@lang('menu.edit_item_discount_from_pos_screen')
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
                                                    class="sales_report sales_app_permission super_select_all">
                                                @lang('menu.sales_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="pro_sale_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.sold_items_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_order_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.sales_order_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="ordered_item_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.sales_ordered_items_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sr_wise_order_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.sr_wise_sales_order_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="ordered_item_qty_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.ordered_item_qty_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_report"
                                                    class="sales_report super_select_all sales_app_permission">
                                                @lang('menu.do_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_vs_sales_report"
                                                    class="sales_report super_select_all sales_app_permission">@lang('menu.do_vs_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_return_report"
                                                    class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sales_return_report')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_returned_items_report"
                                                    class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.returned_items_report')
                                            </p>

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_payment_report" class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.receive_payment') @lang('menu.report')
                                                </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="c_register_report" class="sales_report super_select_all sales_app_permission super_select_all"> @lang('menu.cash_register_reports')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="sale_representative_report" class="sales_report super_select_all sales_app_permission super_select_all">@lang('menu.sales_representative_report')
                                                </p> --}}
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all sales_app_permission super_select_all"
                                                    data-target="sales_return" autocomplete="off">
                                                <strong>@lang('menu.sales_return')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_sales_return"
                                                    class="sales_return sales_app_permission super_select_all">@lang('menu.view_all')
                                                @lang('role.sale_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_sales_return"
                                                    class="sales_return sales_app_permission super_select_all">
                                                @lang('menu.add') @lang('menu.sales_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_sales_return"
                                                    class="sales_return sales_app_permission super_select_all">
                                                @lang('menu.edit') @lang('menu.sales_return')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_sales_return"
                                                    class=" sales_return sales_app_permission super_select_all">
                                                @lang('menu.delete') @lang('menu.sales_return')
                                            </p>

                                            <div class="mt-3">
                                                <p class="text-info">
                                                    <input type="checkbox"
                                                        class="select_all super_select_all sales_app_permission"
                                                        data-target="recent_prices" autocomplete="off">
                                                    <strong> @lang('menu.recent_price')</strong>
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="add_new_recent_price"
                                                        class="recent_prices super_select_all sales_app_permission">@lang('menu.add_new_price')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="all_previous_recent_price"
                                                        class="recent_prices super_select_all sales_app_permission">
                                                    @lang('menu.all_pre_price')
                                                </p>

                                                <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="today_recent_price"
                                                        class="recent_prices super_select_all sales_app_permission">@lang('menu.today_price')
                                                </p>
                                            </div>
                                        </div>

                                        <hr class="my-2">

                                        <div class="col-lg-3 col-sm-6">

                                            <p class="text-info">
                                                <input type="checkbox"
                                                    class="select_all super_select_all sales_app_permission super_select_all"
                                                    data-target="sale" autocomplete="off">
                                                <strong>@lang('menu.sales')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="create_add_sale"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_add_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_sales"
                                                    class="sale sales_app_permission super_select_all">@lang('menu.view_sales')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_sale"
                                                    class="sale sales_app_permission super_select_all">@lang('menu.edit_sale')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="delete_sale"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.delete_sale')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_settings"
                                                    class="sale sales_app_permission super_select_all">@lang('menu.sale_settings')
                                            </p>

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_index" class="sale sales_app_permission super_select_all">
                                                    @lang('menu.view_all_receive_payments')
                                                </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_create" class="sale sales_app_permission super_select_all">@lang('menu.create_receive_payment')
                                                </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_view" class="sale sales_app_permission super_select_all">@lang('menu.single_receive_payment_view')
                                                </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_update" class="sale sales_app_permission super_select_all"> @lang('menu.update_receive_payment')
                                                </p> --}}

                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                    <input type="checkbox" name="receive_payment_delete" class="sale sales_app_permission super_select_all">
                                                    @lang('menu.delete') @lang('menu.receive_payment')
                                                </p> --}}
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="add_quotation"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_list"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.manage') @lang('menu.quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_edit"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('role.edit_quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_quotation_delete"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('role.delete_quotation')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_add"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.create_sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_all"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('menu.manage') @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_edit"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.edit')
                                                @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_do_approval"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.do_approval')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sale_order_delete"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.delete')
                                                @lang('menu.sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_add"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.create')
                                                @lang('menu.delivery_order')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_all"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.manage')
                                                @lang('menu.delivery_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_edit"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.edit')
                                                @lang('menu.delivery_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_delete"
                                                    class="sale sales_app_permission super_select_all"> @lang('menu.delete')
                                                @lang('menu.delivery_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="change_expire_date"
                                                    class="sale sales_app_permission super_select_all">@lang('role.change_expire_date')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_to_final"
                                                    class="sale sales_app_permission super_select_all">@lang('role.do_to_final')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="quotation_notification"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('role.get_notification_after_creating_the_quotation')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="sales_order_notification"
                                                    class="sale sales_app_permission super_select_all">
                                                @lang('role.get_notification_after_creating_the_sales_order')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_notification"
                                                    class="sale sales_app_permission super_select_all">@lang('role.get_notification_after_creating_the_do')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="price_update_notification"
                                                    class="sale sales_app_permission super_select_all">@lang('role.notification_about_price_update')
                                            </p>
                                        </div>

                                        <div class="col-lg-3 col-sm-6">
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="hidden">
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="do_approval_notification"
                                                    class="sale sales_app_permission super_select_all">@lang('role.get_notification_after_do_approval')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_price_sale_screen"
                                                    class="sale sales_app_permission super_select_all">@lang('role.edit_product_price_from_sales_screen')
                                            </p>
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="edit_discount_sale_screen"
                                                    class="sale sales_app_permission super_select_all">@lang('role.edit_product_discount_in_sale_scr')
                                            </p>
                                            {{-- <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="shipment_access" class="sale sales_app_permission super_select_all">@lang('role.access_shipments')
                                            </p> --}}
                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="view_product_cost_is_sale_screed"
                                                    class="sale sales_app_permission super_select_all">@lang('role.view_item_cost_in_sale_screen')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" checked name="view_own_sale"
                                                    class="sale sales_app_permission super_select_all">@lang('role.view_only_own_data')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="discounts"
                                                    class="sale sales_app_permission super_select_all">@lang('menu.manage_offers')
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
                                                    class="manage_sr sales_app_permission super_select_all">
                                                @lang('menu.sr_list')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manage_sr_manage"
                                                    class="manage_sr sales_app_permission super_select_all">
                                                @lang('menu.manage_sr')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manage_sr_create"
                                                    class="manage_sr sales_app_permission super_select_all">
                                                @lang('menu.add_sr')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manage_sr_edit"
                                                    class="manage_sr sales_app_permission super_select_all">
                                                @lang('menu.edit_sr')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="manage_sr_delete"
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
                                    <input type="checkbox" class="select_all super_select_all procurement_permission"
                                        data-target="purchase" autocomplete="off"><strong> @lang('menu.purchases')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_all"
                                        class="purchase procurement_permission super_select_all">
                                    @lang('menu.manage') @lang('role.purchase')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_add"
                                        class="purchase procurement_permission super_select_all"> @lang('menu.add')
                                    @lang('role.purchase')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_edit"
                                        class="purchase procurement_permission super_select_all"> @lang('menu.edit')
                                    @lang('role.purchase')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_delete"
                                        class="purchase procurement_permission super_select_all"> @lang('role.delete')
                                    @lang('role.purchase')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_settings"
                                        class="purchase procurement_permission super_select_all"> @lang('role.purchase')
                                    @lang('menu.settings')
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all procurement_permission"
                                        data-target="requisition" autocomplete="off"><strong> @lang('menu.requisitions')</strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="create_requisition"
                                        class="requisition procurement_permission super_select_all"> @lang('menu.create_requisition')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="all_requisition"
                                        class="requisition procurement_permission super_select_all"> @lang('menu.manage')
                                    @lang('menu.requisitions')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="edit_requisition"
                                        class="requisition procurement_permission super_select_all"> @lang('menu.edit')
                                    @lang('menu.requisitions')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="approve_requisition"
                                        class="requisition procurement_permission super_select_all"> @lang('menu.approved')
                                    @lang('menu.requisitions')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="delete_requisition"
                                        class="requisition procurement_permission super_select_all"> @lang('role.delete')
                                    @lang('menu.requisitions')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="requisition_notification"
                                        class="requisition procurement_permission super_select_all">@lang('role.get_notification_after_creating_requisition')
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all procurement_permission"
                                        data-target="purchase_order" autocomplete="off"><strong>
                                        @lang('menu.purchase_order')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="create_po"
                                        class="purchase_order procurement_permission super_select_all"> @lang('menu.create_purchase_order')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="all_po"
                                        class="purchase_order procurement_permission super_select_all"> @lang('menu.manage')
                                    @lang('menu.purchase_order')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="edit_po"
                                        class="purchase_order procurement_permission super_select_all"> @lang('menu.edit')
                                    @lang('menu.purchase_order')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="delete_po"
                                        class="purchase_order procurement_permission super_select_all"> @lang('menu.delete')
                                    @lang('menu.purchase_order')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="po_notification"
                                        class="purchase_order procurement_permission super_select_all"> @lang('role.get_notification_after_creating')
                                    @lang('role.purchase') @lang('menu.order')
                                </p>
                            </div>

                            {{-- <div class="col-lg-3 col-sm-6">
                                            <p class="text-info">
                                                <input type="checkbox" class="select_all super_select_all procurement_permission" data-target="purchase_payment" autocomplete="off"><strong> @lang('role.purchase') @lang('menu.payments')</strong>
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_index" class="purchase_payment procurement_permission super_select_all"> @lang('role.view_all') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_create" class="purchase_payment procurement_permission super_select_all"> @lang('role.create') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_view" class="purchase_payment procurement_permission super_select_all"> @lang('role.single_purchase') @lang('menu.payments') @lang('role.view')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_update" class="purchase_payment procurement_permission super_select_all"> @lang('menu.update') @lang('role.purchase') @lang('menu.payments')
                                            </p>

                                            <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_delete" class="purchase_payment procurement_permission super_select_all"> @lang('role.delete') @lang('role.purchase') @lang('menu.payments')
                                            </p>
                                        </div> --}}

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox"
                                        class="select_all super_select_all procurement_permission super_select_all"
                                        data-target="suppliers" autocomplete="off"> <strong> @lang('menu.suppliers')</strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_all"
                                        class="suppliers procurement_permission super_select_all"> @lang('menu.view_all_supplier')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_add"
                                        class="suppliers procurement_permission super_select_all">@lang('menu.add_supplier')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_import"
                                        class="suppliers procurement_permission super_select_all"> @lang('role.import')
                                    @lang('role.supplier')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_edit"
                                        class="suppliers procurement_permission super_select_all"> @lang('menu.edit_supplier')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_delete"
                                        class="suppliers procurement_permission super_select_all"> @lang('role.delete')
                                    @lang('role.supplier')
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox"
                                        class="select_all super_select_all procurement_permission super_select_all"
                                        data-target="purchase_by_scale" autocomplete="off"> <strong> @lang('role.purchase')
                                        By @lang('role.scale')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_by_scale_index"
                                        class="purchase_by_scale procurement_permission super_select_all">
                                    @lang('role.view_all') @lang('role.purchase') By @lang('role.scale')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_by_scale_view"
                                        class="purchase_by_scale procurement_permission super_select_all">
                                    @lang('role.single_view') @lang('role.purchase') By @lang('role.scale')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_by_scale_create"
                                        class="purchase_by_scale procurement_permission super_select_all">
                                    @lang('role.add') @lang('role.purchase') By @lang('role.scale')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_by_scale_delete"
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
                                        class="purchase_return procurement_permission super_select_all">
                                    @lang('role.view_all') @lang('role.purchase') @lang('role.return')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="add_purchase_return"
                                        class="purchase_return procurement_permission super_select_all">
                                    @lang('menu.add') @lang('role.purchase') @lang('role.return')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="edit_purchase_return"
                                        class="purchase_return procurement_permission super_select_all">
                                    @lang('menu.edit') @lang('role.purchase') @lang('role.return')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="delete_purchase_return"
                                        class="purchase_return procurement_permission super_select_all">
                                    @lang('role.delete') @lang('role.purchase') @lang('role.return')
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info"><input type="checkbox"
                                        class="select_all super_select_all procurement_permission"
                                        data-target="stock_issue" autocomplete="off">
                                    <strong>@lang('role.stock_issue')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_index"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                    @lang('role.list')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_create"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                    @lang('role.create')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_view"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                    @lang('role.details')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_update"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                    @lang('role.update')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_delete"
                                        class="stock_issue procurement_permission super_select_all"> @lang('role.stock_issue')
                                    @lang('role.delete')
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info"><input type="checkbox"
                                        class="select_all super_select_all procurement_permission"
                                        data-target="receive_stocks" autocomplete="off">
                                    <strong>@lang('role.receive_stocks')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="receive_stocks_index"
                                        class="receive_stocks procurement_permission super_select_all"> @lang('role.receive_stocks_list')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="receive_stocks_view"
                                        class="receive_stocks procurement_permission super_select_all"> @lang('role.stock_issue_details')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="receive_stocks_create"
                                        class="receive_stocks procurement_permission super_select_all"> @lang('role.receive_stock_create')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="receive_stocks_update"
                                        class="receive_stocks procurement_permission super_select_all"> @lang('role.receive_stock_update')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="receive_stocks_delete"
                                        class="receive_stocks procurement_permission super_select_all"> @lang('role.receive_stock_delete')
                                </p>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox"
                                        class="select_all super_select_all procurement_permission super_select_all"
                                        data-target="procurement_report" autocomplete="off"><strong>
                                        @lang('menu.procurement_reports')</strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="requested_product_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('menu.requested_item_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="weighted_product_report"
                                        class="procurement_report procurement_permission super_select_all">@lang('menu.weighted_item_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="received_stocks_report"
                                        class="procurement_report procurement_permission super_select_all">@lang('menu.received_stocks_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('menu.purchase_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_sale_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('role.purchase') & @lang('role.sale_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="pro_purchase_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('menu.purchased_items_report')
                                </p>

                                {{-- <p class="checkbox_input_wrap mt-1">
                                                <input type="checkbox" name="purchase_payment_report" class="procurement_report procurement_permission super_select_all"> @lang('menu.purchase_payment_report')
                                            </p> --}}

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issue_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('role.stock_issue_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="stock_issued_items_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('role.stock_issued_items_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_return_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('menu.purchase_return_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="purchase_returned_items_report"
                                        class="procurement_report procurement_permission super_select_all">
                                    @lang('menu.purchase_returned_items_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="supplier_report"
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
                                <input type="checkbox" class="select_all super_select_all inventory_permission"
                                    data-target="product" autocomplete="off"> <strong>@lang('menu.items')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="product_all"
                                    class="product inventory_permission super_select_all">
                                @lang('menu.view_all_item')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="product_add"
                                    class="product inventory_permission super_select_all">@lang('menu.add_item')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="product_edit"
                                    class="product inventory_permission super_select_all"> @lang('role.edit_item')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="openingStock_add"
                                    class="product inventory_permission super_select_all">
                                @lang('menu.add')/@lang('menu.edit') @lang('menu.opening_stock')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="product_delete"
                                    class="product inventory_permission super_select_all"> @lang('role.delete')
                                @lang('role.item')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="product_settings"
                                    class="product inventory_permission super_select_all"> @lang('menu.item_settings')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="checkbox_input_wrap mt-1">
                                <input type="hidden">
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="categories"
                                    class="product inventory_permission super_select_all"> @lang('role.categories')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="brand"
                                    class="product inventory_permission super_select_all"> Brands
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="units"
                                    class="product inventory_permission super_select_all"> @lang('role.unit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="variant"
                                    class="product inventory_permission super_select_all"> @lang('role.variants')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="warranties"
                                    class="product inventory_permission super_select_all"> @lang('menu.warranties')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="selling_price_group"
                                    class="product inventory_permission super_select_all">@lang('menu.selling_price_group')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="generate_barcode"
                                    class="product inventory_permission super_select_all"> @lang('menu.generate_barcode')
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
                                    class="stock_adjustments inventory_permission super_select_all"> @lang('role.view_all')
                                @lang('role.stock_adjustments')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="stock_adjustments_add"
                                    class="stock_adjustments inventory_permission super_select_all">@lang('role.add_stock_adjustment')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="stock_adjustments_delete"
                                    class="stock_adjustments inventory_permission super_select_all"> @lang('role.delete_stock_adjustment')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all inventory_permission " data-target="daily_stock"
                                    autocomplete="off">
                                <strong>@lang('role.daily_stock')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock"
                                    class="daily_stock inventory_permission super_select_all">
                                @lang('role.daily_stock')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_index"
                                    class="daily_stock inventory_permission super_select_all">
                                @lang('role.daily_stock') @lang('role.list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_create"
                                    class="daily_stock inventory_permission super_select_all">
                                @lang('role.daily_stock') @lang('role.create')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_view"
                                    class="daily_stock inventory_permission super_select_all"> @lang('role.daily_stock')
                                @lang('role.details')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_update"
                                    class="daily_stock inventory_permission super_select_all">
                                @lang('role.daily_stock') @lang('role.update')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_delete"
                                    class="daily_stock inventory_permission super_select_all">
                                @lang('role.daily_stock') @lang('role.details')
                            </p>

                            {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="stock_out_report" class="daily_stock inventory_permission super_select_all">
                                            Stock out report
                                        </p> --}}
                        </div>

                        <hr class="my-2">

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox"
                                    class="select_all super_select_all inventory_permission super_select_all"
                                    data-target="transfer_stock" autocomplete="off"><strong> @lang('role.transfer_stock')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="transfer_wh_to_bl"
                                    class="transfer_stock inventory_permission super_select_all"> @lang('role.transfer_stock')
                                @lang('role.wh_to_b_location')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="transfer_bl_wh"
                                    class="transfer_stock inventory_permission super_select_all"> @lang('role.transfer_stock')
                                @lang('role.s_location_to_wh')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox"
                                    class="select_all super_select_all inventory_permission super_select_all"
                                    data-target="inventory_report" autocomplete="off"><strong>
                                    @lang('role.inventory_report')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="stock_adjustment_report"
                                    class="inventory_report inventory_permission super_select_all">@lang('menu.stock_adjustment_report')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="stock_report"
                                    class="inventory_report inventory_permission super_select_all">@lang('menu.stock_report')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_stock_report"
                                    class="inventory_report inventory_permission super_select_all"> @lang('role.daily_stock_report')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="stock_in_out_report"
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
                                    data-target="banks" autocomplete="off"><strong> @lang('role.banks')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="banks_index"
                                    class="banks finance_permission super_select_all"> @lang('role.bank_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="banks_add"
                                    class="banks finance_permission super_select_all"> @lang('role.bank_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="banks_edit"
                                    class="banks finance_permission super_select_all"> @lang('role.bank_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="banks_edit"
                                    class="banks finance_permission super_select_all"> @lang('role.bank_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="account_groups" autocomplete="off"><strong> @lang('menu.account_groups')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="account_groups_index"
                                    class="account_groups finance_permission super_select_all"> @lang('role.account_group_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="account_groups_add"
                                    class="account_groups finance_permission super_select_all"> @lang('role.account_group_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="account_groups_edit"
                                    class="account_groups finance_permission super_select_all"> @lang('role.account_group_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="account_groups_delete"
                                    class="account_groups finance_permission super_select_all"> @lang('role.account_groups_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="accounts" autocomplete="off"><strong> @lang('menu.accounts')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="accounts_index"
                                    class="accounts finance_permission super_select_all"> @lang('role.account_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="accounts_ledger"
                                    class="accounts finance_permission super_select_all"> @lang('role.account_ledger')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="accounts_add"
                                    class="accounts finance_permission super_select_all"> @lang('role.account_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="accounts_edit"
                                    class="accounts finance_permission super_select_all"> @lang('role.account_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="accounts_delete"
                                    class="accounts finance_permission super_select_all"> @lang('role.account_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="cost_centres" autocomplete="off"><strong> @lang('menu.cost_centres')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centres_index"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centres_add"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centres_edit"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centres_delete"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_delete')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centre_categories_add"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_category_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centre_categories_edit"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_category_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cost_centre_categories_delete"
                                    class="cost_centres finance_permission super_select_all"> @lang('role.cost_centre_category_delete')
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
                                    class="chart_of_accounts finance_permission super_select_all"> @lang('role.view_chart_of_accounts')
                            </p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="receipts" autocomplete="off"><strong> @lang('role.receipts')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="receipts_index"
                                    class="receipts finance_permission super_select_all"> @lang('role.receipt_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="receipts_add"
                                    class="receipts finance_permission super_select_all"> @lang('role.receipt_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="receipts_edit"
                                    class="receipts finance_permission super_select_all"> @lang('role.receipt_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="receipts_delete"
                                    class="receipts finance_permission super_select_all"> @lang('role.receipt_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="payments" autocomplete="off"><strong> @lang('menu.payments')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="payments_index"
                                    class="payments finance_permission super_select_all"> @lang('role.payment_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="payments_add"
                                    class="payments finance_permission super_select_all"> @lang('role.payment_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="payments_edit"
                                    class="payments finance_permission super_select_all"> @lang('role.payment_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="payments_delete"
                                    class="payments finance_permission super_select_all"> @lang('role.payment_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="journals" autocomplete="off"><strong> @lang('menu.journals')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="journals_index"
                                    class="journals finance_permission super_select_all"> @lang('role.journal_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="journals_add"
                                    class="journals finance_permission super_select_all"> @lang('role.journal_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="journals_edit"
                                    class="journals finance_permission super_select_all"> @lang('role.journal_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="journals_delete"
                                    class="journals finance_permission super_select_all"> @lang('role.journal_delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all finance_permission"
                                    data-target="contras" autocomplete="off"><strong> @lang('role.contras')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="contras_index"
                                    class="contras finance_permission super_select_all"> @lang('role.contra_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="contras_add"
                                    class="contras finance_permission super_select_all"> @lang('role.contra_add')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="contras_edit"
                                    class="contras finance_permission super_select_all"> @lang('role.contra_edit')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="contras_delete"
                                    class="contras finance_permission super_select_all"> @lang('role.contra_delete')
                            </p>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all finance_permission" data-target="expenses"
                                    autocomplete="off"><strong> @lang('menu.expense')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="view_expense"
                                    class="expenses finance_permission super_select_all">
                                @lang('role.view_expense')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="add_expense"
                                    class="expenses finance_permission super_select_all">
                                @lang('menu.add_expense')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="edit_expense"
                                    class="expenses finance_permission super_select_all">
                                @lang('menu.edit_expense')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="delete_expense"
                                    class="expenses finance_permission super_select_all">
                                @lang('role.delete') @lang('menu.expense')
                            </p>
                            {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="expense_category" class="expenses finance_permission super_select_all"> expense categories
                                        </p>
                                        <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="category_wise_expense" class="expenses finance_permission super_select_all"> View category wise expense
                                        </p> --}}
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all finance_permission" data-target="incomes"
                                    autocomplete="off"><strong> @lang('menu.incomes')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="incomes_index"
                                    class="incomes finance_permission super_select_all">
                                @lang('menu.income_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="incomes_show"
                                    class="incomes finance_permission super_select_all">
                                @lang('menu.income_single_view')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="incomes_create"
                                    class="incomes finance_permission super_select_all">
                                @lang('menu.add_income')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="incomes_edit"
                                    class="incomes finance_permission super_select_all">
                                @lang('menu.edit_income')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="incomes_delete"
                                    class="incomes finance_permission super_select_all">
                                @lang('role.delete') @lang('menu.income')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all finance_permission" data-target="finance_report"
                                    autocomplete="off"><strong> @lang('menu.finance_report')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="balance_sheet"
                                    class="finance_report finance_permission super_select_all">@lang('menu.balance_sheet')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="trial_balance"
                                    class="finance_report finance_permission super_select_all">@lang('menu.trial_balance')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cash_flow"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.cash_flow')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="fund_flow"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.fund_flow')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="day_book"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.day_book')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="outstanding_receivables"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.outstanding_receivables')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="outstanding_payables"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.outstanding_payables')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="profit_loss_ac"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.profit_loss_account')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="daily_profit_loss"
                                    class="finance_report finance_permission super_select_all">@lang('menu.daily_profit_loss')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="expanse_report"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.expanse_report')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="income_report"
                                    class="finance_report finance_permission super_select_all"> @lang('menu.income_report')
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
                    <a data-bs-toggle="collapse" class="manufacturing_role" href="#collapseFive" href="">
                        @lang('menu.manufacturing_permissions')
                    </a>
                </div>
                <div id="collapseFive" class="collapse" data-bs-parent="#accordion">
                    <div class="element-body border-top">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info"><input type="checkbox"
                                        class="select_all super_select_all manufacturing_permission"
                                        data-target="manage_production" autocomplete="off"><strong> @lang('menu.manage')
                                        @lang('menu.production')</strong></strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="process_view"
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('menu.view_process')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="process_add"
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('menu.add_process')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="process_edit"
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('menu.edit_process')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="process_delete"
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
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('menu.view_production')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="production_add"
                                        class="manage_production manufacturing_permission super_select_all">@lang('menu.add_production')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="production_edit"
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('menu.edit_production')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="production_delete"
                                        class="manage_production manufacturing_permission super_select_all">
                                    @lang('role.delete') @lang('menu.production')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="manuf_settings"
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
                                        class="menufacturing_report manufacturing_permission super_select_all">
                                    @lang('menu.process_report')
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="manuf_report"
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
                <input type="checkbox" class="communication_check select_all super_select_all communication_permission"
                    data-target="communication_permission" autocomplete="off">
                <a data-bs-toggle="collapse" class="communication_role" href="#collapseSix" href="">
                    @lang('menu.communication_permissions')
                </a>
            </div>
            <div id="collapseSix" class="collapse" data-bs-parent="#accordion">
                <div class="element-body border-top">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all communication_permission"
                                    data-target="communication" autocomplete="off"><strong>@lang('menu.communication')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="notice_board"
                                    class="communication super_select_all communication_permission">@lang('menu.notice_board')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="email"
                                    class="communication super_select_all communication_permission"> @lang('menu.email')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="email_settings"
                                    class="communication super_select_all communication_permission"> @lang('menu.email')
                                @lang('menu.settings')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="sms"
                                    class="communication super_select_all communication_permission"> @lang('menu.sms')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="sms_settings"
                                    class="communication super_select_all communication_permission"> @lang('menu.sms_settings')
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form_element rounded mt-0 mb-1">
            <div class="accordion-header">
                <input type="checkbox" class="utilities_check select_all super_select_all utilities_permission "
                    data-target="utilities_permission" autocomplete="off">
                <a data-bs-toggle="collapse" class="utilities_role" href="#collapseSeven" href="">
                    @lang('menu.utilities_permissions')
                </a>
            </div>
            <div id="collapseSeven" class="collapse" data-bs-parent="#accordion">
                <div class="element-body border-top">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all utilities_permission"
                                    data-target="utilities" autocomplete="off"><strong> @lang('menu.utilities')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="media"
                                    class="utilities utilities_permission super_select_all">
                                @lang('menu.media')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="calender"
                                    class="utilities utilities_permission super_select_all">
                                @lang('menu.calender')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="announcement"
                                    class="utilities utilities_permission super_select_all">
                                @lang('menu.announcement')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="activity_log"
                                    class="utilities utilities_permission super_select_all">
                                @lang('menu.activity_log')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="database_backup"
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
                                    class="asset asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_create"
                                    class="asset asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.create')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_view"
                                    class="asset asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_update"
                                    class="asset asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_delete"
                                    class="asset asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.delete')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_settings"
                                    class="asset asset_permission super_select_all">
                                @lang('menu.asset') @lang('menu.settings')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission" data-target="asset_allocation"
                                    autocomplete="off"> <strong>@lang('menu.asset')
                                    @lang('role.allocation')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_allocation_index"
                                    class="asset_allocation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.allocation ') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_allocation_create"
                                    class="asset_allocation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.allocation') @lang('menu.create')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_allocation_view"
                                    class="asset_allocation asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.allocation ') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_allocation_update"
                                    class="asset_allocation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.allocation') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_allocation_delete"
                                    class="asset_allocation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.allocation') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission "
                                    data-target="asset_depreciation" autocomplete="off"> <strong>@lang('menu.asset')
                                    @lang('role.depreciation')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_depreciation_index"
                                    class="asset_depreciation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.depreciation') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_depreciation_create"
                                    class="asset_depreciation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.depreciation') @lang('menu.create')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_depreciation_view"
                                    class="asset_depreciation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.depreciation') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_depreciation_update"
                                    class="asset_depreciation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.depreciation') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_depreciation_delete"
                                    class="asset_depreciation asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.depreciation') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_licenses"
                                    autocomplete="off"> <strong>@lang('menu.asset') @lang('role.licenses')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_index"
                                    class="asset_licenses asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.licenses') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_create"
                                    class="asset_licenses asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.licenses') @lang('menu.create')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_view"
                                    class="asset_licenses asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.licenses') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_update"
                                    class="asset_licenses asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.licenses') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_delete"
                                    class="asset_licenses asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.licenses') @lang('role.delete')
                            </p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission "
                                    data-target="asset_manufacturer" autocomplete="off"> <strong>@lang('menu.asset')
                                    @lang('role.manufacturer')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_manufacturer_index"
                                    class="asset_manufacturer asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.manufacturer') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_manufacturer_create"
                                    class="asset_manufacturer asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.manufacturer') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_manufacturer_view"
                                    class="asset_manufacturer asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.manufacturer') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_manufacturer_update"
                                    class="asset_manufacturer asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.manufacturer') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_manufacturer_delete"
                                    class="asset_manufacturer asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.manufacturer') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_categories"
                                    autocomplete="off"> <strong>@lang('menu.asset')
                                    @lang('role.categories')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_categories_index"
                                    class="asset_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.categories') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_categories_create"
                                    class="asset_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.categories') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_categories_view"
                                    class="asset_categories asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.categories') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_categories_update"
                                    class="asset_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.categories') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_categories_delete"
                                    class="asset_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.categories') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_locations"
                                    autocomplete="off"> <strong>@lang('menu.asset')
                                    @lang('role.locations')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_locations_index"
                                    class="asset_locations asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.locations') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_locations_create"
                                    class="asset_locations asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.locations') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_locations_view"
                                    class="asset_locations asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.locations') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_locations_update"
                                    class="asset_locations asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.locations') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_locations_delete"
                                    class="asset_locations asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.locations') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_units"
                                    autocomplete="off">
                                <strong> @lang('menu.asset') @lang('role.units')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_units_index"
                                    class="asset_units asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.units')
                                @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_units_create"
                                    class="asset_units asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.units')
                                @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_units_view"
                                    class="asset_units asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.units')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_units_update"
                                    class="asset_units asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.units')
                                @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_units_delete"
                                    class="asset_units asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.units')
                                @lang('role.delete')
                            </p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_requests"
                                    autocomplete="off"> <strong> @lang('menu.asset') @lang('role.requests')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_requests_index"
                                    class="asset_requests asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.requests') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_requests_create"
                                    class="asset_requests asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.requests') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_requests_view"
                                    class="asset_requests asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.requests') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_requests_update"
                                    class="asset_requests asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.requests') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_requests_delete"
                                    class="asset_requests asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.requests') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_warranties"
                                    autocomplete="off"> <strong> @lang('menu.asset')
                                    @lang('role.warranties')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_warranties_index"
                                    class="asset_warranties asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.warranties') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_warranties_create"
                                    class="asset_warranties asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.warranties') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_warranties_view"
                                    class="asset_warranties asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.warranties') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_warranties_update"
                                    class="asset_warranties asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.warranties') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_warranties_delete"
                                    class="asset_warranties asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.warranties') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_audits"
                                    autocomplete="off">
                                <strong>@lang('menu.asset') @lang('role.audits')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_audits_index"
                                    class="asset_audits asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.audits')
                                @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_audits_create"
                                    class="asset_audits asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.audits') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_audits_view"
                                    class="asset_audits asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.audits')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_audits_update"
                                    class="asset_audits asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.audits') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_audits_delete"
                                    class="asset_audits asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.audits') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_revokes"
                                    autocomplete="off">
                                <strong> @lang('menu.asset') @lang('role.revokes')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_revokes_index"
                                    class="asset_revokes asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.revokes') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_revokes_create"
                                    class="asset_revokes asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.revokes') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_revokes_view"
                                    class="asset_revokes asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.revokes') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_revokes_update"
                                    class="asset_revokes asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.revokes') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_revokes_delete"
                                    class="asset_revokes asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.revokes') @lang('role.delete')
                            </p>
                        </div>
                    </div>
                    <hr class="my-2">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission " data-target="asset_components"
                                    autocomplete="off">
                                <strong> @lang('menu.asset') @lang('role.components')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_components_index"
                                    class="asset_components asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.components') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_components_create"
                                    class="asset_components asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.components') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_components_view"
                                    class="asset_components asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.components') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_components_update"
                                    class="asset_components asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.components') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_components_delete"
                                    class="asset_components asset_permission super_select_all"> @lang('menu.asset')
                                @lang('role.components') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission "
                                    data-target="asset_licenses_categories" autocomplete="off">
                                <strong> @lang('menu.asset') licenses categories</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_categories_index"
                                    class="asset_licenses_categories asset_permission super_select_all">
                                @lang('menu.asset') licenses categories @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_categories_create"
                                    class="asset_licenses_categories asset_permission super_select_all">
                                @lang('menu.asset') licenses categories @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_categories_view"
                                    class="asset_licenses_categories asset_permission super_select_all">
                                @lang('menu.asset') licenses categories @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_categories_update"
                                    class="asset_licenses_categories asset_permission super_select_all">
                                @lang('menu.asset') licenses categories @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_licenses_categories_delete"
                                    class="asset_licenses_categories asset_permission super_select_all">
                                @lang('menu.asset') licenses categories @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission "
                                    data-target="asset_terms_and_conditions" autocomplete="off">
                                <strong> @lang('menu.asset') terms and condition</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_terms_and_conditions_index"
                                    class="asset_terms_and_conditions asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_terms_and_conditions_create"
                                    class="asset_terms_and_conditions asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_terms_and_conditions_view"
                                    class="asset_terms_and_conditions asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_terms_and_conditions_update"
                                    class="asset_terms_and_conditions asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_terms_and_conditions_delete"
                                    class="asset_terms_and_conditions asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all asset_permission "
                                    data-target="asset_term_condition_categories" autocomplete="off"> <strong>
                                    @lang('menu.asset') @lang('role.terms_and_condition')
                                    @lang('role.category')</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_term_condition_categories_index"
                                    class="asset_term_condition_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_term_condition_categories_create"
                                    class="asset_term_condition_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_term_condition_categories_view"
                                    class="asset_term_condition_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_term_condition_categories_update"
                                    class="asset_term_condition_categories asset_permission super_select_all">
                                @lang('menu.asset') @lang('role.terms_and_condition') @lang('role.category') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="asset_term_condition_categories_delete"
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

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all lc_permission " data-target="opening_lc"
                                    autocomplete="off"><strong>@lang('role.opening_lc')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc"
                                    class="opening_lc lc_permission super_select_all"> @lang('role.opening_lc')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc_index"
                                    class="opening_lc lc_permission super_select_all"> @lang('role.opening_lc')
                                @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc_create"
                                    class="opening_lc lc_permission super_select_all"> @lang('role.opening_lc')
                                @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc_view"
                                    class="opening_lc lc_permission super_select_all"> @lang('role.opening_lc')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc_update"
                                    class="opening_lc lc_permission super_select_all">
                                @lang('role.opening_lc') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="opening_lc_delete"
                                    class="opening_lc lc_permission super_select_all">
                                @lang('role.opening_lc') @lang('role.delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all lc_permission "
                                    data-target="import_purchase_order" autocomplete="off">
                                <strong>@lang('role.import') @lang('role.purchase') @lang('menu.order')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order"
                                    class="import_purchase_order lc_permission super_select_all">
                                @lang('role.import') @lang('role.purchase') @lang('menu.order')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order_index"
                                    class="import_purchase_order lc_permission super_select_all">
                                @lang('role.import') @lang('role.purchase') @lang('menu.order') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order_create"
                                    class="import_purchase_order lc_permission super_select_all">
                                @lang('role.import') @lang('role.purchase') @lang('menu.order') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order_view"
                                    class="import_purchase_order lc_permission super_select_all"> @lang('role.import')
                                @lang('role.purchase') @lang('menu.order')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order_update"
                                    class="import_purchase_order lc_permission super_select_all">
                                @lang('role.import') @lang('role.purchase') @lang('menu.order') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="import_purchase_order_delete"
                                    class="import_purchase_order lc_permission super_select_all">
                                @lang('role.import') @lang('role.purchase') @lang('menu.order') @lang('role.delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all lc_permission " data-target="exporters"
                                    autocomplete="off">
                                <strong>@lang('menu.exporters')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters"
                                    class="exporters lc_permission super_select_all">
                                @lang('menu.exporters')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters_index"
                                    class="exporters lc_permission super_select_all">
                                @lang('menu.exporters') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters_create"
                                    class="exporters lc_permission super_select_all">
                                @lang('menu.exporters') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters_view"
                                    class="exporters lc_permission super_select_all"> @lang('menu.exporters')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters_update"
                                    class="exporters lc_permission super_select_all">
                                @lang('menu.exporters') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="exporters_delete"
                                    class="exporters lc_permission super_select_all">
                                @lang('menu.exporters') @lang('role.delete')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all lc_permission " data-target="insurance_companies"
                                    autocomplete="off">
                                <strong>@lang('role.insurance_companies')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies"
                                    class="insurance_companies lc_permission super_select_all">
                                @lang('role.insurance_companies')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies_index"
                                    class="insurance_companies lc_permission super_select_all">
                                @lang('role.insurance_companies') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies_create"
                                    class="insurance_companies lc_permission super_select_all">
                                @lang('role.insurance_companies') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies_view"
                                    class="insurance_companies lc_permission super_select_all"> @lang('role.insurance_companies')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies_update"
                                    class="insurance_companies lc_permission super_select_all">
                                @lang('role.insurance_companies') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="insurance_companies_delete"
                                    class="insurance_companies lc_permission super_select_all">
                                @lang('role.insurance_companies') @lang('role.delete')
                            </p>
                        </div>

                        <hr class="my-2">

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all lc_permission" data-target="cnf_agents"
                                    autocomplete="off">
                                <strong>@lang('role.cnf_agent')</strong>
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents"
                                    class="cnf_agents lc_permission super_select_all">
                                @lang('role.cnf_agent')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents_index"
                                    class="cnf_agents lc_permission super_select_all">
                                @lang('role.cnf_agent') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents_create"
                                    class="cnf_agents lc_permission super_select_all">
                                @lang('role.cnf_agent') @lang('role.list')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents_view"
                                    class="cnf_agents lc_permission super_select_all"> @lang('role.cnf_agent')
                                @lang('role.detail')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents_update"
                                    class="cnf_agents lc_permission super_select_all">
                                @lang('role.cnf_agent') @lang('role.update')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cnf_agents_delete"
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
                    <input type="checkbox" class="project_check select_all super_select_all project_permission"
                        data-target="project_permission" autocomplete="off">
                    <a data-bs-toggle="collapse" class="project_role" href="#collapseNine" href="">
                        @lang('role.project_management_permissions')
                    </a>
                </div>
                <div id="collapseNine" class="collapse" data-bs-parent="#accordion">
                    <div class="element-body border-top">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all project_permission"
                                        data-target="manage_task" autocomplete="off"><strong>
                                        @lang('menu.manage') @lang('menu.task')</strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="assign_todo"
                                        class="manage_task project_permission super_select_all">

                                    @lang('menu.todo')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="work_space"
                                        class="manage_task project_permission super_select_all">

                                    @lang('role.work_space')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="memo"
                                        class="manage_task project_permission super_select_all">
                                    @lang('menu.memo')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="msg"
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
                                    data-target="settings" autocomplete="off"> <strong>@lang('menu.settings')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="g_settings"
                                    class="settings setup_permission super_select_all">
                                @lang('menu.general')
                                @lang('role.settings')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="p_settings"
                                    class="settings setup_permission super_select_all">
                                @lang('menu.payment')
                                @lang('role.settings')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="barcode_settings"
                                    class="settings setup_permission super_select_all">

                                @lang('menu.barcode') @lang('role.settings')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="reset"
                                    class="settings setup_permission super_select_all">
                                @lang('menu.reset')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info">
                                <input type="checkbox" class="select_all super_select_all setup_permission "
                                    data-target="app_setup" autocomplete="off"> <strong> @lang('role.app_set_up')</strong>
                            </p>

                            {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="tax" class="app_setup setup_permission super_select_all">
                                            @lang('role.tax')
                                        </p> --}}

                            {{-- <p class="checkbox_input_wrap mt-1">
                                            <input type="checkbox" name="branch" class="app_setup setup_permission super_select_all">
                                            @lang('role.business_location')
                                        </p> --}}

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="warehouse"
                                    class="app_setup setup_permission super_select_all">
                                @lang('role.warehouse')
                            </p>


                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="inv_sc"
                                    class="app_setup setup_permission super_select_all">
                                @lang('role.invoice_schemas')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="inv_lay"
                                    class="app_setup setup_permission super_select_all">
                                @lang('role.invoice_layout')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="cash_counters"
                                    class="app_setup setup_permission super_select_all">
                                @lang('role.cash_counters')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all setup_permission " data-target="users"
                                    autocomplete="off"><strong> @lang('menu.users')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="user_view"
                                    class="users setup_permission super_select_all">
                                @lang('menu.view_user')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="user_add" class="users setup_permission super_select_all"
                                    autocomplete="off">
                                @lang('role.add_user')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="user_edit"
                                    class="users setup_permission super_select_all" autocomplete="off">
                                @lang('menu.edit_user')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="user_delete"
                                    class="users setup_permission super_select_all" autocomplete="off">
                                @lang('role.delete') @lang('role.user')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><input type="checkbox"
                                    class="select_all super_select_all setup_permission " data-target="roles"
                                    autocomplete="off"><strong> Roles</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="role_view"
                                    class="roles setup_permission super_select_all">
                                @lang('role.view_role')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="role_add"
                                    class="roles setup_permission super_select_all">
                                @lang('role.add_role')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="role_edit"
                                    class="roles setup_permission super_select_all">
                                @lang('role.edit_role')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="role_delete"
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
                                    class="select_all super_select_all cash_permission" data-target="cash_register"
                                    autocomplete="off"><strong>
                                    @lang('role.cash_register')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="register_view"
                                    class="cash_register cash_permission super_select_all">

                                @lang('role.view') @lang('role.cash_register')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="register_close"
                                    class="cash_register cash_permission super_select_all">
                                @lang('role.cash_register')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="another_register_close"
                                    class="cash_register cash_permission super_select_all"> @lang('role.close_another')
                                @lang('role.cash_register')
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
                                    class="select_all super_select_all dashboard_permission" data-target="dashboard"
                                    autocomplete="off"><strong> @lang('menu.dashboard')</strong>
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="dash_data"
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
                    <a data-bs-toggle="collapse" class="hr_role" href="#collapseThirteen" href="">
                        @lang('role.human_resource_permissions')
                    </a>
                </div>
                <div id="collapseThirteen" class="collapse" data-bs-parent="#accordion">
                    <div class="element-body border-top">
                        <div class="row">

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_employee" autocomplete="off"><strong>
                                        {{ __('Employee') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_index"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_create"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_view"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_update"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Employee update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_delete"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Employee delete') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_master_list_index"
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
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Employee bulk import index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employees_bulk_import_store"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Employee bulk import store') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_id_card_print_index"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee Id card index') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_id_card_print"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee Id card print') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_appointment_with_select_letter_index"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee appointment letter with selection') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_appointment_with_select_letter_print"
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
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee appointment letter index') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_appointment_letter_print"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee appointment letter print') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_daily_attendance_update"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee promotion Index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_promotion_create"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee promotion create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_promotion_view"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee promotion view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_promotion_update"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee promotion update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_promotion_delete"
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
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Employee shift change index') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_change_action"
                                        class="hrm_employee human_permission super_select_all">

                                    {{ __('Employee shift change action') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_resigned_employee_index"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Resigned employee index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_left_employee_index"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Left employee index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_trashed_employee_index"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Trash employee index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_final_settlement_index"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Final settlement employee index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_final_settlement_action"
                                        class="hrm_employee human_permission super_select_all">
                                    {{ __('Final settlement employee action') }}
                                </p>

                            </div>
                            <hr class="my-2">

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_attendance" autocomplete="off"><strong>
                                        {{ __('Attendances') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_index"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_create"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_view"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_update"
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Attendance update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_delete"
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Attendance delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_person_wise_attendance_index"
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
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Section wise attendance index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_section_wise_attendance_store"
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Section wise attendance store') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_log_index"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance log list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_log_update"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance log update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_log_view"
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
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Daily attendance list') }}
                                </p>


                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_daily_attendance_view"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Daily attendance view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_daily_attendance_update"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Daily attendance edit') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_rapid_update"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Attendance rapid view') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_rapid_update_employee_wise"
                                        class="hrm_attendance human_permission super_select_all">

                                    {{ __('Employee wise attendance rapid update') }}
                                </p>



                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_rapid_update_date_wise"
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
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Bulk attendance import index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_bulk_attendance_import_text_file"
                                        class="hrm_attendance human_permission super_select_all">
                                    {{ __('Bulk attendance import text') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_bulk_attendance_import_index"
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
                                        class="hrm_department human_permission super_select_all">

                                    {{ __('Department List') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_departments_create"
                                        class="hrm_department human_permission super_select_all">

                                    {{ __('Department create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_departments_view"
                                        class="hrm_department human_permission super_select_all">
                                    {{ __('Department view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_departments_update"
                                        class="hrm_department human_permission super_select_all">
                                    {{ __('Department update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_departments_delete"
                                        class="hrm_department human_permission super_select_all">
                                    {{ __('Department delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_section" autocomplete="off"><strong>
                                        {{ __('Sections') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sections_index"
                                        class="hrm_section human_permission super_select_all">

                                    {{ __('Section list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sections_create"
                                        class="hrm_section human_permission super_select_all">

                                    {{ __('Section create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sections_view"
                                        class="hrm_section human_permission super_select_all">
                                    {{ __('Section view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sections_update"
                                        class="hrm_section human_permission super_select_all">
                                    {{ __('Section update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sections_delete"
                                        class="hrm_section human_permission super_select_all">
                                    {{ __('Section delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_sub_section" autocomplete="off"><strong>
                                        {{ __('Sub Sections') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sub_sections_index"
                                        class="hrm_sub_section human_permission super_select_all">

                                    {{ __('Subsection list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sub_sections_create"
                                        class="hrm_sub_section human_permission super_select_all">

                                    {{ __('Subsection create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sub_sections_view"
                                        class="hrm_sub_section human_permission super_select_all">

                                    {{ __('Subsection view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sub_sections_update"
                                        class="hrm_sub_section human_permission super_select_all">
                                    {{ __('Subsection update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_sub_sections_delete"
                                        class="hrm_sub_section human_permission super_select_all">
                                    {{ __('Subsection delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_designation" autocomplete="off"><strong>
                                        {{ __('Designations') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_designations_index"
                                        class="hrm_designation human_permission super_select_all">

                                    {{ __('Designation list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_designations_create"
                                        class="hrm_designation human_permission super_select_all">

                                    {{ __('Designation create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_designations_view"
                                        class="hrm_designation human_permission super_select_all">

                                    {{ __('Designation view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_designations_update"
                                        class="hrm_designation human_permission super_select_all">
                                    {{ __('Designation update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_designations_delete"
                                        class="hrm_designation human_permission super_select_all">
                                    {{ __('Designation delete') }}
                                </p>


                            </div>



                            <hr class="my-2">

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info"><input type="checkbox"
                                        class="select_all super_select_all human_permission" data-target="hrm_shift"
                                        autocomplete="off"><strong> {{ __('Shifts') }}</strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shifts_index"
                                        class="hrm_shift human_permission super_select_all">

                                    {{ __('Shift List') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shifts_create"
                                        class="hrm_shift human_permission super_select_all">

                                    {{ __('Shift create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shifts_view"
                                        class="hrm_shift human_permission super_select_all">
                                    {{ __('Shift view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shifts_update"
                                        class="hrm_shift human_permission super_select_all">
                                    {{ __('Shift update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shifts_delete"
                                        class="hrm_shift human_permission super_select_all">
                                    {{ __('Shift delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_shift_adjustment " autocomplete="off"><strong>
                                        {{ __('Shift adjustment') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_adjustments_index"
                                        class="hrm_shift_adjustment  human_permission super_select_all">

                                    {{ __('Shift adjustment list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_adjustments_create"
                                        class="hrm_shift_adjustment  human_permission super_select_all">

                                    {{ __('Shift adjustment create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_adjustments_view"
                                        class="hrm_shift_adjustment  human_permission super_select_all">
                                    {{ __('Shift adjustment view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_adjustments_update"
                                        class="hrm_shift_adjustment  human_permission super_select_all">
                                    {{ __('Shift adjustment update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_shift_adjustments_delete"
                                        class="hrm_shift_adjustment  human_permission super_select_all">
                                    {{ __('Shift adjustment delete') }}
                                </p>
                            </div>





                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_grade" autocomplete="off"><strong>
                                        {{ __('Grades') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_grades_index"
                                        class="hrm_grade human_permission super_select_all">

                                    {{ __('Grade list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_grades_create"
                                        class="hrm_grade human_permission super_select_all">

                                    {{ __('Grade create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_grades_view"
                                        class="hrm_grade human_permission super_select_all">

                                    {{ __('Grade view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_grades_update"
                                        class="hrm_grade human_permission super_select_all">
                                    {{ __('Grade update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_grades_delete"
                                        class="hrm_grade human_permission super_select_all">
                                    {{ __('Grade delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info"><input type="checkbox"
                                        class="select_all super_select_all human_permission" data-target="hrm_holidays"
                                        autocomplete="off"><strong> {{ __('Holidays') }}</strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_index"
                                        class="hrm_holidays human_permission super_select_all">

                                    {{ __('Holidays List') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_create"
                                        class="hrm_holidays human_permission super_select_all">

                                    {{ __('Holidays create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_view"
                                        class="hrm_holidays human_permission super_select_all">
                                    {{ __('Holidays view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_update"
                                        class="hrm_holidays human_permission super_select_all">
                                    {{ __('Holidays update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_delete"
                                        class="hrm_holidays human_permission super_select_all">
                                    {{ __('Holidays delete') }}
                                </p>
                            </div>

                            {{-- kdjgklsjklds ojksd;lfjks;dlfj lskdrflwefkwel piweoprkowepk pwp[rkwepwep pwprqprqpir[pq [pqirpqp[riq[pri]]]]] --}}
                            <hr class="my-2">

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_holiday_calendar" autocomplete="off"><strong>
                                        {{ __('Holiday calendar') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_calendar_index"
                                        class="hrm_holiday_calendar human_permission super_select_all">

                                    {{ __('Holiday calendar list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_calendar_create"
                                        class="hrm_holiday_calendar human_permission super_select_all">

                                    {{ __('Holiday calendar create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_calendar_view"
                                        class="hrm_holiday_calendar human_permission super_select_all">

                                    {{ __('Holiday calendar view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_calendar_update"
                                        class="hrm_holiday_calendar human_permission super_select_all">
                                    {{ __('Holiday calendar update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_holidays_calendar_delete"
                                        class="hrm_holiday_calendar human_permission super_select_all">
                                    {{ __('Holiday calendar delete') }}
                                </p>

                            </div>


                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_leave_application" autocomplete="off"><strong>
                                        {{ __('Leave applications') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_applications_index"
                                        class="hrm_leave_application human_permission super_select_all">

                                    {{ __('Leave application list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_applications_create"
                                        class="hrm_leave_application human_permission super_select_all">

                                    {{ __('Leave application create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_applications_view"
                                        class="hrm_leave_application human_permission super_select_all">
                                    {{ __('Leave application view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_applications_update"
                                        class="hrm_leave_application human_permission super_select_all">
                                    {{ __('Leave application update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_applications_delete"
                                        class="hrm_leave_application human_permission super_select_all">
                                    {{ __('Leave application delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_view"
                                        class="hrm_leave_application human_permission super_select_all">

                                    {{ __('Leave register view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_register_view"
                                        class="hrm_leave_application human_permission super_select_all">

                                    {{ __('Leave register list') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_leave_type" autocomplete="off"><strong>
                                        {{ __('Leave types') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_types_index"
                                        class="hrm_leave_type human_permission super_select_all">

                                    {{ __('Leave type list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_types_create"
                                        class="hrm_leave_type human_permission super_select_all">

                                    {{ __('Leave type create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_types_view"
                                        class="hrm_leave_type human_permission super_select_all">

                                    {{ __('Leave type view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_types_update"
                                        class="hrm_leave_type human_permission super_select_all">
                                    {{ __('Leave type update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_types_delete"
                                        class="hrm_leave_type human_permission super_select_all">
                                    {{ __('Leave type delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_salary_settlement" autocomplete="off"><strong>
                                        {{ __('Salary settlement') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_settlement_index"
                                        class="hrm_salary_settlement human_permission super_select_all">

                                    {{ __('Salary settlement list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_settlement_create"
                                        class="hrm_salary_settlement human_permission super_select_all">

                                    {{ __('Salary settlement create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_settlement_view"
                                        class="hrm_salary_settlement human_permission super_select_all">

                                    {{ __('Salary settlement view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_settlement"
                                        class="hrm_salary_settlement human_permission super_select_all">
                                    {{ __('Salary settlement settlement') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_settlement_delete"
                                        class="hrm_salary_settlement human_permission super_select_all">
                                    {{ __('Salary settlement delete') }}
                                </p>
                            </div>
                            {{-- kdjgklsjklds ojksd;lfjks;dlfj lskdrflwefkwel piweoprkowepk pwp[rkwepwep pwprqprqpir[pq [pqirpqp[riq[pri]]]]] --}}
                            <hr class="my-2">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_amount_adjustmnet" autocomplete="off"><strong>
                                        {{ __('Amount adjusments') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustments_index"
                                        class="hrm_amount_adjustmnet human_permission super_select_all">

                                    {{ __('Amount adjusment list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustments_create"
                                        class="hrm_amount_adjustmnet human_permission super_select_all">

                                    {{ __('Amount adjusment create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustments_view"
                                        class="hrm_amount_adjustmnet human_permission super_select_all">

                                    {{ __('Amount adjusment view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustments_update"
                                        class="hrm_amount_adjustmnet human_permission super_select_all">
                                    {{ __('Amount adjusment update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustments_delete"
                                        class="hrm_amount_adjustmnet human_permission super_select_all">
                                    {{ __('Amount adjusment delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_overtime_adjustmnet" autocomplete="off"><strong>
                                        {{ __('Overtime adjusments') }}
                                    </strong>
                                </p>


                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_overtimeAdjustments_index"
                                        class="hrm_overtime_adjustmnet human_permission super_select_all">

                                    {{ __('Overtime adjusment list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_overtimeAdjustments_create"
                                        class="hrm_overtime_adjustmnet human_permission super_select_all">

                                    {{ __('Overtime adjusment create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_overtimeAdjustments_view"
                                        class="hrm_overtime_adjustmnet human_permission super_select_all">

                                    {{ __('Overtime adjusment view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_overtimeAdjustments_update"
                                        class="hrm_overtime_adjustmnet human_permission super_select_all">
                                    {{ __('Overtime adjusment update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_overtimeAdjustments_delete"
                                        class="hrm_overtime_adjustmnet human_permission super_select_all">
                                    {{ __('Overtime adjusment delete') }}
                                </p>
                            </div>


                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_tax_adjustmnet" autocomplete="off"><strong>
                                        {{ __('Tax adjusments') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employeeTaxAdjustments_index"
                                        class="hrm_tax_adjustmnet human_permission super_select_all">

                                    {{ __('Tax adjusment list') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employeeTaxAdjustments_create"
                                        class="hrm_tax_adjustmnet human_permission super_select_all">

                                    {{ __('Tax adjusment create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employeeTaxAdjustments_view"
                                        class="hrm_tax_adjustmnet human_permission super_select_all">

                                    {{ __('Tax adjusment view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employeeTaxAdjustments_update"
                                        class="hrm_tax_adjustmnet human_permission super_select_all">
                                    {{ __('Tax adjusment update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_employeeTaxAdjustments_delete"
                                        class="hrm_tax_adjustmnet human_permission super_select_all">
                                    {{ __('Tax adjusment delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_advances" autocomplete="off"><strong>
                                        {{ __('Salary advances') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_advances_index"
                                        class="hrm_advances human_permission super_select_all">

                                    {{ __('Salary advance list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_advances_create"
                                        class="hrm_advances human_permission super_select_all">

                                    {{ __('Salary advance create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_advances_view"
                                        class="hrm_advances human_permission super_select_all">

                                    {{ __('Salary advance view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_advances_update"
                                        class="hrm_advances human_permission super_select_all">

                                    {{ __('Salary advance update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salary_advances_delete"
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
                                        class="hrm_payment_types human_permission super_select_all">

                                    {{ __('payment type List') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payments_types_create"
                                        class="hrm_payment_types human_permission super_select_all">

                                    {{ __('payment type create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payments_types_view"
                                        class="hrm_payment_types human_permission super_select_all">
                                    {{ __('payment type view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payments_types_update"
                                        class="hrm_payment_types human_permission super_select_all">
                                    {{ __('payment type update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payments_types_delete"
                                        class="hrm_payment_types human_permission super_select_all">
                                    {{ __('payment type delete') }}
                                </p>

                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_el_payment" autocomplete="off"><strong>
                                        {{ __('Earned leave payments') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_payments_index"
                                        class="hrm_el_payment human_permission super_select_all">

                                    {{ __('Earned leave list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_payments_create"
                                        class="hrm_el_payment human_permission super_select_all">

                                    {{ __('Earned leave create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_payments_view"
                                        class="hrm_el_payment human_permission super_select_all">

                                    {{ __('Earned leave view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_payments_update"
                                        class="hrm_el_payment human_permission super_select_all">
                                    {{ __('Earned leave update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_payments_delete"
                                        class="hrm_el_payment human_permission super_select_all">
                                    {{ __('Earned leave delete') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_el_calculation_index"
                                        class="hrm_el_payment  human_permission super_select_all">

                                    {{ __('Earned leave calculation index') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_payroll" autocomplete="off"><strong>
                                        {{ __('Payroll') }}
                                    </strong>
                                </p>


                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payroll_index"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Payroll index') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payroll_salary_generate"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Salary generate') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payroll_payslip_generate"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Payslip generate') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_payroll_custom_excel"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Payroll custom excel') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_calculation_checker_jobVsSalary"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Job card vs salary calculation check') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_calculation_checker_summaryVsSalary"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('Summary vs salary calculation check') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_calculation_checker_allCalculation"
                                        class="hrm_payroll human_permission super_select_all">

                                    {{ __('All calculation check') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_job_card" autocomplete="off"><strong>
                                        {{ __('Job card') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_job_card"
                                        class="hrm_job_card human_permission super_select_all">
                                    {{ __('Job card index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_job_card_print"
                                        class="hrm_job_card human_permission super_select_all">
                                    {{ __('Employee wise job card print') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_attendance_job_summary_print"
                                        class="hrm_job_card human_permission super_select_all">
                                    {{ __('Job card summary print') }}
                                </p>
                            </div>
                            <hr class="my-2">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview" autocomplete="off"><strong>
                                        {{ __('Reqruitment') }}
                                    </strong>
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_interview_index"
                                        class="hrm_interview human_permission super_select_all">

                                    {{ __('Interview index') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_interview_create"
                                        class="hrm_interview human_permission super_select_all">

                                    {{ __('Interview create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_interview_show"
                                        class="hrm_interview human_permission super_select_all">

                                    {{ __('Interview view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_interview_update"
                                        class="hrm_interview human_permission super_select_all">
                                    {{ __('Interview update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_interview_delete"
                                        class="hrm_interview human_permission super_select_all">
                                    {{ __('Interview delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_job_on_boarding" autocomplete="off"><strong>
                                        {{ __('Job on boarding') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_job_onboarding_index"
                                        class="hrm_interview_job_on_boarding human_permission super_select_all">
                                    {{ __('Job on boarding Index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_job_onboarding_view"
                                        class="hrm_interview_job_on_boarding human_permission super_select_all">
                                    {{ __('Job on boarding view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_job_onboarding_download"
                                        class="hrm_interview_job_on_boarding human_permission super_select_all">
                                    {{ __('Job on boarding download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_job_onboarding_delete"
                                        class="hrm_interview_job_on_boarding human_permission super_select_all">
                                    {{ __('Job on boarding delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_job_onboarding_bulk_select_for_interview"
                                        class="hrm_interview_job_on_boarding human_permission super_select_all">
                                    {{ __('Job on boarding bulk select for interview') }}
                                </p>
                               
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_selected_for_interview" autocomplete="off"><strong>
                                        {{ __('Selected for interview') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_index"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Select for Interview list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_view"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Select for Interview view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_download"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Select for Interview download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_delete"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Select for Interview delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_bulk_mail_sent"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Bulk mail sent for interview') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_select_for_interview_select_email_format"
                                        class="hrm_interview_selected_for_interview human_permission super_select_all">
                                    {{ __('Select mail format for sending interview mail') }}
                                </p>
                               
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_already_mail_for_interview" autocomplete="off"><strong>
                                        {{ __('Mail for interview') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_already_mail_for_interview_index"
                                        class="hrm_interview_already_mail_for_interview human_permission super_select_all">
                                    {{ __('Already mail for applicant list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_already_mail_for_interview_view"
                                        class="hrm_interview_already_mail_for_interview human_permission super_select_all">
                                    {{ __('Already mail for applicant view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_already_mail_for_interview_download"
                                        class="hrm_interview_already_mail_for_interview human_permission super_select_all">
                                    {{ __('Already mail for applicant download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_already_mail_for_interview_delete"
                                        class="hrm_interview_already_mail_for_interview human_permission super_select_all">
                                    {{ __('Already mail for applicant delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_bulk_select_interview_participants"
                                        class="hrm_interview_already_mail_for_interview human_permission super_select_all">
                                    {{ __('Bulk select interview participants') }}
                                </p>
                            </div>
                            <hr class="my-2">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_participants" autocomplete="off"><strong>
                                        {{ __('Interview participants') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_participants_index"
                                        class="hrm_interview_participants human_permission super_select_all">
                                    {{ __('Interview participant list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_participants_index"
                                        class="hrm_interview_participants human_permission super_select_all">
                                    {{ __('Interview participant view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_participants_download"
                                        class="hrm_interview_participants human_permission super_select_all">
                                    {{ __('Interview participant download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_participants_delete"
                                        class="hrm_interview_participants human_permission super_select_all">
                                    {{ __('Interview participant delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_participants_bulk_for_final_select"
                                        class="hrm_interview_participants human_permission super_select_all">
                                    {{ __('Interview participant bulk for final select') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_final_selected_applicants" autocomplete="off"><strong>
                                        {{ __('Final selected applicants') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_final_select_index"
                                        class="hrm_interview_final_selected_applicants human_permission super_select_all">
                                    {{ __('Final selected applicant list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_final_select_view"
                                        class="hrm_interview_final_selected_applicants human_permission super_select_all">
                                    {{ __('Final selected applicant view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_final_select_download"
                                        class="hrm_interview_final_selected_applicants human_permission super_select_all">
                                    {{ __('Final selected applicant download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_final_select_delete"
                                        class="hrm_interview_final_selected_applicants human_permission super_select_all">
                                    {{ __('Final selected applicant delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_final_bulk_select_for_offer_letter"
                                        class="hrm_interview_final_selected_applicants human_permission super_select_all">
                                    {{ __('Final selected applicant bulk select for offer letter') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_applicants_offer_letter" autocomplete="off"><strong>
                                        {{ __('Applicants offer letter') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_offer_letter_index"
                                        class="hrm_interview_applicants_offer_letter human_permission super_select_all">
                                    {{ __('Applicant offer letter list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_offer_letter_view"
                                        class="hrm_interview_applicants_offer_letter human_permission super_select_all">
                                    {{ __('Applicant offer letter view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_offer_letter_download"
                                        class="hrm_interview_applicants_offer_letter human_permission super_select_all">
                                    {{ __('Applicant offer letter download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_offer_letter_delete"
                                        class="hrm_interview_applicants_offer_letter human_permission super_select_all">
                                    {{ __('Applicant offer letter delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_bulk_hired"
                                        class="hrm_interview_applicants_offer_letter human_permission super_select_all">
                                    {{ __('Applicant bulk hired') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_applicants_hired_list" autocomplete="off"><strong>
                                        {{ __('Applicants hired') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_hired_index"
                                        class="hrm_interview_applicants_hired_list human_permission super_select_all">
                                    {{ __('Applicant hired list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_hired_view"
                                        class="hrm_interview_applicants_hired_list human_permission super_select_all">
                                    {{ __('Applicant hired view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_hired_download"
                                        class="hrm_interview_applicants_hired_list human_permission super_select_all">
                                    {{ __('Applicant hired download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_hired_delete"
                                        class="hrm_interview_applicants_hired_list human_permission super_select_all">
                                    {{ __('Applicant hired delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicants_bulk_select_for_reject"
                                        class="hrm_interview_applicants_hired_list human_permission super_select_all">
                                    {{ __('Applicant bulk select for reject') }}
                                </p>
                            </div>
                            <hr class="my-2">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_applicants_convert_to_employee" autocomplete="off"><strong>
                                        {{ __('Convert to employee') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_convert_employee_index"
                                        class="hrm_interview_applicants_convert_to_employee human_permission super_select_all">
                                    {{ __('Convert employee list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_convert_employee_view"
                                        class="hrm_interview_applicants_convert_to_employee human_permission super_select_all">
                                    {{ __('Convert employee view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_convert_employee_download"
                                        class="hrm_interview_applicants_convert_to_employee human_permission super_select_all">
                                    {{ __('Convert employee download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_convert_employee_delete"
                                        class="hrm_interview_applicants_convert_to_employee human_permission super_select_all">
                                    {{ __('Convert employee delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_convert_employee_bulk_select_for_reject"
                                        class="hrm_interview_applicants_convert_to_employee human_permission super_select_all">
                                    {{ __('Convert employee bulk select for reject') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_applicants_reject_list" autocomplete="off"><strong>
                                        {{ __('Applicants reject') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicant_reject_index"
                                        class="hrm_interview_applicants_reject_list human_permission super_select_all">
                                    {{ __('Applicant reject list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicant_reject_view"
                                        class="hrm_interview_applicants_reject_list human_permission super_select_all">
                                    {{ __('Applicant reject view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicant_reject_download"
                                        class="hrm_interview_applicants_reject_list human_permission super_select_all">
                                    {{ __('Applicant reject download') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicant_reject_delete"
                                        class="hrm_interview_applicants_reject_list human_permission super_select_all">
                                    {{ __('Applicant reject delete') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_applicant_reject_bulk_permanent_delete"
                                        class="hrm_interview_applicants_reject_list human_permission super_select_all">
                                    {{ __('Applicant reject bulk permanently delete') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_schedule" autocomplete="off"><strong>
                                        {{ __('Interview schedule') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_schedule_index"
                                        class="hrm_interview_schedule human_permission super_select_all">
                                    {{ __('Interview schedule index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_schedule_create"
                                        class="hrm_interview_schedule human_permission super_select_all">
                                    {{ __('Interview schedule create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_schedule_view"
                                        class="hrm_interview_schedule human_permission super_select_all">
                                    {{ __('Interview schedule view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_schedule_update"
                                        class="hrm_interview_schedule human_permission super_select_all">
                                    {{ __('Interview schedule update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_schedule_delete"
                                        class="hrm_interview_schedule human_permission super_select_all">
                                    {{ __('Interview schedule delete') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_interview_question" autocomplete="off"><strong>
                                        {{ __('Interview question') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_question_index"
                                        class="hrm_interview_question human_permission super_select_all">
                                    {{ __('Interview question index') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_question_create"
                                        class="hrm_interview_question human_permission super_select_all">
                                    {{ __('Interview question create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_question_view"
                                        class="hrm_interview_question human_permission super_select_all">
                                    {{ __('Interview question view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_question_update"
                                        class="hrm_interview_question human_permission super_select_all">
                                    {{ __('Interview question update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_recruitment_interview_question_delete"
                                        class="hrm_interview_question human_permission super_select_all">
                                    {{ __('Interview question delete') }}
                                </p>
                            </div>
                            <hr class="my-2">
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_awards" autocomplete="off"><strong>
                                        {{ __('Awards') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_awards_index"
                                        class="hrm_awards human_permission super_select_all">

                                    {{ __('Awards list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_awards_create"
                                        class="hrm_awards human_permission super_select_all">

                                    {{ __('Awards create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_awards_view"
                                        class="hrm_awards human_permission super_select_all">
                                    {{ __('Awards view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_awards_update"
                                        class="hrm_awards human_permission super_select_all">
                                    {{ __('Awards update') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_awards_delete"
                                        class="hrm_awards human_permission super_select_all">
                                    {{ __('Awards delete') }}
                                </p>
                            </div>
                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_notice" autocomplete="off"><strong>
                                        {{ __('Notices') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_notice_index"
                                        class="hrm_notice human_permission super_select_all">

                                    {{ __('Notice list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_notice_create"
                                        class="hrm_notice human_permission super_select_all">

                                    {{ __('Notice create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_notice_view"
                                        class="hrm_notice human_permission super_select_all">

                                    {{ __('Notice view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_notice_update"
                                        class="hrm_notice human_permission super_select_all">
                                    {{ __('Notice update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_notice_delete"
                                        class="hrm_notice human_permission super_select_all">
                                    {{ __('Notice delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_visits" autocomplete="off"><strong>
                                        {{ __('Visits') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_visit_index"
                                        class="hrm_visits human_permission super_select_all">

                                    {{ __('Visit list') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_visit_create"
                                        class="hrm_visits human_permission super_select_all">

                                    {{ __('Visit create') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_visit_view"
                                        class="hrm_visits human_permission super_select_all">

                                    {{ __('Visit view') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_visit_update"
                                        class="hrm_visits human_permission super_select_all">
                                    {{ __('Visit update') }}
                                </p>

                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_visit_delete"
                                        class="hrm_visits human_permission super_select_all">
                                    {{ __('Visit delete') }}
                                </p>
                            </div>

                            <div class="col-lg-3 col-sm-6">
                                <p class="text-info">
                                    <input type="checkbox" class="select_all super_select_all human_permission "
                                        data-target="hrm_others_report" autocomplete="off"><strong>
                                        {{ __('Reports & others') }}
                                    </strong>
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_dashboard"
                                        class="hrm hrm_others_report human_permission super_select_all ">
                                    @lang('role.hrm_dashboard')
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_leave_application_report"
                                        class="hrm_others_report human_permission super_select_all">

                                    {{ __('Leave application report') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_salaryAdjustment_report"
                                        class="hrm_others_report human_permission super_select_all">

                                    {{ __('Salary adjustment report') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_absent_report"
                                        class="hrm_others_report human_permission super_select_all">

                                    {{ __('Daily attendance report') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_date_range_absent_checker"
                                        class="hrm_others_report human_permission super_select_all">

                                    {{ __('Date range absent checker') }}
                                </p>
                                <p class="checkbox_input_wrap mt-1">
                                    <input type="checkbox" name="hrm_organogram_index"
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
                <input type="checkbox" class="other_check select_all super_select_all weight_scale_permission"
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
                                    class="weight_scale weight_scale_permission super_select_all">
                                @lang('menu.weight_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="single_view_weight_scale"
                                    class="weight_scale weight_scale_permission super_select_all">
                                @lang('menu.weight_single_view')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="add_weight_scale"
                                    class="weight_scale weight_scale_permission super_select_all">
                                @lang('menu.add_weight')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="delete_weight_scale"
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
                                    class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                @lang('menu.weight_scale_client_list')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="add_weight_scale_client"
                                    class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                @lang('menu.add_weight_scale_client')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="edit_weight_scale_client"
                                    class="weight_scale_clients weight_scale_clients_permission super_select_all">
                                @lang('menu.edit_weight_scale_client')
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="delete_weight_scale_client"
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
                                        class="select_all super_select_all others_permission" data-target="others">
                                    Others</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="print_invoice"
                                    class="others others_permission super_select_all">
                                @lang('menu.print_invoice')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="print_challan"
                                    class="others others_permission super_select_all">
                                @lang('menu.print_challan')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="print_weight"
                                    class="others others_permission super_select_all">
                                @lang('menu.print_weight')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="today_summery"
                                    class="others others_permission super_select_all">
                                Today summery
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="website_link"
                                    class="others others_permission super_select_all">
                                Website link
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="hrm_menu"
                                    class="others others_permission super_select_all">
                                HRM Menus
                            </p>

                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="modules_page"
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
                                        class="select_all super_select_all website_permission" data-target="client">
                                    Client</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_client"
                                    class="client website_permission super_select_all">
                                @lang('menu.manage_clients')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_client"
                                    class="client website_permission super_select_all">
                                @lang('menu.add_client')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_client"
                                    class="client website_permission super_select_all">
                                @lang('menu.edit_client')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_client"
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
                                    class="buyer_requisition website_permission super_select_all">
                                @lang('menu.show')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_requisition_delete"
                                    class="buyer_requisition website_permission super_select_all">
                                @lang('menu.delete')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="partners">
                                    Partners</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_partner"
                                    class="partners website_permission super_select_all">
                                @lang('menu.manage_partners')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_partner"
                                    class="partners website_permission super_select_all">
                                @lang('menu.add_partner')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_partner"
                                    class="partners website_permission super_select_all">
                                @lang('menu.edit_partner')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_partner"
                                    class="partners website_permission super_select_all">
                                @lang('menu.delete_partner')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="team">
                                    Teams</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_team"
                                    class="team website_permission super_select_all">
                                @lang('menu.manage_teams')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_team"
                                    class="team website_permission super_select_all">
                                @lang('menu.add_team')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_team"
                                    class="team website_permission super_select_all">
                                @lang('menu.edit_team')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_team"
                                    class="team website_permission super_select_all">
                                @lang('menu.delete_team')
                            </p>
                        </div>
                        <hr class="my-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="category">
                                    Category</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_category"
                                    class="category website_permission super_select_all">
                                @lang('menu.manage_categorys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_category"
                                    class="category website_permission super_select_all">
                                @lang('menu.add_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_category"
                                    class="category website_permission super_select_all">
                                @lang('menu.edit_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_category"
                                    class="category website_permission super_select_all">
                                @lang('menu.delete_category')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="product">
                                    Product</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_product"
                                    class="product website_permission super_select_all">
                                @lang('menu.manage_products')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_product"
                                    class="product website_permission super_select_all">
                                @lang('menu.add_product')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_product"
                                    class="product website_permission super_select_all">
                                @lang('menu.edit_product')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_product"
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
                                    class="job_category website_permission super_select_all">
                                @lang('menu.manage_job_categorys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_job_category"
                                    class="job_category website_permission super_select_all">
                                @lang('menu.add_job_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_job_category"
                                    class="job_category website_permission super_select_all">
                                @lang('menu.edit_job_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_job_category"
                                    class="job_category website_permission super_select_all">
                                @lang('menu.delete_job_category')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="job">
                                    Job</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_job"
                                    class="job website_permission super_select_all">
                                @lang('menu.manage_jobs')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_job"
                                    class="job website_permission super_select_all">
                                @lang('menu.add_job')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_job"
                                    class="job website_permission super_select_all">
                                @lang('menu.edit_job')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_job"
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
                                    class="job_applied website_permission super_select_all">
                                @lang('menu.download')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_job_applied_delete"
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
                                    class="gallery_category website_permission super_select_all">
                                @lang('menu.manage_categorys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_gallery_category"
                                    class="gallery_category website_permission super_select_all">
                                @lang('menu.add_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_gallery_category"
                                    class="gallery_category website_permission super_select_all">
                                @lang('menu.edit_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_gallery_category"
                                    class="gallery_category website_permission super_select_all">
                                @lang('menu.delete_category')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="gallery">
                                    Gallery</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_gallery"
                                    class="gallery website_permission super_select_all">
                                @lang('menu.manage_gallerys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_gallery"
                                    class="gallery website_permission super_select_all">
                                @lang('menu.add_gallery')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_gallery"
                                    class="gallery website_permission super_select_all">
                                @lang('menu.edit_gallery')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_gallery"
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
                                    class="blog_category website_permission super_select_all">
                                @lang('menu.manage_blog_categorys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_blog_category"
                                    class="blog_category website_permission super_select_all">
                                @lang('menu.add_blog_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_blog_category"
                                    class="blog_category website_permission super_select_all">
                                @lang('menu.edit_blog_category')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_blog_category"
                                    class="blog_category website_permission super_select_all">
                                @lang('menu.delete_blog_category')
                            </p>
                        </div>
                        <hr class="my-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="blog">
                                    Blog</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_blog"
                                    class="blog website_permission super_select_all">
                                @lang('menu.manage_blogs')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_blog"
                                    class="blog website_permission super_select_all">
                                @lang('menu.add_blog')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_blog"
                                    class="blog website_permission super_select_all">
                                @lang('menu.edit_blog')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_blog"
                                    class="blog website_permission super_select_all">
                                @lang('menu.delete_blog')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="comment">
                                    Comments</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_comment"
                                    class="comment website_permission super_select_all">
                                @lang('menu.manage_comments')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_comment"
                                    class="comment website_permission super_select_all">
                                @lang('menu.edit_comment')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_comment"
                                    class="comment website_permission super_select_all">
                                @lang('menu.delete_comment')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="page">
                                    Pages</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_page"
                                    class="page website_permission super_select_all">
                                @lang('menu.manage_pages')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_page"
                                    class="page website_permission super_select_all">
                                @lang('menu.add_page')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_page"
                                    class="page website_permission super_select_all">
                                @lang('menu.edit_page')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_page"
                                    class="page website_permission super_select_all">
                                @lang('menu.delete_page')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_about_us"
                                    class="page website_permission super_select_all">
                                @lang('menu.about_us')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_history"
                                    class="page website_permission super_select_all">
                                @lang('menu.history')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_message_of_director"
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
                                    class="testimonial website_permission super_select_all">
                                @lang('menu.manage_testimonials')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_testimonial"
                                    class="testimonial website_permission super_select_all">
                                @lang('menu.add_testimonial')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_testimonial"
                                    class="testimonial website_permission super_select_all">
                                @lang('menu.edit_testimonial')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_testimonial"
                                    class="testimonial website_permission super_select_all">
                                @lang('menu.delete_testimonial')
                            </p>
                        </div>
                        <hr class="my-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="campaign">
                                    Campaign</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_campaign"
                                    class="campaign website_permission super_select_all">
                                @lang('menu.manage_campaigns')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_campaign"
                                    class="campaign website_permission super_select_all">
                                @lang('menu.add_campaign')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_campaign"
                                    class="campaign website_permission super_select_all">
                                @lang('menu.edit_campaign')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_campaign"
                                    class="campaign website_permission super_select_all">
                                @lang('menu.delete_campaign')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="faq">
                                    FAQ</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_faq"
                                    class="faq website_permission super_select_all">
                                @lang('menu.manage_faqs')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_faq"
                                    class="faq website_permission super_select_all">
                                @lang('menu.add_faq')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_faq"
                                    class="faq website_permission super_select_all">
                                @lang('menu.edit_faq')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_faq"
                                    class="faq website_permission super_select_all">
                                @lang('menu.delete_faq')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="buet_test">
                                    Buet Test</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_buet_test"
                                    class="buet_test website_permission super_select_all">
                                @lang('menu.manage_buet_tests')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_buet_test"
                                    class="buet_test website_permission super_select_all">
                                @lang('menu.add_buet_test')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_buet_test"
                                    class="buet_test website_permission super_select_all">
                                @lang('menu.edit_buet_test')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_buet_test"
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
                                    class="dealership_request website_permission super_select_all">
                                @lang('menu.manage_dealership_requests')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_dealership_request"
                                    class="dealership_request website_permission super_select_all">
                                @lang('menu.delete_dealership_request')
                            </p>
                        </div>
                        <hr class="my-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="slider">
                                    Slider</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_slider"
                                    class="slider website_permission super_select_all">
                                @lang('menu.manage_sliders')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_slider"
                                    class="slider website_permission super_select_all">
                                @lang('menu.add_slider')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_slider"
                                    class="slider website_permission super_select_all">
                                @lang('menu.edit_slider')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_slider"
                                    class="slider website_permission super_select_all">
                                @lang('menu.delete_slider')
                            </p>
                        </div>

                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="video">
                                    Video</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_video"
                                    class="video website_permission super_select_all">
                                @lang('menu.manage_videos')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_video"
                                    class="video website_permission super_select_all">
                                @lang('menu.add_video')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_video"
                                    class="video website_permission super_select_all">
                                @lang('menu.edit_video')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_video"
                                    class="video website_permission super_select_all">
                                @lang('menu.delete_video')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="country">
                                    Country</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_country"
                                    class="country website_permission super_select_all">
                                @lang('menu.manage_countrys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_country"
                                    class="country website_permission super_select_all">
                                @lang('menu.add_video')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_country"
                                    class="country website_permission super_select_all">
                                @lang('menu.edit_video')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_country"
                                    class="country website_permission super_select_all">
                                @lang('menu.delete_video')
                            </p>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="city">
                                    City</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_manage_city"
                                    class="city website_permission super_select_all">
                                @lang('menu.manage_citys')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_add_city"
                                    class="city website_permission super_select_all">
                                @lang('menu.add_city')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_edit_city"
                                    class="city website_permission super_select_all">
                                @lang('menu.edit_city')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="web_delete_city"
                                    class="city website_permission super_select_all">
                                @lang('menu.delete_city')
                            </p>
                        </div>
                        <hr class="my-2">
                        <div class="col-lg-3 col-sm-6">
                            <p class="text-info"><strong><input type="checkbox"
                                        class="select_all super_select_all website_permission" data-target="setting">
                                    Setting</strong></p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="general_setting"
                                    class="setting website_permission super_select_all">
                                @lang('menu.general_settings')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="seo"
                                    class="setting website_permission super_select_all">
                                @lang('menu.seo')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="social_link"
                                    class="setting website_permission super_select_all">
                                @lang('menu.social_link')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="banner"
                                    class="setting website_permission super_select_all">
                                @lang('menu.banner')
                            </p>
                            <p class="checkbox_input_wrap mt-1">
                                <input type="checkbox" name="contact"
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
                            class="fas fa-spinner"></i></button>
                    <button class="btn w-auto btn-success submit_button float-end ">@lang('menu.save')</button>
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
