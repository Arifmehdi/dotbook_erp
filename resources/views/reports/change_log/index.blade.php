@extends('layout.master')
@section('title', 'Change Log - ')


@push('css')
@endpush

@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.change_log')</h6>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>
        </div>
        <div class="row margin_row">
            <div class="card">
                <div class="row">
                    <div class="col-md-12 border-radius-5  p-2" style="background: #ddd; border-radius: 5px;">
                        <h6>@lang('menu.version_log')</h6>
                    </div>
                    @foreach ($changelog as $change)
                        <div class="mt-3" style="argin-left: -9px !important;">
                            <h4 class="text-center p-2">@lang('menu.update') <br> <span style="font-size: 12px;">{{ $change->title }}</span> </h4>
                            <div class="card border p-2">
                                <div class="p-2">
                                    <h6> <span style="font-size: 12px; font-weight: 700;">New {{ $change->title }}</span>
                                        <span style="background: #ddd; border-radius: 5px; font-size: 12px;">{{ $change->created_at }}</span>
                                    </h6>
                                    {!! $change->description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {

            })
            .catch(error => {

            });
    </script>
@endpush
