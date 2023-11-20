@extends('layout.master')
@push('css')
@endpush
@section('title', 'Weight Scale Client List - ')
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.weight_scale_client_list')</h6>
            </div>
            <x-all-buttons>
                <x-add-button :can="'add_weight_scale_client'" id="addWeightClient" />
            </x-all-buttons>
        </div>

        <div class="p-15">
            <div class="row g-0">
                <div class="card">
                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.client_id')</th>
                                        <th>@lang('menu.name')</th>
                                        <th>@lang('menu.phone')</th>
                                        <th>@lang('menu.email')</th>
                                        <th>@lang('menu.company_name')</th>
                                        <th>@lang('menu.address')</th>
                                        <th>@lang('menu.tax_no')</th>
                                        <th>@lang('menu.actions')</th>
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

<!-- Add Weight client Modal -->
<div class="modal fade" id="addOrEditWeightClientModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>
<!-- Add Weight client  Modal End-->
@endsection
@push('scripts')

<script>
    var table = $('.data_tbl').DataTable({
        dom: "lBfrtip"
        , buttons: [{
                extend: 'pdf'
                , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
            , {
                extend: 'excel'
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
            , {
                extend: 'print'
                , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                , className: 'pdf btn text-white btn-sm px-1'
                , exportOptions: {
                    columns: 'th:not(:first-child)'
                }
            }
        , ]
        , "processing": true
        , "serverSide": true,
        //aaSorting: [[0, 'asc']],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('scale.client.index') }}"
        , columns: [{
                data: 'client_id'
                , name: 'weight_clients.client_id'
            }
            , {
                data: 'name'
                , name: 'weight_clients.name'
            }
            , {
                data: 'phone'
                , name: 'weight_clients.phone'
            }
            , {
                data: 'email'
                , name: 'weight_clients.email'
            }
            , {
                data: 'company_name'
                , name: 'weight_clients.company_name'
            }
            , {
                data: 'address'
                , name: 'weight_clients.address'
            }
            , {
                data: 'tax_no'
                , name: 'weight_clients.tax_no'
            }
            , {
                data: 'action'
            }
        , ]
    });

    table.buttons().container().appendTo('#exportButtonsContainer');

    $(document).on('click', '#delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $('#deleted_form').attr('action', url);
        $.confirm({
            'title': 'Delete Confirmation'
            , 'content': 'Are you sure, you want to delete?'
            , 'buttons': {
                'Yes': {
                    'class': 'yes btn-primary'
                    , 'action': function() {
                        $('#deleted_form').submit();
                    }
                }
                , 'No': {
                    'class': 'no btn-danger'
                    , 'action': function() {}
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
            , data: request
            , success: function(data) {
                if (!$.isEmptyObject(data.errorMsg)) {

                    toastr.error(data.errorMsg);
                    return;
                }

                table.ajax.reload();
                toastr.error(data);
            }
            , error: function(err) {

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                } else if (err.status == 500) {

                    toastr.error('Server Error. Please contact to the support team.');
                }
            }
        });
    });

    $('#addWeightClient').on('click', function(e) {
        e.preventDefault();

        $.get("{{route('scale.add.weight.client.modal')}}", function(data) {

            $('#addOrEditWeightClientModal').html(data);
            $('#addOrEditWeightClientModal').modal('show');

            setTimeout(function() {

                $('#client_name').focus();
            }, 500);
        });
    });

    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        $.get(url, function(data) {

            $('#addOrEditWeightClientModal').html(data);
            $('#addOrEditWeightClientModal').modal('show');

            setTimeout(function() {

                $('#e_client_name').focus().select();
            }, 500);
        });
    });

</script>
@endpush
