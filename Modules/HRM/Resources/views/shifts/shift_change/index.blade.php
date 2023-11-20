@extends('layout.master')
@section('title','Employee Shift Change - ')
@push('css')
<style>
    .sorting_disabled {
        background: none;
    }

    .font-weight-bold {
        font-weight: bold;
    }
    #overtime-checkbox::after{
        display: none;
    }
</style>
@endpush

@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <h6>{{ __('Shift Change') }}</h6>
        </div>
        <x-all-buttons/>
    </div>

    <div class="p-15">
        <div class="row mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table shift_table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{__('S/L')}}</th>
                                        <th class="text-start">{{__('Employee ID')}}</th>
                                        <th class="text-start">{{__('Name')}}</th>
                                        <th class="text-start">{{__('Division')}}</th>
                                        <th class="text-start">{{__('Phone')}}</th>
                                        <th class="text-start">{{__('Address')}}</th>
                                        <th class="text-start">{{__('Email')}}</th>
                                        <th class="text-start">{{ __('Shift') }}</th>
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

@endsection

@push('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        var allRow = '';
        var trashedRow = '';
        var table = $('.shift_table').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: ':visible'}},
                {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: ':visible'}},
                {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: { columns: ':visible'}},
            ],

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            processing: true,
            serverSide: true,
            searchable: true,
            "ajax": {
                "url": "{{ route('hrm.shifts.changes') }}",
            },

            columns: [
                {data: 'DT_RowIndex', searchable: false, orderable: false},
                {name: 'employee_id', data: 'employee_id'},
                {name: 'name', data: 'name'},
                {name: 'hrm_department_id', data: 'hrm_department_id'},
                {name: 'phone', data: 'phone'},
                {name: 'address', data: 'address'},
                {name: 'email', data: 'email'},
                {name: 'shift_id', data: 'shift_id'},
            ],
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('change', '.dropdown_shift_id', function(e) {
            //e.preventDefault();
            var id = $(this).val();
            var employee_id = $(this).attr('id');
            $.confirm({
                'title': 'Shift change Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger'
                        , 'action': function() {
                            $.ajax({
                                url: "{{ url('hrm/employee/shift/change/') }}/"+id+'/'+employee_id,
                                type: 'get',
                                success: function(data) {
                                    toastr.success(data);
                                    table.ajax.reload();
                                }
                            });
                        }
                    }
                    , 'No': {
                        'class': 'no btn-primary'
                        , 'action': function() {
                        }
                    }
                }
            });
        });
    });

</script>

@endpush
