@extends('layout.master')
@push('css')
    
    <style>
        .parent a {
            font-size: 14px !important;
        }

        /* span.select2-results ul li {font-size: 15px;font-weight: 450;line-height: 15px;} */
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
@section('title', 'Cost Centres - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.cost_centres')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="before">
                        <x-add-button :href="route('cost.centres.categories.create')" id="addBtn" :can="'cost_centre_categories_add'" data-btn_type="add_category" :text="'Add Category'" />
                        <x-add-button :href="route('cost.centres.create')" id="addBtn" :can="'cost_centres_add'" data-btn_type="add_cost_centre" :text="'Add Cost Centre'" />
                    </x-slot>
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="row margin_row g-0 p-15">
            <div class="col-12">
                <div class="card">
                    <h6 class="p-2 fw-bold">@lang('menu.list_of_cost_centres')</h6>
                    <hr class="p-0 m-0 mb-2">

                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>

                    <div class="card-body" id="list_of_cost_centres">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Add Payment modal-->
    <div class="modal fade" id="addOrEditCostCentreModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>
    <div class="modal fade" id="addOrEditCategoryModal" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop"></div>

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

        $(document).on('click', '#addBtn', function(e) {
            e.preventDefault();
            var btn_type = $(this).data('btn_type');
            var category_id = $(this).data('category_id');
            var url = $(this).attr('href');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    var focusableInput = '';
                    if (btn_type == 'add_category') {

                        $('#addOrEditCategoryModal').html(data);
                        $('#addOrEditCategoryModal').modal('show');
                        focusableInput = $('#addOrEditCategoryModal #name');
                        if (category_id) {

                            $('#parent_category_id').val(category_id).trigger('change');
                        }
                    } else if (btn_type == 'add_cost_centre') {

                        $('#addOrEditCostCentreModal').html(data);
                        $('#addOrEditCostCentreModal').modal('show');
                        focusableInput = $('#addOrEditCostCentreModal #name');
                    }


                    setTimeout(function() {

                        focusableInput.focus();
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

        // pass editable data to edit modal fields
        $(document).on('click', '#edit', function(e) {
            e.preventDefault();

            $('.data_preloader').show();
            var url = $(this).attr('href');
            var btn_type = $(this).data('btn_type');
            lastChartListClass = $(this).data('class_name');

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    var focusableInput = '';
                    if (btn_type == 'edit_category') {

                        $('#addOrEditCategoryModal').html(data);
                        $('#addOrEditCategoryModal').modal('show');
                        focusableInput = $('#addOrEditCategoryModal #e_name');
                    } else if (btn_type == 'edit_cost_centre') {

                        $('#addOrEditCostCentreModal').html(data);
                        $('#addOrEditCostCentreModal').modal('show');
                        focusableInput = $('#addOrEditCostCentreModal #e_name');
                    }

                    $('.data_preloader').hide();


                    setTimeout(function() {

                        focusableInput.focus().select();
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
                url: "{{ route('cost.centres.list.of.cost.centres') }}",
                async: true,
                type: 'get',
                success: function(data) {

                    var div = $('#list_of_cost_centres').html(data);

                    if (lastChartListClass) {

                        var scrollTo = $('.' + lastChartListClass);
                        scrollTo.addClass('jstree-clicked');
                        // window.scrollTo(0, scrollTo.offset().top);

                        try {

                            $('html, body').animate({

                                // scrollTop: $(scrollTo).offset().top
                                test: function() {

                                },
                                scrollTop: scrollTo.offset().top - 500
                            }, 0);
                        } catch (error) {

                        }
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

        // data delete by ajax
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

                        getAjaxList();
                        toastr.error(data);
                        $('#deleted_form')[0].reset();
                    }
                }
            });
        });

        $("#addOrEditCostCentreModal").on("hidden.bs.modal", function() {

            $('#addOrEditCostCentreModal').empty();
        });

        $("#addOrEditCategoryModal").on("hidden.bs.modal", function() {

            $('#addOrEditCategoryModal').empty();
        });
    </script>
@endpush
