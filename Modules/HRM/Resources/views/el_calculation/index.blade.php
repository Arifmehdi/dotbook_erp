@extends('layout.master')
@section('title', 'Earned Leave Calculation - ')

@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Earned Leave Calculation') }}</h6>
            </div>
            <x-all-buttons />
        </div>

        <form id="bulk_action_form" action="{{ route('hrm.el-payments.bulk-action') }}" method="POST">
            <div class="p-15">
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header header-elements-inline d-flex justify-content-between align-content-center p-2">
                                <div>
                                    <h5 class="card-title">EL Calculation List</h5>
                                </div>
                                <div class="row g-2">
                                    <label class="col-6"><strong>{{ __('Select Year') }} </strong></label>
                                    <div class="col-6">
                                        <select class="form-control form-control-sm submitable" id="year"
                                            name="year">
                                            <option value="">{{ __('Select Year') }}</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}"
                                                    @if (date('Y') == $year) selected @endif>{{ $year }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                                </div>
                                <form id="bulk_action">
                                    <div class="table-responsive h-350" id="data-list">
                                        <table class="display data_tbl data__table el-payments-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">@lang('menu.sl')</th>
                                                    <th class="text-start all">{{ __('Employee ID') }} </th>
                                                    <th class="text-start all">{{ __('Employee Name') }} </th>
                                                    <th class="text-start all">{{ __('Section') }}</th>
                                                    <th class="text-start all">{{ __('Joining') }}</th>
                                                    <th class="text-start all">{{ __('Phone') }}</th>
                                                    <th class="text-start all">{{ __('Present') }}</th>
                                                    <th class="text-start all">{{ __('EL Count') }}</th>
                                                    <th class="text-start all">{{ __('EL Enjoyed') }}</th>
                                                    <th class="text-start all">{{ __('EL Payable') }}</th>
                                                    <th class="text-start all">{{ __('Remuneration') }}</th>
                                                    <th class="text-start all">{{ __('Payable') }}</th>
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

    <!-- Delete Form -->
    <form id="delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

@endsection

@push('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        //Date Difference
        function dateDiffInDays(date1, date2) {
            // round to the nearest whole number
            return Math.round((date2 - date1) / (1000 * 60 * 60 * 24));
        }
        var table;
        $(document).ready(function() {
            var allRow = '';
            table = $('.el-payments-table').DataTable({
                dom: "lBfrtip",
                buttons: [{
                        extend: 'pdf',
                        text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                        className: 'pdf btn text-white btn-sm px-1',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                ],
                "drawCallback": function(data) {
                    allRow = data.json.allRow;
                    $('#all_item').text('All (' + allRow + ')');
                },
                initComplete: function() {

                    var toolbar = `<div class="d-flex">
                    <div class="me-3">
                            <a href="#" style="color:#2688cd" class="font-weight-bold" id="all_item">All</a>
                    </div>
                </div>`;
                    $("div.dataTables_filter").prepend(toolbar);
                    $("div.dataTables_filter").addClass('d-flex');
                    $('#all_item').text('All (' + allRow + ')');
                },
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                processing: true,
                serverSide: true,
                searchable: true,
                ajax: {
                    url: "{{ route('hrm.el-calculation.index') }}",
                    data: function(d) {
                        d.year = $('#year').val()
                    }
                },
                columns: [{
                        name: 'DT_RowIndex',
                        data: 'DT_RowIndex'
                    },
                    {
                        data: 'employee_id',
                        name: 'employee_id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'section_name',
                        name: 'section_name'
                    },
                    {
                        data: 'joining_date',
                        name: 'joining_date'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'yearly_total_present',
                        name: 'yearly_total_present'
                    },
                    {
                        data: 'yearly_el_count',
                        name: 'yearly_el_count'
                    },
                    {
                        data: 'taken_el',
                        name: 'taken_el'
                    },
                    {
                        data: 'payable_el',
                        name: 'payable_el'
                    },
                    {
                        data: 'daily_remuneration',
                        name: 'daily_remuneration'
                    },
                    {
                        data: 'net_payable',
                        name: 'net_payable'
                    }
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
            });
            table.buttons().container().appendTo('#exportButtonsContainer');
        });
        //Submit filter form by select input changing
        $(document).on('change', '.submitable', function() {

            table.ajax.reload();
        });

        //all item
        $(document).on('click', '#all_item', function(e) {
            e.preventDefault();
            trashed_item = $('#trashed_item');
            $('#is_check_all').prop('checked', false);
            $('.check1').prop('checked', false);
            trashed_item.attr("showtrash", false);
            $(this).addClass("font-weight-bold");
            $('.el-payments-table').DataTable().draw(false);
            $('#trashed_item').removeClass("font-weight-bold")
            $("#delete_option").css('display', 'none');
            $("#restore_option").css('display', 'none');
            $("#move_to_trash").css('display', 'block');
        })
    </script>
@endpush
