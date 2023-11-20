@extends('layout.master')
@section('title', 'Area - ')

@push('css')
@endpush

@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.area')</h6>
                </div>
                <div class="d-flex">
                    <div id="exportButtonsContainer">
                        <a href="" data-bs-toggle="modal" data-bs-target="#addModal" class="btn text-white btn-sm"><i
                                class="fa-thin fa-circle-plus fa-2x"></i><br> @lang('menu.add_new')</a>
                    </div>
                    <a href="#" class="btn text-white btn-sm d-lg-block d-none"><span
                            class="fas fa-thin fa-circle-question fa-2x"></span><br>@lang('menu.help')</a>
                </div>
                <div>
                    <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i
                            class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')
                    </a>
                </div>
            </div>
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="sec-name">
                        <div class="col-md-12">
                            <form id="filter_form" class="px-2">
                                <div class="form-group row">
                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.district') :</strong></label>
                                        <select name="customer_id" class="form-control submit_able"
                                            id="customer_id" autofocus>
                                            <option value="">@lang('menu.all')</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label><strong>@lang('menu.thana') :</strong></label>
                                        <select name="payment_status" id="payment_status"
                                            class="form-control">
                                            <option value="">@lang('menu.all')</option>
                                        </select>
                                    </div>



                                    <div class="col-md-2">
                                        <label><strong></strong></label>
                                        <div class="input-group">
                                            <button type="submit" class="btn py-1 px-2 text-white btn-sm btn-secondary float-start"><i class="fa-solid fa-filter-list"></i> @lang('menu.filter')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> --}}

        </div>

        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="data-list">
                        <table class="display data_tbl data__table" id="areaTable">
                            <thead>
                                <tr>
                                    <th class="text-start">@lang('menu.division')</th>
                                    <th class="text-start">@lang('menu.district')</th>
                                    <th class="text-start">@lang('menu.thana')</th>
                                    <th class="text-start">@lang('menu.union')</th>
                                    <th class="text-start">@lang('menu.area')</th>
                                    <th class="text-start">@lang('menu.date')</th>
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

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document">
            <div class="modal-content" style="margin-left: 2%; margin-top: 6%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_new')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <!--begin::Form-->
                    <form id="add_area_form" action="{{ route('core.area.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row mt-1">
                            <h6 class="text-center text-muted">@lang('menu.division_wise')</h6>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.division') </strong></label>
                                <select class="form-control add-input form-select" name="division" id="division" required>
                                    <option value="">>>--@lang('menu.select_division')--<<< /option>
                                            @foreach ($divisions as $division)
                                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.district') </strong></label>
                                <select class="form-control add-input district form-select" name="district" id="district"
                                    required>
                                    <option value="">>>--@lang('menu.select_district')--<<< /option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.sub_district') </strong></label>
                                <select class="form-control add-input thanas form-select" name="thanas" id="thanas"
                                    required>
                                    <option value="">>>--@lang('menu.select_thana')--<<< /option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.union') </strong></label>
                                <select class="form-control add-input unions form-select" name="unions" id="unions">
                                    <option value=""> >>--@lang('menu.select_union')--<<< /option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.postal_code') </strong></label>
                                <input type="text" name="postalcode" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label><strong>@lang('menu.area') </strong></label>
                                <input type="text" class="form-control" name="area" required>
                            </div>
                            <div class="mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none"><i
                                                class="fas fa-spinner"></i></button>
                                        <button type="submit"
                                            class="btn btn-sm btn-success submit_button float-start  float-end">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal"
                                            class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>

    <!-- show Modal -->
    <div id="requisition_details">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>


    <form id="deleted_form" action="" method="post">
        @csrf
        @method('DELETE')
    </form>


@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        // Setup ajax for csrf token.
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            // create division district thana and union start
            $("#division").change(function() {
                let division_value = this.value;
                $.get('/district?district=' + division_value, function(data) {
                    $('#district').html(data);
                })
            })
            $("#district").change(function() {
                let thana_value = this.value;
                $.get('/thanas?thanas=' + thana_value, function(data) {
                    $('#thanas').html(data);
                })
            })
            $("#thanas").change(function() {
                let union_value = this.value;
                $.get('/unions?unions=' + union_value, function(data) {
                    $('#unions').html(data);
                })
            })
            // create division district thana and union end


            var areaTable = $('#areaTable').DataTable({
                ajax: {
                    url: "{{ route('core.area.index') }}",
                },
                columns: [{
                    'data': 'division_name',
                    'name': 'division_name',
                }, {
                    'data': 'district_name',
                    'name': 'district_name'
                }, {
                    'data': 'thanas_name',
                    'name': 'thanas_name'
                }, {
                    'data': 'unions_name',
                    'name': 'unions_name',
                }, {
                    'data': 'area',
                    'name': 'area'
                }, {
                    'data': 'created_at',
                    'name': 'created_at',
                }, {
                    'data': 'action',
                    'name': 'action'
                }],
                dom: "lBfrtip",
                buttons: [{
                    extend: 'pdf',
                    text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'excel',
                    text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, {
                    extend: 'print',
                    text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
                    className: 'pdf btn text-white btn-sm px-1',
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }, ],
            });
            areaTable.buttons().container().appendTo('#exportButtonsContainer');

            // Add category by ajax
            $(document).on('submit', '#add_area_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        toastr.success(data);
                        $('#add_area_form')[0].reset();
                        $('#addModal').hide();
                        $('.loading_button').hide();
                        areaTable.ajax.reload();
                    },
                    error: function(err) {
                        $('.loading_button').hide();
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

            // Add category by ajax
            $(document).on('submit', '#add_multipale_area_form', function(e) {
                e.preventDefault();
                $('.loading_button').show();
                var url = $(this).attr('action');
                $.ajax({
                    url: url,
                    type: 'post',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {

                        toastr.success(data);
                        $('#add_multipale_area_form')[0].reset();
                        $('#addModal2').hide();
                        $('.loading_button').hide();
                        areaTable.ajax.reload();
                    },
                    error: function(err) {
                        $('.loading_button').hide();
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
            //data delete by ajax
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
                        if ($.isEmptyObject(data.errorMsg)) {
                            toastr.error(data);
                            areaTable.ajax.reload();
                        } else {
                            toastr.error(data.errorMsg);
                        }
                    },
                    error: function(err) {
                        if (err.status == 0) {
                            toastr.error('Net Connetion Error. Please check the connection.');
                        } else if (err.status == 500) {
                            toastr.error('Server Error. Please contact to the support team.');
                        }
                    }
                });
            });
        });

        // pass editable data to edit modal fields

        $(document).on('click', '#edit', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    $('#edit-content').empty();
                    $('#edit-content').html(data);
                    $('#editModal').modal('show');
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

        // Show details modal with data
        $(document).on('click', '.details_button', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $.get(url, function(data) {
                $('#requisition_details').html(data);
                $('#detailsModal').modal('show');
            })
        });
    </script>
@endpush

@push('js')
@endpush
