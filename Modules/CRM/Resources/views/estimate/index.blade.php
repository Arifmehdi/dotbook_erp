@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- select 2 --}}


    <style>
        button.btn.btn-danger.deletewarrantyButton {
            border-radius: 0px !important;
            padding: 0.7px 10px !important;
        }
    </style>
@endpush
@section('title', 'CRM - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Estimate</h6>
                </div>
                <x-all-buttons>
                    <x-add-button />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>

        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <table class="display data_tbl data__table TemplateTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">@lang('crm.subject')</th>
                                            <th class="text-start">@lang('crm.body')</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 32%;width: 54%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">New Message</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">

                    <form id="mail_send" action="{{ route('crm.proposal_template.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row" id="to_area">
                            <div class="col-md-12">
                                <label><strong>CC</strong> </label>
                                <input type="text" name="cc" class="form-control add_input" data-name="To"
                                    id="cc" placeholder="CC" />
                                <span><small>Comma separated values of emails</small></span>
                                <span class="error error_to"></span>
                            </div>
                            <div class="col-md-12">
                                <label><strong>BCC</strong> </label>
                                <input type="text" name="bcc" class="form-control add_input" data-name="To"
                                    id="bcc" placeholder="BCC" />
                                <span><small>Comma separated values of emails</small></span>
                                <span class="error error_to"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Subject</strong> <span class="text-danger">*</span></label>
                                <input required type="text" name="subject" class="form-control add_input"
                                    data-name="Subject" id="subject" placeholder="Subject" />
                                <span class="error error_subject"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Body </strong><span class="text-danger">*</span></label>
                                <textarea id="editor" name="description" id="description"></textarea>
                                <span class="error error_description"></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <label><strong>Attachment </strong></label>
                                <input type="file" name="file[]" class="form-control add_input" data-name="file"
                                    id="file" placeholder="Attachments" multiple />
                                <span class="d-block" style="line-height: 20px;font-size: 10px;">Max File Size : 5Mb</span>
                                <span class="d-block" style="line-height: 12px;font-size: 10px;">Allowed File: .pdf, .csv,
                                    .zip, .doc, .docx, .jpeg, .jpg, .png</span>
                                <span class="error error_file"></span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i
                                            class="fas fa-spinner"></i></button>
                                    <button type="submit"
                                        class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                    <button type="reset" data-bs-dismiss="modal"
                                        class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content"></div>
    </div>

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        ClassicEditor.create(document.querySelector('#editor')).then(editor => {

            })
            .catch(error => {
                console.error(error);
            });

        $('.select2').select2({
            placeholder: "Select Leads",
            allowClear: true
        });
    </script>

    <script>
        $(document).ready(function() {

            // Data Table
            var table = $('.TemplateTable').DataTable({
                processing: true,
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, ],
                serverSide: true,
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: "{{ route('crm.proposal_template.index') }}",
                columns: [{
                    data: 'action',
                    name: 'action'
                }, {
                    data: 'subject',
                    name: 'subject'
                }, {
                    data: 'body',
                    name: 'body'
                }, ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            $('#mail_send').on('submit', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                var request = $(this).serialize();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                        toastr.success(data);
                        $('#addModal').modal('hide');
                        $('#mail_send')[0].reset();
                        $('.loading_button').hide();
                        $('.TemplateTable').DataTable().ajax.reload();
                    },
                    error: function(err) {

                        $('.loading_button').hide();
                        $('.error').html('');
                        $('.submit_button').prop('type', 'submit');

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

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                $('.data_preloader').show();
                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'get',
                    success: function(data) {
                        $('.data_preloader').hide();
                        $('#edit-content').html(data);
                        $('#editModal').modal('show');
                    },
                    error: function(err) {
                        $('.data_preloader').hide();
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Reload This Page.');
                        } else if (err.status == 500) {
                            toastr.error('Server Error, Please contact to the support team.');
                        }
                    }
                });
            });

            // delete part
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

            //data delete by ajax
            $(document).on('submit', '#deleted_form', function(e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var request = $(this).serialize();

                $.ajax({
                    url: url,
                    type: 'post',
                    data: request,
                    success: function(data) {
                        table.ajax.reload();
                        if (!$.isEmptyObject(data.errorMsg)) {
                            toastr.error(data.errorMsg);
                            return;
                        }
                        toastr.error(data.responseJSON);
                    },
                    error: function(data) {
                        toastr.error(data.responseJSON)
                        asset_table.ajax.reload();
                    }
                });
            });

        });
    </script>
@endpush
