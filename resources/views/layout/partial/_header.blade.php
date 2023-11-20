<header id="header">
    <div class="navigation" style="">
        <div class="panel__nav">
            <div class="top-menu">
                <div class="d-inline-flex align-items-center">
                    <div class="header-logo">
                        <a href="{{ route('dashboard.dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="Logo"></a>
                    </div>
                    <button class="left-sidebar-toggler"><i class="fa-light fa-bars"></i></button>
                    <button class="apps-panel-btn d-lg-inline-block d-none" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="See All Apps"><i class="fa-light fa-grid-2"></i></button>
                    @can('website_link')
                        <a href="{{ config('app.website_url') }}" target="_blank" class="view-websit-btn d-lg-inline-block d-none text-white" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="View Website"><span class="fa-light fa-globe"></span></a>
                    @endcan

                    <div class="dropdown create-dropdown">
                        <button class="create-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Tooltip on bottom">
                            <i class="fa-light fa-plus"></i>
                        </button>
                        <ul class="dropdown-menu short_create_btn_list">
                            <li><span class="d-block fw-500 px-2 py-1 fz-14">@lang('menu.quick_create')</span></li>
                            <hr class="m-0">
                            @if (auth()->user()->can('product_add'))
                                <li><a class="dropdown-item" href="{{ route('products.add.view') }}">@lang('menu.add_item')</a></li>
                            @endif

                            @if (auth()->user()->can('sale_order_add'))
                                <li><a class="dropdown-item" href="{{ route('sales.order.create') }}">@lang('menu.create_sales_order')</a></li>
                            @endif

                            @if (auth()->user()->can('create_add_sale'))
                                <li><a class="dropdown-item" href="{{ route('sales.create') }}">@lang('menu.create_direct_sale')</a>
                                </li>
                            @endif

                            @if (auth()->user()->can('do_to_final'))
                                <li><a class="dropdown-item" href="{{ route('sales.delivery.order.to.final') }}">@lang('menu.do_to_invoice')</a></li>
                            @endif

                            @if (auth()->user()->can('add_sales_return'))
                                <li><a class="dropdown-item" href="{{ route('sale.return.random.create') }}">@lang('menu.add_sales_return')</a></li>
                            @endif

                            @if (auth()->user()->can('create_requisition'))
                                <li><a class="dropdown-item" href="{{ route('purchases.requisition.create') }}">@lang('menu.add_requisition')</a></li>
                            @endif

                            @if (auth()->user()->can('receive_stocks_create'))
                                <li><a class="dropdown-item" href="{{ route('purchases.receive.stocks.create') }}">@lang('menu.create_receive_stock')</a></li>
                            @endif

                            @if (auth()->user()->can('purchase_add'))
                                <li><a class="dropdown-item" href="{{ route('purchases.create') }}">@lang('menu.add_purchase')</a></li>
                            @endif

                            @if (auth()->user()->can('stock_issue_create'))
                                <li><a class="dropdown-item" href="{{ route('stock.issue.create') }}">@lang('menu.add_stock_issue')</a></li>
                            @endif

                            @if (auth()->user()->can('purchase_by_scale_create'))
                                <li><a class="dropdown-item" href="{{ route('purchases.by.scale.create') }}">@lang('menu.purchase_by_scale')</a></li>
                            @endif

                            @if (auth()->user()->can('add_weight_scale'))
                                <li><a class="dropdown-item" href="{{ route('scale.create') }}">@lang('menu.add_weight')</a>
                                </li>
                            @endif

                            @if (auth()->user()->can('payments_add'))
                                <li><a class="dropdown-item" href="{{ route('vouchers.payments.create', 1) }}">@lang('menu.add_payment_single_entry')</a></li>

                                <li><a class="dropdown-item" href="{{ route('vouchers.payments.create', 2) }}">@lang('menu.add_payment_double_entry')</a></li>
                            @endif

                            @if (auth()->user()->can('receipts_add'))
                                <li><a class="dropdown-item" href="{{ route('vouchers.receipts.create', 1) }}">@lang('menu.add_receipt_single_entry')</a></li>

                                <li><a class="dropdown-item" href="{{ route('vouchers.receipts.create', 2) }}">@lang('menu.add_receipt_double_entry')</a></li>
                            @endif

                            @if (auth()->user()->can('journals_add'))
                                <li><a class="dropdown-item" href="{{ route('vouchers.journals.create') }}">@lang('menu.add_journal')</a></li>
                            @endif
                        </ul>
                    </div>

                    <div class="company-name">
                        <p class="text-uppercase">{{ json_decode($generalSettings->business, true)['shop_name'] }}</p>
                        <span><strong>FY :</strong> 1 Jun 2023 - 30 May 2024</span>
                    </div>
                </div>
                <div class="notify-menu">
                    <div class="head__content__sec">
                        <ul class="head__cn">

                            <li>
                                <form action="" class="search-bar" id="headerSearch">
                                    <input type="search" name="search" placeholder="search..." pattern=".*\S.*" required>
                                    <button class="search-btn" type="submit">
                                        <span><i class="far fa-search"></i></span>
                                    </button>
                                </form>
                            </li>

                            {{-- Today --}}
                            @if (auth()->user()->can('today_summery'))
                                <li class="top-icon font-weight-bolder">
                                    <a href="#" id="today_summery" class="">
                                        <b class="d-lg-block d-none">@lang('menu.today')</b>
                                        <i class="fa-thin fa-calendar d-lg-none"></i>
                                    </a>
                                </li>
                            @endif

                            {{-- Note --}}

                            <li class="top-icon"><a href="{{ route('note.index') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notes"><span class="fa-thin fa-note"></span></a></li>

                            {{-- Calculator --}}
                            <li class="top-icon d-lg-block d-none">
                                <a role="button" class="pos-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" aria-haspopup="true" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Calculator">
                                    <span class="fas fa-thin fa-calculator"></span>
                                </a>
                                <ul class="dropdown-menu p-0">
                                    <div class="dtb-calc-box">
                                        <div>
                                            <input type="text" id="dtbCalcResult" placeholder="0" autocomplete="off" readonly>
                                        </div>
                                        <table>
                                            <tr>
                                                <td>C</td>
                                                <td>CE</td>
                                                <td class="dtb-calc-oprator">/</td>
                                                <td class="dtb-calc-oprator">*</td>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>8</td>
                                                <td>9</td>
                                                <td class="dtb-calc-oprator">-</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>5</td>
                                                <td>6</td>
                                                <td class="dtb-calc-oprator">+</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>2</td>
                                                <td>3</td>
                                                <td rowspan="2" class="dtb-calc-sum">=</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">0</td>
                                                <td>.</td>
                                            </tr>
                                        </table>
                                    </div>
                                </ul>
                            </li>

                            {{-- Notification --}}
                            <li class="dropdown dp__top d-lg-flex display-none">
                                <span class="notify">12</span>
                                <a href="#" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notification">
                                    <span class="fas fa-thin fa-envelope px-1"></span>
                                </a>

                                <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 1</a>
                                    </li>

                                    <li>
                                        <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 2</a>
                                    </li>

                                    <li>
                                        <span class="fas fa-user dropdown__icon"></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 3</a>
                                    </li>
                                    <a href="#" class="btn__sub">@lang('menu.view_all')</a>
                                </ul>
                            </li>

                            {{-- <li class="dropdown dp__top">
                                <a href="#" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown">
                                    <span class="fa-thin fa-arrows-maximize"></span>
                                </a>
                            </li> --}}

                            {{-- Messages --}}
                            <li class="dropdown dp__top notification-dropdown">
                                <span class="notify">12</span>
                                <a href="#" class="top-icon" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Notification">
                                    <span class="far fa-thin fa-bell px-1"></span>
                                </a>
                                <ul class="dropdown-menu dropdown__main__menu " aria-labelledby="dropdownMenuButton1">

                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 1 <span>3
                                                @lang('menu.days') ago</span></a>
                                    </li>
                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#">@lang('menu.notification') 2 <span>3
                                                @lang('menu.days') ago</span></a>
                                    </li>
                                    <li>
                                        <span class="dropdown__icon"><i class="fas fa-user"></i></span> <a class="dropdown-item" href="#"> @lang('menu.notification') 3 <span>3
                                                @lang('menu.days') ago</span></a>
                                    </li>
                                    <a href="{{ route('notification.index') }}" class="btn__sub">@lang('menu.view_all')</a>
                                </ul>
                            </li>

                            {{-- Profile --}}
                            <li class="top-icon"><a href="#" id="powerButton" aria-haspopup="true" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="User"><span class="fas fa-thin fa-user px-1"></span></a></li>
                            {{-- <li class="top-icon"><a href="#" id="powerButton"><span class="fas fa-power-off"></span></a></li> --}}
                        </ul>
                    </div>
                </div>
                {{-- <div id="left_bar_toggle"><span class="fas fa-times-circle"></span></div> --}}
            </div>
        </div>
    </div>
    {{-- <div id="left_bar_toggle"><span class="fas fa-times-circle"></span></div> --}}
