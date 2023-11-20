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
            @if(auth()->user()->can('web_job_applied_download') || auth()->user()->can('web_job_applied_delete'))
                <div class="main__content">
                    <div class="sec-name">
                        <div class="name-head">
                            <h6>@lang('menu.job_applied')</h6>
                        </div>
                        <div class="d-flex">
                            <div id="exportButtonsContainer"></div>
                            <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                        </div>
                        <div>
                            <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')</a>
                        </div>
                    </div>
                </div>
                <div class="p-15">
                    <div class="row g-0 dot-shadow-wrap">
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="data_preloader">
                                    <h6><i class="fas fa-spinner"></i> @lang('menu.processing')</h6>
                                </div>
                                <div class="table-responsive h-350" id="data-list">
                                    <table class="display data_tbl data__table customerTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.action')</th>
                                                <th class="text-start">@lang('menu.job_title')</th>
                                                <th class="text-start">@lang('menu.name')</th>
                                                <th class="text-start">@lang('menu.email')</th>
                                                <th class="text-start">@lang('menu.mobile')</th>
                                                <th class="text-start">@lang('menu.resume')</th>
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
            @else
            <div class="p-15">
                <div class="bd-callout bd-callout-info">
                    <code>Warning!!</code> You do not have permission to access please contact with administrator.
                </div>
            </div>
            @endif
        </div>


<form id="deleted_form" action="" method="POST">
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
            , ajax: "{{ route('website.job-applied.index') }}"
            , columns: [{
                    data: 'action'
                    , name: 'action'
                }
                , {
                    data: 'job_title'
                    , name: 'job_title'
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
                    data: 'mobile'
                    , name: 'mobile'
                }
                , {
                    data: 'resume'
                    , name: 'resume'
                }
                , {
                    data: 'status'
                    , name: 'status'
                }
            ]
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

    });

</script>
@endpush
