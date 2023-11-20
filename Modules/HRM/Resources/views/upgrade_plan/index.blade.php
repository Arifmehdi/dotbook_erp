@extends('layout.master')
@push('css')

@endpush

@section('title', 'HRM Dashboard - ')

@section('content')

<section class="p-15">
    <div class="row g-1">
        <div class="col-md-9">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body">
                    {{-- <div class="section-header mb-3">
                        <h6>{{ __('Upgrade Plan') }}</h6>
                    </div> --}}
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">Basic Package</a>
                                        </h6>
                                        <br>
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">                                        <small class="card-text">For individuals or teams just getting started with project management</small></a>
                                        </h6>
                                        <br>

                                        <p class="card-text">User : 0 - 50</p><br>
                                        <h3 class="mb-0 font-weight-semibold text-default">$25.00 / Month</h3>
                                        <p class="card-text">24/7 support</p>
                                        <br>
                                        <a href="#" class="btn btn-primary"><i class="fa-light fa-cart-plus"></i>&nbsp; Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">Premium Package</a>
                                        </h6>
                                        <br>
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">                                        <small class="card-text">For teams that need to create project plans with confidence.</small></a>
                                        </h6>
                                        <br>

                                        <p class="card-text">User : 50 - 100</p><br>
                                        <h3 class="mb-0 font-weight-semibold text-default">$50.00 / Month</h3>
                                        <p class="card-text">24/7 support</p>
                                        <br>
                                        <a href="#" class="btn btn-primary"><i class="fa-light fa-cart-plus"></i>&nbsp; Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">Enterprise Beginner</a>
                                        </h6>
                                        <br>
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">                                        <small class="card-text">For teams and companies that need to manage work across initiatives.
                                            </small></a>
                                        </h6>
                                        <br>

                                        <p class="card-text">User : 101 - 300</p><br>
                                        <h3 class="mb-0 font-weight-semibold text-default">$80.00 / Month</h3>
                                        <p class="card-text">24/7 support</p>
                                        <br>
                                        <a href="#" class="btn btn-primary"><i class="fa-light fa-cart-plus"></i>&nbsp; Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">Enterprise Advanced</a>
                                        </h6>
                                        <br>
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">                                        <small class="card-text">For organizations that need additional security, control, and support.</small></a>
                                        </h6>
                                        <br>

                                        <p class="card-text">User : 301 - 1000</p><br>
                                        <h3 class="mb-0 font-weight-semibold text-default">Semi Pro</h3>
                                        <p class="card-text">24/7 support</p>
                                        <br>
                                        <a href="#" class="btn btn-primary"><i class="fa-light fa-cart-plus"></i>&nbsp; Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">Premium Package</a>
                                        </h6>
                                        <br>
                                        <h6 class="card-title">
                                            <a href="#" class="text-default">                                        <small class="card-text">For organizations that need additional security, control, and support.</small></a>
                                        </h6>
                                        <br>

                                        <p class="card-text">User : 1000+</p><br>
                                        <h3 class="mb-0 font-weight-semibold text-default">Pro</h3>
                                        <p class="card-text">24/7 support</p>
                                        <br>
                                        <a href="#" class="btn btn-primary"><i class="fa-light fa-cart-plus"></i>&nbsp; Contact Us</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body">
                    <div class="sidebar-content">

                        <!-- Categories -->
                        <div class="card">
                            <div class="card-header bg-transparent header-elements-inline">
                                {{-- <button type="button" class="collapsible">Open Collapsible</button>
                                <div class="content">
                                <p>Lorem ipsum...</p>
                                </div> --}}
                                <span class="text-uppercase font-size-sm font-weight-semibold " class="collapsible text-center">Contact Us</span>
                                <div class="header-elements">
                                    <div class="list-icons">
                                        <a class="list-icons-item" data-action="collapse"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body border-0 p-0 content ">
                                <h4 class="pl-5 text-center mt-3" > Speeddigit pvt. ltd </h4>
                                <div class="mt-2"> <strong class="p-4"> <i class="fa-regular fa-envelope"></i> &nbsp; speeddigitinfo@gmail.com </strong> </div>
                                <div> <strong class="p-4"> <i class="fa-solid fa-mobile-screen-button"></i>&nbsp;  01792288555 </strong> </div>
                                <div> <strong class="p-4"> <i class="fa-solid fa-phone-rotary"></i> &nbsp;+8809639251222 </strong> </div>
                                <div> <strong class="p-4"> <i class="fa-brands fa-internet-explorer"></i> &nbsp; www.speeddigit.com </strong> </div><br><br>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!--Add shortcut menu modal-->
<div class="modal fade" id="shortcutMenuModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog four-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="payment_heading">@lang('menu.add_shortcut_menus')</h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body" id="modal-body_shortcuts">
                <!--begin::Form-->
            </div>
        </div>
    </div>
</div>

@endsection
@push('css')

@endpush
@push('js')

