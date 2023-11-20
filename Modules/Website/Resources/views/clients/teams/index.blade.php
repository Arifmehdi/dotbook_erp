@extends('layout.master')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/css/litepicker.min.css" integrity="sha512-7chVdQ5tu5/geSTNEpofdCgFp1pAxfH7RYucDDfb5oHXmcGgTz0bjROkACnw4ltVSNdaWbCQ0fHATCZ+mmw/oQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.css')}}" />
<style>
    button.btn.btn-danger.deletewarrantyButton {
        border-radius: 0px !important;
        padding: 0.7px 10px !important;
    }

    .form-title {
        background: transparent;
        color: #0c0c0c;
        text-shadow: 0 0;
        height: 50px;
        line-height: 50px;
        margin: 0px;
    }

</style>
@endpush
@section('title', 'Website - ')
@section('content')
<div class="body-wraper">
    @if (auth()->user()->can('web_manage_team') || auth()->user()->can('web_add_team')
    || auth()->user()->can('web_edit_team') || auth()->user()->can('web_delete_team'))
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h6>@lang('menu.teams')</h6>
            </div>
            <x-all-buttons>
                <x-add-button id="add_customer" :can="'web_add_team'" :text="'Add Team'" />
                <x-slot name="after">
                    <x-help-button />
                </x-slot>
            </x-all-buttons>
        </div>
    </div>
    @if (auth()->user()->can('web_manage_team') || auth()->user()->can('web_edit_team') || auth()->user()->can('web_delete_team'))
    <div class="p-15">
        <div class="row g-0 dot-shadow-wrap">
            <div class="card">
                <div class="card-body">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="table-responsive h-350" id="data-list">
                        <table class="display data_tbl data__table customerTable">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.action')</th>
                                    <th class="text-start">@lang('menu.image')</th>
                                    <th class="text-start">@lang('menu.name')</th>
                                    <th class="text-start">@lang('menu.email')</th>
                                    <th class="text-start">@lang('menu.phone')</th>
                                    <th class="text-start">@lang('menu.designation')</th>
                                    <th class="text-start">@lang('menu.status')</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @else
    <div class="p-15">
        <div class="bd-callout bd-callout-info">
            <code>Warning!!</code> You do not have permission to access please contact with administrator.
        </div>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_customer_basic_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
<div class="modal fade" id="add_customer_detailed_modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true"></div>

<form id="deleted_form" action="" method="post">
    @method('DELETE')
    @csrf
</form>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {

        // Data Table

        var table = $('.customerTable').DataTable({
            processing: true
            , dom: "lBfrtip"
            , buttons: [{
                    extend: 'pdf'
                    , text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang("menu.pdf")'
                    , className: 'pdf btn text-white btn-sm px-1'
                    , exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }
                , {
                    extend: 'excel'
                    , text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang("menu.excel")'
                    , className: 'pdf btn text-white btn-sm px-1'
                    , exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }
                , {
                    extend: 'print'
                    , text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang("menu.print")'
                    , className: 'pdf btn text-white btn-sm px-1'
                    , exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }
            ]
            , serverSide: true
            , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
            , "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1]
                , [10, 25, 50, 100, 500, 1000, "All"]
            ]
            , ajax: "{{ route('website.teams.index') }}"
            , columns: [{
                    data: 'action'
                    , name: 'action'
                }
                , {
                    data: 'image'
                    , name: 'image'
                }
                , {
                    data: 'name'
                    , name: 'name'
                }
                , {
                    data: 'email'
                    , name: 'email'
                }
                , {
                    data: 'phone'
                    , name: 'phone'
                }
                , {
                    data: 'designation'
                    , name: 'designation'
                }
                , {
                    data: 'status'
                    , name: 'status'
                }
            , ]
        });

        table.buttons().container().appendTo('#exportButtonsContainer');

        // Show and hide input field
        $(document).on('change', '#customer_type', function() {
            if ($(this).val() == 2) {

                $('.hidable').slideToggle("slow").removeClass('d-none');
                $('#credit_limit').addClass('add_input');
            } else {

                $('.hidable').slideToggle("slow").addClass('d-none');
                $('#credit_limit').removeClass('add_input');
            }
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
                        , 'action': function() {

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
                url: url
                , type: 'post'
                , async: false
                , data: request
                , success: function(data) {
                    toastr.success(data);
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg, 'Attention');
                        return;
                    }
                    table.ajax.reload();
                    refresh();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                }
            });
        });

        $('#add_customer').on('click', function(e) {
            e.preventDefault();
            $.get("{{ route('website.teams.create') }}", function(data) {
                $('#add_customer_basic_modal').html(data);
                $('#add_customer_basic_modal').modal('show');

                $('#editModal').empty();
            });
        });
        // Pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#editModal').html(data);
                $('#editModal').modal('show');
                $('.data_preloader').hide();

            });
        });
    });

</script>
@endpush
