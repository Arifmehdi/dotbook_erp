@extends('layout.master')
@section('content')
@php
$setting = Modules\Website\Entities\GeneralSetting::first();
@endphp
<div class="body-wraper">
    <div class="container-fluid">
        <div class="row">
            <div class="main__content">
                <h1>{{ $setting->site_name }} Dashboard</h1>
                <div class="p-15">
                    <div class="dashboard-bg">
                        @php
                            $testimonials = Modules\Website\Entities\Testimonial::count();
                            $jobs =  Modules\Website\Entities\Job::count();
                            $job_applied =  Modules\Website\Entities\JobApply::count();
                            $partners = Modules\Website\Entities\Partner::count();
                            $clients = Modules\Website\Entities\Client::count();
                            $blogs = Modules\Website\Entities\Blog::count();
                            $categories = Modules\Website\Entities\ProductCategory::count();
                            $products = Modules\Website\Entities\Product::count();
                        @endphp
                        <div class="d-flex justify-content-end pt-2 px-3"></div>
                        <div class="mx-lg-3 mx-2 mt-2">
                            <div class="row g-xl-4 g-lg-3 mb-3 g-3">
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Testimonials</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $testimonials }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Jobs</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $jobs }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Job Applied</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $job_applied }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Partners</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $partners }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Clients</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $clients }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Blogs</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $blogs }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Product Categories</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $categories }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-6">
                                    <div class="card-counter primary d-flex justify-content-around align-content-center">
                                        <div class="card-body">
                                            <h5 class="card-title">Products</h5>
                                            <p class="d-flex justify-content-end"><strong class="badge rounded-pill bg-primary fs-2">{{ $products }}</strong></p>
                                            <a href="{{ route('website.testimonial.index') }}" class="card-link">Manage</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Add shortcut menu modal-->
    <div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal ui-draggable ui-draggable-handle" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="payment_heading">Add POS Shortcut Menus</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body" id="modal-body_shortcuts">
                    <!--begin::Form-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
