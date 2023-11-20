@extends('layout.master')
@section('title', 'Website - ')
@section('content')
<div class="body-wraper">
    @if(auth()->user()->can('web_manage_banner') || auth()->user()->can('web_add_banner')
    || auth()->user()->can('web_edit_banner') || auth()->user()->can('web_delete_banner'))
    <div class="container-fluid p-0">
        <form action="{{ route('website.banner.store') }}" enctype="multipart/form-data" method="POST">
            @csrf
            <div class="row g-0">
                <div class="form_element mt-0 border-0">
                    <div class="sec-name">
                        <h6>@lang('menu.banner')</h6>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button">
                                <i class="fa-thin fa-left-to-line fa-2x"></i><br>Back
                            </a>
                        </div>
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
                                            <label for="title" class="form-label">Title</label>
                                            <input class="form-control" name="title" id="title" value="{{ $banner?->title }}"/>
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner One</label>
                                            <input type="file" id="image" class="form-control" name="image" onchange="readURL1(this);">
                                            <img src="{{ asset($banner?->banner1) }}" id="image1" class="preview-image1 @if($banner?->banner1 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner Two</label>
                                            <input type="file" id="image" class="form-control" name="banner2" onchange="readURL2(this);">
                                            <img src="{{ asset($banner?->banner2) }}" id="image2" class="preview-image2 @if($banner?->banner2 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner Three</label>
                                            <input type="file" id="image" class="form-control" name="banner3" onchange="readURL3(this);">
                                            <img src="{{ asset($banner?->banner3) }}" id="image3" class="preview-image3 @if($banner?->banner3 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner Four</label>
                                            <input type="file" id="image" class="form-control" name="banner4" onchange="readURL4(this);">
                                            <img src="{{ asset($banner?->banner4) }}" id="image4" class="preview-image4 @if($banner?->banner4 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner Five</label>
                                            <input type="file" id="image" class="form-control" name="banner5" onchange="readURL5(this);">
                                            <img src="{{ asset($banner?->banner5) }}" id="image5" class="preview-image5 @if($banner?->banner5 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <label for="ideas" class="form-label">Banner Six</label>
                                            <input type="file" id="image" class="form-control" name="banner6" onchange="readURL6(this);">
                                            <img src="{{ asset($banner?->banner6) }}" id="image6" class="preview-image6 @if($banner?->banner6 == NULL) d-none @endif" style="height: 45px; width:100px">
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-sm btn-success me-0 float-end submit_button">@lang('menu.save')</button>
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
<script>
    function readURL1(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image1')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image1').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL2(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image2')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image2').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL3(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image3')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image3').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL4(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image4')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image4').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL5(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image5')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image5').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL6(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image6')
                .attr('src', e.target.result)
                .width(80)
                .height(80);
            };
            $('.preview-image6').removeClass('d-none');
            reader.readAsDataURL(input.files[0]);
        }
    }

</script>
@endpush
