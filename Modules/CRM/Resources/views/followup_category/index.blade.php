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
                    <h6>Followup Catgeory</h6>
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
                                <table class="display data_tbl data__table followupCategoryTable">
                                    <thead>
                                        <tr>
                                            <th class="text-start">@lang('menu.action')</th>
                                            <th class="text-start">@lang('menu.name')</th>
                                            <th class="text-start">@lang('menu.description')</th>
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
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Followup Catgeory</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_followup_category_form" action="{{ route('crm.followup.category.store') }}">
                        @csrf
                        <div class="form-group">
                            <label><b>Followup Category</b> <span class="text-danger">*</span></label>
                            <input required type="text" name="name" class="form-control add_input"
                                data-name="Followup Category" id="name" placeholder="Followup Category" />
                            <span class="error error_name"></span>
                        </div>

                        <div class="form-group mt-1">
                            <label><b>@lang('menu.description')</b> </label>
                            <textarea name="description" class="form-control form-control-sm ckEditor" id="description" cols="10"
                                rows="5" placeholder="@lang('menu.description')"></textarea>
                        </div>

                        <div class="form-group row mt-3">
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
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog double-col-modal" role="document" id="edit-content">
        </div>

        <form id="deleted_form" action="" method="post">
            @method('DELETE')
            @csrf
        </form>
    @endsection

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
            integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script>
            $(document).ready(function() {

                var table = $('.followupCategoryTable').DataTable({
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
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>Save as Excel',
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
                    ajax: "{{ route('crm.followup.category.index') }}",
                    columns: [{
                        data: 'action',
                        name: 'action'
                    }, {
                        data: 'name',
                        name: 'name'
                    }, {
                        data: 'description',
                        name: 'description'
                    }, ],
                });

                table.buttons().container().appendTo('#exportButtonsContainer');

                $('#add_followup_category_form').on('submit', function(e) {
                    e.preventDefault();

                    $('.loading_button').show();
                    var url = $(this).attr('action');
                    var request = $(this).serialize();

                    $('.submit_button').prop('type', 'button');

                    $.ajax({
                        url: url,
                        type: 'post',
                        data: request,
                        success: function(data) {

                            $('#add_followup_category_form')[0].reset();
                            toastr.success(data);
                            $('.loading_button').hide();
                            $('#addModal').modal('hide');
                            $('.submit_button').prop('type', 'submit');
                            $('.followupCategoryTable').DataTable().ajax.reload();

                        },
                        error: function(err) {

                            $('.loading_button').hide();
                            $('.submit_button').prop('type', 'submit');
                            $('.error').html('');

                            if (err.status == 0) {

                                toastr.error('Net Connetion Error. Reload This Page.');
                                return;
                            } else if (err.status == 500) {

                                toastr.error('Server Error. Please contact to the support team.');
                                return;
                            }

                            toastr.error('Please check again all form fields.',
                                'Some thing went wrong.');

                            $.each(err.responseJSON.errors, function(key, error) {

                                $('.error_' + key + '').html(error[0]);
                            });
                        }
                    });
                });

                // Pass editable data to edit modal fields
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
                            toastr.error(data);
                            table.ajax.reload();
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
