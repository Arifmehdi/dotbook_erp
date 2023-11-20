@extends('layout.master')
@section('title', 'Website - ')
@section('content')
    <div class="body-wraper">
        @if (auth()->user()->can('general_setting'))
            <div class="container-fluid p-0">
                <form action="{{ route('website.general_settings.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row g-0">
                        <div class="form_element mt-0 border-0">
                            <div class="sec-name">
                                <h6>@lang('menu.general_setting')</h6>
                                <x-back-button />
                            </div>
                        </div>
                    </div>
                    <section class="p-15">
                        <div class="sale-content">
                            <div class="row g-1">
                                <div class="col-xl-12">
                                    <div class="form_element m-0 mb-1 rounded">
                                        <div class="element-body">
                                            <div class="row g-lg-4 g-1">
                                                <div class="mb-3">
                                                    <label for="site_name" class="form-label">Site Name</label>
                                                    <input class="form-control" name="site_name" id="site_name"
                                                        value="{{ $setting?->site_name }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="app_url" class="form-label">App Url</label>
                                                    <input class="form-control" name="app_url" id="app_url"
                                                        value="{{ $setting?->app_url }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control ckEditor" name="description" id="description" rows="3">{{ $setting?->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ideas" class="form-label">Backend Logo</label>
                                                    <input type="file" id="image" class="form-control"
                                                        name="backend_logo" onchange="readURL(this);">
                                                    <img src="{{ asset($setting?->backend_logo) }}" id="one"
                                                        class="img-thumbnail preview-image1 @if ($setting?->backend_logo == null) d-none @endif"
                                                        style="height: 100px;width: 100px;margin: 5px 0px;">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ideas" class="form-label">Frontend Header Logo</label>
                                                    <input type="file" id="image" class="form-control"
                                                        name="frontend_header_logo" onchange="readURL1(this);">
                                                    <img src="{{ asset($setting?->frontend_header_logo) }}" id="two"
                                                        class="img-thumbnail preview-image2 @if ($setting?->frontend_header_logo == null) d-none @endif"
                                                        style="height: 100px;width: 100px;margin: 5px 0px;">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ideas" class="form-label">Favicon</label>
                                                    <input type="file" id="image" class="form-control" name="favicon"
                                                        onchange="faviconIm(this);">
                                                    <img src="{{ asset($setting?->favicon) }}" id="favicon"
                                                        class="img-thumbnail preview-image3 @if ($setting?->favicon == null) d-none @endif"
                                                        style="height: 100px;width: 100px;margin: 5px 0px;">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ideas" class="form-label">Frontend Footer Logo</label>
                                                    <input type="file" id="image" class="form-control"
                                                        name="frontend_footer_logo" onchange="readURL2(this);">
                                                    <img src="{{ asset($setting?->frontend_footer_logo) }}"
                                                        id="banner_three"
                                                        class="img-thumbnail preview-image4 @if ($setting?->frontend_footer_logo == null) d-none @endif"
                                                        style="height: 100px;width: 100px;margin: 5px 0px;">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control ckEditor" name="address1" id="address1" rows="3">{{ $setting?->address1 }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="address2" class="form-label">Factory</label>
                                                    <textarea class="form-control ckEditor" name="address2" id="address2" rows="3">{{ $setting?->address2 }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="map" class="form-label">MAP</label>
                                                    <input type="text" class="form-control" name="map"
                                                        id="map" value="{{ $setting?->map }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone</label>
                                                    <input type="text" class="form-control" name="phone"
                                                        id="phone" value="{{ $setting?->phone }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        id="email" value="{{ $setting?->email }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="office_time" class="form-label">Office Time</label>
                                                    <input type="text" class="form-control" name="office_time"
                                                        id="office_time" value="{{ $setting?->office_time }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="office_days" class="form-label">Office Day</label>
                                                    <input type="text" class="form-control" name="office_days"
                                                        id="office_days" value="{{ $setting?->office_days }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="call_hour" class="form-label">Office Hour</label>
                                                    <input type="text" class="form-control" name="call_hour"
                                                        id="call_hour" value="{{ $setting?->call_hour }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="office_days" class="form-label">Office Day</label>
                                                    <input type="text" class="form-control" name="office_days"
                                                        id="office_days" value="{{ $setting?->office_days }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fax" class="form-label">Fax</label>
                                                    <input type="text" class="form-control" name="fax"
                                                        id="fax" value="{{ $setting?->fax }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="fax" class="form-label">Website</label>
                                                    <input type="text" class="form-control" name="website"
                                                        id="website" value="{{ $setting?->website }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="get_in_touch" class="form-label">Get in touch text</label>
                                                    <textarea class="form-control ckEditor" name="get_in_touch" id="get_in_touch" rows="3">{{ $setting?->get_in_touch }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <button type="submit"
                                                        class="btn btn-sm btn-success me-0 float-end submit_button">@lang('menu.save')</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>
        @else
            <div class="p-15">
                <div class="bd-callout bd-callout-info">
                    <code>Warning!!</code> You do not have permission to access please contact with administrator.
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#one')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };
                $('.preview-image1').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#two')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };
                $('.preview-image2').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        }

        function faviconIm(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#favicon')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };
                $('.preview-image3').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#banner_three')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };
                $('.preview-image4').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
