@php
    $userActivityLogUtil = new App\Utils\UserActivityLogUtil();
@endphp
@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css"
        integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <style>
        .log_table td {
            font-size: 9px !important;
            font-weight: 500 !important;
        }
    </style>
@endpush
@section('title', 'User Activities Log - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('User Activities Log') }}</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-md-12">
                    <div class="form_element rounded m-0">
                        <div class="element-body">
                            <form id="filter_form">
                                <div class="form-group row align-items-end g-2">
                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.action_by') </strong></label>
                                        <select name="user_id" class="form-control form-select" id="user_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.action_name') </strong></label>
                                        <select name="action" class="form-control form-select" id="action" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($userActivityLogUtil->actions() as $key => $action)
                                                <option value="{{ $key }}">{{ $action }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.subject_type') </strong></label>
                                        <select name="subject_type" class="form-control select2 form-select" id="subject_type" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                            @foreach ($userActivityLogUtil->subjectTypes() as $key => $subjectTypes)
                                                <option value="{{ $key }}">{{ $subjectTypes }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.from_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-week input_f"></i></span>
                                            </div>
                                            <input type="text" name="from_date" id="datepicker" class="form-control from_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-2 col-md-6">
                                        <label><strong>@lang('menu.to_date') </strong></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">
                                                    <i class="fas fa-calendar-week input_f"></i>
                                                </span>
                                            </div>
                                            <input type="text" name="to_date" id="datepicker2" class="form-control to_date date" autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-xl-1 col-md-6">
                                        <button type="submit" class="btn btn-sm btn-info"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-1">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="data_preloader">
                                <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                            </div>
                            <div class="table-responsive h-350" id="data-list">
                                {{-- <table class="display data_tbl data__table table-hover"> --}}
                                <table class="log_table display data_tbl modal-table table-sm table-striped">
                                    <thead>
                                        <tr>
                                            <th>@lang('menu.date')</th>
                                            <th>@lang('menu.action_by')</th>
                                            <th>@lang('menu.action_name')</th>
                                            <th>@lang('menu.subject_type')</th>
                                            <th>@lang('menu.description')</th>
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

@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $('.select2').select2();

        var log_table = $('.data_tbl').DataTable({
            "processing": true,
            "serverSide": true,
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1'
                },
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('reports.user.activities.log.index') }}",
                "data": function(d) {
                    d.user_id = $('#user_id').val();
                    d.action = $('#action').val();
                    d.subject_type = $('#subject_type').val();
                    d.from_date = $('.from_date').val();
                    d.to_date = $('.to_date').val();
                }
            },
            columnDefs: [{
                "targets": [3, 4],
                "orderable": false,
                "searchable": false
            }],
            columns: [{
                    data: 'date',
                    name: 'date'
                },
                {
                    data: 'action_by',
                    name: 'users.name'
                },
                {
                    data: 'action',
                    name: 'action'
                },
                {
                    data: 'subject_type',
                    name: 'subject_type'
                },
                {
                    data: 'descriptions',
                    name: 'descriptions'
                },
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });
        log_table.buttons().container().appendTo('#exportButtonsContainer');

        //Submit filter form by select input changing
        $(document).on('submit', '#filter_form', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            log_table.ajax.reload();
        });
    </script>

    <script type="text/javascript">
        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY'
        });

        new Litepicker({
            singleMode: true,
            element: document.getElementById('datepicker2'),
            dropdowns: {
                minYear: new Date().getFullYear() - 50,
                maxYear: new Date().getFullYear() + 100,
                months: true,
                years: true
            },
            tooltipText: {
                one: 'night',
                other: 'nights'
            },
            tooltipNumber: (totalDays) => {
                return totalDays - 1;
            },
            format: 'DD-MM-YYYY',
        });
    </script>
@endpush
