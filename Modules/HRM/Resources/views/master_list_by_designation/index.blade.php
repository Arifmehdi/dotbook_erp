@extends('layout.master')
@section('title','Master List By Designation - ')
@push('css')
<style>
    .sorting_disabled {
        background: none;
    }

    .font-weight-bold {
        font-weight: bold;
    }
</style>
@endpush

@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <h6>{{ __('Master List By Designation') }}</h6>
        </div>
        <x-all-buttons/>
    </div>

    <form id="bulk_action_form" action="{{route('hrm.payment-types.bulk-action')}}" method="POST">
        <div class="p-15">
        <div class="row mt-1">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table leave-type-table">
                                <thead>
                                    <tr>
                                        <th class="text-start">{{__('SL.')}}</th>
                                        <th class="text-start">{{__('Designation')}}</th>
                                        <th class="text-start">{{__('Total Person')}}</th>
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
    </form>
</div>

@endsection

@push('js')
<script>

    $(document).ready(function() {
        var allRow = '';
        var trashedRow = '';
        var table = $('.leave-type-table').DataTable({
            dom: "lBfrtip",
            buttons: [
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: [0,1,2]}},
                {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: [0,1,2]}},
                {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns:  [0,1,2]}},
            ],

            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
            processing: true,
            serverSide: true,
            searchable: true,
            "ajax": {
                "url": "{{ route('hrm.employee.master.list') }}",
                "data": function(data) {

                    //send types of request for colums
                    data.showTrashed = $('#trashed_item').attr('showtrash');

                }
            },

            initComplete : function () {

                $("div.dataTables_filter").addClass('d-flex');
            },

            columns: [
                {name: 'DT_RowIndex', data: 'DT_RowIndex'},
                {name: 'designation', data: 'designation'},
                {name: 'totalPerson', data: 'totalPerson'},
            ],
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

    });

</script>

@endpush
