@extends('layout.master')
@push('css') @endpush
@section('title', 'SR List - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.manage_sr')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        @if (auth()->user()->can('user_add'))
                            <a href="{{ route('sales.sr.create') }}" class="btn text-white btn-sm"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_new')</span></a>
                        @endif
                    </x-slot>
                    <x-slot name="after">
                        <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table">
                            <thead>
                                <tr>
                                    <th>@lang('menu.name')</th>
                                    <th>@lang('menu.phone')</th>
                                    <th>@lang('menu.user_id')</th>
                                    <th>@lang('menu.username')</th>
                                    <th>@lang('menu.allow_login')</th>
                                    <th>@lang('menu.role')</th>
                                    <th>@lang('menu.email')</th>
                                    <th>@lang('menu.action')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Show session message by toster alert.
        @if (Session::has('successMsg'))

            toastr.success('{{ session('successMsg') }}');
        @endif

        var table = $('.data_tbl').DataTable({
            dom: "lBfrtip",
            buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
            ],
            "processing": true,
            "serverSide": true,
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('sales.sr.index') }}",
            },
            columns: [{
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'user_id',
                    name: 'user_id'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'allow_login',
                    name: 'prefix'
                },
                {
                    data: 'role_name',
                    name: 'last_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'action'
                },
            ],
        });

        table.buttons().container().appendTo('#exportButtonsContainer');
        //Submit filter form by select input changing

        $(document).on('change', '.submit_able', function() {

            table.ajax.reload();
        });
    </script>
@endpush
