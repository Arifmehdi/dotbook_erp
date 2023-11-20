@extends('layout.master')
@section('title', 'Database Backup - ')

@push('css')
@endpush

@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.database_backup')</h6>
                </div>
                <x-back-button />
            </div>
        </div>
    </div>

    <div class="p-15">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="btn-box-4 d-flex justify-content-end gap-2 mb-2">
                            <a href="#" class="btn btn-primary">@lang('menu.create') @lang('menu.database_backup')</a>
                            <button type="button" id="edit" class="btn btn-primary" data-toggle="modal"
                                data-target="#exampleModalCenter">
                                @lang('menu.auto_backup')
                            </button>
                        </div>
                        <p class="text-primary p-0">@lang('menu.database_backup_long_text')</p>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table" id="backupTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Backup') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- auto backup --}}

    <!-- Modal -->
    <!-- edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">@lang('menu.auto_backup')</h4>
                        <a class="close text-white" href="" data-dismiss="Close" aria-label="Close"><span
                                aria-hidden="true"><i class="fa-thin fa-circle-xmark"></i></span></a>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="auto_backup_enabled" class="control-label clearfix">
                                Enabled (Requires Cron)</label>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="y_opt_1_auto_backup_enabled" name="settings" value="1">
                                <label for="y_opt_1_auto_backup_enabled"> Yes</label>
                            </div>
                            <div class="radio radio-primary radio-inline">
                                <input type="radio" id="y_opt_2_auto_backup_enabled" name="settings" value="0"
                                    checked="">
                                <label for="y_opt_2_auto_backup_enabled"> No</label>
                            </div>
                        </div>
                        <div data-toggle="tooltip" title=""
                            data-original-title="24 hours format eq. 9 for 9am or 15 for 3pm.">
                            <div class="form-group" app-field-wrapper="auto_backup_hour">
                                <label for="auto_backup_hour" class="control-label">Hour of day to perform backup</label>
                                <input type="number" id="auto_backup_hour" name="auto_backup_hour" class="form-control"
                                    value="6">
                            </div>
                        </div>
                        <div class="form-group" app-field-wrapper="auto_backup_every">
                            <label for="auto_backup_every" class="control-label">Create backup every X days</label>
                            <input type="number" id="auto_backup_every" name="auto_backup_every" class="form-control"
                                value="7">
                        </div>
                        <div class="form-group" app-field-wrapper="delete_backups_older_then">
                            <label for="delete_backups_older_then" class="control-label">Auto delete backups older then X
                                days (set 0 to disable)</label>
                            <input type="number" id="delete_backups_older_then" name="delete_backups_older_then"
                                class="form-control" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn p-1 btn-default" data-dismiss="modal">@lang('menu.close')</button>
                        <button type="submit" class="btn p-1 btn-info">@lang('menu.save')</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn p-1 btn-primary" data-dismiss="modal">@lang('menu.close')</button>
                    <button type="submit" class="btn p-1 btn-info">@lang('menu.save')</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {

            var backupTable = $('#backupTable').DataTable({
                ajax: {
                    // url: "{{ route('core.area.index') }}",
                },
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                columns: [{
                    'data': 'backup',
                    'name': 'backup',
                }, {
                    'data': 'backup_size',
                    'name': 'backup_size'
                }, {
                    'data': 'date',
                    'name': 'date'
                }, {
                    'data': 'option',
                    'name': 'option'
                }, ]
            });
            //         // Add category by ajax
            //         $(document).on('submit', '#add_area_form', function(e) {
            //             e.preventDefault();
            //             $('.loading_button').show();
            //             var url = $(this).attr('action');
            //             $.ajax({
            //                 url: url,
            //                 type: 'post',
            //                 data: new FormData(this),
            //                 contentType: false,
            //                 cache: false,
            //                 processData: false,
            //                 success: function(data) {
            //
            //                     toastr.success(data);
            //                     $('#add_area_form')[0].reset();
            //                     $('#addModal').hide();
            //                     $('.loading_button').hide();
            //                     areaTable.ajax.reload();
            //                 },
            //                 error: function(err) {
            //                     $('.loading_button').hide();
            //                     if (err.status == 0) {
            //                         toastr.error('Net Connetion Error. Reload This Page.');
            //                         return;
            //                     }
            //                     $.each(err.responseJSON.errors, function(key, error) {
            //                         $('.error_' + key + '').html(error[0]);
            //                     });
            //                 }
            //             });
            //         });

            //         // Add category by ajax
            //         $(document).on('submit', '#add_multipale_area_form', function(e) {
            //             e.preventDefault();
            //             $('.loading_button').show();
            //             var url = $(this).attr('action');
            //             $.ajax({
            //                 url: url,
            //                 type: 'post',
            //                 data: new FormData(this),
            //                 contentType: false,
            //                 cache: false,
            //                 processData: false,
            //                 success: function(data) {
            //
            //                     toastr.success(data);
            //                     $('#add_multipale_area_form')[0].reset();
            //                     $('#addModal2').hide();
            //                     $('.loading_button').hide();
            //                     areaTable.ajax.reload();
            //                 },
            //                 error: function(err) {
            //                     $('.loading_button').hide();
            //                     if (err.status == 0) {
            //                         toastr.error('Net Connetion Error. Reload This Page.');
            //                         return;
            //                     }
            //                     $.each(err.responseJSON.errors, function(key, error) {
            //                         $('.error_' + key + '').html(error[0]);
            //                     });
            //                 }
            //             });
            //         });
            //         //data delete by ajax
            //         $(document).on('click', '#delete', function(e) {
            //             e.preventDefault();
            //             var url = $(this).attr('href');
            //             $('#deleted_form').attr('action', url);
            //             $.confirm({
            //                 'title': 'Delete Confirmation',
            //                 'content': 'Are you sure?',
            //                 'buttons': {
            //                     'Yes': {
            //                         'class': 'yes btn-primary',
            //                         'action': function() {
            //                             $('#deleted_form').submit();
            //                         }
            //                     },
            //                     'No': {
            //                         'class': 'no btn-danger',
            //                         'action': function() {
            //
            //                         }
            //                     }
            //                 }
            //             });
            //         });

            //         //data delete by ajax
            //         $(document).on('submit', '#deleted_form', function(e) {
            //             e.preventDefault();
            //             var url = $(this).attr('action');
            //             var request = $(this).serialize();
            //             $.ajax({
            //                 url: url,
            //                 type: 'post',
            //                 data: request,
            //                 success: function(data) {
            //                     if ($.isEmptyObject(data.errorMsg)) {
            //                         toastr.error(data);
            //                         areaTable.ajax.reload();
            //                     } else {
            //                         toastr.error(data.errorMsg);
            //                     }
            //                 },
            //                 error: function(err) {
            //                     if (err.status == 0) {
            //                         toastr.error('Net Connetion Error. Please check the connection.');
            //                     } else if (err.status == 500) {
            //                         toastr.error('Server Error. Please contact to the support team.');
            //                     }
            //                 }
            //             });
            //         });
            //     });

            //     // pass editable data to edit modal fields

            $(document).on('click', '#edit', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax({
                    // url: url,
                    // type: 'get',
                    success: function(data) {
                        $('#edit-content').empty();
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

            //     // Show details modal with data
            //     $(document).on('click', '.details_button', function(e) {
            //         e.preventDefault();
            //         var url = $(this).attr('href');
            //         $.get(url, function(data) {
            //             $('#requisition_details').html(data);
            //             $('#detailsModal').modal('show');
            //         })
        });
    </script>
@endpush

@push('js')
@endpush
