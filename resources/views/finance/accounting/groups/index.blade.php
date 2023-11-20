@extends('layout.master')
@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('plugins/select2/select2.min.js')}}" />
<style>
    .parent a {
        font-size: 14px !important;
    }

    span.select2-results ul li {
        font-size: 15px;
        font-weight: 450;
        line-height: 15px;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-weight: 700;
    }

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

    .parent #parentText {
        text-transform: uppercase !important;
        font-weight: 700 !important;
        font-size: 17px !important;
    }

    .jstree-default .jstree-anchor {
        font-size: 11px !important;
    }

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
@endpush
@section('title', 'Account Groups - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.account_groups')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button :href="route('accounting.groups.create')" id="addAccountGroupBtn" :can="'account_groups_add'" />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="row margin_row g-0 p-15">
            <div class="col-12">
                <div class="card">
                    <h6 class="p-2 fw-bold">@lang('menu.list_of_groups')</h6>
                    <hr class="p-0 m-0 mb-2">

                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>

                    <div class="card-body" id="list_of_groups">

                    </div>
                </div>
            </div>
        </div>
    </div>

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

                    var is_allowed_bank_details = $('#parent_group_id').find('option:selected').data('is_allowed_bank_details');
                    $('#is_allowed_bank_details').val(is_allowed_bank_details);
                    var is_default_tax_calculator = $('#parent_group_id').find('option:selected').data('is_default_tax_calculator');
                    $('#is_default_tax_calculator').val(is_default_tax_calculator);

                    setTimeout(function() {

                        $('#account_group_name').focus();
                    }, 500);
                }
            })
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
                url: "{{ route('accounting.groups.list') }}",
                async: true,
                type: 'get',
                success: function(data) {

                    var div = $('#list_of_groups').html(data);

                    // if (lastChartListClass) {

                    //     var scrollTo = $('.' + lastChartListClass);
                    //     scrollTo.addClass('jstree-clicked');

                    //     $('html, body').animate({

                    //         scrollTop: scrollTo.offset().top - 500
                    //     }, 0);
                    // }

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
                        $("#parent_group_id").load(location.href + " #parent_group_id>*", "");
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                }
            });
        });
    </script>
@endpush
