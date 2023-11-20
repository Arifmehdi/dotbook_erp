@extends('layout.master')
@section('title', 'Website - ')
@section('content')
    <div class="body-wraper">
        @if (auth()->user()->can('web_history'))
            <div class="container-fluid p-0">
                <form action="{{ route('website.history.update') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="row g-0">
                        <div class="form_element mt-0 border-0">
                            <div class="sec-name">
                                <h6>History</h6>
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
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control ckEditor" name="description" id="description" rows="3">{{ $history?->description }}</textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="ideas" class="form-label">Image</label>
                                                    <input type="file" id="image" class="form-control" name="image"
                                                        onchange="readURL(this);">
                                                    <img src="{{ asset($history?->image) }}" id="one"
                                                        class="preview-image @if ($history?->image == null) d-none @endif"
                                                        style="height: 45px; width:100px">
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
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#one')
                        .attr('src', e.target.result)
                        .width(80)
                        .height(80);
                };
                $('.preview-image').removeClass('d-none');
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endpush
