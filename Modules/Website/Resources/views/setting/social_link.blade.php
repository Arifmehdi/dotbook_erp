@extends('layout.master')
@section('title', 'Website - ')
@section('content')
<div class="body-wraper">
    @if(auth()->user()->can('social_link'))
    <div class="container-fluid p-0">
        <form action="{{ route('website.social.link.update') }}" method="POST">
            @csrf
            <div class="row g-0">
                <div class="form_element mt-0 border-0">
                    <div class="sec-name">
                        <h6>@lang('menu.social_link')</h6>
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
                                            <label for="facebook" class="form-label">Facebook</label>
                                            <input class="form-control" name="facebook" id="facebook" value="{{ $socila_link?->facebook }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="twitter" class="form-label">Twitter</label>
                                            <input class="form-control" name="twitter" id="twitter" value="{{ $socila_link?->twitter }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="instagram" class="form-label">Instagram</label>
                                            <input class="form-control" name="instagram" id="instagram" value="{{ $socila_link?->instagram }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="pinterest" class="form-label">Pinterest</label>
                                            <input class="form-control" name="pinterest" id="pinterest" value="{{ $socila_link?->pinterest }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label">Linkedin</label>
                                            <input class="form-control" name="linkedin" id="linkedin" value="{{ $socila_link?->linkedin }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="youtube" class="form-label">You Tube</label>
                                            <input class="form-control" name="youtube" id="youtube" value="{{ $socila_link?->youtube }}">
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
<script type="text/javascript">
	function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
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
            reader.onload = function (e) {
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
            reader.onload = function (e) {
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
            reader.onload = function (e) {
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
