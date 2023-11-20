@extends('layout.master')
@section('title', 'Website - ')
@section('content')
    <div class="body-wraper">
        @if (auth()->user()->can('seo'))
            <div class="container-fluid p-0">
                <form action="{{ route('website.seo_settings.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row g-0">
                        <div class="form_element mt-0 border-0">
                            <div class="sec-name">
                                <h6>@lang('menu.seo_setting')</h6>
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
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input class="form-control" name="meta_title" id="meta_title"
                                                        value="{{ $seo_setting?->meta_title }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="meta_tag" class="form-label">Meta Tag</label>
                                                    <input class="form-control" name="meta_tag" id="meta_tag"
                                                        value="{{ $seo_setting?->meta_tag }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="meta_description" class="form-label">Meta
                                                        Description</label>
                                                    <textarea class="form-control ckEditor" name="meta_description" id="meta_description" rows="3">{{ $seo_setting?->meta_description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="meta_author" class="form-label">Meta Author Tag</label>
                                                    <input class="form-control" name="meta_author" id="meta_author"
                                                        value="{{ $seo_setting?->meta_author }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="google_analytics" class="form-label">Google
                                                        Analytics</label>
                                                    <input class="form-control" name="google_analytics"
                                                        id="google_analytics" value="{{ $seo_setting?->google_analytics }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="google_verification" class="form-label">Google
                                                        Verification</label>
                                                    <input class="form-control" name="google_verification"
                                                        id="google_verification"
                                                        value="{{ $seo_setting?->google_verification }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="bing_verification" class="form-label">Bing
                                                        Verification</label>
                                                    <input class="form-control" name="bing_verification"
                                                        id="bing_verification"
                                                        value="{{ $seo_setting?->bing_verification }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="alexa_analytics" class="form-label">Alexa Analytics</label>
                                                    <input class="form-control" name="alexa_analytics" id="alexa_analytics"
                                                        value="{{ $seo_setting?->alexa_analytics }}">
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