@endpush
{{-- @section('content')
<!-- Content area -->
			<div class="content">

				<!-- Inner container -->
				<div class="d-flex align-items-start flex-column flex-md-row">

					<!-- Left content -->
					<div class="w-100  order-2 order-md-1">

						<!-- Grid -->
						<div class="row">
							<div class="col-xl-3 col-sm-6">
								<div class="card">
									<div class="card-body bg-light text-center">
										<div class="mb-2">
											<h6 class="font-weight-semibold mb-0">
												<a href="#" class="text-default">Basic Package</a><br>
												<small>For individuals or teams just getting started with project management</small>
											</h6><br>

											<a href="#" class="text-muted">User : 0 - 50</a>
										</div>

										<h3 class="mb-0 font-weight-semibold">$25.00 / Month</h3>

										<div class="text-muted mb-3">24/7 support</div>

										<button type="button" class="btn bg-teal-400" ><i class="icon-cart-remove"></i> Contact Us</button>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6">
								<div class="card">
									<div class="card-body bg-light text-center">
										<div class="mb-2">
											<h6 class="font-weight-semibold mb-0">
												<a href="#" class="text-default">Premium Package</a><br>
												<small>For teams that need to create project plans with confidence.</small>
											</h6><br>

											<a href="#" class="text-muted">User : 50 - 100</a>
										</div>

										<h3 class="mb-0 font-weight-semibold">$50.00 / Month</h3>

										<div class="text-muted mb-3">24/7 support</div>

										<button type="button" class="btn bg-teal-400" ><i class="icon-cart-remove"></i> Contact Us</button>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6">
								<div class="card">
									<div class="card-body bg-light text-center" style="border-top:2px solid orange;">
										<div class="mb-2">
											<h6 class="font-weight-semibold mb-0">
												<a href="#" class="text-default">Business Package</a><br>
												<small>For teams and companies that need to manage work across initiatives.</small>
											</h6><br>

											<a href="#" class="text-muted">User : 101 - 300</a>
										</div>

										<h3 class="mb-0 font-weight-semibold">$80.00 / Month</h3>

										<div class="text-muted mb-3">24/7 support</div>

										<button type="button" class="btn bg-teal-400" ><i class="icon-cart-remove"></i> Contact Us</button>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6">
								<div class="card">
									<div class="card-body bg-light text-center">
										<div class="mb-2">
											<h6 class="font-weight-semibold mb-0">
												<a href="#" class="text-default">Enterprise Beginner</a><br>
												<small>For organizations that need additional security, control, and support.</small>
											</h6><br>

											<a href="#" class="text-muted">User : 301 - 1000</a>
										</div>

										<h3 class="mb-0 font-weight-semibold">Semi Pro</h3>

										<div class="text-muted mb-3">24/7 support</div>

										<button type="button" class="btn bg-teal-400" ><i class="icon-cart-remove"></i> Contact Us</button>
									</div>
								</div>
							</div>
							<div class="col-xl-3 col-sm-6">
								<div class="card">
									<div class="card-body bg-light text-center">
										<div class="mb-2">
											<h6 class="font-weight-semibold mb-0">
												<a href="#" class="text-default">Enterprise Advanced</a><br>
												<small>For organizations that need additional security, control, and support.</small>
											</h6><br>

											<a href="#" class="text-muted">User : 1000+</a>
										</div>

										<h3 class="mb-0 font-weight-semibold">Pro</h3>

										<div class="text-muted mb-3">24/7 support</div>

										<button type="button" class="btn bg-teal-400" ><i class="icon-cart-remove"></i> Contact Us</button>
									</div>
								</div>
							</div>



						</div>
					</div>
					<!-- /left content -->


					<!-- Right sidebar component -->
					<div class="sidebar sidebar-light bg-transparent sidebar-component sidebar-component-right border-0 shadow-0 order-1 order-md-2 sidebar-expand-md">

						<!-- Sidebar content -->
						<div class="sidebar-content">

							<!-- Categories -->
							<div class="card">
								<div class="card-header bg-transparent header-elements-inline">
									<span class="text-uppercase font-size-sm font-weight-semibold">Contact Us</span>
									<div class="header-elements">
										<div class="list-icons">
					                		<a class="list-icons-item" data-action="collapse"></a>
				                		</div>
			                		</div>
								</div>
								<div class="card-body border-0 p-0">
								  <h4 class="pl-5"> Speeddigit pvt. ltd </h4>
								  <div>	<strong class="p-4"> <i class="icon-envelop4"></i> speeddigitinfo@gmail.com </strong> </div>
								  <div>	<strong class="p-4"> <i class="icon-mobile"></i> 01792288555 </strong> </div>
								  <div>	<strong class="p-4"> <i class="icon-phone-hang-up"></i> +8809639251222 </strong> </div>
								  <div>	<strong class="p-4"> <i class="icon-IE"></i> www.speeddigit.com </strong> </div><br><br>

								</div>
							</div>
							<!-- /categories -->




						</div>
						<!-- /sidebar content -->

					</div>
					<!-- /right sidebar component -->

				</div>
				<!-- /inner container -->

			</div>
			<!-- /content area -->

@endsection --}}
