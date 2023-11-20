@extends('layout.master')
@push('css')
@endpush
@section('content')
    <!-- Header ends -->
    <div class="body-woaper">
        <div class="main__content">
            <div class="form_element rounded m-0">
                <div class="element-body">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-available-tab" data-bs-toggle="tab" data-bs-target="#nav-available" type="button" role="tab" aria-controls="nav-available" aria-selected="true">
                                @lang('menu.available_app')/@lang('menu.modules')
                            </button>
                            <button class="nav-link" id="nav-find-external-tab" data-bs-toggle="tab" data-bs-target="#nav-find-external" type="button" role="tab" aria-controls="nav-find-external" aria-selected="false">
                                @lang('menu.find_external_app')/@lang('menu.modules')
                            </button>
                            <button class="nav-link" id="nav-deploy-tab" data-bs-toggle="tab" data-bs-target="#nav-deploy" type="button" role="tab" aria-controls="nav-deploy" aria-selected="false">
                                @lang('menu.deploy')/ @lang('menu.external_app')/@lang('menu.modules')
                            </button>
                            <button class="nav-link" id="nav-develop-tab" data-bs-toggle="tab" data-bs-target="#nav-develop" type="button" role="tab" aria-controls="nav-develop" aria-selected="false">
                                @lang('menu.develop_your_own_app')/@lang('menu.modules')
                            </button>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-available" role="tabpanel" aria-labelledby="nav-available-tab" tabindex="0">
                            <div class="note">
                                <p>
                                    ...............................................................................................
                                </p>
                            </div>
                            <div class="top-bar">
                                <div class="row g-4">
                                    <div class="col-xxl-0 col-xl-0 d-xl-block d-none"></div>
                                    <div class="col-xxl-10 col-xl-10 col-lg-10">
                                        <form action="">
                                            <input type="search" placeholder="Keyword">
                                            <select name="origin" id="">
                                                <option value="*">Origin</option>
                                                <option value="1">Option 1</option>
                                            </select>

                                            <select name="status" id="">
                                                <option value="*">@lang('menu.status')</option>
                                                <option value="1">Option 1</option>
                                            </select>

                                            <button type="submit">@lang('menu.refresh')</button>
                                            <button type="reset">@lang('menu.reset')</button>
                                            <div class="d-lg-none d-sm-block d-none">
                                                <div class="view-style-btns">
                                                    <a role="button" class="single-btn active gridView">
                                                        <i class="fas fa-th"></i>
                                                    </a>
                                                    <a role="button" class="single-btn listView">
                                                        <i class="fas fa-th-list"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-xxl-2 col-xl-2 col-lg-2 d-lg-block d-none">
                                        <div class="view-style-btns">
                                            <button class="single-btn active gridView">
                                                <i class="fas fa-th"></i>
                                            </button>
                                            <button class="single-btn listView">
                                                <i class="fas fa-th-list"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">@lang('menu.human_resource_management') (HR)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>@lang('menu.users') & @lang('menu.groups')</h6>
                                            <p>
                                                Lorem............................
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>@lang('menu.members')</h6>
                                            <p>
                                                Lorem ............
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-umbrella-beach"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Leave Request Management</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-wallet"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Expense Reports</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-id-card-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Recruitment</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Customer Relationship Management (CRM)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Third Parties</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Proposals</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-invoice"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Sales Orders</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-dolly"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Shipments</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-briefcase"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Contract/Subscriptions</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-ambulance"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Interventions</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-ticket-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Tickets</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Vendor Relationship Management (VRM)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-building"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Vendors</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-signature"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Vendor Commercial Proposals</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-dolly"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Reception</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-truck-loading"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Incoterms</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Financial Modules (Accounting/Treasury)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Invoices</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Taxes & Special Expense</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-money-check-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Salaries</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-money-bill"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>@lang('menu.loans')</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Donations</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Banks & Cash</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-money-check-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Payment By Credit Transfer</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-money-check-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Payments By Direct Debit</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-calculator"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Margins</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-search-dollar"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Accounting (Simplified)</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-search-dollar"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Accounting (Double Entry)</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Product Management (PM)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>@lang('menu.products')</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-concierge-bell"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Services</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-box-open"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Stocks</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Product Lots</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Product Variants</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-shapes"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Bills Of Material</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cubes"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Manufacturing Orders</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Projects/Collaborative work</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-project-diagram"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Projects Or Leads</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Events/Agenda</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-laptop-house"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Resources</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Electronic Content Management (ECM)</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-folder-open"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Projects Or Leads</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Multi-modules Tools</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Tags/@lang('menu.categories')</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-paragraph"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>WYSIWYG Editor</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-dollar-sign"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Multicurrency</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-rss"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>RSS Feed</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-star"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Bookmarks & Shortcuts</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Barcodes</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Inter-modules Workflow</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Data Imports</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Data Exports</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-bug"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Module & Application Builder</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Websites & Other Frontal Application</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-check-double"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Projects Or Leads</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-globe-asia"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Projects Or Leads</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cash-register"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Projects Or Leads</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">Interfaces With External Systems</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-share-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Social Networks</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-at"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Notifications On Business Event</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-at"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Mass Emailings</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-at"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Email Collector</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>API/Web Services (Rest Server)</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>API/Web Services (Soap Server)</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>LDAP</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>OAUTH</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-globe-americas"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>External Site</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-folder-open"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>FTP</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>GeoiP Maxmind</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cube"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Paybox</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <img src="images/paypal.png" alt="Paypal">
                                        </div>
                                        <div class="part-txt">
                                            <h6>Paypal</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <img src="images/letter-s.png" alt="S">
                                        </div>
                                        <div class="part-txt">
                                            <h6>Stripe</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Click To Dial</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-print"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Direct Printing</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-print"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Receipt Printers</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Mailman & SPIP</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <img src="images/g.png" alt="G">
                                        </div>
                                        <div class="part-txt">
                                            <h6>Gravatar</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="far fa-file"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Dav</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="box-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="box-group-title">System</h6>
                                    </div>
                                    <div class="col-md-6 d-flex justify-content-end">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-wrap">
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-business-time"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Scheduled Jobs</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            {{-- <div class="check-wrap"> --}}
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                                </div>
                                            {{-- </div> --}}
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="" title="">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-bug"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Debug Logs</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="" title="">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-bug"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Debug Bar</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="" title="">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-box">
                                        <div class="part-icon">
                                            <i class="fas fa-cogs"></i>
                                        </div>
                                        <div class="part-txt">
                                            <h6>Unalterable Archives</h6>
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            </p>
                                        </div>
                                        <div class="action">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckDefault">
                                            </div>
                                            <a href="#">
                                                <i class="fas fa-cog"></i>
                                            </a>
                                            <div class="popover-btn">
                                                <a role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="" title="">
                                                    <i class="fas fa-info-circle"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-find-external" role="tabpanel" aria-labelledby="nav-find-external-tab" tabindex="0">.2..</div>
                        <div class="tab-pane fade" id="nav-deploy" role="tabpanel" aria-labelledby="nav-deploy-tab" tabindex="0">..3.</div>
                        <div class="tab-pane fade" id="nav-develop" role="tabpanel" aria-labelledby="nav-develop-tab" tabindex="0">...4</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<!-- module-permission js -->
<script>
    $(document).ready(function() {

        $('#settingsNav').on('click', function() {
            $('.settings-nav').addClass('active');
        });
        $('#settingsNavClose').on('click', function() {
            $('.settings-nav').removeClass('active');
        });

        $('.gridView').on('click', function() {
            $(this).addClass('active').siblings().removeClass('active');
            $('.box-wrap').removeClass('list-view');
        });
        $('.listView').on('click', function() {
            $(this).addClass('active').siblings().removeClass('active');
            $('.box-wrap').addClass('list-view');
        });
        if ($(window).width() < 992) {
            $('.box-wrap').removeClass('list-view');
        }

        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    });
</script>
<!-- module-permission js -->

@endpush
