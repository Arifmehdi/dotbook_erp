@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'All Previous Prices - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <h6>@lang('menu.all_pre_price')</h6>
                <x-all-buttons>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>
        <div class="p-15">
            <div class="card">
                {{-- <div class="section-header">
                    <div class="col-md-10">
                        <h6>All Sale</h6>
                    </div>

                    @if (auth()->user()->can('create_add_sale'))
                        <div class="col-md-2">
                            <div class="btn_30_blue float-end">
                                <a href="{{ route('sales.create') }}" id="add_btn"><i class="fas fa-plus-square"></i> Add (Ctrl+Enter)</a>
                            </div>
                        </div>
                    @endif
                </div> --}}

                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        {{-- <table class="display data_tbl data__table table-hover"> --}}
                        {{-- <table class="display data_tbl modal-table table-sm table-striped"> --}}
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th class="text-start">Start Time</th>
                                    <th class="text-start">@lang('menu.end_time')</th>
                                    <th class="text-start">@lang('menu.category')</th>
                                    <th class="text-start">@lang('menu.subcategory')</th>
                                    <th class="text-start">@lang('menu.item_name')</th>
                                    <th class="text-start">@lang('menu.created_by')</th>
                                    <th class="text-start">@lang('menu.previous_price')</th>
                                    <th class="text-start">@lang('menu.new_price')</th>
                                    <th class="text-start">@lang('menu.actions')</th>
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
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))
            toastr.success('{{ session('successMsg') }}');
        @endif

        var price_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],

            ajax: "{{ route('sales.recent.price.index') }}",

            columns: [
                {data: 'start_time', name: 'start_time'},
                {data: 'end_time', name: 'end_time'},
                {data: 'category', name: 'categories.name'},
                {data: 'subcategory', name: 'subcategories.name'},
                {data: 'product_name', name: 'products.name'},
                {data: 'created_by', name: 'users.name'},
                {data: 'previous_price', name: 'previous_price'},
                {data: 'new_price', name: 'new_price'},
                {data: 'action'}
            ]
        });
        price_table.buttons().container().appendTo('#exportButtonsContainer');

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
                    sales_table.ajax.reload();
                    toastr.error(data);
                }
            });
        });
    </script>

    <script type="text/javascript">

    </script>
@endpush
