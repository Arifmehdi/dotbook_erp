@extends('layout.master')
@push('css')
@endpush
@section('title', 'Requisition Departments List - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.departments')</h6>
            </div>

            <x-all-buttons>
                <x-slot name="before">
                    <x-add-button :href="route('requisitions.departments.create')" id="addRequisitionDepartment"/>
                </x-slot>
                <x-slot name="after">
                    <a href="{{ route('requisitions.departments.print') }}" id="print_department" class="btn text-white btn-sm"><span><i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')</span></a>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                </x-slot>
            </x-all-buttons>
        </div>
    </div>
    <div class="p-15">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-startx">@lang('menu.serial')</th>
                                        <th class="text-startx">@lang('menu.name')</th>
                                        <th class="text-startx">@lang('menu.phone')</th>
                                        <th class="text-startx">@lang('menu.address')</th>
                                        <th class="text-startx">@lang('menu.action')</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <form id="deleted_form" action="" method="post">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal -->
<div class="modal fade" id="requisitionDepartmentAddOrEditModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
@endsection
@push('scripts')
<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [
            {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: [0,1,2,3]}},
            {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: [0,1,2,3]}},
        ],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [[10, 25, 50, 100, 500, 1000, -1], [10, 25, 50, 100, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('requisitions.departments.index') }}",
        columnDefs: [{"targets": [0], "orderable": false, "searchable": false}],
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'name',name: 'name'},
            {data: 'phone',name: 'phone'},
            {data: 'address',name: 'address'},
            {data: 'action',name: 'action'},
        ],
    });

    table.buttons().container().appendTo('#exportButtonsContainer');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // pass editable data to edit modal fields
        $(document).on('click', '#addRequisitionDepartment', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#requisitionDepartmentAddOrEditModal').html(data);
                    $('#requisitionDepartmentAddOrEditModal').modal('show');


                    setTimeout(function () {

                        $('#department_name').focus().select();
                    }, 500);
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#requisitionDepartmentAddOrEditModal').html(data);
                    $('#requisitionDepartmentAddOrEditModal').modal('show');

                    setTimeout(function () {

                        $('#department_name').focus().select();
                    }, 500);
                }
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation'
                , 'message': 'Are you sure?'
                , 'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger'
                        , 'action': function() {
                            $('#deleted_form').submit();
                        }
                    }
                    , 'No': {
                        'class': 'no btn-primary'
                        , 'action': function() {  }
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
                url: url
                , type: 'post'
                , async: false
                , data: request
                , success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                    table.ajax.reload();
                }
            });
        });
    });

    //Print purchase Payment report
    $(document).on('click', '#print_department', function (e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data){

                $(data).printThis({
                    debug: false,
                    importCSS: true,
                    importStyle: true,
                    loadCSS: "{{asset('css/print/sale.print.css')}}",
                    removeInline: false,
                    printDelay: 1000,
                });
            }
        });
    });
</script>
@endpush
