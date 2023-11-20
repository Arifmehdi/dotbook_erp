@extends('layout.master')
@push('css')

    <style>
        .top-menu-area ul li {
            display: inline-block;
            margin-right: 3px;
        }

        .top-menu-area a {
            border: 1px solid lightgray;
            padding: 1px 5px;
            border-radius: 3px;
            font-size: 11px;
        }
    </style>
@endpush
@section('title', 'Whatsapp - index')
@section('content')
    <div class="body-wraper">
        <section class="mt-5x">
            <div class="container-fluid">
                <div class="row">
                    <div class="sec-name">
                        <h6>Whatsapp Section</h6>
                        <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button">
                            <i class="fa-thin fa-left-to-line fa-2x"></i>
                            <br> Back
                        </a>
                    </div>
                </div>

            </div>
        </section>
        <div class="content-wrapper p-15">
            <section class="content email-content">
                <div class="row g-1">
                    <div class="col-md-9 mt-1">
                        <div class="card card-primary card-outline">
                            <div class="card-body">

                                <div class="form-group row">
                                    <form action="{{ route('communication.whatsapp.whatsapp.store') }}" method="post">
                                        <label for="phone" class="col-md-4 col-form-label text-md-right">Whatsapp
                                            Number</label>
                                        <div class="col-md-6">
                                            <input id="phone" type="text"
                                                class="form-control @error('message') is-invalid @enderror" name="message"
                                                value="{{ old('message') }}" placeholder="+88 01711 222 333" required>
                                            @error('message')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <input type="text" class="form-control" name="message_view"
                                                value="{{ @$whatsapp->body }}" placeholder="">
                                            <textarea name="" id="" cols="100" rows="4">{{ @$whatsapp }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>

    <script>
        $('.select2').select2({
            placeholder: "Select Group",
            allowClear: true
        });

        ClassicEditor
            .create(document.querySelector('#editor'))
            .then(editor => {

            })
            .catch(error => {
                console.error(error);
            });
    </script>
    <script>
        var sms_table = $('.data_tbl').DataTable({
            "processing": true,

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('communication.whatsapp.index') }}",
            },
            columns: [{
                    data: 'check',
                    name: 'check'
                }, {
                    data: 'status',
                    name: 'status'
                }, {
                    data: 'to',
                    name: 'to'
                }, {
                    data: 'message',
                    name: 'message'
                }, {
                    data: 'delete',
                    name: 'Delete'
                }, {
                    data: 'time',
                    name: 'time'
                },

            ],
            fnDrawCallback: function() {
                $('.data_preloader').hide();
            }
        });

        $(document).ready(function() {
            $(document).on('click', '#status', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.loading_button').hide();
                        $('.whatsappTable').DataTable().ajax.reload();
                        toastr.success(data);
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');

                        if (err.status == 0) {

                            toastr.error('Net Connetion Error. Reload This Page.');
                            return;
                        } else if (err.status == 500) {

                            toastr.error('Server error. Please contact to the support team.');
                            return;
                        }
                        $.each(err.responseJSON.errors, function(key, error) {
                            $('.error_' + key + '').html(error[0]);
                        });
                    }
                });
            });
        });

        $('#whatsapp_send').on('submit', function(e) {

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
                    $('.whatsappTable').DataTable().ajax.reload();
                    $('#addCompose').modal('hide');
                    $('#whatsapp_send')[0].reset();

                    $('.loading_button').hide();
                    $('#addCompose').hide();
                    $('#addCompose').close();
                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {
                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                    $.each(err.responseJSON.errors, function(key, error) {
                        if (key === 'to.0') {
                            key = 'to'
                        }
                        let errorMessage = error[0].replace('.0', '');
                        errorMessage += ' if group is not selected!';

                        $('.error_' + key + '').html(errorMessage);
                    });
                }
            });
        });

        $('.all_delete').on('click', function() {

            $('#all_delete_form').submit();
        });

        $('#all_delete_form').on('submit', function(e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var request = $(this).serialize();

            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    $('.whatsappTable').DataTable().ajax.reload();


                    toastr.success(data);


                },
                error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
                }
            });
        });

        $('#check_all').on('click', function() {

            if ($('#is_check_all').is(":checked")) {

                $('#is_check_all').prop("checked", false);
            } else {

                $('#is_check_all').prop("checked", true);
            }

            if ($('#is_check_all').is(":checked")) {

                $('.check1').prop('checked', true);
            } else {

                $('.check1').prop('checked', false);
            }
        });
        // delete

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {

                        }
                    }
                }
            });
        });

        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {
                    sms_table.ajax.reload();
                    if (!$.isEmptyObject(data.errorMsg)) {
                        toastr.error(data.errorMsg);
                        return;
                    }
                    toastr.success(data.responseJSON);
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    sms_table.ajax.reload();
                }
            });
        });


        // adding more To fields
        var child = '';
        child += '<div class="col-md-12 mt-2">';
        child += '<div class="row">';
        child += '<div class="col-md-10">';
        child +=
            '<input type="text" name="to[]" class="form-control add_input" data-name="To" id="phone_number" placeholder="Whatsapp Number" />';
        child += '<span class="error error_to"></span>';
        child += '</div>';
        child += '<div class="col-md-2 text-end">';
        child +=
            '<button class="btn btn-sm btn-danger deletewarrantyButton" type="button" onclick="this.parentElement.parentElement.parentElement.remove()" style="padding:4px 20px">X</button>';
        child += '</div>';
        child += '</div>';
        child += '</div>';

        var addMoreButton = document.getElementById('addMoreButton');
        var warrantyContainer = document.getElementById('to_area');

        $('#addMoreButton').on('click', function(e) {
            e.preventDefault();
            $('#to_area').append(child);
        });

        // body-wraper
        var height = $(window).height() - 113;
        $('.email-content').height(height);
    </script>
@endpush
