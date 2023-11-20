@extends('layout.master')
@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
@section('title', 'Requester List - ')
@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <h6>@lang('menu.requester')</h6>
        </div>

        <x-all-buttons>
            <x-slot name="before">
                <x-add-button :href="route('requesters.create')" id="addRequester" :can="'asset_allocation_create'"/>
            </x-slot>
            <x-slot name="after">
                <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
            </x-slot>
        </x-all-buttons>
    </div>

    <div class="p-15">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table requesterTable">
                                <thead>
                                    <tr>
                                        <th class="text-start">@lang('menu.name')</th>
                                        <th class="text-start">@lang('menu.phone_number')</th>
                                        <th class="text-start">@lang('menu.address')</th>
                                        <th class="text-start">@lang('menu.action')</th>
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

<div class="modal fade" id="requestAddOrEditModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
</div>

<form id="deleted_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>
@endsection
@push('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var requester_table = $('.requesterTable').DataTable({
            "processing": true,
            dom: "lBfrtip",
            buttons: [
                {extend: 'print',text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'pdf',text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
                {extend: 'excel',text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',className: 'pdf btn text-white btn-sm px-1',exportOptions: {columns: 'th:not(:last-child)'}},
            ],
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            ajax: {
                "url": "{{ route('requesters.index') }}",
            },
            columns: [
                { data: 'name', name: 'name'},
                {data: 'phone_number',name: 'phone_number'},
                {data: 'area', name: 'area'},
                { data: 'action'},
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

        requester_table.buttons().container().appendTo('#exportButtonsContainer');

        $(document).on('click', '#addRequester', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#requestAddOrEditModal').html(data);
                    $('#requestAddOrEditModal').modal('show');

                    setTimeout(function () {

                        $('#requester_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else if (err.status == 500) {

                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('.data_preloader').hide();
                    $('#requestAddOrEditModal').html(data);
                    $('#requestAddOrEditModal').modal('show');

                    setTimeout(function () {

                        $('#requester_name').focus().select();
                    }, 500);
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

        // Delete Part Start
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

        $(document).on('submit', '#deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                        return;
                    }

                    toastr.error(data);
                    requester_table.ajax.reload();
                },
                error: function(err) {
                    toastr.error(err.responseJSON)
                    asset_table.ajax.reload();
                }
            });
        });
        // edit Part end
    </script>
@endpush
