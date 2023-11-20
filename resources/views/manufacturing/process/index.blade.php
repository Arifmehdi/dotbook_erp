@extends('layout.master')
@push('css')
    
@endpush
@section('title', 'All Process - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div>
                    <h6>@lang('menu.processes_manage')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :can="'process_add'" />
                    @if (auth()->user()->can('process_view'))
                        <div>
                            <a href="{{ route('manufacturing.process.index') }}"
                                class="text-white btn text-white btn-sm"><span><i
                                        class="fa-thin  fa-dumpster-fire  fa-2x"></i><br> @lang('menu.process')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('production_view'))
                        <div>
                            <a href="{{ route('manufacturing.productions.index') }}"
                                class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-shapes fa-2x"></i><br>
                                    @lang('menu.productions')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_report'))
                        <div>
                            <a href="{{ route('manufacturing.report.index') }}"
                                class="text-white btn text-white btn-sm"><span><i
                                        class="fa-thin fa-file-lines fa-2x"></i><br> @lang('menu.manufacturing_report')</span></a>
                        </div>
                    @endif
                    @if (auth()->user()->can('manuf_settings'))
                        <div>
                            <a href="{{ route('manufacturing.settings.index') }}"
                                class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-sliders fa-2x"></i><br>
                                    @lang('menu.manufacturing_setting')</span></a>
                        </div>
                    @endif
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                <form id="update_product_cost_form" action="">
                                    <table class="display data_tbl data__table">
                                        <thead>
                                            <tr class="bg-navey-blue">
                                                <th data-bSortable="false">
                                                    <input class="all" type="checkbox" name="all_checked" />
                                                </th>
                                                <th class="text-black">@lang('menu.actions')</th>
                                                <th class="text-black">@lang('menu.product_name')</th>
                                                <th class="text-black">@lang('menu.category')</th>
                                                <th class="text-black">@lang('menu.sub_category')</th>
                                                <th class="text-black">@lang('menu.wastage')</th>
                                                <th class="text-black">@lang('menu.output_quantity')</th>
                                                <th class="text-black">@lang('menu.total_ingredient_cost')</th>
                                                <th class="text-black">@lang('menu.production_cost')</th>
                                                <th class="text-black">@lang('menu.total_cost')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </form>
                            </div>
                        </div>

                        @if (auth()->user()->can('process_delete'))
                            <form id="deleted_form" action="" method="post">
                                @method('DELETE')
                                @csrf
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (auth()->user()->can('process_add'))
        <div class="modal fade" id="addModal" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog double-col-modal" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">@lang('menu.choose_product')</h6>
                        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                                class="fas fa-times"></span></a>
                    </div>
                    <div class="modal-body">
                        <!--begin::Form-->
                        <form action="{{ route('manufacturing.process.create') }}" method="GET">
                            <div class="form-group">
                                <label><b>@lang('menu.select_product')</b> <span class="text-danger">*</span></label>
                                <select required name="product_id" class="form-control select2 form-select">
                                    @foreach ($products as $product)
                                        @php
                                            $variant_name = $product->variant_name ? $product->variant_name : '';
                                            $product_code = $product->variant_code ? $product->variant_code : $product->product_code;
                                        @endphp
                                        <option
                                            value="{{ $product->id . '-' . ($product->v_id ? $product->v_id : 'NULL') }}">
                                            {{ $product->name . ' ' . $variant_name . ' (' . $product_code . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group row mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none">
                                            <i class="fas fa-spinner"></i>
                                        </button>
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
    @endif

    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog col-50-modal" role="document">
            <div class="modal-content" id="view-modal-content">

            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2();

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                extend: 'pdf',
                text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, {
                extend: 'excel',
                text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, {
                extend: 'print',
                text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                className: 'pdf btn text-white btn-sm px-1',
                exportOptions: {
                    columns: [2, 3, 4, 5, 6, 7, 8, 9]
                }
            }, ],
            "processing": true,
            "serverSide": true,
            aaSorting: [
                [0, 'asc']
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            ajax: "{{ route('manufacturing.process.index') }}",
            columnDefs: [{
                "targets": [0],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                data: 'multiple_update',
                name: 'multiple_update'
            }, {
                data: 'action',
                name: 'action'
            }, {
                data: 'product',
                name: 'product'
            }, {
                data: 'cate_name',
                name: 'cate_name'
            }, {
                data: 'sub_cate_name',
                name: 'sub_cate_name'
            }, {
                data: 'wastage_percent',
                name: 'wastage_percent'
            }, {
                data: 'total_output_qty',
                name: 'total_output_qty'
            }, {
                data: 'total_ingredient_cost',
                name: 'total_ingredient_cost'
            }, {
                data: 'production_cost',
                name: 'production_cost'
            }, {
                data: 'total_cost',
                name: 'total_cost'
            }, ],
        });
        table.buttons().container().appendTo('#exportButtonsContainer');

        //Show process view modal with data
        $(document).on('click', '#view', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $.get(url, function(data) {

                $('#view-modal-content').html(data);
                $('#viewModal').modal('show');
            });
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);

            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
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
                    toastr.error(data);
                }
            });
        });

        $(document).on('change', '.all', function() {

            if ($(this).is(':CHECKED', true)) {

                $('.data_id').prop('checked', true);
            } else {

                $('.data_id').prop('checked', false);
            }
        });
    </script>
@endpush