</header>

<div class="all-app-panel">
    <div class="app-search-box">
        <form action="">
            <input type="search" placeholder="search..." id="mySearch" onkeyup="myFunction()">
            <button><i class="fa-regular fa-magnifying-glass"></i></button>
        </form>
    </div>
    <div class="all-app-area" id="myMenu">
        @if (auth()->user()->can('customer_all') ||
                auth()->user()->can('customer_manage') ||
                auth()->user()->can('customer_import') ||
                auth()->user()->can('customer_group'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.customer')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('customer_all') ||
                            auth()->user()->can('customer_manage'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('contacts.customers.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.customer_list')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('customer_import'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('contacts.customers.import.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.import_customers')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('customer_group'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('customers.groups.index') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.customer_groups')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('create_add_sale') ||
                auth()->user()->can('view_sales') ||
                auth()->user()->can('sale_draft') ||
                auth()->user()->can('sale_quotation_list') ||
                auth()->user()->can('add_quotation') ||
                auth()->user()->can('sale_order_add') ||
                auth()->user()->can('sale_order_all') ||
                auth()->user()->can('do_all') ||
                auth()->user()->can('do_to_final') ||
                auth()->user()->can('sale_settings') ||
                auth()->user()->can('discounts'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.sale')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('create_add_sale'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.direct_sale')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('view_sales'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.manage_sales')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('add_quotation'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.quotations.create') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.add_quotation')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('sale_quotation_list'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.quotations') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.manage_quotation')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('sale_order_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.order.create') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.add_order')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('sale_order_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.order.index') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.manage_order')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('do_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.delivery.order.list') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.manage_delivery_order')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('do_to_final'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.delivery.order.to.final') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.do_to_invoice')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('discounts'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.discounts.index') }}"><i class="fa-thin fa-users"></i></a></span>
                            <span class="app-name">@lang('menu.manage_offers')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('pos_all') ||
                auth()->user()->can('pos_add'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.pos')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('pos_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.pos.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.point_of_sale')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('pos_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.pos.list') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.manage_pos_sale')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('view_sales_return') ||
                auth()->user()->can('add_sales_return'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.sales_return')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('add_sales_return'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sale.return.random.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.add_sales_return')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('view_sales_return'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.returns.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sale_return_list')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('add_new_recent_price') ||
                auth()->user()->can('all_previous_recent_price') ||
                auth()->user()->can('today_recent_price'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.recent_price')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('add_new_recent_price'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.recent.price.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.add_new_price')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('all_previous_recent_price'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.recent.price.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.all_pre_price')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('today_recent_price'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.recent.price.today') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.today_price')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('sales_report') ||
                auth()->user()->can('sales_return_report') ||
                auth()->user()->can('pro_sale_report'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.sales_report')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('sales_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sales.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sales_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('pro_sale_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sold.items.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sold_items_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('sales_order_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sales.order.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sales_order_report')</span>
                        </li>

                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sales.order.report.user.wise.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sr_wise_sales_order_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('ordered_item_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sales.ordered.items.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sales_ordered_items_report')</span>
                        </li>
                    @endif

                    <li>
                        <span class="app-wrap"><a href="{{ route('reports.sales.ordered.item.qty.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.order_item_report')</span>
                    </li>

                    @if (auth()->user()->can('do_vs_sales_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.do.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.do_report')</span>
                        </li>
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.do.vs.sales.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.do_vs_sale')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('sales_return_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sale.return.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sales_return_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('c_register_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.cash.registers.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.cash_register_reports')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('customer_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.customer.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.customer_reports')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('sale_settings') ||
                auth()->user()->can('pos_sale_settings'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.settings')</h3>
                </div>
                <ul>
                    {{-- @if (auth()->user()->can('sale_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.app.settings.sale.settings.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.sale_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('pos_sale_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('sales.app.settings.pos.settings.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.pos_settings')</span>
                        </li>
                    @endif --}}
                    <li>
                        <span class="app-wrap"><a href="#"><i class="fa-thin fa-users"></i></a></span>
                        <span class="app-name">@lang('menu.terms_and_condition')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('supplier_all') ||
                auth()->user()->can('supplier_import'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.supplier')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('supplier_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('contacts.supplier.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_supplier')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('supplier_import'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('contacts.suppliers.import.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.import_suppliers')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('create_requisition') ||
                auth()->user()->can('all_requisition'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.requisition')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('create_requisition'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.requisition.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_requisition')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('all_requisition'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.requisition.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.requisition_list')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('requisitions.departments.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.departments')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('requesters.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.requester')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('purchase_add') ||
                auth()->user()->can('purchase_all') ||
                auth()->user()->can('create_po') ||
                auth()->user()->can('all_po') ||
                auth()->user()->can('purchase_settings') ||
                auth()->user()->can('purchase_by_scale_index') ||
                auth()->user()->can('purchase_by_scale_create'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.purchase')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('purchase_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_purchase')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_list')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('create_po'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.order.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.add_purchase_order')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('all_po'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.order.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_order_list')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.product.list') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_item_list')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_by_scale_create'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.by.scale.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_by_sale')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_by_scale_index'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchases.by.scale.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_by_sale_list')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('purchase.settings') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_settings')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('stock_issue'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.stock_issue')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('stock_issue_create'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('stock.issue.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_stock_issue')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('stock_issue_index'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('stock.issue.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.manage_stock_issue')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('stock.issues.events.index') }}"><i class="fa-thin fa-users"></i></a></span>
                        <span class="app-name">@lang('menu.stock_issue_event')</span>
                    </li>
                </ul>
            </div>
        @endif

        @if (auth()->user()->can('view_purchase_return') ||
                auth()->user()->can('add_purchase_return'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.purchase_return')</h3>
                </div>
                <ul>
                    <li>
                        <span class="app-wrap"><a href="{{ route('purchases.returns.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.add_purchase_return')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('purchases.returns.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.purchase_return_list')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('purchase_report') ||
                auth()->user()->can('purchase_sale_report') ||
                auth()->user()->can('pro_purchase_report') ||
                auth()->user()->can('stock_issue_report') ||
                auth()->user()->can('stock_issue_item_report') ||
                auth()->user()->can('supplier_report'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.procurement_reports')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('requested_product_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.requested.products.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.requested_item_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('weighted_product_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.weighted.products.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.weighted_item_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.purchases.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('pro_purchase_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.product.purchases.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchased_items_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('purchase_sale_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.sales.purchases.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.purchase_sale_compare')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('stock_issue_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.stock.issue.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.Stock_issue_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('stock_issued_items_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.stock.issued.items.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.stock_issued_items_reports')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('supplier_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.supplier.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.supplier_reports')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('product_all') ||
                auth()->user()->can('product_add') ||
                auth()->user()->can('categories') ||
                auth()->user()->can('brand') ||
                auth()->user()->can('units') ||
                auth()->user()->can('variant') ||
                auth()->user()->can('warranties') ||
                auth()->user()->can('selling_price_group') ||
                auth()->user()->can('generate_barcode') ||
                auth()->user()->can('daily_stock_index'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.manage_item')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('product_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('products.add.view') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_item')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('product_all'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('products.all.product') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.item_list')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('product_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.import.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.import_item')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('categories'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.categories.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.categories')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('brand'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.brands.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.brands')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('units'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('products.units.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.units')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('variant'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.variants.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.variants')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('warranties'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.warranties.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.warranties')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('selling_price_group'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('product.selling.price.groups.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.selling_price_group')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('generate_barcode'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('barcode.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.generate_barcode')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('daily_stock_index') ||
                auth()->user()->can('daily_stock_create'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.daily_stock')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('daily_stock_create'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('daily.stock.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_daily_stock')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('daily_stock_index'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('daily.stock.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.manage_daily_stock')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('adjustment_all') ||
                auth()->user()->can('adjustment_add_from_location') ||
                auth()->user()->can('adjustment_add_from_warehouse'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.stock_adjustment')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('adjustment_add_from_location'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('stock.adjustments.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.form_b_location')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('adjustment_add_from_warehouse'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('stock.adjustments.create') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.form_warehouses')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('adjustment_add_from_location'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('stock.adjustments.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.stock_adjustment_list')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('transfer_wh_to_bl'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.transfer_wh_location')</h3>
                </div>
                <ul>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stock.to.branch.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.add_transfer')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stock.to.branch.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.transfer_list')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stocks.to.warehouse.receive.stock.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.stock_receive')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('transfer_bl_wh'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.transfer_b_loc_wh')</h3>
                </div>
                <ul>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stock.to.warehouse.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.add_transfer')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stock.to.warehouse.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.transfer_list')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('transfer.stocks.to.branch.receive.stock.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.stock_receive')</span>
                    </li>
                </ul>
            </div>
        @endif

        <div class="single-app">
            @if (auth()->user()->can('stock_report') ||
                    auth()->user()->can('stock_in_out_report') ||
                    auth()->user()->can('stock_adjustment_report') ||
                    auth()->user()->can('daily_stock_report'))
                <div class="title">
                    <h3>@lang('menu.inventory_reports')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('stock_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.stock.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.stock_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('stock_in_out_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.daily.stock.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.daily_stock_item_reports')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('stock_adjustment_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.stock.adjustments.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.stock_adjustment_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('daily_stock_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.stock.in.out.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.stock_in_out_report')</span>
                        </li>
                    @endif
                </ul>
            @endif
        </div>
        @if (auth()->user()->can('banks') ||
                auth()->user()->can('accounts') ||
                auth()->user()->can('assets') ||
                auth()->user()->can('contra'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.accounting')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('banks'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('accounting.banks.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.bank')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('accounts'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('accounting.accounts.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.accounts')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('vouchers.journals.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.journals')</span>
                    </li>
                    @if (auth()->user()->can('contra'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('vouchers.contras.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.contra')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif

        @if (auth()->user()->can('view_expense') ||
                auth()->user()->can('add_expense'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.expense')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('add_expense'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('vouchers.expenses.create', '1') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_expense')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('view_expense'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('vouchers.expenses.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.expense_list')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('incomes_index') ||
                auth()->user()->can('incomes_create'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.incomes')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('incomes_create'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('income.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_income')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('incomes_index'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('income.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.income_list')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('balance_sheet') ||
                auth()->user()->can('trial_balance') ||
                auth()->user()->can('cash_flow') ||
                auth()->user()->can('profit_loss_ac') ||
                auth()->user()->can('daily_profit_loss') ||
                auth()->user()->can('financial_report') ||
                auth()->user()->can('expanse_report'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.finance_report')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('balance_sheet'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.balance.sheet.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.balance_sheet')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('trial_balance'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.trial.balance.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.trial_balance')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('cash_flow'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.cash.flow.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.cash_flow')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('profit_loss_ac'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.profit.loss.account.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.profit_loss_account')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('daily_profit_loss'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.profit.loss.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.daily_profit_loss')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('expanse_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.expenses.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.expense_report')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('income_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.incomes.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.income_report')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        <div class="single-app">
            <div class="title">
                <h3>@lang('menu.lc_management')</h3>
            </div>
            <ul>
                @can('opening_lc')
                    <li>
                        <span class="app-wrap"><a href="{{ route('manage.lc.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.opening_lc')</span>
                    </li>
                @endcan
                @can('import_purchase_order')
                    <li>
                        <span class="app-wrap"><a href="{{ route('lc.imports.create') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.import_purchase_order')</span>
                    </li>
                @endcan

                <li>
                    <span class="app-wrap"><a href=""><i class="fa-thin fa-user"></i></a></span>
                    <span class="app-name">@lang('menu.manage_import_purchase')</span>
                </li>
                @can('exporters')
                    <li>
                        <span class="app-wrap"><a href="{{ route('lc.exporters.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.exporters')</span>
                    </li>
                @endcan

                @can('insurance_companies')
                    <li>
                        <span class="app-wrap"><a href="{{ route('lc.insurance.companies.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.insurance_companies')</span>
                    </li>
                @endcan
                @can('cnf_agents')
                    <li>
                        <span class="app-wrap"><a href="{{ route('lc.cnf.agents.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.cnf_gents')</span>
                    </li>
                @endcan
                <li>
                    <span class="app-wrap"><a href=""><i class="fa-thin fa-user"></i></a></span>
                    <span class="app-name">@lang('menu.reports')</span>
                </li>
            </ul>
        </div>
        @if (auth()->user()->can('process_view') ||
                auth()->user()->can('production_view') ||
                auth()->user()->can('manuf_settings'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.manage_production')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('process_view'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('manufacturing.process.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.processes')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('production_view'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('manufacturing.productions.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.productions')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('manuf_report') ||
                auth()->user()->can('process_report'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.manufacturing_report')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('process_report'))
                        <li>
                            <span class="app-wrap"><a href=""><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.process_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('manuf_report'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('manufacturing.report.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.production_report')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('manuf_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('manufacturing.settings.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.manufacturing_setting')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('notice_board') ||
                auth()->user()->can('email') ||
                auth()->user()->can('email_settings') ||
                auth()->user()->can('sms') ||
                auth()->user()->can('sms_settings'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.communication')</h3>
                </div>
                <ul>
                    <li>
                        <span class="app-wrap"><a href="{{ route('communication.contacts.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.contact_croup')</span>
                    </li>
                    @if (auth()->user()->can('email'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('communication.email.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.email')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('sms'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('communication.sms.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sms')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('communication.whatsapp.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.whatsapp')</span>
                    </li>
                    @if (auth()->user()->can('email_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('communication.email.settings') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.email_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('sms_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('communication.sms.settings') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.sms_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('notice_board'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('notice_boards.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.notice_board')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('assign_todo') ||
                auth()->user()->can('work_space') ||
                auth()->user()->can('memo') ||
                auth()->user()->can('msg'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.project_manage_dashboard')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('assign_todo'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('todo.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.todo')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('work_space'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('workspace.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.work_space')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('memo'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('memos.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.memo')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('msg'))
                        <li>
                            <span class="app-wrap"><a href="}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.message')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('media') ||
                auth()->user()->can('calender') ||
                auth()->user()->can('announcement') ||
                auth()->user()->can('activity_log') ||
                auth()->user()->can('change_log') ||
                auth()->user()->can('database_backup'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.utilities')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('media'))
                        <li>
                            <span class="app-wrap"><a href="#"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.media')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('calender'))
                        <li>
                            <span class="app-wrap"><a href="#"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.calender')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('announcement'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('announcements.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.announcement')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('activity_log'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('reports.user.activities.log.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.activity_log')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('change_log'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('change_log.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.change_log')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('database_backup'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('database-backup.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.database_backup')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('downloads.download.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.download_center')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('terms.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.terms_conditions')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->role_type == '1')
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.crm_app')</h3>
                </div>
                <ul>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.customers.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.customer')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="#"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.leads')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.source.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.source')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.life.stage.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">Life Stage</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.followup.category.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.followup_category')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.proposal_template.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.proposal_template')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.proposal.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('crm.proposal')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.settings.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('crm.settings')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.estimates.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('crm.estimate')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('crm.appointment.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('crm.appointment')</span>
                    </li>
                </ul>
            </div>
        @endif
        <div class="single-app">
            <div class="title">
                <h3>@lang('menu.asset')</h3>
            </div>
            <ul>
                @can('asset_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.dashboard') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                        <span class="app-name">@lang('menu.asset_dashboard')</span>
                    </li>
                @endcan
                @can('asset_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.asset')</span>
                    </li>
                @endcan
                @can('asset_allocation_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.allocation.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.allocations')</span>
                    </li>
                @endcan
                @can('asset_revokes_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.revoke.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.revokes')</span>
                    </li>
                @endcan
                @can('asset_depreciation_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.depreciation.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.depreciation')</span>
                    </li>
                @endcan
                @can('asset_requests_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.request.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.requests')</span>
                    </li>
                @endcan
                @can('asset_licenses_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.licenses.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.licenses')</span>
                    </li>
                @endcan
                <li>
                    <span class="app-wrap"><a href="{{ route('assets.supplier.index') }}"><i class="fa-thin fa-user"></i></a></span>
                    <span class="app-name">@lang('menu.suppliers')</span>
                </li>
                <li>
                    <span class="app-wrap"><a href="{{ route('assets.consume.services.index') }}"><i class="fa-thin fa-user"></i></a></span>
                    <span class="app-name">@lang('menu.consume_services')</span>
                </li>
                @can('asset_audits_index')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.audit.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.audit')</span>
                    </li>
                @endcan

                @can('asset_settings')
                    <li>
                        <span class="app-wrap"><a href="{{ route('assets.settings.index') }}"><i class="fa-thin fa-user"></i></a></span>
                        <span class="app-name">@lang('menu.settings')</span>
                    </li>
                @endcan
            </ul>
        </div>
        @if (auth()->user()->can('g_settings') ||
                auth()->user()->can('p_settings') ||
                auth()->user()->can('barcode_settings'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.settings')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('g_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.general.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.general_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('p_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.payment.method.settings.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.payment_method_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('barcode_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.barcode.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.barcode_settings')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('reset'))
                        <li>
                            <span class="app-wrap"><a href=""><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.reset')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('warehouse') ||
                auth()->user()->can('inv_sc') ||
                auth()->user()->can('inv_lay') ||
                auth()->user()->can('cash_counters'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.app_set_up')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('warehouse'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.warehouses.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.warehouses')</span>
                        </li>
                    @endif

                    @if (auth()->user()->can('p_settings'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.payment.method.index') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                            <span class="app-name">@lang('menu.payment_methods')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('inv_sc'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('invoices.schemas.index') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                            <span class="app-name">@lang('menu.invoice_schema')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('inv_lay'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('invoices.layouts.index') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                            <span class="app-name">@lang('menu.invoice_layout')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('cash_counters'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('settings.cash.counter.index') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                            <span class="app-name">@lang('menu.cash_counter')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('modules.control') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                        <span class="app-name">@lang('menu.control_modules')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('user_view') ||
                auth()->user()->can('user_add'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.users')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('user_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('users.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_new')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('user_view'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('users.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.all_user')</span>
                        </li>
                    @endif
                    <li>
                        <span class="app-wrap"><a href="{{ route('core.area.index') }}"><i class="fa-thin fa-users"></i></a></span>
                        <span class="app-name">@lang('menu.area_list')</span>
                    </li>
                    <li>
                        <span class="app-wrap"><a href="{{ route('core.area.create') }}"><i class="fa-thin fa-bag-shopping"></i></a></span>
                        <span class="app-name">@lang('menu.add_area')</span>
                    </li>
                </ul>
            </div>
        @endif
        @if (auth()->user()->can('role_view') ||
                auth()->user()->can('role_add'))
            <div class="single-app">
                <div class="title">
                    <h3>@lang('menu.roles')</h3>
                </div>
                <ul>
                    @if (auth()->user()->can('role_add'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('users.role.create') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                            <span class="app-name">@lang('menu.add_roles')</span>
                        </li>
                    @endif
                    @if (auth()->user()->can('role_view'))
                        <li>
                            <span class="app-wrap"><a href="{{ route('users.role.index') }}"><i class="fa-thin fa-user"></i></a></span>
                            <span class="app-name">@lang('menu.role_list')</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
        <div class="single-app">
            <div class="title">
                <h3>@lang('menu.feedback')</h3>
            </div>
            <ul>
                <li>
                    <span class="app-wrap"><a href="{{ route('feedback.index') }}"><i class="fa-thin fa-user-plus"></i></a></span>
                    <span class="app-name">@lang('menu.feedback')</span>
                </li>
            </ul>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.apps-panel-btn').on('click', function() {
                $('.all-app-panel').toggleClass('active');
            });

        });

        function myFunction() {
            var input, filter, ul, li, a, i;
            input = document.getElementById("mySearch");
            filter = input.value.toUpperCase();
            ul = document.getElementById("myMenu");
            li = ul.getElementsByTagName("li");
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {

                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }
    </script>
@endpush
