<style>
    .left-sidebar {
        position: fixed;
        top: 0;
        left: 0;
        width: 40px;
        max-width: 220px;
        height: 100%;
        background: #0D1F2A;
        border-right: 1px solid rgba(255, 255, 255, 0.2);
        display: flex;
        flex-wrap: wrap;
    }

    .left-sidebar.open-sub {
        width: 220px;
    }

    .left-sidebar .main-logo {
        width: 40px;
        height: 40px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
    }

    .left-sidebar .main-logo .logo-small {
        width: 100%;
        height: 100%;
        padding: 5px;
        display: flex;
        align-items: center;
    }

    .left-sidebar .main-logo .logo-big {
        display: none;
    }

    .left-sidebar.open-sub .main-logo {
        width: 220px;
    }

    .left-sidebar.open-sub .main-logo .logo-small {
        display: none;
    }

    .left-sidebar.open-sub .main-logo .logo-big {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 40px;
        padding: 8px;
    }

    .left-sidebar.open-sub .main-logo .logo-big img {
        max-width: 206px;
        max-height: 24px;
    }

    .left-sidebar .nav-arrow {
        display: none;
    }

    .left-sidebar .main-menu {
        width: 40px;
        height: calc(100% - 40px);
    }

    .left-sidebar .main-menu .main-nav-list {
        height: calc(100% - 80px);
        overflow: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .left-sidebar .main-menu .main-nav-list::-webkit-scrollbar {
        display: none;
    }

    .left-sidebar .main-menu .help-nav {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0px -5px 10px -5px rgba(255, 255, 255, 0.5);
    }

    .left-sidebar .main-menu .main-menu-link {
        display: block;
        height: 40px;
        line-height: 40px;
        text-align: center;
        color: #fff;
        font-size: 16px;
        position: relative;
    }

    .left-sidebar .main-menu .main-menu-link::before {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        right: 0;
        height: 1px;
        background: rgba(255, 255, 255, 0.05);
    }

    .left-sidebar .main-menu .main-menu-link .badge {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 8px;
        padding: 0 4px;
        height: 14px;
        line-height: 14px;
        min-width: 14px;
        border-radius: 7px;
        letter-spacing: 0.2px;
    }

    .left-sidebar .main-menu .main-menu-link::after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        width: 0;
        background: #70baff;
        transition: .3s;
    }

    .left-sidebar .main-menu .main-menu-link.active,
    .left-sidebar .main-menu .main-menu-link:hover {
        background: #3b3d58;
    }

    .left-sidebar .main-menu .main-menu-link.active::after,
    .left-sidebar .main-menu .main-menu-link:hover::after {
        width: 3px;
    }

    .left-sidebar .main-menu .main-menu-link.open {
        background: #3b3d58;
    }

    .left-sidebar .menu-txt {
        display: none;
    }

    .left-sidebar .submenu-panel {
        max-width: 178px;
        width: max-content;
        height: calc(100% - 40px);
    }

    .left-sidebar .submenu-panel .single-submenu {
        width: 179px;
        height: 100%;
        color: #fff;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        overflow: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
        display: none;
    }

    .left-sidebar .submenu-panel .single-submenu::-webkit-scrollbar {
        display: none;
    }

    .left-sidebar.open-sub .submenu-panel .single-submenu:first-child {
        /* display: block; */
    }

    .single-submenu .submenu-title {
        display: block;
        font-size: 14px;
        line-height: 100%;
        text-align: center;
        padding: 7px 0;
        background: #3b3d58;
        color: #fff;
        margin-bottom: 5px;
    }

    .single-submenu .submenu-group {
        border-left: 1px solid #3b3d58;
        border-bottom: 1px solid #3b3d58;
        border-top: 1px solid #3b3d58;
        background: #102634;
        margin-left: 5px;
        display: none;
    }

    .single-submenu .submenu-link {
        display: block;
        font-size: 11px;
        line-height: 2.5;
        color: #f3f3f3;
        padding: 0 5px;
    }

    .single-submenu .submenu-link.active {
        background: #3b3d58;
        color: #70baff;
    }

    .single-submenu .submenu-link.has-sub {
        position: relative;
    }

    .single-submenu .submenu-link.has-sub::after {
        position: absolute;
        content: "\f105";
        font-family: "Font Awesome 6 Pro";
        font-weight: 500;
        font-size: 10px;
        line-height: 100%;
        top: 50%;
        right: 5px;
        transform: translateY(-50%);
        transition: .3s;
    }

    .single-submenu .submenu-link.has-sub.open {
        color: #70baff;
    }

    .single-submenu .submenu-link.has-sub.open::after {
        transform: translateY(-50%) rotate(90deg);
    }

    .single-submenu .submenu-group .submenu-link {
        padding: 0 10px;
    }

    .single-submenu .submenu-group .submenu-link.active,
    .single-submenu .submenu-group .submenu-link:hover {
        background: #3b3d58;
    }

    .left-sidebar.horizontal-menu {
        top: 40px;
        width: 100%;
        max-width: none;
        background: #1b1b1b;
        border: 0;
        height: 30px;
        z-index: 1055;
    }

    .left-sidebar.horizontal-menu::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    }

    .left-sidebar.horizontal-menu .main-logo {
        display: none;
    }

    .left-sidebar.horizontal-menu .main-menu {
        width: 100%;
        height: 100%;
        display: flex;
        position: relative;
    }

    .left-sidebar.horizontal-menu .nav-arrow {
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        display: flex;
        justify-content: space-between;
        visibility: hidden;
    }

    .left-sidebar.horizontal-menu .nav-arrow button {
        width: 30px;
        height: 30px;
        visibility: visible;
        border: 0;
        background: linear-gradient(#494949, #181818);
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        border-right: 1px solid rgba(0, 0, 0, 0.9);
        box-shadow: 12px 0px 10px -7px rgb(199, 199, 199, 0.7);
        color: #fff;
        transition: .3s;
        z-index: 1;
    }

    .left-sidebar.horizontal-menu .nav-arrow button:hover {
        color: #70baff;
    }

    .left-sidebar.horizontal-menu .nav-arrow button:last-child {
        box-shadow: -12px 0px 10px -7px rgb(199, 199, 199, 0.7);
    }

    .left-sidebar.horizontal-menu .nav-arrow button:disabled {
        color: #7d7d7d;
        box-shadow: 0 0;
    }

    .left-sidebar.horizontal-menu .main-menu .main-nav-list {
        display: flex;
        -ms-overflow-style: none;
        scrollbar-width: none;
        height: auto;
        padding-left: 30px;
        padding-right: 30px;
    }

    .left-sidebar.horizontal-menu .main-menu .main-nav-list::-webkit-scrollbar {
        display: none;
    }

    .left-sidebar.horizontal-menu .main-menu .help-nav {
        display: flex;
        width: max-content;
        border-top: 0;
    }

    .left-sidebar.horizontal-menu .main-menu .help-nav .main-menu-link {
        background: linear-gradient(#2b9ed0, #0f5167) !important;
        border-left: 1px solid #3ebdf3;
        border-right: 1px solid #16516a;
        color: #fff !important;
    }

    .left-sidebar.horizontal-menu .main-menu .help-nav .main-menu-link.open {
        color: #fff;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link {
        width: max-content;
        height: 30px;
        line-height: 30px;
        padding: 0 10px;
        display: flex;
        align-items: center;
        font-size: 12px;
        background: linear-gradient(#494949, #181818);
        border-left: 1px solid rgba(255, 255, 255, 0.2);
        border-right: 1px solid rgba(0, 0, 0, 0.9);
        overflow: hidden;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link.open {
        color: #70baff;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link::before {
        display: none;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link::after {
        top: auto;
        right: 0;
        height: 0;
        width: auto;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link.active::after,
    .left-sidebar.horizontal-menu .main-menu .main-menu-link:hover::after {
        height: 3px;
    }

    .left-sidebar.horizontal-menu .main-menu .main-menu-link .badge {
        right: auto;
        left: 1px;
        top: 1px;
    }

    .left-sidebar.horizontal-menu .menu-txt {
        display: block;
        font-size: 12px;
        line-height: 100%;
        margin-left: 5px;
    }

    .left-sidebar.horizontal-menu .submenu-panel .single-submenu {
        position: absolute;
        height: max-content;
        background: #0D1F2A;
        border: 1px solid rgba(255, 255, 255, 0.1);
        overflow: visible;
    }

    .left-sidebar.open-sub.horizontal-menu .submenu-panel .single-submenu:first-child {
        display: none;
    }

    .horizontal-menu .single-submenu .submenu-title {
        display: none;
    }

    .horizontal-menu .single-submenu ul li {
        position: relative;
    }

    .horizontal-menu .single-submenu .submenu-group {
        position: absolute;
        top: 0;
        left: 100%;
        width: 170px;
        margin: 0;
        background: #0d1f2a;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: block;
        opacity: 0;
        visibility: hidden;
    }

    .horizontal-menu .single-submenu:last-child .submenu-group {
        left: auto;
        right: 100%;
    }

    .horizontal-menu .single-submenu ul li:hover>a {
        color: #70baff;
    }

    .horizontal-menu .single-submenu ul li:hover>.submenu-group {
        opacity: 1;
        visibility: visible
    }

    .horizontal-menu .single-submenu .submenu-link.has-sub.open::after {
        transform: translateY(-50%);
    }

    .horizontal-menu-active .tooltip {
        display: none !important;
    }

    .light-nav .left-sidebar {
        background: #ffffff;
        border-color: rgba(0, 0, 0, 0.1)
    }

    .light-nav .left-sidebar .main-logo {
        border-color: rgba(0, 0, 0, 0.1)
    }

    .light-nav .left-sidebar .main-menu .main-menu-link {
        color: #000;
    }

    .light-nav .left-sidebar .main-menu .main-menu-link.open {
        color: #fff;
    }

    .light-nav .left-sidebar .main-menu .main-menu-link.active,
    .light-nav .left-sidebar .main-menu .main-menu-link:hover {
        color: #fff;
    }

    .light-nav .left-sidebar .main-menu .main-menu-link::before {
        background: rgba(0, 0, 0, 0.05);
    }

    .light-nav .left-sidebar .main-menu .help-nav {
        border-color: rgba(0, 0, 0, 0.1);
        box-shadow: 0px -5px 10px -5px rgba(0, 0, 0, 0.5);
    }

    .light-nav .left-sidebar .submenu-panel .single-submenu {
        border-color: rgba(0, 0, 0, 0.1);
        color: #000;
    }

    .light-nav .single-submenu .submenu-title {
        border-color: rgba(0, 0, 0, 0.1);
    }

    .light-nav .single-submenu .submenu-link {
        color: #181818;
    }

    .light-nav .single-submenu .submenu-link.active {
        color: #fff !important;
    }

    .light-nav .single-submenu .submenu-group {
        background: #f3f3f3;
        border-color: rgba(0, 0, 0, 0.05)
    }

    .light-nav .single-submenu .submenu-group .submenu-link.active {
        background: #cccccc;
        color: #000 !important;
        font-weight: 500;
    }

    .light-nav .single-submenu .submenu-group .submenu-link:hover {
        background: #dbdbdb;
        color: #000 !important;
        font-weight: 500;
    }

    .light-nav .left-sidebar.horizontal-menu .nav-arrow button:disabled {
        color: #7d7d7d;
        box-shadow: 0 0;
    }

    .light-nav .left-sidebar.horizontal-menu .main-menu .main-menu-link {
        color: #fff;
    }

    .light-nav .left-sidebar.horizontal-menu .main-menu .main-menu-link.open,
    .light-nav .left-sidebar.horizontal-menu .main-menu .main-menu-link.active,
    .light-nav .left-sidebar.horizontal-menu .main-menu .main-menu-link:hover {
        background: linear-gradient(#494949, #181818);
        color: #fff;
    }

    .light-nav .left-sidebar.horizontal-menu .submenu-panel .single-submenu {
        background: #fff;
    }

    .light-nav .horizontal-menu .single-submenu .submenu-group {
        background: #fff;
        border-color: rgba(0, 0, 0, 0.1)
    }

    .light-nav .horizontal-menu .single-submenu .submenu-group .submenu-link.active,
    .light-nav .horizontal-menu .single-submenu .submenu-group .submenu-link:hover {
        background: #f3f3f3;
    }

    .light-nav .horizontal-menu .single-submenu ul li:hover>a {
        color: #2b9ed0;
    }


    @media only screen and (max-width: 991px) {
        .left-sidebar {
            z-index: 1056;
        }
    }
</style>

<div class="left-sidebar" id="left-sidebar">
    <div class="main-logo">
        <div class="logo-small">
            <a href="{{ route('dashboard.dashboard') }}"><img src="{{ asset('images/favicon.png') }}" alt="{{ config('app.name') }}"></a>
        </div>
        <div class="logo-big">
            <a href="{{ route('dashboard.dashboard') }}"><img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}"></a>
        </div>
    </div>

    <div class="main-menu">
        <div class="nav-arrow">
            <button id="leftArrow" disabled><i class="fa-regular fa-angle-left"></i></button>
            <button id="rightArrow"><i class="fa-regular fa-angle-right"></i></button>
        </div>
        <ul class="main-nav-list">
            @canany(['customer_all', 'customer_import', 'customer_group', 'create_add_sale', 'view_sales', 'sale_draft', 'add_quotation', 'sale_order_add', 'sale_order_all', 'do_all', 'do_to_final', 'sale_quotation_list', 'shipment_access', 'sale_settings', 'discounts', 'pos_all', 'pos_add', 'pos_sale_settings', 'view_sales_return', 'add_sales_return', 'view_sales_return', 'sales_report', 'pro_sale_report', 'sales_return_report', 'sales_returned_items_report', 'ordered_item_qty_report', 'do_report', 'sales_order_report', 'sr_wise_order_report', 'do_vs_sales_report', 'ordered_item_report', 'manage_sr_index', 'manage_sr_create'])
                <x-nav-parent-li role="button" data-submenu="salesApp" class="main-menu-link {{ request()->is('sales/*') || request()->routeIs('dashboard.dashboard') ? 'open' : '' }}" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Sales" style="">
                    <span class="fa-light fa-store"></span>
                    <span class="menu-txt">
                        {{ __('Sales') }}
                    </span>
                </x-nav-parent-li>
            @endcanany

            @canany(['supplier_all', 'supplier_import', 'purchase_add', 'purchase_all', 'create_requisition', 'all_requisition', 'create_po', 'all_po', 'purchase_settings', 'view_purchase_return', 'add_purchase_return', 'requested_product_report', 'weighted_product_report', 'received_stocks_report', 'purchase_report', 'pro_purchase_report', 'purchase_sale_report', 'stock_issue_report', 'stock_issued_items_report', 'purchase_return_report', 'purchase_returned_items_report', 'supplier_report', 'stock_issue_index', 'stock_issue_create', 'purchase_by_scale_index', 'purchase_by_scale_create'])
                <x-nav-parent-li role="button" data-submenu="procurement" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Procurement" prefix="procurement/*">
                    <span class="fa-light fa-bag-shopping"></span>
                    <span class="menu-txt">
                        {{ __('Procurement') }}
                    </span>
                </x-nav-parent-li>
            @endcanany

            @canany(['product_all', 'product_add', 'categories', 'brand', 'units', 'variant', 'warranties', 'selling_price_group', 'generate_barcode', 'product_settings', 'adjustment_all', 'adjustment_add_from_location', 'adjustment_add_from_warehouse', 'transfer_wh_to_bl', 'transfer_bl_wh', 'stock_report', 'stock_in_out_report', 'stock_adjustment_report', 'daily_stock_report', 'daily_stock_index'])
                <x-nav-parent-li role="button" data-submenu="inventoryApp" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Inventory" prefix="inventories/*">
                    <span class="badge bg-danger">{{ __('0') }}</span>
                    <span class="fa-light fa-cart-flatbed-boxes"></span>
                    <span class="menu-txt">{{ __('Inventory') }}</span>
                </x-nav-parent-li>
            @endcanany

            @canany(['banks_index', 'account_groups_index', 'accounts_index', 'cost_centres_index', 'chart_of_accounts_index', 'receipts_index', 'receipts_add', 'payments_index', 'payments_add', 'journals_index', 'journals_add', 'contras_index', 'contras_add', 'contra', 'view_expense', 'add_expense', 'incomes_index', 'incomes_create', 'balance_sheet', 'trial_balance', 'cash_flow', 'fund_flow', 'day_book', 'outstanding_receivables', 'outstanding_payables', 'profit_loss_ac', 'daily_profit_loss', 'expanse_report', 'income_report'])
                <x-nav-parent-li role="button" data-submenu="finance" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Finance" prefix="finance/*">
                    <span class="fa-light fa-money-check-dollar-pen"></span>
                    <span class="menu-txt">{{ __('Finance') }}</span>
                </x-nav-parent-li>
            @endcanany

            <x-nav-parent-li role="button" data-submenu="lcManagement" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="LC Management" prefix="lc/*">
                <span class="fa-light fa-ship"></span>
                <span class="menu-txt">{{ __('LC') }}</span>
            </x-nav-parent-li>

            <x-nav-parent-li role="button" data-submenu="humanResource" class="main-menu-link" :can="'hrm_menu'" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="HRM" prefix="hrm/*">
                <span class="fa-light fa-people-group"></span>
                <span class="menu-txt">{{ __('HRM') }}</span>
            </x-nav-parent-li>

            @if ($addons->manufacturing == 1)
                @canany(['process_view', 'production_view', 'manuf_settings', 'manuf_report'])
                    <x-nav-parent-li role="button" data-submenu="manufacturing" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Manufacturing" prefix="manufacturing/*">
                        <span class="fa-light fa-industry-windows"></span>
                        <span class="menu-txt">{{ __('Manufacturing') }}</span>
                    </x-nav-parent-li>
                @endcanany
            @endif

            @canany(['notice_board', 'email', 'email_settings', 'sms', 'sms_settings'])
                <x-nav-parent-li role="button" data-submenu="communication" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Communication" prefix="*communication/*">
                    <span class="badge bg-danger">{{ __('99') }}</span>
                    <span class="fa-light fa-comment-dots"></span>
                    <span class="menu-txt">{{ __('Communication') }}</span>
                </x-nav-parent-li>
            @endcanany

            @canany(['media', 'calender', 'activity_log', 'change_log', 'database_backup'])
                <x-nav-parent-li role="button" data-submenu="utilities" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Utilities" prefix="utilities/*">
                    <span class="fa-light fa-folder-gear"></span>
                    <span class="menu-txt">{{ __('Utilities') }}</span>
                </x-nav-parent-li>
            @endcanany

            <x-nav-parent-li role="button" data-submenu="crmApp" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="CRM" prefix="crm/*">
                <span class="fa-light fa-users-gear"></span>
                <span class="menu-txt">CRM</span>
            </x-nav-parent-li>
            @canany(['t_revokes_view', 'asset_depreciation_view', 'asset_requests_index', 'asset_licenses_index', 'asset_audits_index', 'asset_settings'])
                <x-nav-parent-li role="button" data-submenu="asset" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Asset" prefix="*/asset/*">
                    <span class="fa-light fa-hand-holding-dollar"></span>
                    <span class="menu-txt">{{ __('Assets') }}</span>
                </x-nav-parent-li>
            @endcanany

            @if ($addons->todo == 1)
                @canany(['assign_todo', 'work_space', 'memo', 'msg'])
                    <x-nav-parent-li role="button" data-submenu="projectManagement" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Project Management" prefix="project/management/task/management*">
                        <span class="fa-light fa-list-check"></span>
                        <span class="menu-txt">{{ __('Project Management') }}</span>
                    </x-nav-parent-li>
                @endcanany
            @endif

            @canany(['index_weight_scale', 'add_weight_scale', 'index_weight_scale_client'])
                <x-nav-parent-li role="button" data-submenu="weightScale" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Weight Scale" prefix="weight/scales*">
                    <span class="fa-light fa-scale-balanced"></span>
                    <span class="menu-txt">{{ __('Weight Scale') }}</span>
                </x-nav-parent-li>
            @endcanany
            <x-nav-parent-li role="button" data-submenu="contacts" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Contacts" prefix="contacts/*">
                <span class="fa-light fa-address-book"></span>
                <span class="menu-txt">{{ __('Contacts') }}</span>
            </x-nav-parent-li>
            <x-nav-parent-li role="button" data-submenu="modules" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Modules" :can="'modules_page'" prefix="modules/*">
                <span class="fa-light fa-grid-2-plus"></span>
                <span class="menu-txt">{{ __('Modules') }}</span>
            </x-nav-parent-li>
            <x-nav-parent-li role="button" data-submenu="website" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Manage Website" :can="'website_link'" prefix="websites/*">
                <span class="fa-light fa-earth-asia"></span>
                <span class="menu-txt">{{ __('Website') }}</span>
            </x-nav-parent-li>
            <x-nav-parent-li role="button" data-submenu="users" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Users" :can="'user_view'" prefix="user-manage/*">
                <span class="fa-light fa-user"></span>
                <span class="menu-txt">{{ __('Users') }}</span>
            </x-nav-parent-li>
        </ul>

        <ul class="help-nav">
            <x-nav-parent-li data-submenu="setup" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Set-up" prefix="settings/*">
                <i class="fa-light fa-gear"></i>
                <span class="menu-txt">{{ __('Set-up') }}</span>
            </x-nav-parent-li>
            <x-nav-parent-li data-submenu="knowledge" class="main-menu-link" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Knowledge">
                <i class="fa-light fa-circle-question"></i>
                <span class="menu-txt">{{ __('Knowledge') }}</span>
            </x-nav-parent-li>
            </li>
        </ul>
    </div>

    <div class="submenu-panel">
        <div class="single-submenu" id="salesApp" style="{{ request()->is('sales/*') || request()->routeIs('dashboard.dashboard') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Sales') }}</span>
            <ul>
                <x-nav-li route="sales.dashboard.index"> {{ __('Sales Dashboard') }} </x-nav-li>
                @canany(['sale_quotation_list', 'add_quotation'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="*/quotation*">
                        {{ __('Quotations') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="*/quotation*">
                                <x-nav-li route="sales.quotations.create" :can="'add_quotation'">{{ __('Add Quotation') }}
                                </x-nav-li>
                                <x-nav-li route="sales.quotations" :can="'sale_quotation_list'">
                                    {{ __('Manage Quotation') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span><span class="right_icon"></span>
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['sale_order_add', 'sale_order_all'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="*/orders/*">
                        {{ __('Sales Order') }}
                        <x-slot name="after">
                            <x-nav-ul :prefix="'sales/app/orders*'" prefix="*/orders/*">
                                <x-nav-li route="sales.order.create" :can="'sale_order_add'">{{ __('Create Order') }}</x-nav-li>
                                <x-nav-li route="sales.order.index" :can="'sale_order_all'">
                                    {{ __('Manage Order') }}
                                    <span class="text-white validate_count" id="validate_order_count">0</span><span class="right_icon"></span>
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['create_add_sale', 'view_sales', 'sale_draft', 'shipment_access', 'discounts'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="sales/app/sales/*">
                        {{ __('Sales') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/sales/*">
                                <x-nav-li route="sales.create" :can="'create_add_sale'">{{ __('Direct Sale') }}</x-nav-li>
                                <x-nav-li route="sales.index" :can="'view_sales'">
                                    {{ __('Manage Sales') }}
                                </x-nav-li>
                                <x-nav-li route="sales.discounts.index" :can="'discounts'">
                                    {{ __('Manage Offers') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['do_all', 'do_to_final'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="sales/app/delivery/orders*">
                        {{ __('Delivery Order') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/delivery/orders*">
                                <x-nav-li route="sales.delivery.order.list" :can="'do_all'">
                                    {{ __('Manage D/o') }}
                                    <span class="text-white validate_count" id="validate_do_count">0</span><span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="sales.delivery.order.to.final" :can="'do_to_final'">
                                    {{ __('D/o to Invoice') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['pos_all', 'pos_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="sales/app/pos*">
                        {{ __('POS') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/pos*">
                                <x-nav-li route="sales.pos.create" :can="'pos_add'">
                                    {{ __('Point of Sale') }}
                                </x-nav-li>
                                <x-nav-li route="sales.pos.list" :can="'pos_all'">
                                    {{ __('Manage Pos Sale') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['view_sales_return', 'add_sales_return'])
                    <x-nav-parent-li class="has-sub caret" prefix="sales/app/returns*">
                        {{ __('Sales Return') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/returns*">
                                <x-nav-li route="sale.return.random.create" :can="'add_sales_return'">
                                    {{ __('Add Sale Return') }}
                                </x-nav-li>
                                <x-nav-li route="sales.returns.index" :can="'view_sales_return'">
                                    {{ __('Sale Return List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['add_new_recent_price', 'all_previous_recent_price', 'today_recent_price'])
                    <x-nav-parent-li class="has-sub caret" prefix="sales/app/recent*">
                        {{ __('Price Master') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/recent*">
                                <x-nav-li route="sales.recent.price.create" :can="'add_new_recent_price'">
                                    {{ __('Add New Price') }}
                                </x-nav-li>
                                <x-nav-li route="sales.recent.price.index" :can="'all_previous_recent_price'">
                                    {{ __('All Previous Price') }}
                                </x-nav-li>
                                <x-nav-li route="sales.recent.price.today" :can="'today_recent_price'">
                                    {{ __('Today Price') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['manage_sr_index', 'manage_sr_create'])
                    <x-nav-parent-li class="has-sub caret" prefix="sales/app/sr*">
                        {{ __('Manage SR') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/sr*">
                                <x-nav-li route="sales.sr.index" :can="'manage_sr_index'">
                                    {{ __('SR List') }}
                                </x-nav-li>
                                <x-nav-li route="sales.sr.create" :can="'manage_sr_create'">
                                    {{ __('Add SR') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['sales_report', 'pro_sale_report', 'sales_return_report', 'sales_returned_items_report', 'ordered_item_qty_report', 'do_report', 'sales_order_report', 'sr_wise_order_report', 'ordered_item_report', 'do_vs_sales_report'])
                    <x-nav-parent-li class="has-sub caret" prefix="sales/app/reports*">
                        {{ __('Sales Report') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="sales/app/reports*">
                                <x-nav-li route="reports.sales.report.index" :can="'sales_report'">
                                    {{ __('Sales Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sold.items.report.index" :can="'pro_sale_report'">
                                    {{ __('Sold Items Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sales.order.report.index" :can="'sales_order_report'">
                                    {{ __('Sales Order Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sales.order.report.user.wise.index" :can="'sr_wise_order_report'">
                                    {{ __('Sr. Wise Order Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sales.ordered.items.report.index" :can="'ordered_item_report'">
                                    {{ __('Sales Ordered Items Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sales.ordered.item.qty.report.index" :can="'ordered_item_qty_report'">
                                    {{ __('menu.ordered_item_qty_report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.do.report.index" :can="'do_report'">
                                    {{ __('D/o Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.do.vs.sales.report.index">
                                    {{ __('D/o Vs Sale') }}
                                </x-nav-li>

                                <x-nav-li route="reports.sale.return.report.index" :can="'sales_return_report'">
                                    {{ __('Sales Return Report') }}
                                </x-nav-li>

                                <x-nav-li route="reports.sales.returned.items.report.index" :can="'sales_returned_items_report'">
                                    @lang('menu.sales_returned_items_report')
                                </x-nav-li>

                                <x-nav-li route="reports.cash.registers.index" :can="'c_register_report'">
                                    {{ __('Cash Register Reports') }}
                                </x-nav-li>

                                <x-nav-li route="reports.customer.index" :can="'customer_report'">
                                    {{ __('Customer Reports') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                {{-- @canany(['sale_settings', 'pos_sale_settings'])
                    <x-nav-li class="has-sub caret">
                        {{ __('Settings') }}
                <x-slot name="after">
                    <x-nav-ul :prefix="'sales/app/settings*'">
                        <x-nav-li route="sales.app.settings.sale.settings.create" :can="'sale_settings'">
                            {{ __('Sale Settings') }}
                        </x-nav-li>
                        <x-nav-li route="sales.app.settings.pos.settings.create" :can="'pos_sale_settings'">
                            {{ __('POS Settings') }}
                        </x-nav-li>
                        <x-nav-li>
                            {{ __('Terms Conditions') }}
                        </x-nav-li>
                    </x-nav-ul>
                </x-slot>
                </x-nav-li>
                @endcanany --}}

                <x-nav-li>
                    {{ __('Sales Terms Conditions') }}
                </x-nav-li>

                @canany(['sale_settings', 'pos_sale_settings'])
                    <x-nav-li route="sales.app.settings.sale.settings.index">
                        {{ __('menu.settings') }}
                    </x-nav-li>
                @endcanany

            </ul>
        </div>

        <div class="single-submenu" id="procurement" style="{{ request()->is('procurement/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Procurement') }}</span>
            <ul>
                <x-nav-li route="purchases.dashboard.index"> {{ __('Procurement Dashboard') }}</x-nav-li>
                @canany(['create_requisition', 'all_requisition'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/requisitions*">
                        {{ __('Requisition') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/requisitions*">
                                <x-nav-li route="purchases.requisition.create" :can="'create_requisition'">
                                    {{ __('Add Requisition') }}
                                </x-nav-li>
                                <x-nav-li route="purchases.requisition.index" :can="'all_requisition'">
                                    {{ __('Requisition List') }}
                                </x-nav-li>
                                <x-nav-li route="requisitions.departments.index" :can="'all_requisition'">
                                    {{ __('Departments') }}
                                </x-nav-li>
                                <x-nav-li route="requesters.index">
                                    {{ __('Requester') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['create_po', 'all_po'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/purchase-orders*">
                        {{ __('Purchase Orders') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/purchase-orders*">
                                <x-nav-li route="purchases.order.create" :can="'create_po'">
                                    {{ __('Add Purchase Order') }}
                                </x-nav-li>
                                <x-nav-li route="purchases.order.index" :can="'all_po'">
                                    {{ __('Purchase Order List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['receive_stocks_create', 'receive_stocks_index'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/receive/stocks*">
                        {{ __('Receive Stocks') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/receive/stocks*">
                                <x-nav-li route="purchases.receive.stocks.create" :can="'receive_stocks_create'">
                                    {{ __('Create Receive Stock') }}</x-nav-li>
                                <x-nav-li route="purchases.receive.stocks.index" :can="'receive_stocks_index'">
                                    {{ __('Receive Stock List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['purchase_add', 'purchase_all', 'purchase_settings'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/purchases*">
                        {{ __('Purchases') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/purchases*">
                                <x-nav-li route="purchases.create" :can="'purchase_add'">{{ __('Add Purchase') }}</x-nav-li>
                                <x-nav-li route="purchases.index" :can="'purchase_all'">
                                    {{ __('Purchase List') }}
                                </x-nav-li>
                                <x-nav-li route="purchases.product.list" :can="'purchase_all'">
                                    {{ __('Purchase Item List') }}
                                </x-nav-li>
                                <x-nav-li route="purchase.settings" :can="'purchase_settings'">
                                    {{ __('Purchase Settings') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['stock_issue'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/stock/issue*">
                        {{ __('Stock Issue') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/stock/issue*">
                                <x-nav-li route="stock.issue.create" :can="'stock_issue_create'">{{ __('Add Stock Issue') }}
                                </x-nav-li>
                                <x-nav-li route="stock.issue.index" :can="'stock_issue_index'">
                                    {{ __('Manage Stock Issue') }}
                                </x-nav-li>
                                <x-nav-li route="stock.issues.events.index">
                                    {{ __('Stock Issue Event') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['view_purchase_return', 'add_purchase_return'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/purchase-returns*">
                        {{ __('Purchase Return') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/purchase-returns*">
                                <x-nav-li route="purchases.returns.create">
                                    {{ __('Add Purchase Return') }}
                                </x-nav-li>
                                <x-nav-li route="purchases.returns.index">
                                    {{ __('Purchase Return List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['purchase_by_scale_index', 'purchase_by_scale_create'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/purchase-by-scale*">
                        {{ __('Purchase By Scale') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/purchase-by-scale*">
                                <x-nav-li route="purchases.by.scale.create" :can="'purchase_by_scale_create'">
                                    {{ __('Purchase By Scale') }}
                                </x-nav-li>
                                <x-nav-li route="purchases.by.scale.index" :can="'purchase_by_scale_index'">
                                    {{ __('Purchase By Scale List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany

                @canany(['requested_product_report', 'weighted_product_report', 'received_stocks_report', 'purchase_report', 'pro_purchase_report', 'purchase_sale_report', 'stock_issue_report', 'stock_issued_items_report', 'purchase_return_report', 'purchase_returned_items_report', 'supplier_report'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="procurement/reports*">
                        {{ __('Procurement Reports') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="procurement/reports*">
                                <x-nav-li route="reports.requested.products.index" :can="'requested_product_report'">
                                    {{ __('Requested Item Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.weighted.products.index" :can="'weighted_product_report'">
                                    {{ __('Weighted Item Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.receive.stocks.index" :can="'received_stocks_report'">
                                    {{ __('Received Stocks Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.purchases.report.index" :can="'purchase_report'">
                                    {{ __('Purchase Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.product.purchases.index" :can="'pro_purchase_report'">
                                    {{ __('Purchased Items Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.sales.purchases.index" :can="'purchase_sale_report'">
                                    {{ __('Purchase Sale Compare') }}
                                </x-nav-li>

                                <x-nav-li route="reports.stock.issue.report.index" :can="'stock_issue_report'">
                                    {{ __('Stock Issue Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.stock.issued.items.report.index" :can="'stock_issued_items_report'">
                                    {{ __('Stock Issued Items Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.purchase.return.report.index" :can="'purchase_return_report'">
                                    {{ __('menu.purchase_return_report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.purchase.returned.items.report.index" :can="'purchase_returned_items_report'">
                                    {{ __('menu.returned_items_report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.supplier.index" :can="'supplier_report'">
                                    {{ __('Supplier Reports') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
            </ul>
        </div>

        <div class="single-submenu" id="inventoryApp" style="{{ request()->is('inventories/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Inventory') }}</span>
            <ul>
                <x-nav-li route="inventories.dashboard.index"> {{ __('Inventory Dashboard') }}</x-nav-li>
                @canany(['product_all', 'product_add', 'categories', 'brand', 'units', 'variant', 'warranties', 'selling_price_group', 'generate_barcode', 'daily_stock_index'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/product*">
                        {{ __('Manage Item') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/product*">
                                <x-nav-li route="products.add.view" :can="'product_add'">{{ __('Add Item') }}</x-nav-li>
                                <x-nav-li route="products.all.product" :can="'product_all'">
                                    {{ __('Item List') }}
                                </x-nav-li>
                                <x-nav-li route="product.import.create" :can="'product_add'">
                                    {{ __('Import Item') }}
                                </x-nav-li>
                                <x-nav-li route="product.categories.index" :can="'categories'">
                                    {{ __('Categories') }}
                                </x-nav-li>
                                <x-nav-li route="product.brands.index" :can="'brand'">
                                    {{ __('Brands') }}
                                </x-nav-li>
                                <x-nav-li route="products.units.index" :can="'units'">
                                    {{ __('Units') }}
                                </x-nav-li>
                                <x-nav-li route="product.variants.index" :can="'variant'">
                                    {{ __('Variants') }}
                                </x-nav-li>
                                <x-nav-li route="product.warranties.index" :can="'warranties'">
                                    {{ __('Warranties') }}
                                </x-nav-li>
                                <x-nav-li route="product.selling.price.groups.index" :can="'selling_price_group'">
                                    {{ __('Selling Price Group') }}
                                </x-nav-li>
                                <x-nav-li route="barcode.index" :can="'generate_barcode'">
                                    {{ __('Generate Barcode') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['daily_stock_index', 'daily_stock_create'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/daily/stock*">
                        {{ __('Daily Stock') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/daily/stock*">
                                <x-nav-li route="daily.stock.create" :can="'daily_stock_create'">
                                    {{ __('Add Daily Stock') }}
                                </x-nav-li>
                                <x-nav-li route="daily.stock.index" :can="'daily_stock_index'">
                                    {{ __('Manage Daily Stock') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['stock_adjustments_all', 'stock_adjustments_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/stock/adjust*">
                        {{ __('Stock Adjustments') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/stock/adjust*">
                                <x-nav-li route="stock.adjustments.create" :can="'stock_adjustments_add'">
                                    {{ __('Add Stock Adjustment') }}
                                </x-nav-li>
                                <x-nav-li route="stock.adjustments.index" :can="'stock_adjustments_all'">
                                    {{ __('Stock Adjustment List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['transfer_wh_to_bl'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/transfer/stocks/wh/to/branch*">
                        {{ __('Transfer WH Location') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/transfer/stocks/wh/to/branch*">
                                <x-nav-li route="transfer.stock.to.branch.create">
                                    {{ __('Add Transfer') }}
                                </x-nav-li>
                                <x-nav-li route="transfer.stock.to.branch.index">
                                    {{ __('Transfer List') }}
                                </x-nav-li>
                                <x-nav-li route="transfer.stocks.to.warehouse.receive.stock.index">
                                    {{ __('Stock Receive') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['transfer_bl_wh'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/transfer/stocks/branch/to/wh*">
                        {{ __('Transfer B Locaction To WH') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/transfer/stocks/branch/to/wh*">
                                <x-nav-li route="transfer.stock.to.warehouse.create">
                                    {{ __('Add Transfer') }}
                                </x-nav-li>
                                <x-nav-li route="transfer.stock.to.warehouse.index">
                                    {{ __('Transfer List') }}
                                </x-nav-li>
                                <x-nav-li route="transfer.stocks.to.branch.receive.stock.index">
                                    {{ __('Stock Receive') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany

                @canany(['stock_report', 'stock_in_out_report', 'stock_adjustment_report', 'daily_stock_report'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="inventories/reports*">
                        {{ __('Inventory Reports') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="inventories/reports*">
                                <x-nav-li route="reports.stock.index" :can="'stock_report'">
                                    {{ __('Stock Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.daily.stock.index" :can="'stock_in_out_report'">
                                    {{ __('Daily Stock Item Reports') }}
                                </x-nav-li>
                                <x-nav-li route="reports.stock.adjustments.index" :can="'stock_adjustment_report'">
                                    {{ __('Stock Adjustment Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.stock.adjusted.index" :can="'stock_adjustment_report'">
                                    {{ __('Stock Adjusted Item Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.stock.in.out.index" :can="'daily_stock_report'">
                                    {{ __('Stock In Out Report') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany

                @canany(['product_settings'])
                    <x-nav-li route="inventories.settings.index">
                        {{ __('menu.settings') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>

        <div class="single-submenu" id="finance" style="{{ request()->is('finance/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Finance') }}</span>
            <ul>
                <x-nav-li route="finance.dashboard.index"> {{ __('Finance Dashboard') }}</x-nav-li>
                @canany(['banks_index', 'account_groups_index', 'accounts_index', 'chart_of_accounts_index'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/accounting*">
                        {{ __('Accounting') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/accounting*">
                                <x-nav-li route="accounting.banks.index" :can="'banks_index'">{{ __('Bank') }}
                                </x-nav-li>
                                <x-nav-li route="accounting.groups.index" :can="'account_groups_index'">
                                    {{ __('Groups') }}
                                </x-nav-li>
                                <x-nav-li route="accounting.charts.index" :can="'chart_of_accounts_index'">
                                    {{ __('Chart Of Accounts') }}
                                </x-nav-li>
                                <x-nav-li route="accounting.accounts.index" :can="'accounts_index'">
                                    {{ __('Accounts') }}
                                </x-nav-li>
                                <x-nav-li route="cost.centres.index" :can="'cost_centres_index'">
                                    {{ __('Cost Centres') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany

                @canany(['receipts_index', 'receipts_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/vouchers/receipts*">
                        {{ __('menu.receipt') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/vouchers/receipts*">
                                <x-nav-li route="vouchers.receipts.index" :can="'receipts_index'">{{ __('Receipt List') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.receipts.create'" :routeParams="['mode' => 1]" :can="'receipts_add'" class="{{ request()->is('finance/vouchers/receipts/create/1') ? 'active' : '' }}">
                                    {{ __('menu.add_single_entry') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.receipts.create'" :routeParams="['mode' => 2]" :can="'receipts_add'" class="{{ request()->is('finance/vouchers/receipts/create/2') ? 'active' : '' }}">
                                    {{ __('menu.add_double_entry') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany

                @canany(['payments_index', 'payments_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/vouchers/payments*">
                        {{ __('menu.payment') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/vouchers/payments*">
                                <x-nav-li route="vouchers.payments.index" :can="'payments_index'">
                                    {{ __('Payment List') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.payments.create'" :routeParams="['mode' => 1]" :can="'payments_add'" class="{{ request()->is('finance/vouchers/payments/create/1') ? 'active' : '' }}">
                                    {{ __('menu.add_single_entry') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.payments.create'" :routeParams="['mode' => 2]" :can="'payments_add'" class="{{ request()->is('finance/vouchers/payments/create/2') ? 'active' : '' }}">
                                    {{ __('menu.add_double_entry') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['journals_index', 'journals_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/vouchers/journals*">
                        {{ __('menu.journal') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/vouchers/journals*">
                                <x-nav-li route="vouchers.journals.index" :can="'journals_index'">
                                    {{ __('Journal List') }}
                                </x-nav-li>
                                <x-nav-li route="vouchers.journals.create" :can="'journals_add'">
                                    {{ __('Add Journal') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['contras_index', 'contras_add'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/vouchers/contras*">
                        {{ __('menu.contra') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/vouchers/contras*">
                                <x-nav-li route="vouchers.contras.index" :can="'contras_index'">
                                    {{ __('Contra List') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.contras.create'" :routeParams="['mode' => 1]" :can="'contras_add'" class="{{ request()->is('finance/vouchers/contras/create/1') ? 'active' : '' }}">
                                    {{ __('menu.add_single_entry') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.contras.create'" :routeParams="['mode' => 2]" :can="'contras_add'" class="{{ request()->is('finance/vouchers/contras/create/2') ? 'active' : '' }}">
                                    {{ __('menu.add_double_entry') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['view_expense', 'add_expense'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/vouchers/expenses*">
                        {{ __('menu.expense') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/vouchers/expenses*">
                                <x-nav-li route="vouchers.expenses.index" :can="'view_expense'">
                                    {{ __('Expense List') }}
                                </x-nav-li>
                                <x-nav-li :route="'vouchers.expenses.create'" :routeParams="['mode' => 1]" :can="'add_expense'" class="{{ request()->is('finance/vouchers/expenses/create/1') ? 'active' : '' }}">
                                    {{ __('menu.add_single_entry') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['incomes_index', 'incomes_create'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/income*">
                        {{ __('menu.income') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/income*">
                                <x-nav-li route="income.create" :can="'incomes_create'">
                                    {{ __('Add Income') }}
                                </x-nav-li>
                                <x-nav-li route="income.index" :can="'incomes_index'">
                                    {{ __('Income List') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['balance_sheet', 'trial_balance', 'cash_flow', 'fund_flow', 'day_book', 'outstanding_receivables', 'outstanding_payables', 'profit_loss_ac', 'daily_profit_loss', 'expanse_report'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="finance/reports*">
                        {{ __('Finance Report') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="finance/reports*">
                                <x-nav-li route="reports.trial.balance.index" :can="'trial_balance'">
                                    {{ __('Trial Balance') }}
                                </x-nav-li>
                                <x-nav-li route="reports.profit.loss.account.index" :can="'profit_loss_ac'">
                                    {{ __('Profit Loss Account') }}
                                </x-nav-li>
                                <x-nav-li route="reports.balance.sheet.index" :can="'balance_sheet'">
                                    {{ __('Balance Sheet') }}
                                </x-nav-li>
                                <x-nav-li route="reports.cash.flow.index" :can="'cash_flow'">
                                    {{ __('Cash Flow') }}
                                </x-nav-li>
                                <x-nav-li route="reports.fund.flow.index" :can="'fund_flow'">
                                    {{ __('Fund Flow') }}
                                </x-nav-li>
                                <x-nav-li route="reports.outstanding.receivable.index" :can="'outstanding_receivables'">
                                    {{ __('Outstanding Receivables') }}
                                </x-nav-li>
                                <x-nav-li route="reports.outstanding.payable.index" :can="'outstanding_payables'">
                                    {{ __('Outstanding Payables') }}
                                </x-nav-li>
                                <x-nav-li route="reports.profit.loss.index" :can="'daily_profit_loss'">
                                    {{ __('Daily Profit Loss') }}
                                </x-nav-li>
                                <x-nav-li route="reports.daybook.index" :can="'day_book'">
                                    {{ __('Day Book') }}
                                </x-nav-li>
                                <x-nav-li route="reports.cash.bank.books.index">
                                    {{ __('menu.cash_bank_books') }}
                                </x-nav-li>
                                <x-nav-li route="reports.expenses.index" :can="'expanse_report'">
                                    {{ __('Expense Report') }}
                                </x-nav-li>
                                <x-nav-li route="reports.incomes.index" :can="'income_report'">
                                    {{ __('Income Report') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                <x-nav-parent-li role="button" class="has-sub" prefix="finance/settings*">
                    {{ __('Settings') }}
                    <x-slot name="after">
                        <x-nav-ul :prefix="'finance/settings*'">
                            <x-nav-li route="finance.voucher.settings.index">
                                {{ __('Voucher Settings') }}
                            </x-nav-li>
                        </x-nav-ul>
                    </x-slot>
                </x-nav-parent-li>
            </ul>
        </div>

        <div class="single-submenu" id="lcManagement" style="{{ request()->is('lc/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('LC Management') }}</span>
            <ul>
                <x-nav-li> {{ __('LC Management Dashboard') }}</x-nav-li>
                @canany(['opening_lc', 'import_purchase_order', 'exporters', 'insurance_companies', 'cnf_agents'])
                    <x-nav-li route="manage.lc.index" :can="'opening_lc'">
                        {{ __('Opening LC') }}
                    </x-nav-li>
                    <x-nav-li route="lc.imports.create" :can="'import_purchase_order'">
                        {{ __('Import Purchase Order') }}
                    </x-nav-li>
                    <x-nav-li>
                        {{ __('Manage Import Purchase') }}
                    </x-nav-li>
                    <x-nav-li route="lc.exporters.index" :can="'exporters'">
                        {{ __('Exporters') }}
                    </x-nav-li>
                    <x-nav-li route="lc.insurance.companies.index" :can="'insurance_companies'">
                        {{ __('Insurance Companies') }}
                    </x-nav-li>
                    <x-nav-li route="lc.cnf.agents.index" :can="'cnf_agents'">
                        {{ __('CNF Agents') }}
                    </x-nav-li>
                    <x-nav-li>
                        {{ __('Reports') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>

        <div class="single-submenu" id="humanResource" style="{{ request()->is('hrm/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('HRM') }}</span>
            <ul>
                <x-nav-li route="hrm.hrm-dashboard">{{ __('HRM DashBoard') }}</x-nav-li>
                @canany(['attendance', 'person_wise_attendance', 'section_wise_attendance', 'machine_attendance', 'attendance_log', 'daily_attendance_report', 'attendance_absent_report', 'attendance_rapid_update', 'man_power', 'overtime_manage', 'job_card', 'range_absent_checker', 'range_absent_checker', 'departments', 'manage_mepartment', 'manage_section', 'manage_sub_section', 'manage_designation', 'shifts', 'manage_shift', 'manage_grade', 'company_organogram', ])
                    <x-nav-parent-li role="button" class="has-sub {{ request()->is('hrm/attendances*') ? 'open' : '' }}">
                        {{ __('Attendances') }}
                        <x-slot name="after">
                            <x-nav-ul :prefix="'hrm/attendances*'">
                                <x-nav-li route="hrm.persons.index">
                                    {{ __('Attendance') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.persons.create">
                                    {{ __('Person Wise Attendance') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.section-wise.create">
                                    {{ __('Section Wise Attendance') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.create.missing.attendance">
                                    {{ __('Missing Attendance') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.bulk_attendance_imports.index">
                                    {{ __('Machine Attendance') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.attendance_log.index">
                                    {{ __('Attendance Log') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.daily_attendance_list.index">
                                    {{ __('Daily Attendance Report') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.attendance.absent">
                                    {{ __('Absent Report') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.attendance_rapid_update">
                                    {{ __('Rapid Update') }}
                                </x-nav-li>
                                <x-nav-li>
                                    {{ __('Man Power') }}
                                </x-nav-li>
                                <x-nav-li>
                                    {{ __('Overtime Manage') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.attendance.job.card">
                                    {{ __('Job Card') }}
                                </x-nav-li>
                                <x-nav-li>
                                    {{ __('Summary Sheet') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.date_range.absence_checker.index">
                                    {{ __('Range Absent Checker') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/payroll*">
                        {{ __('Payroll') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/payroll*">
                                <x-nav-li route="hrm.calculation.index">
                                    {{ __('Calculation Checker') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.payrolls.sallary.list">
                                    {{ __('Salary List Generate') }}
                                </x-nav-li>
                                {{-- <x-nav-li route="hrm.payrolls.sallary.list"> --}}
                                <x-nav-li>
                                    {{ __('Night & Tiffin Bill') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/manage-departments*">
                        {{ __('Departments') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/manage-departments*">
                                <x-nav-li route="hrm.departments.index">
                                    {{ __('Manage Department') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.sections.index">
                                    {{ __('Manage Section') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.subsections.index">
                                    {{ __('Manage Sub Section') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.designations.index">
                                    {{ __('Manage Designation') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.shifts.index">
                                    {{ __('Shifts') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.shift-adjustments.index">
                                    {{ __('Manage Shift') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.grades.index">
                                    {{ __('Manage Grade') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.organogram.index">
                                    {{ __('Company Organogram') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/leaves*">
                        {{ __('Leave & Holiday') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/leaves*">
                                <x-nav-li route="hrm.holidays.index">
                                    {{ __('Manage Holiday') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.leave-applications.index">
                                    {{ __('Leave Application') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.leave_register">
                                    {{ __('Leave Register') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.leave-types.index">
                                    {{ __('Leave Type') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.payment-types.index">
                                    {{ __('Payment Type') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.el-calculation.index">
                                    {{ __('Earned Leave Calculation') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.el-payments.index">
                                    {{ __('Earned Leave Payments') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.calendar.index">
                                    {{ __('Holiday Calendar') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub {{ request()->is('hrm/employee*') ? 'open' : '' }}" prefix="hrm/employee*">
                        {{ __('Employees') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/employee*">
                                <x-nav-li route="hrm.employees.create">
                                    {{ __('Add Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employees.index">
                                    {{ __('All Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee-import.index">
                                    {{ __('Import Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee.card">
                                    {{ __('Bulk ID Card') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee.appointment.letter">
                                    {{ __('Bulk Appointment Letter') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.appointmentLetter-2">
                                    {{ __('Bulk Appointment Letter 2') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.arrivals.index">
                                    {{ __('New Arrivals') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.promotions.index">
                                    {{ __('Promotion') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.shifts.changes">
                                    {{ __('Employee Shift Change') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee.master.list">
                                    {{ __('Master List By Designation') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.resign-employees.index">
                                    {{ __('Resign Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.left-employees.index">
                                    {{ __('Left Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee.trashed">
                                    {{ __('Trashed Employee') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.final_settlement.index">
                                    {{ __('Final Settlement') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/salary*">
                        {{ __('Settlement Salary') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/salary*">
                                <x-nav-li route="hrm.salary-settlements.index">
                                    {{ __('Settlement') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/adjustment/*">
                        {{ __('Salary Adjustment') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/adjustment/*">
                                <x-nav-li route="hrm.salaryAdjustments.index">
                                    {{ __('Amount of Add/Deduct') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.overtimeAdjustments.index">
                                    {{ __('OT Addition / Deduction') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.employee-tax-adjustments.index">
                                    {{ __('Tax Add / Deduct') }}
                                </x-nav-li>
                                <x-nav-li>
                                    {{ __('Addition / Deduction Log') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.salary-advances.index">
                                    {{ __('Advance') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/recruitment*">
                        {{ __('Reqruitment') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/recruitment*">
                                <x-nav-li>
                                    {{ __('Job Openning') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.interview_question_list">
                                    {{ __('Interview Questions') }}
                                </x-nav-li>
                                @canany(['web_manage_job_category', 'web_add_job_category', 'web_edit_job_category', 'web_delete_job_category'])
                                    @if (Route::has('website.job-categories.index'))
                                        <x-nav-li route="website.job-categories.index" target="_blank">
                                            {{ __('Job Category') }}
                                        </x-nav-li>
                                    @endif
                                @endcanany
                                @if (Route::has('website.jobs.index'))
                                    @canany(['web_manage_job', 'web_add_job', 'web_edit_job', 'web_delete_job'])
                                        <x-nav-li route="website.jobs.index" target="_blank">
                                            {{ __('Job') }}
                                        </x-nav-li>
                                    @endcanany
                                @endif
                                <x-nav-li route="hrm.job_applicant_list">
                                    {{ __('Job On-Boarding') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.selected_for_interview_list">
                                    {{ __('Selected For Interview') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.already_mail_for_interview_list">
                                    {{ __('Mail For Interview') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.interview_participate_list">
                                    {{ __('Interview Participants') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.applicant_final_selected_list">
                                    {{ __('Final Selected') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.applicant_offer_letter_list">
                                    {{ __('Applicants Offer Letter') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.applicant_hired_list">
                                    {{ __('Applicants Hired List') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.convert_employee_list">
                                    {{ __('Convert to Employee') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.applicant_reject_list">
                                    {{ __('Applicants Reject List') }}
                                    <span class="text-white validate_count" id="validate_quotation_count">0</span>
                                    <span class="right_icon"></span>
                                </x-nav-li>
                                <x-nav-li route="hrm.interview_list">
                                    {{ __('Interview') }}
                                    {{-- <span class="text-white validate_count" id="validate_quotation_count">0</span> <span
                                    class="right_icon"></span> --}}
                                </x-nav-li>
                                <x-nav-li route="hrm.schedule_list">
                                    {{ __('Interview Schedule') }}
                                    {{-- <span class="text-white validate_count" id="validate_quotation_count">0</span> <span
                                    class="right_icon"></span> --}}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/others*">
                        {{ __('Others') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/others*">
                                <x-nav-li route="hrm.awards.index">
                                    {{ __('Award/Prize') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.notices.index">
                                    {{ __('Notice') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.visit.index">
                                    {{ __('Visit/Travel') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-parent-li role="button" class="has-sub" prefix="hrm/reports*">
                        {{ __('Reports') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="hrm/reports*">
                                <x-nav-li route="hrm.leave_report">
                                    {{ __('Leave Application Report') }}
                                </x-nav-li>
                                <x-nav-li route="hrm.salary_adjustment_report">
                                    {{ __('Salary Adjustment Report') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-li route="hrm.settings">
                        {{ __('HRM Settings') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="manufacturing" style="{{ request()->is('manufacturing*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Manufacturing') }}</span>
            <ul>
                <x-nav-li route="manufacturing.dashboard.index"> {{ __('Manufacturing Dashboard') }} </x-nav-li>
                @canany(['process_view', 'production_view', 'manuf_settings'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="manufacturing/manage/production*">
                        {{ __('Manage Production') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="manufacturing/manage/production*">
                                <x-nav-li route="manufacturing.process.index" :can="'process_view'">
                                    {{ __('Processes') }}
                                </x-nav-li>
                                <x-nav-li route="manufacturing.productions.index" :can="'production_view'">
                                    {{ __('Productions') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['manuf_report', 'process_report'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="manufacturing/report*">
                        {{ __('Manufacturing Report') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="manufacturing/report*">
                                <x-nav-li :can="'process_report'">
                                    {{ __('Process Report') }}
                                </x-nav-li>
                                <x-nav-li route="manufacturing.report.index" :can="'manuf_report'">
                                    {{ __('Production Report') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                    <x-nav-li route="manufacturing.settings.index" :can="'manuf_settings'" prefix="manufacturing/setting*">
                        {{ __('Manufacturing Setting') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="communication" style="{{ request()->is('*communication/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Communication') }}</span>
            <ul>
                <x-nav-li> {{ __('Communication Dashboard') }}</x-nav-li>
                @canany(['notice_board', 'email', 'email_settings', 'sms', 'sms_settings', 'announcement'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="*communication/email*">
                        {{ __('Email') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="*communication/email*">
                                <x-nav-li route="communication.email.index">
                                    {{ __('Send Mail') }}
                                </x-nav-li>
                                <x-nav-li route="communication.email.server-setup">
                                    {{ __('Add Server') }}
                                </x-nav-li>
                                <x-nav-li route="communication.email.body">
                                    {{ __('Body Format') }}
                                </x-nav-li>
                                <x-nav-li route="communication.email.setting">
                                    {{ __('Defoult Server') }}
                                </x-nav-li>
                                <x-nav-li route="communication.email.manual-service">
                                    {{ __('Manual Email') }}
                                </x-nav-li>
                                <x-nav-li route="communication.email.permission">
                                    {{ __('Email Permission') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                <x-nav-parent-li role="button" class="has-sub" prefix="*communication/sms*">
                    {{ __('SMS') }}
                    <x-slot name="after">
                        <x-nav-ul prefix="*communication/sms*">
                            <x-nav-li route="communication.sms.index">
                                {{ __('Send SMS') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.server-setup">
                                {{ __('Add Provider') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.body">
                                {{ __('Body Format') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.setting">
                                {{ __('Defoult Provider') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.manual-service">
                                {{ __('Manual SMS') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.permission">
                                {{ __('SMS Permission') }}
                            </x-nav-li>
                        </x-nav-ul>
                    </x-slot>
                </x-nav-parent-li>
                <x-nav-parent-li role="button" class="has-sub" prefix="*communication/whatsapp*">
                    {{ __('WhatsApp') }}
                    <x-slot name="after">
                        <x-nav-ul prefix="*communication/whatsapp*">
                            <x-nav-li route="communication.whatsapp.index">
                                {{ __('Send Whatsapp') }}
                            </x-nav-li>
                            <x-nav-li route="communication.whatsapp.body">
                                {{ __('Body Format') }}
                            </x-nav-li>
                            <x-nav-li route="communication.whatsapp.manual-service">
                                {{ __('Manual Whatsapp') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.setting">
                                {{ __('Defoult Provider') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.manual-service">
                                {{ __('Manual SMS') }}
                            </x-nav-li>
                            <x-nav-li route="communication.sms.permission">
                                {{ __('SMS Permission') }}
                            </x-nav-li>
                        </x-nav-ul>
                    </x-slot>
                </x-nav-parent-li>
                <x-nav-li route="communication.contacts.index">
                    {{ __('Contact Group') }}
                </x-nav-li>
                <x-nav-li route="notice_boards.index" :can="'notice_board'">
                    {{ __('Notice Board') }}
                </x-nav-li>
                <x-nav-li route="announcements.index" :can="'announcement'">
                    {{ __('Announcement') }}
                </x-nav-li>
            </ul>
        </div>
        <div class="single-submenu" id="utilities" style="{{ request()->is('utilities/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Utilities') }}</span>
            <ul>
                <x-nav-li> {{ __('Utilities Dashboard') }}</x-nav-li>
                @canany(['media', 'calender', 'activity_log', 'change_log', 'database_backup'])
                    <x-nav-li :can="'media'">
                        {{ __('Media') }}
                    </x-nav-li>
                    <x-nav-li>
                        {{ __('Calender') }}
                    </x-nav-li>

                    <x-nav-li route="reports.user.activities.log.index" :can="'activity_log'">
                        {{ __('Activity Log') }}
                    </x-nav-li>
                    <x-nav-li route="change_log.index">
                        {{ __('Change Log') }}
                    </x-nav-li>
                    <x-nav-li route="database-backup.index">
                        {{ __('Database Backup') }}
                    </x-nav-li>
                    <x-nav-li route="downloads.download.index">
                        {{ __('Download Center') }}
                    </x-nav-li>
                    <x-nav-li route="terms.index">
                        {{ __('Terms & Conditions') }}
                    </x-nav-li>
                    <x-nav-li route="feedback.index">
                        {{ __('Feedback') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="crmApp" style="{{ request()->is('crm/*') ? 'display: block' : '' }}">
            <span class="submenu-title">CRM</span>
            <ul>
                <x-nav-li route="crm.dashboard.index"> {{ __('CRM Dashboard') }}</x-nav-li>
                <x-nav-li route="crm.customers.index">
                    {{ __('Customers') }}
                </x-nav-li>
                <x-nav-li route="crm.business-leads.index">
                    {{ __('Business Leads') }}
                </x-nav-li>
                <x-nav-li route="crm.individual-leads.index">
                    {{ __('Individual Leads') }}
                </x-nav-li>
                <x-nav-li route="crm.source.index">
                    {{ __('Source') }}
                </x-nav-li>
                <x-nav-li route="crm.life.stage.index">
                    {{ __('Life Stage') }}
                </x-nav-li>
                <x-nav-li route="crm.followup.category.index">
                    {{ __('Followup Category') }}
                </x-nav-li>
                <x-nav-li route="crm.followup.index">
                    {{ __('Followup') }}
                </x-nav-li>
                <x-nav-li route="crm.proposal_template.index">
                    {{ __('Proposal Template') }}
                </x-nav-li>
                <x-nav-li route="crm.proposal.index">
                    {{ __('Proposal') }}
                </x-nav-li>
                <x-nav-li route="crm.settings.index">
                    {{ __('Settings') }}
                </x-nav-li>
                <x-nav-li route="crm.estimates.index">
                    {{ __('Estimate') }}
                </x-nav-li>
                <x-nav-li route="crm.appointment.index">
                    {{ __('Appointment') }}
                </x-nav-li>
                <x-nav-li route="contacts.index">
                    {{ __('Contact') }}
                </x-nav-li>
            </ul>
        </div>
        <div class="single-submenu" id="asset" style="{{ request()->is('*/asset/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Assets') }}</span>
            <ul>
                <x-nav-li route="assets.dashboard" :can="'asset_index'">
                    {{ __('Asset Dashboard') }}
                </x-nav-li>
                <x-nav-li route="assets.index" :can="'asset_index'">
                    {{ __('Assets') }}
                </x-nav-li>
                <x-nav-li route="assets.allocation.index" :can="'asset_allocation_index'">
                    {{ __('Allocations') }}
                </x-nav-li>
                <x-nav-li route="assets.revoke.index" :can="'asset_revokes_index'">
                    {{ __('Revokes') }}
                </x-nav-li>
                <x-nav-li route="assets.depreciation.index" :can="'asset_depreciation_index'">
                    {{ __('Depreciation') }}
                </x-nav-li>
                <x-nav-li route="assets.request.index" :can="'asset_requests_index'">
                    {{ __('Requests') }}
                </x-nav-li>
                <x-nav-li route="assets.licenses.index" :can="'asset_licenses_index'">
                    {{ __('Licenses') }}
                </x-nav-li>
                <x-nav-li route="assets.supplier.index">
                    {{ __('Suppliers') }}
                </x-nav-li>
                <x-nav-li route="assets.consume.services.index">
                    {{ __('Consume Services') }}
                </x-nav-li>
                <x-nav-li route="assets.audit.index" :can="'asset_audits_index'">
                    {{ __('Consume Services') }}
                </x-nav-li>
                <x-nav-li route="assets.settings.index" :can="'asset_settings'">
                    {{ __('Settings') }}
                </x-nav-li>
            </ul>
        </div>
        <div class="single-submenu" id="projectManagement" style="{{ request()->is('project/management/task/management*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Project Management') }}</span>
            <ul>
                <x-nav-li> {{ __('Project Management Dashboard') }}</x-nav-li>
                @canany(['assign_todo', 'work_space', 'memo', 'msg'])
                    <x-nav-li route="todo.index" :can="'assign_todo'">
                        {{ __('Todo') }}
                    </x-nav-li>
                    <x-nav-li route="workspace.index" :can="'work_space'">
                        {{ __('Work Space') }}
                    </x-nav-li>
                    <x-nav-li route="memos.index" :can="'memo'">
                        {{ __('Memo') }}
                    </x-nav-li>
                    <x-nav-li route="messages.index" :can="'msg'">
                        {{ __('Message') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="weightScale" style="{{ request()->is('weight/scales*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Weight Scale') }}</span>
            <ul>
                <x-nav-li> {{ __('Weight Scale Dashboard') }}</x-nav-li>
                <x-nav-li route="scale.create" :can="'add_weight_scale'">
                    {{ __('Add Weight') }}
                </x-nav-li>
                <x-nav-li route="scale.index" :can="'index_weight_scale'">
                    {{ __('Weight List') }}
                </x-nav-li>
                <x-nav-li route="scale.client.index" :can="'index_weight_scale_client'">
                    {{ __('Client List') }}
                </x-nav-li>
            </ul>
        </div>
        {{-- <div class="single-submenu  {{ request()->is('sales/app/customers*') || request()->is('procurement/suppliers*') ? 'd-block' : '' }}" id="contacts"> --}}
        <div class="single-submenu" id="contacts" style="{{ request()->is('contacts/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Contacts') }}</span>
            <ul>
                <x-nav-li route="contacts.index2"> {{ __('Contacts') }}</x-nav-li>
                @canany(['customer_all', 'customer_manage', 'customer_import', 'customer_group'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="contacts/customers*">
                        {{ __('Customers') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="contacts/customers*">
                                <x-nav-li route="contacts.customers.index" :can="'customer_all'">
                                    {{ __('Customer List') }}
                                </x-nav-li>
                                <x-nav-li route="contacts.customers.import.create" :can="'customer_import'">
                                    {{ __('Import Customers') }}
                                </x-nav-li>
                                <x-nav-li route="customers.groups.index" :can="'customer_group'">
                                    {{ __('Customer Groups') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['supplier_all', 'supplier_import', 'customer_import', 'customer_group'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="contacts/procurement*">
                        {{ __('Suppliers') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="contacts/procurement*">
                                <x-nav-li route="contacts.supplier.index" :can="'supplier_all'">
                                    {{ __('Supplier List') }}
                                </x-nav-li>
                                <x-nav-li route="contacts.suppliers.import.create" :can="'supplier_import'">
                                    {{ __('Import Suppliers') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
            </ul>
        </div>

        <div class="single-submenu" id="website" style="{{ request()->is('websites/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Manage Website') }}</span>
            <ul>
                @canany([
                    'web_manage_client',
                    'web_add_client',
                    'web_edit_client',
                    'web_delete_client',
                    'web_requisition_show',
                    'web_requisition_delete',
                    'web_manage_partner',
                    'web_add_partner',
                    'web_edit_partner',
                    'web_delete_partner',
                    'web_manage_team',
                    'web_add_team',
                    'web_edit_team',
                    'web_delete_team',
                    'web_manage_category',
                    'web_add_category',
                    'web_edit_category',
                    'web_delete_category',
                    'web_manage_product',
                    'web_add_product',
                    'web_edit_product',
                    'web_delete_product',
                    'web_manage_job_category',
                    'web_add_job_category',
                    'web_edit_job_category',
                    'web_delete_job_category',
                    'web_manage_job',
                    'web_add_job',
                    'web_edit_job',
                    'web_delete_job',
                    'web_job_applied_download',
                    'web_job_applied_delete',
                    'web_manage_gallery_category',
                    'web_add_gallery_category',
                    'web_edit_gallery_category',
                    'web_delete_gallery_category',
                    'web_manage_gallery',
                    'web_add_gallery',
                    'web_edit_gallery',
                    'web_delete_gallery',
                    'web_manage_blog_category',
                    'web_add_blog_category',
                    'web_edit_blog_category',
                    'web_delete_blog_category',
                    'web_manage_blog',
                    'web_add_blog',
                    'web_edit_blog',
                    'web_delete_blog',
                    'web_manage_comment',
                    'web_edit_comment',
                    'web_delete_comment',
                    'web_manage_page',
                    'web_add_page',
                    'web_edit_page',
                    'web_delete_page',
                    'web_about_us',
                    'web_history',
                    'web_message_of_director',
                    'web_manage_testimonial',
                    'web_add_testimonial',
                    'web_edit_testimonial',
                    'web_delete_testimonial',
                    'web_manage_campaign',
                    'web_add_campaign',
                    'web_edit_campaign',
                    'web_delete_campaign',
                    'web_manage_faq',
                    'web_add_faq',
                    'web_edit_faq',
                    'web_delete_faq',
                    'web_manage_buet_test',
                    'web_add_buet_test',
                    'web_edit_buet_test',
                    'web_delete_buet_test',
                    'web_manage_dealership_requests',
                    'web_delete_dealership_request',
                    'web_manage_slider',
                    'web_add_slider',
                    'web_edit_slider',
                    'web_delete_slider',
                    'web_manage_video',
                    'web_add_video',
                    'web_edit_video',
                    'web_delete_video',
                    'web_manage_country',
                    'web_add_country',
                    'web_edit_country',
                    'web_delete_country',
                    'web_manage_city',
                    'web_add_city',
                    'web_edit_city',
                    'web_delete_city',
                    'general_setting',
                    'seo',
                    'social_link',
                    'banner',
                    'contact',
                    ])
                    <x-nav-li> {{ __('Website Dashboard') }}</x-nav-li>
                    @canany(['web_manage_client', 'web_add_client', 'web_edit_client', 'web_delete_client', 'web_requisition_show', 'web_requisition_delete', 'web_manage_partner', 'web_add_partner', 'web_edit_partner', 'web_delete_partner', 'web_manage_team', 'web_add_team', 'web_edit_team', 'web_delete_team'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/contacts/*">
                            {{ __('Contacts') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/contacts/*">
                                    @if (Route::has('website.clients.index'))
                                        <x-nav-li route="website.clients.index" :can="'web_manage_client'">
                                            {{ __('Clients') }}
                                        </x-nav-li>
                                    @endif
                                    <x-nav-li route="website.buyer-requisition.index" :can="'web_requisition_show' || 'web_requisition_delete'">
                                        {{ __('Buyer Requisition') }}
                                    </x-nav-li>
                                    @if (Route::has('website.partners.index'))
                                        <x-nav-li route="website.partners.index" :can="'web_manage_team'">
                                            {{ __('Partners') }}
                                        </x-nav-li>
                                    @endif
                                    @if (Route::has('website.teams.index'))
                                        <x-nav-li route="website.teams.index" :can="'web_manage_partner'">
                                            {{ __('Teams') }}
                                        </x-nav-li>
                                    @endif
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['web_manage_category', 'web_add_category', 'web_edit_category', 'web_delete_category', 'web_manage_product', 'web_add_product', 'web_edit_product', 'web_delete_product'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-products/*">
                            {{ __('Product') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-products/*">
                                    @if (Route::has('website.categories.index'))
                                        <x-nav-li route="website.categories.index" :can="'web_manage_category'">
                                            {{ __('Categories') }}
                                        </x-nav-li>
                                    @endif
                                    <x-nav-li route="website.products.index" :can="'web_manage_product'">
                                        {{ __('Products') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['web_manage_job_category', 'web_add_job_category', 'web_edit_job_category', 'web_delete_job_category', 'web_manage_job', 'web_add_job', 'web_edit_job', 'web_delete_job', 'web_job_applied_download', 'web_job_applied_delete'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-careers/*">
                            {{ __('Career') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-careers/*">
                                    @if (Route::has('website.job-categories.index'))
                                        <x-nav-li route="website.job-categories.index" :can="'web_manage_job_category'">
                                            {{ __('Job Category') }}
                                        </x-nav-li>
                                    @endif
                                    @if (Route::has('website.jobs.index'))
                                        <x-nav-li route="website.jobs.index" :can="'web_manage_job'">
                                            {{ __('Job') }}
                                        </x-nav-li>
                                    @endif
                                    <x-nav-li route="website.job-applied.index" :can="'web_job_applied_download'">
                                        {{ __('Job Applied') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['web_manage_gallery_category', 'web_add_gallery_category', 'web_edit_gallery_category', 'web_delete_gallery_category', 'web_manage_gallery', 'web_add_gallery', 'web_edit_gallery', 'web_delete_gallery'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-gallery/*">
                            {{ __('Gallery') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-gallery/*">
                                    <x-nav-li route="website.gallery-categories.index" :can="'web_manage_gallery_category'">
                                        {{ __('Category') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.gallery.index" :can="'web_manage_gallery'">
                                        {{ __('Gallery') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['web_manage_blog_category', 'web_add_blog_category', 'web_edit_blog_category', 'web_delete_blog_category', 'web_manage_blog', 'web_add_blog', 'web_edit_blog', 'web_delete_blog', 'web_manage_comment', 'web_edit_comment', 'web_delete_comment'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-blog/*">
                            {{ __('Blog') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-blog/*">
                                    <x-nav-li route="website.blog-categories.index" :can="'web_manage_blog_category'">
                                        {{ __('Category') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.blog.index" :can="'web_manage_blog'">
                                        {{ __('Blog') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.comments" :can="'web_manage_comment'">
                                        {{ __('Post Comments') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['web_manage_page', 'web_add_page', 'web_edit_page', 'web_delete_page', 'web_about_us', 'web_history', 'web_message_of_director'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-pages/*">
                            {{ __('Pages') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-pages/*">
                                    <x-nav-li route="website.pages.index" :can="'web_manage_page'">
                                        {{ __('Pages') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.about.us" :can="'web_about_us'">
                                        {{ __('About Us') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.history" :can="'web_history'">
                                        {{ __('History') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.message.director" :can="'web_message_of_director'">
                                        {{ __('Message Of Director') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                        </x-nav-parent-li>
                    @endcanany

                    @canany(['general_setting', 'seo', 'social_link', 'web_manage_testimonial', 'web_add_testimonial', 'web_edit_testimonial', 'web_delete_testimonial', 'web_manage_campaign', 'web_add_campaign', 'web_edit_campaign', 'web_delete_campaign', 'web_manage_faq', 'web_add_faq', 'web_edit_faq', 'web_delete_faq', 'web_manage_buet_test', 'web_add_buet_test', 'web_edit_buet_test', 'web_delete_buet_test', 'web_manage_dealership_requests', 'web_delete_dealership_request', 'web_manage_slider', 'web_add_slider', 'web_edit_slider', 'web_delete_slider', 'web_manage_video', 'web_add_video', 'web_edit_video', 'web_delete_video', 'web_manage_country', 'web_add_country', 'web_edit_country', 'web_delete_country', 'web_manage_city', 'web_add_city', 'web_edit_city', 'web_delete_city', 'banner',
                        'contact'])
                        <x-nav-parent-li role="button" class="has-sub" prefix="websites/manage-settings/*">
                            {{ __('Settings') }}
                            <x-slot name="after">
                                <x-nav-ul prefix="websites/manage-settings/*">
                                    <x-nav-li route="website.general.settings" :can="'general_setting'">
                                        {{ __('General Settings') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.seo.settings" :can="'seo'">
                                        {{ __('SEO') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.social.link" :can="'social_link'">
                                        {{ __('Social Link') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.testimonial.index" :can="'web_manage_testimonial'">
                                        {{ __('Testimonial') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.campaign.index" :can="'web_manage_campaign'">
                                        {{ __('Campaign') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.award.index" :can="'web_manage_award'">
                                        {{ __('Award List') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.banner.index" :can="'web_manage_banner'">
                                        {{ __('Banner') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.faq.index" :can="'web_manage_faq'">
                                        {{ __('FAQ') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.contact" :can="'contact'">
                                        {{ __('Contact') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.report.index" :can="'web_manage_report'">
                                        {{ __('Buet Test') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.dealership.request" :can="'dealership-request'">
                                        {{ __('Dealership_request') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.slider.index" :can="'web_manage_slider'">
                                        {{ __('Slider') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.video.index" :can="'web_manage_video'">
                                        {{ __('Video') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.country.index" :can="'web_manage_country'">
                                        {{ __('Country') }}
                                    </x-nav-li>
                                    <x-nav-li route="website.city.index" :can="'web_manage_city'">
                                        {{ __('City') }}
                                    </x-nav-li>
                                </x-nav-ul>
                            </x-slot>
                            </x-nav-li>
                        @endcanany
                    @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="modules" style="{{ request()->is('modules/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Modules') }}</span>
            <ul>
                <x-nav-li route="modules.purchases"> {{ __('Modules Dashboard') }}</x-nav-li>
            </ul>
        </div>
        <div class="single-submenu" id="users" style="{{ request()->is('user-manage/*') ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Users') }}</span>
            <ul>
                @canany(['user_view', 'user_add', 'role_view', 'role_add'])
                    <x-nav-li route="users.create" :can="'user_add'">
                        {{ __('Add New') }}
                    </x-nav-li>
                    <x-nav-li route="users.index" :can="'user_view'">
                        {{ __('All User') }}
                    </x-nav-li>
                    <x-nav-li route="users.role.create" :can="'role_add'">
                        {{ __('Add Roles') }}
                    </x-nav-li>
                    <x-nav-li route="users.role.index" :can="'role_view'">
                        {{ __('Role List') }}
                    </x-nav-li>
                @endcanany
            </ul>
        </div>
        <div class="single-submenu" id="setup" style="{{ (request()->is('settings/*') || request()->is('core/locations/*')) ? 'display: block' : '' }}">
            <span class="submenu-title">{{ __('Set-up') }}</span>
            <ul>
                <x-nav-li> {{ __('Setting Dashboard') }}</x-nav-li>
                @canany(['g_settings', 'p_settings', 'barcode_settings'])
                    <x-nav-parent-li role="buttton" class="has-sub" prefix="settings/core/*">
                        {{ __('Settings') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="settings/core/*">
                                <x-nav-li route="settings.general.index" :can="'g_settings'">
                                    {{ __('General Settings') }}
                                </x-nav-li>
                                <x-nav-li route="settings.payment.method.settings.index" :can="'p_settings'">
                                    {{ __('Payment Method Settings') }}
                                </x-nav-li>
                                <x-nav-li route="settings.barcode.index" :can="'barcode_settings'">
                                    {{ __('Barcode Settings') }}
                                </x-nav-li>
                                <x-nav-li :can="'reset'">
                                    {{ __('Reset') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['warehouse', 'inv_sc', 'inv_lay', 'cash_counters'])
                    <x-nav-parent-li role="button" class="has-sub" prefix="settings/app-setup/*">
                        {{ __('App Set Up') }}
                        <x-slot name="after">
                            <x-nav-ul prefix="settings/app-setup/*">
                                <x-nav-li route="settings.warehouses.index" :can="'warehouse'">
                                    {{ __('Warehouses') }}
                                </x-nav-li>
                                <x-nav-li route="settings.payment.method.index" :can="'p_settings'">
                                    {{ __('Payment Methods') }}
                                </x-nav-li>
                                <x-nav-li route="invoices.schemas.index" :can="'inv_sc'">
                                    {{ __('Invoice Schema') }}
                                </x-nav-li>
                                <x-nav-li route="invoices.layouts.index" :can="'inv_lay'">
                                    {{ __('Invoice Layout') }}
                                </x-nav-li>
                                <x-nav-li route="settings.cash.counter.index" :can="'cash_counters'">
                                    {{ __('Cash Counter') }}
                                </x-nav-li>
                                <x-nav-li route="modules.control">
                                    {{ __('Control Modules') }}
                                </x-nav-li>
                            </x-nav-ul>
                        </x-slot>
                    </x-nav-parent-li>
                @endcanany
                @canany(['hrm_divisions_index', 'hrm_districts_index', 'hrm_divisions_index', 'hrm_divisions_index'])
                <x-nav-parent-li role="button" class="has-sub" prefix="core/locations/*">
                    {{ __('Locations') }}
                    <x-slot name="after">
                        <x-nav-ul prefix="core/locations/*">
                            <x-nav-li route="core.bd-divisions.index">
                                {{ __('BD Divisions') }}
                            </x-nav-li>
                            <x-nav-li route="core.bd-districts.index">
                                {{ __('BD Districts') }}
                            </x-nav-li>
                            <x-nav-li route="core.bd-upazila.index">
                                {{ __('BD Upazilas') }}
                            </x-nav-li>
                            <x-nav-li route="core.bd-unions.index">
                                {{ __('BD  Unions') }}
                            </x-nav-li>
                        </x-nav-ul>
                    </x-slot>
                </x-nav-parent-li>
                @endcanany
            </ul>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".menu-style-switch").on('click', function(e) {
                e.preventDefault();
                let isHorizontal = window.localStorage.getItem('isHorizontal') === 'true';
                if (isHorizontal) {
                    document.getElementById('main-wraper').style.marginLeft = '220px';
                    window.localStorage.setItem('isHorizontal', false);
                }
                if (!isHorizontal) {
                    document.getElementById('main-wraper').style.marginLeft = '0px';
                    window.localStorage.setItem('isHorizontal', true);
                }
            })
        });

        // Retrieve the saved state from local storage on page load
        // $(document).ready(function() {
        //     var sidebarState = localStorage.getItem('sidebarState');
        //     if (sidebarState === 'open') {
        //         $('.left-sidebar').addClass('open-sub');
        //         $('#main-wraper').addClass('menu-expanded');
        //         $('#shortcut-section').addClass('menu-expanded');
        //     }
        // });

        // Attach click event handler to save the state in local storage and toggle classes
        $('.left-sidebar-toggler').on('click', function() {
            var sidebar = $('.left-sidebar');
            var mainWrapper = $('#main-wraper');
            var shortcutSection = $('#shortcut-section');

            // Toggle classes
            sidebar.toggleClass('open-sub');
            mainWrapper.toggleClass('menu-expanded');
            shortcutSection.toggleClass('menu-expanded');

            // Save the state in local storage
            var sidebarState = sidebar.hasClass('open-sub') ? 'open' : 'closed';
            localStorage.setItem('sidebarState', sidebarState);
        });

        if ($('.left-sidebar').hasClass('open-sub')) {
            $('#shortcut-section').addClass('menu-expanded');
        }

        $('.main-menu-link').on('click', function() {
            var submenu_id = $(this).attr('data-submenu');
            $('.left-sidebar').addClass('open-sub');
            $(this).addClass('open').parent().siblings().find('.main-menu-link').removeClass('open');
            $('.left-sidebar').addClass('open-sub');
            $('#main-wraper').addClass('menu-expanded');
            if (!$('.left-sidebar').hasClass('horizontal-menu')) {
                $('#' + submenu_id).show().siblings().hide();
            }
        });

        $('.single-submenu .submenu-link.has-sub').on('click', function() {
            $(this).toggleClass('open').parent().siblings().find('.submenu-link.has-sub').removeClass('open');
            $(this).parents('.single-submenu').siblings().find('.submenu-link.has-sub').removeClass('open');
            $(this).siblings('.submenu-group').toggle();
            $(this).parent().siblings().find('.submenu-group').hide();
            $(this).parents('.single-submenu').siblings().find('.submenu-group').hide();
        });

        $('.menu-style-switch').on('click', function() {
            $('.left-sidebar').toggleClass('horizontal-menu');
            $('body').toggleClass('horizontal-menu-active');
            $('.main-wraper').toggleClass('has-horizontal has-vertical');
            $('#shortcut-section').toggleClass('has-horizontal has-vertical');

            if (!$('.left-sidebar').hasClass('vertical-menu')) {
                $(this).find('span:last-child').text('Vertical');
                $('.main-menu-link').attr('data-bs-toggle', '');
                $('.main-menu-link').each(function() {
                    var isMouseOverElement = false;
                    // $(this).mouseenter(function() {
                    //     $(this).addClass('open');
                    // });
                    // $(this).mouseleave(function() {
                    //     $(this).removeClass('open');
                    // });

                    // $('.left-sidebar').mouseleave(function() {
                    //     $('.single-submenu').removeClass('d-block');
                    // });

                    $(this, '.single-submenu').mouseenter(function() {
                        var submenuId = $(this).data('submenu');
                        var submenuElement = $('#' + submenuId);
                        var submenuElementLast = submenuElement.parent().children(':last-child');
                        var isLastChild = submenuElement.is(submenuElementLast);
                        if (submenuElement.length) {
                            var position = $(this).offset();
                            var top = $(this).outerHeight();
                            var left;
                            if (isLastChild) {
                                left = position.left + $(this).outerWidth() - submenuElement
                                    .outerWidth();
                            } else {
                                left = position.left;
                            }
                            submenuElement.css({
                                top: top,
                                left: left
                            });
                            submenuElement.addClass('d-block').siblings().removeClass('d-block');
                        }
                    });

                    $('.single-submenu').mouseover(function() {
                        var submenuId = $(this).attr('id');
                        $('.main-menu-link[data-submenu=' + submenuId + ']').addClass('open');
                    });

                    $('.single-submenu').mouseleave(function() {
                        var submenuElement = $(this);
                        submenuElement.removeClass('d-block');
                        $('.main-menu-link').removeClass('open');
                    });

                    $(this).removeClass('open');
                    $('.single-submenu').hide();
                    $('.submenu-link.has-sub').removeClass('open');
                });

                var helpNav = $('.help-nav').outerWidth();
                $('.nav-arrow').css('right', helpNav);
                var navArrowWidth = $('.nav-arrow').outerWidth();
                $('.main-nav-list').width(navArrowWidth - 60)


                $('.single-submenu .submenu-link.has-sub').off('click');

                var scroller = document.querySelector('.main-nav-list');
                var leftArrow = document.getElementById('leftArrow');
                var direction = 0;
                var active = false;
                var max = 10;
                var Vx = 0;
                var x = 0.0;
                var prevTime = 0;
                var f = 0.2;
                var prevScroll = 0;

                function physics(time) {
                    var diffTime = time - prevTime;
                    if (!active) {
                        diffTime = 80;
                        active = true;
                    }
                    prevTime = time;

                    Vx = (direction * max * f + Vx * (1 - f)) * (diffTime / 20);

                    x += Vx;
                    var thisScroll = scroller.scrollLeft;
                    var nextScroll = Math.floor(thisScroll + Vx);

                    if (Math.abs(Vx) > 0.5 && nextScroll !== prevScroll) {
                        scroller.scrollLeft = nextScroll;
                        requestAnimationFrame(physics);
                    } else {
                        Vx = 0;
                        active = false;
                    }
                    prevScroll = nextScroll;
                }

                leftArrow.addEventListener('mousedown', function() {
                    direction = -1;
                    if (!active) {
                        requestAnimationFrame(physics);
                    }
                });

                leftArrow.addEventListener('mouseup', function() {
                    direction = 0;
                });

                rightArrow.addEventListener('mousedown', function() {
                    direction = 1;
                    if (!active) {
                        requestAnimationFrame(physics);
                    }
                });

                rightArrow.addEventListener('mouseup', function(event) {
                    direction = 0;
                });

                $(scroller).on('scroll', function() {
                    if ($(this).scrollLeft() < 1) {
                        $(leftArrow).prop('disabled', true);
                    } else {
                        $(leftArrow).prop('disabled', false);
                    }
                    if ($(this).scrollLeft() + 30 + $(this).outerWidth() >= $(this)[0].scrollWidth) {
                        $(rightArrow).prop('disabled', true);
                    } else {
                        $(rightArrow).prop('disabled', false);
                    }
                });


                var rightArrowButton = document.querySelector('#rightArrow');
                if (scroller.scrollWidth > scroller.clientWidth) {
                    rightArrowButton.disabled = false;
                } else {
                    rightArrowButton.disabled = true;
                }
            }

            if (!$('.left-sidebar').hasClass('horizontal-menu')) {
                $(this).find('span:last-child').text('Horizontal');
                $('.main-nav-list').css('width', '100%');
                $('.main-menu-link').unbind('mouseenter mouseleave');
                $('.single-submenu').unbind('mouseleave');
                $('.single-submenu:first-child').show();
                $('.single-submenu .submenu-link.has-sub').on('click', function() {
                    $(this).toggleClass('open').parent().siblings().find('.submenu-link.has-sub')
                        .removeClass('open');
                    $(this).parents('.single-submenu').siblings().find('.submenu-link.has-sub').removeClass(
                        'open');
                    $(this).siblings('.submenu-group').toggle();
                    $(this).parent().siblings().find('.submenu-group').hide();
                    $(this).parents('.single-submenu').siblings().find('.submenu-group').hide();
                });
            }
        });

        if ($('.left-sidebar').hasClass('horizontal-menu')) {
            $('.menu-style-switch').find('span:last-child').text('Vertical');
            $('body').addClass('horizontal-menu-active');
            $('.main-wraper').addClass('has-horizontal');
            $('.main-menu-link').attr('data-bs-toggle', '');

            $('.main-menu-link').each(function() {
                var isMouseOverElement = false;
                $(this).mouseenter(function() {
                    $(this).addClass('open');
                });
                $(this).mouseleave(function() {
                    $(this).removeClass('open');
                });
                $('.left-sidebar').mouseleave(function() {
                    $('.single-submenu').removeClass('d-block');
                });

                $(this, '.single-submenu').mouseenter(function() {
                    var submenuId = $(this).data('submenu');
                    var submenuElement = $('#' + submenuId);
                    var submenuElementLast = submenuElement.parent().children(':last-child');
                    var isLastChild = submenuElement.is(submenuElementLast);
                    if (submenuElement.length) {
                        var position = $(this).offset();
                        var top = $(this).outerHeight();
                        var left;
                        if (isLastChild) {
                            left = position.left + $(this).outerWidth() - submenuElement.outerWidth();
                        } else {
                            left = position.left;
                        }
                        submenuElement.css({
                            top: top,
                            left: left
                        });
                        submenuElement.addClass('d-block').siblings().removeClass('d-block');
                    }
                });

                $('.single-submenu').mouseover(function() {
                    var submenuId = $(this).attr('id');
                    $('.main-menu-link[data-submenu=' + submenuId + ']').addClass('open');
                });

                $('.single-submenu').mouseleave(function() {
                    var submenuElement = $(this);
                    submenuElement.removeClass('d-block').hide();
                    $('.main-menu-link').removeClass('open');
                });
            });


            var helpNav = $('.help-nav').outerWidth();
            $('.nav-arrow').css('right', helpNav);
            var navArrowWidth = $('.nav-arrow').outerWidth();
            $('.main-nav-list').width(navArrowWidth - 60)


            $('.single-submenu .submenu-link.has-sub').unbind('click');


            var scroller = document.querySelector('.main-nav-list');
            var leftArrow = document.getElementById('leftArrow');
            var direction = 0;
            var active = false;
            var max = 10;
            var Vx = 0;
            var x = 0.0;
            var prevTime = 0;
            var f = 0.2;
            var prevScroll = 0;

            function physics(time) {
                var diffTime = time - prevTime;
                if (!active) {
                    diffTime = 80;
                    active = true;
                }
                prevTime = time;

                Vx = (direction * max * f + Vx * (1 - f)) * (diffTime / 20);

                x += Vx;
                var thisScroll = scroller.scrollLeft;
                var nextScroll = Math.floor(thisScroll + Vx);

                if (Math.abs(Vx) > 0.5 && nextScroll !== prevScroll) {
                    scroller.scrollLeft = nextScroll;
                    requestAnimationFrame(physics);
                } else {
                    Vx = 0;
                    active = false;
                }
                prevScroll = nextScroll;
            }
            leftArrow.addEventListener('mousedown', function() {
                direction = -1;
                if (!active) {
                    requestAnimationFrame(physics);
                }
            });
            leftArrow.addEventListener('mouseup', function() {
                direction = 0;
            });
            rightArrow.addEventListener('mousedown', function() {
                direction = 1;
                if (!active) {
                    requestAnimationFrame(physics);
                }
            });
            rightArrow.addEventListener('mouseup', function(event) {
                direction = 0;
            });
            $(scroller).on('scroll', function() {
                if ($(this).scrollLeft() < 1) {
                    $(leftArrow).prop('disabled', true);
                } else {
                    $(leftArrow).prop('disabled', false);
                }
                if ($(this).scrollLeft() + 30 + $(this).outerWidth() >= $(this)[0].scrollWidth) {
                    $(rightArrow).prop('disabled', true);
                } else {
                    $(rightArrow).prop('disabled', false);
                }
            });

            if (scroller.scrollWidth > scroller.clientWidth) {
                rightArrow.disabled = false;
            } else {
                rightArrow.disabled = true;
            }
        } else {
            $('.main-wraper').addClass('has-vertical');
        }


        $(document).ready(function() {
            const $scrollableElement = $(".main-nav-list");
            let isMouseDown = false;
            let startX;
            let startY;

            $scrollableElement.on("mousedown", function(event) {
                isMouseDown = true;
                startX = event.pageX - $scrollableElement.offset().left;
                startY = event.pageY - $scrollableElement.offset().top;
            });

            $(document).on("mouseup", function() {
                isMouseDown = false;
            });

            $(document).on("mousemove", function(event) {
                if (!isMouseDown) return;

                event.preventDefault();
                const currentX = event.pageX - $scrollableElement.offset().left;
                const currentY = event.pageY - $scrollableElement.offset().top;

                const scrollLeft = $scrollableElement.scrollLeft() + startX - currentX;
                const scrollTop = $scrollableElement.scrollTop() + startY - currentY;

                $scrollableElement.scrollLeft(scrollLeft);
                $scrollableElement.scrollTop(scrollTop);
            });
        });
    </script>
@endpush
