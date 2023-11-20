@extends('layout.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('naeem/feedback.css') }}">
    <style>
        #body-wraper {
            padding: 0;
        }

        .fa-2x {
            font-size: 1.6em;
        }

        .d-none {
            display: none;
        }

        .fa-star:hover {
            color: rgb(57, 230, 51);
        }

        /* testing work */
        * {
            margin: 0;
            padding: 0;
        }

        .font-size {
            font-size: 35px !important;
        }

        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 40px;
            color: rgb(0, 0, 0);
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <section class="sec-name">
                <h6>@lang('menu.feedback')</h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                        class="fa-thin fa-left-to-line fa-2x"></i>
                    <br>@lang('menu.back')
                </a>
            </section>

            <section class="p-15">
                <div class="form_element rounded m-0 window-height">
                    <div class="element-body h-100 d-flex flex-column justify-content-between">
                        <div>
                            <div class="sale-item-inner">
                                <h2>@lang('menu.help_improve_dotbook')</h2>
                                <p>@lang('menu.thank_you_provide_suggestion')</p>
                                <p>@lang('menu.you_feedback_dotbook_application_better')</p>
                                <span>@lang('menu.free_contact_us_anytime') <small class="text-info">{{ auth()->user()->email }}</small></span>
                            </div>
                            <form id="createFeedback" action="{{ route('feedback.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-md-4">
                                    <label><b>@lang('menu.your_name')</b></label>
                                    <input type="text" name="name" class="form-control" required>

                                    <label><b>@lang('menu.email_address') </b></label>
                                    <input type="email" value="{{ auth()->user()->email }}" name="email"
                                        class="form-control" readonly>

                                    <label><b>@lang('menu.message') </b></label>
                                    <textarea cols="10" rows="10" name="message" class="form-control ckEditor"></textarea>
                                    {{-- testing rating testing --}}
                                    <div>
                                        <p>@lang('menu.overall_rating') </p>
                                        <div class="rate">
                                            <input type="radio" id="star5" name="rating" value="5" />
                                            <label class="font-size" for="star5" title="5 stars"></label>
                                            <input type="radio" id="star4" name="rating" value="4" />
                                            <label class="font-size" for="star4" title="4 stars"></label>
                                            <input type="radio" id="star3" name="rating" value="3" />
                                            <label class="font-size" for="star3" title="3 stars"></label>
                                            <input type="radio" id="star2" name="rating" value="2" />
                                            <label class="font-size" for="star2" title="2 stars"></label>
                                            <input type="radio" id="star1" name="rating" value="1" />
                                            <label class="font-size" for="star1" title="1 star"></label>
                                        </div>
                                    </div>
                                    {{-- testing rating testing end --}}
                                </div>
                                <div class="btn-box-3 d-flex justify-content-end gap-2 mt-5">
                                    <input type="submit" class="btn btn-success w-auto" value="Submit">
                                    <button type="reset" class="btn btn-danger w-auto">@lang('menu.cancel')</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var windowHeight = window.innerHeight - 120;
        $('.window-height').height(windowHeight);

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Add category by ajax
            $(document).on('submit', '#createFeedback', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#createFeedback')[0].reset();
                        $('.loading_button').hide();
                        $('.fa-star').removeClass('text-success');
                    },
                    error: function(err) {
                        $('.loading_button').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });
    </script>
@endpush
