@extends('layout.master')
@push('css')
    
    <style>
        .parent a {
            font-size: 14px !important;
        }

        /* span.select2-results ul li {font-size: 15px;font-weight: 450;line-height: 15px;} */
        /* .select2-container--default .select2-selection--single .select2-selection__rendered {font-weight: 700;} */
        .fw-icon {
            font-size: 11px;
            font-weight: 400;
        }

        .add_btn_frm_group:hover {
            color: rgb(21, 255, 21) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .delete_group_btn:hover {
            color: rgb(244, 16, 16) !important;
            font-weight: 700 !important;
            font-size: 13px !important
        }

        .account_icon {
            font-size: 16px !important;
        }

        .parent #parentText {
            text-transform: uppercase !important;
            font-weight: 700 !important;
            font-size: 17px !important;
        }

        .jstree-default .jstree-anchor {
            font-size: 11px !important;
        }

        .input-group-text {
            font-size: 12px !important;
            margin-top: 1px;
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush

@section('title', 'Chart Of Accounts - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.chart_of_accounts')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('accounting.accounts.create.modal')" id="addAccountBtn" :can="'accounts_add'" :text="__('Add Account')" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="row margin_row g-0 p-15">
            <div class="col-12">
                <div class="card">
                    <h6 class="p-2 fw-bold">@lang('menu.list_of_accounts')</h6>
                    <hr class="p-0 m-0 mb-2">

                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>

                    <div class="card-body" id="list_of_accounts">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar :items="[
        ['key' => 'Alt + A', 'value' => __('Add Account')]
    ]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <!--Add/Edit Account modal-->
    <div class="modal fade" id="accountAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account modal End-->

    <!--Add/Edit Account Group modal-->
    <div class="modal fade" id="accountGroupAddOrEditModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <!--Add/Edit Account Group modal End-->

    <form id="deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
        var lastChartListClass = '';
        $('.select2').select2();

        $(document).on('click', '#addAccountBtn', function(e) {
            e.preventDefault();
            var group_id = $(this).data('group_id');
            $('#parent_group_id').val(group_id).trigger('change');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                async: false,
                success: function(data) {

                    $('#accountAddOrEditModal').empty();
                    $('#accountAddOrEditModal').html(data);
                    $('#accountAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#account_name').focus();
                    }, 500);

                    $('.data_preloader').hide();

                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#addAccountGroupBtn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var group_id = $(this).data('group_id');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountGroupAddOrEditModal').html(data);
                    $('#accountGroupAddOrEditModal').modal('show');

                    $('#parent_group_id').val(group_id).trigger('change');

                    var is_allowed_bank_details = $('#parent_group_id').find('option:selected').data(
                        'is_allowed_bank_details');
                    $('#is_allowed_bank_details').val(is_allowed_bank_details);
                    var is_default_tax_calculator = $('#parent_group_id').find('option:selected').data(
                        'is_default_tax_calculator');
                    $('#is_default_tax_calculator').val(is_default_tax_calculator);

                    setTimeout(function() {

                        $('#account_group_name').focus();
                    }, 500);
                }
            })
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#editAccount', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            lastChartListClass = $(this).data('class_name');

            groupHead = $(this).data('group_head');


            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountAddOrEditModal').empty();
                    $('#accountAddOrEditModal').html(data);
                    $('#accountAddOrEditModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#account_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        // pass editable data to edit modal fields
        $(document).on('click', '#editAccountGroupBtn', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            lastChartListClass = $(this).data('class_name');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#accountGroupAddOrEditModal').empty();
                    $('#accountGroupAddOrEditModal').html(data);
                    $('#accountGroupAddOrEditModal').modal('show');
                    $('.data_preloader').hide();

                    setTimeout(function() {

                        $('#account_group_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        function getAjaxList() {

            $('.data_preloader').show();
            $.ajax({
                url: "{{ route('accounting.charts.list') }}",
                async: true,
                type: 'get',
                success: function(data) {

                    var div = $('#list_of_accounts').html(data);

                    if (lastChartListClass) {

                        var scrollTo = $('.' + lastChartListClass);
                        scrollTo.addClass('jstree-clicked');
                        // window.scrollTo(0, scrollTo.offset().top);

                        $('html, body').animate({

                            // scrollTop: $(scrollTo).offset().top
                            test: function() {

                            },
                            scrollTop: scrollTo.offset().top - 500
                        }, 100);

                        $('.data_preloader').hide();
                        return;
                    }

                    $('.data_preloader').hide();
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        }

        getAjaxList();

        $(document).on('click', '#viewLedger', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            window.open(url, '_blank');
        });

        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'message': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#deleted_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-primary',
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
                    if (!$.isEmptyObject(data.errorMsg)) {

                        toastr.error(data.errorMsg);
                    } else {

                        lastChartListClass = '';
                        getAjaxList();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                }
            });
        });

        $('#accountAddOrEditModal').on('hidden.bs.modal', function(e) {
            $('#accountAddOrEditModal').empty();
        });

        $('#accountGroupAddOrEditModal').on('hidden.bs.modal', function(e) {
            $('#accountGroupAddOrEditModal').empty();
        });

        document.onkeyup = function() {
            var e = e || window.event; // for IE to cover IEs window event-object

            console.log(e.which);
            if (e.altKey && e.which == 65) {

                $('#addAccountBtn').click();
                return false;
            }
        }
    </script>
@endpush
