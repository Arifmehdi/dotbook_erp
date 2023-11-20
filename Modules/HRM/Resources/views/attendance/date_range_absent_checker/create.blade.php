@extends('layout.master')
@section('title', 'Date range absent checker - ')
@push('css')

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        .sorting_disabled {
            background: none;
        }



        .daterangepicker .calendar-table tr th {
            padding-top: 2px !important;
            padding-bottom: 2px !important;
            background-color: #e7e7e7 !important;
            color: black !important;
            border-radius: unset;
            line-height: unset;
        }
    </style>
@endpush

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Date range absent checker') }}</h6>
            </div>
            <x-all-buttons />
        </div>
        <div class="row g-0">
            <div class="col-md-12 p-15 pb-0">
                <div class="form_element m-0 rounded">
                    <div class="element-body">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="filter_form">
                                    <div class="form-group row align-items-end g-2">
                                        <div class="col-xl-2 col-md-2">
                                            <label><strong>{{ __('From') }} </strong></label>
                                            <input type="date" name="from" value="@php echo date('Y-m-01') @endphp"
                                                class="form-control" id="fromDate">
                                        </div>
                                        <div class="col-xl-2 col-md-2">
                                            <label><strong>{{ __('To') }} </strong></label>
                                            <input type="date" name="to" value="@php echo date('Y-m-d') @endphp"
                                                class="form-control" id="toDate">
                                        </div>
                                        <div class="col-xl-3 col-md-3">
                                            <button type="submit" class="btn btn-sm btn-info" id="submitButton">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form id="bulk_action_form" action="{{ route('hrm.awards.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table award_table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">{{ __('SL.') }}</th>
                                                    <th class="text-start">{{ __('Employee ID') }}</th>
                                                    <th class="text-start">{{ __('Employee Name') }}</th>
                                                    <th class="text-start">{{ __('Joining date') }}</th>
                                                    <th class="text-start">
                                                        {{ __('Query Days - (Present+Offdays+Leaves) = Absent') }}</th>
                                                    <th class="text-start">{{ __('Shift (Section)') }}</th>
                                                    <th class="text-start">{{ __('Present Address (Phone)') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>


@endsection

@push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // $('#employee_id').select2();
        // $('#hrm_department_id').select2();
        // $('#designation_id').select2();
        // $('#shift_id').select2();
        // $('#grade_id').select2();
        //     const fromDate = document.querySelector('[name="from"]').value
        // const toDate = document.querySelector('[name="to"]').value
        const fromDate = document.querySelector('[name="from"]').value;
        const toDate = document.querySelector('[name="to"]').value;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table;
        //Date Difference
        function dateDiffInDays(date1, date2) {
            // round to the nearest whole number
            return Math.round((date2 - date1) / (1000 * 60 * 60 * 24));
        }

        $(document).ready(function() {
            $('#enddate').on('change', function(e) {
                var date1 = $('#startdate').val();
                var date2 = $('#enddate').val();
                var daysDiff = dateDiffInDays(new Date(date1), new Date(date2));
                var totaldays = daysDiff + 1;
                if (isNaN(totaldays)) {
                    $('.num_of_days').val("");
                } else {
                    $('.num_of_days').val(totaldays);
                }
            });
            $('#startdate').on('change', function(e) {
                var date1 = $('#startdate').val();
                var date2 = $('#enddate').val();
                var daysDiff = dateDiffInDays(new Date(date1), new Date(date2));
                var totaldays = daysDiff + 1;
                if (isNaN(totaldays)) {
                    $('.num_of_days').val("");
                } else {
                    $('.num_of_days').val(totaldays);
                }

            });
        });

        $(document).ready(function() {
            var allRow = '';
            var trashedRow = '';
            // var table = $('.holiday-table').DataTable({
            table = $('.award_table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('
                                        menu.pdf ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('
                                        menu.excel ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('
                                        menu.print ')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }
                }, ],

                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                processing: true,
                serverSide: true,
                searchable: true,
                "ajax": {
                    "url": "{{ route('hrm.date_range.absence_checker.index') }}",
                    "data": function(data) {

                        //send types of request for colums
                        data.showTrashed = $('#trashed_item').attr('showtrash');
                        //filter options
                        data.from = $('#fromDate').val();
                        data.to = $('#toDate').val();

                    }
                },
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    $('#all_item').text('All (' + allRow + ')');

                },

                initComplete: function() {

                    var toolbar = `<div class="d-flex">
                                    <div class="me-3">
                                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                                            <span style="color:#2688cd; margin-right:3px;" id="trash_separator"></span><a style="color:#2688cd" href="#" id="trashed_item"></a>
                                    </div>
                                </div>`;

                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $('#all_item').text('All (' + allRow + ')');
                },

                columns: [{
                        "title": "SL.",
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    }, {
                        data: 'employee_id',
                        title: 'Employee ID'
                    }, {
                        data: 'employee_name',
                        title: 'Employee Name'
                    }, {
                        data: 'joining_date',
                        title: 'Joinging Date'
                    }, {
                        data: 'summary',
                        title: 'Query Days - (Present + Offdays + Leaves) = Absent'
                    }, {
                        data: 'shift_and_section',
                        title: 'Shift (Division)'
                    }, {
                        data: 'present_address_and_phone',
                        title: 'Present Address (Phone)'
                    }

                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });

            table.buttons().container().appendTo('#exportButtonsContainer');

            // //Bulk Action
            // $('#bulk_action_form').on('submit', function(e) {
            //     e.preventDefault();
            //     var url = $(this).attr('action');
            //     var request = $(this).serialize();


            //     $.ajax({
            //         url: url
            //         , type: 'POST'
            //         , data: request
            //         , success: function(data) {
            //             toastr.success(data);
            //             table.ajax.reload();
            //         },
            //         error: function(error) {
            //             toastr.error(error.responseJSON.message);
            //         }
            //     });
            // });

            //    //Add new data
            //     $('#add_form').on('submit', function(e) {
            //         e.preventDefault();
            //         $('.loading_button').show();
            //         var url = $(this).attr('action');
            //         var request = $(this).serialize();
            //         $('.error').html('');

            //         $.ajax({
            //             url: url
            //             , type: 'post'
            //             , data: request
            //             , success: function(data) {
            //                 toastr.success(data);
            //                 $('#add_form')[0].reset();
            //                 $('.loading_button').hide();
            //                 table.ajax.reload();
            //                 $('#addModal').modal('hide');
            //             },

            //             error: function(error) {
            //             $('.loading_button').hide();
            //             toastr.error(error.responseJSON.message);
            //             ///field error.
            //             $.each(error.responseJSON.errors, function(key, error) {
            //                 $('.error_' + key + '').html(error[0].replace(' id ', ' '));
            //             });
            //         }
            //         });
            //     });
            //Submit filter form by select input changing
            $(document).on('change', '.submitable', function() {

                table.ajax.reload();

            });

            $('.submitable_input').on('hide.daterangepicker', function(ev, picker) {
                // $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
                table.ajax.reload();
            });

            $('.submitable_input').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
                    'MM/DD/YYYY'));
                table.ajax.reload();
            });

            //         //date range picker
            //         $(function() {
            //     $('.reportrange').daterangepicker({
            //         autoUpdateInput: false
            //         , applyClass: 'btn-primary'
            //         , cancelClass: 'btn-secondary'
            //         , ranges: {
            //             'Today': [moment(), moment()]
            //             , 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
            //             , 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
            //             , 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
            //             , 'This Month': [moment().startOf('month'), moment().endOf('month')]
            //             , 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            //             , 'This Year': [moment().startOf('year'), moment().endOf('year')]
            //             , 'Last Year': [moment().startOf('year').subtract(1, 'year'), moment().endOf('year').subtract(1, 'year')]
            //         , }

            //     });
            // });
        });

        // $(document.body).on('click', '#is_check_all', function(event){
        //     var checked = event.target.checked;
        //     if (true == checked) {$('.check1').prop('checked', true);}
        //     if (false == checked) {$('.check1').prop('checked', false);}
        // });

        // $('#is_check_all').parent().addClass('text-center');

        // $(document.body).on('click', '.check1', function(event){

        //     var allItem = $('.check1');

        //     var array = $.map(allItem, function(el, index) {
        //         return[el]
        //     })

        //     var allChecked = array.every(isSameAnswer);

        //     function isSameAnswer(el,index,arr) {
        //         if (index === 0){
        //             return true;
        //         }
        //         else {
        //             return (el.checked === arr[index - 1].checked);
        //         }
        //     }

        //     if (allChecked && array[0].checked) {
        //             $('#is_check_all').prop('checked', true);
        //         }else{
        //             $('#is_check_all').prop('checked', false);
        //         }
        // });

        // //trashed item
        // $(document).on('click','#trashed_item',function(e){
        //      e.preventDefault();
        //     $(this).attr("showtrash", true);
        //     $('.check1').prop('checked', false)
        //     $(this).addClass("font-weight-bold");
        //     $('.award_table').DataTable().draw(false);
        //     $('#is_check_all').prop('checked', false);
        //     $('#all_item').removeClass("font-weight-bold");
        //     $("#delete_option").css('display','block');
        //     $("#restore_option").css('display','block');
        //     $("#move_to_trash").css('display','none');
        // })

        // //all item
        // $(document).on('click','#all_item',function(e){
        //     e.preventDefault();
        //     trashed_item = $('#trashed_item');
        //     $('#is_check_all').prop('checked', false);
        //     $('.check1').prop('checked', false);
        //     trashed_item.attr("showtrash", false);
        //     $(this).addClass("font-weight-bold");
        //     $('.award_table').DataTable().draw(false);
        //     $('#trashed_item').removeClass("font-weight-bold")
        //     $("#delete_option").css('display','none');
        //     $("#restore_option").css('display','none');
        //     $("#move_to_trash").css('display','block');
        // })

        // // Edit Modal
        // $(document).on('click', '.edit', function(e) {
        //     e.preventDefault();
        //     $('.data_preloader').show();
        //     var url = $(this).attr('href');

        //     $.ajax({
        //         url: url,
        //         type: 'get',
        //         success: function(data) {
        //             $('#edit_modal_body').html(data);
        //             $('#editModal').modal('show');
        //             $('.data_preloader').hide();
        //         },
        //         error: function(err){
        //             $('.data_preloader').hide();
        //             if (err.status == 0) {
        //                 toastr.error('Net Connetion Error. Reload This Page.');
        //             }else{
        //                 toastr.error('Server Error. Please contact to the support team.');
        //             }
        //         }
        //     });
        // });

        //     // Edit Modal
        //     $(document).on('click', '.restore', function(e) {
        //     e.preventDefault();
        //     var url = $(this).attr('href');

        //     $.confirm({
        //         'title': 'Restore Confirmation'
        //         , 'message': 'Are you sure?'
        //         , 'buttons': {
        //             'Yes': {
        //                 'class': 'yes btn-danger'
        //                 , 'action': function() {
        //                     $('.data_preloader').show();
        //                     $.ajax({
        //                         url: url,
        //                         type: 'get',
        //                         success: function(data) {
        //                             $('.award_table').DataTable().draw(false);
        //                             $('.data_preloader').hide();
        //                             toastr.success(data);
        //                         },
        //                         error: function(err){
        //                             $('.data_preloader').hide();
        //                             if (err.status == 0) {
        //                                 toastr.error('Net Connetion Error. Reload This Page.');
        //                             }else{
        //                                 toastr.error('Server Error. Please contact to the support team.');
        //                             }
        //                         }
        //                     });
        //                 }
        //             }
        //             , 'No': {
        //                 'class': 'no btn-primary'
        //                 , 'action': function() {
        //                 }
        //             }
        //         }
        //     });
        // });

        // $(document).on('click', '.delete', function(e) {
        //     e.preventDefault();
        //     var url = $(this).attr('href');
        //     $('#delete_form').attr('action', url);

        //     $.confirm({
        //         'title': '{{ __('Delete Confirmation') }}'
        //         , 'message': '{{ __('Are you sure?') }}'
        //         , 'buttons': {
        //             '{{ __('Yes') }}': {
        //                 'class': 'yes btn-danger'
        //                 , 'action': function() {
        //                     // $('#delete_form').submit();
        //                     $.ajax({
        //                         url: url
        //                         , type: 'DELETE'
        //                         , success: function(data) {
        //                             if ($.isEmptyObject(data.errorMsg)) {
        //                                 toastr.error(data);
        //                                $('.award_table').DataTable().draw(false);
        //                             } else {
        //                                 toastr.error(data.errorMsg);
        //                             }
        //                         },
        //                         error: function(error) {
        //                             $('.loading_button').hide();
        //                             toastr.error(error.responseJSON.message);
        //                         }
        //                     });
        //                 }
        //             }
        //             , '{{ __('No') }}': {
        //                 'class': 'no btn-primary'
        //                 , 'action': function() {
        //                 }
        //             }
        //         }
        //     });
        // });

        // $.ajax({
        //     url: "{{ route('hrm.employees.index') }}",
        //     type: 'get',
        //     dataType: 'json',
        //     success: function(data) {
        //         $.each(data.data, function(key,val){
        //             $('#employee_id').append('<option value="' + val.id + '">' + val.name+'</option>');
        //         })
        //         }

        // });

        // $.ajax({
        //         url: "{{ route('hrm.v1.departments.index') }}",
        //         type: 'get',
        //         dataType: 'json',
        //         success: function(data) {
        //             $.each(data.data, function(key, val) {
        //                 $('#hrm_department_id').append('<option value="' + val.id + '">' + val.name +
        //                     '</option>');
        //             });
        //         }
        //     });

        // $.ajax({
        //     url: "{{ route('hrm.v1.designations.index') }}"
        //     , type: 'get'
        //     , dataType: 'json'
        //     , success: function(data) {
        //         $.each(data.data, function(key, val) {
        //             $('#designation_id').append('<option value="' + val.id + '">' + val.name +
        //                 '</option>');
        //         });
        //     }
        // });

        // $.ajax({
        //     url: "{{ route('hrm.v1.shifts.index') }}"
        //     , type: 'get'
        //     , dataType: 'json'
        //     , success: function(data) {
        //         $.each(data.data, function(key, val) {
        //             $('#shift_id').append('<option value="' + val.id + '">' + val.name +
        //                 '</option>');
        //         });
        //     }
        // });

        // $.ajax({
        //     url: "{{ route('hrm.v1.grades.index') }}"
        //     , type: 'get'
        //     , dataType: 'json'
        //     , success: function(data) {
        //         $.each(data.data, function(key, val) {
        //             $('#grade_id').append('<option value="' + val.id + '">' + val.name +
        //                 '</option>');
        //         });
        //     }
        // });

        //code start here

        const submitButton = document.getElementById('submitButton')
        submitButton.addEventListener('click', function(e) {
            e.preventDefault()
            table.ajax.reload();
        })
    </script>
@endpush
