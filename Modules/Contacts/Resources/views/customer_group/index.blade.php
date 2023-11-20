@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.customer_groups')</h6>
            </div>
            <x-all-buttons>
                <x-slot name="after">
                    <a href="#" class="btn text-white btn-sm"><span><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</span></a>
                </x-slot>
            </x-all-buttons>
        </div>
    </div>

    <div class="p-15">
        <div class="row gx-1">
            <div class="col-md-4">
                <div class="card" id="add_customer_group_form_div">
                    <div class="card-header">
                        <h6>@lang('menu.add_customer_group')</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-area">
                            <form id="add_group_form" action="{{ route('customers.groups.store') }}" method="POST">
                                <div class="form-group mt-2">
                                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control add_input" data-name="Group name" id="name" placeholder="Group name" required />
                                    <span class="error error_name"></span>
                                </div>
                                <div class="form-group mt-2">
                                    <label><strong>@lang('menu.calculation_percent') (%) </strong></label>
                                    <input type="number" step="any" name="calculation_percent" class="form-control" step="any" id="calculation_percent" placeholder="@lang('menu.calculation_percent')" autocomplete="off" />
                                </div>
                                <div class="form-group row mt-3">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <div class="loading-btn-box">
                                            <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                            <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                                            <button type="reset" class="btn btn-sm btn-danger float-end me-2">@lang('menu.reset')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card display-none" id="edit_customer_group_form">
                    <div class="section-header">
                        <div class="col-md-6">
                            <h6>@lang('menu.edit_customer_group')</h6>
                        </div>
                    </div>
                    <div class="form-area px-3 pb-2" id="edit_customer_group_form_body"></div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h6>@lang('menu.all_customer_group')</h6>
                    </div>

                    <div class="card-body">
                        <div class="data_preloader">
                            <h6><i class="fas fa-spinner"></i>@lang('menu.processing')</h6>
                        </div>
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table customerTable">
                                <thead>
                                    <tr>
                                        <th>@lang('menu.serial')</th>
                                        <th>@lang('menu.name')</th>
                                        <th>@lang('menu.calculation_percent')</th>
                                        <th>@lang('menu.action')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
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
<script>
    var table = $('.data_tbl').DataTable({
        processing: true
        , dom: "lBfrtip"
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
                , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>Save as Excel'
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
        , ],

        serverSide: true
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('customers.groups.index') }}"
        , columns: [{
                data: 'DT_RowIndex'
                , name: 'DT_RowIndex'
            }
            , {
                data: 'group_name'
                , name: 'group_name'
            }
            , {
                data: 'calc_percentage'
                , name: 'calc_percentage'
            }
            , {
                data: 'action'
                , name: 'action'
            }
        , ]
    , });
    table.buttons().container().appendTo('#exportButtonsContainer');
    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        $(document).on('submit', '#add_group_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');
            var request = $(this).serialize();

            $.ajax({
                url: url
                , type: 'POST'
                , data: request
                , success: function(data) {
                    $('.error').html('');
                    toastr.success('Customer group created successfully');
                    $('#add_group_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.customerTable').DataTable().ajax.reload();
                }
                , error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }
                    $.each(err.responseJSON.errors, function(key, error) {

                        $('.error_' + key + '').html(error[0]);
                    });
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
                , type: 'POST'
                , async: false
                , data: request
                , success: function(data) {
                    $('.customerTable').DataTable().ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url
                , type: 'GET'
                , success: function(data) {
                    $('#edit_customer_group_form_body').html(data);
                    $('#add_customer_group_form_div').hide();
                    $('#edit_customer_group_form').show();
                    $('.data_preloader').hide();
                    document.getElementById('name').focus();
                }
                , error: function(err) {
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });
    });

</script>
@endpush
