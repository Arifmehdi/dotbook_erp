@extends('layout.master')
@section('title', 'Notice - ')

@push('css')
@endpush

@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.notice_board')</h6>
                </div>
                <x-all-buttons>
                    <x-add-button />
                    <x-slot name="after">
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row g-1">
                <div class="col-12">
                    <div class="card shared-notice">
                        <div class="card-body p-3">
                            <div class="notice-card-row">
                                <div class="notice-card">
                                    <div class="notice-title">
                                        <span><span class="color-box bg-warning"></span>Test Notice</span>
                                        <div class="dropdown">
                                            <button class="btn-flush" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                <li><a href="#" class="dropdown-item"><i class="fa-light fa-eye"></i>
                                                        View</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-trash-can"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="notice-body">
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem consectetur natus
                                            porro unde ipsam beatae provident excepturi sit cumque tempore cupiditate
                                            quisquam incidunt reiciendis, minima sequi quos deleniti inventore placeat.</p>
                                    </div>
                                </div>
                                <div class="notice-card">
                                    <div class="notice-title">
                                        <span><span class="color-box bg-warning"></span>Test Notice</span>
                                        <div class="dropdown">
                                            <button class="btn-flush" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                <li><a href="#" class="dropdown-item"><i class="fa-light fa-eye"></i>
                                                        View</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-trash-can"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="notice-body">
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem consectetur natus
                                            porro unde ipsam beatae provident excepturi sit cumque tempore cupiditate
                                            quisquam incidunt reiciendis, minima sequi quos deleniti inventore placeat.</p>
                                    </div>
                                </div>
                                <div class="notice-card">
                                    <div class="notice-title">
                                        <span><span class="color-box bg-warning"></span>Test Notice</span>
                                        <div class="dropdown">
                                            <button class="btn-flush" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                <li><a href="#" class="dropdown-item"><i class="fa-light fa-eye"></i>
                                                        View</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-trash-can"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="notice-body">
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem consectetur natus
                                            porro unde ipsam beatae provident excepturi sit cumque tempore cupiditate
                                            quisquam incidunt reiciendis, minima sequi quos deleniti inventore placeat.</p>
                                    </div>
                                </div>
                                <div class="notice-card">
                                    <div class="notice-title">
                                        <span><span class="color-box bg-warning"></span>Test Notice</span>
                                        <div class="dropdown">
                                            <button class="btn-flush" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                <li><a href="#" class="dropdown-item"><i class="fa-light fa-eye"></i>
                                                        View</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-trash-can"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="notice-body">
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem consectetur natus
                                            porro unde ipsam beatae provident excepturi sit cumque tempore cupiditate
                                            quisquam incidunt reiciendis, minima sequi quos deleniti inventore placeat.</p>
                                    </div>
                                </div>
                                <div class="notice-card">
                                    <div class="notice-title">
                                        <span><span class="color-box bg-warning"></span>Test Notice</span>
                                        <div class="dropdown">
                                            <button class="btn-flush" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-eye"></i> View</a></li>
                                                <li><a href="#" class="dropdown-item"><i
                                                            class="fa-light fa-trash-can"></i> Delete</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="notice-body">
                                        <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem consectetur natus
                                            porro unde ipsam beatae provident excepturi sit cumque tempore cupiditate
                                            quisquam incidunt reiciendis, minima sequi quos deleniti inventore placeat.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog three-col-modal" role="document">
            <div class="modal-content" style="margin-left: 2%; margin-top: 6%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">@lang('menu.add_notice')</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                            class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">

                    <form id="add_notice_form" action="{{ route('notice_boards.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="col-md-12">
                                <label><strong>@lang('menu.description')</strong></label>
                                <textarea name="description" rows="7" class="form-control ckEditor" contenteditable="true" id="description"
                                    placeholder="Description"></textarea>
                            </div>

                            <div class="col-12">
                                <label><strong>Color</strong></label>
                                <select class="form-control form-select">
                                    <option value="">Primary</option>
                                    <option value="">Success</option>
                                    <option value="">Danger</option>
                                    <option value="">Info</option>
                                    <option value="">Warning</option>
                                </select>
                            </div>

                            {{-- <div class="col-12 pt-3">
                            <div class="notice-modal-checkbox">
                                <div class="checkbox">
                                    <input type="radio" name="notice_type" id="add_personal_notice" checked>
                                    <label for="add_personal_notice">Personal</label>
                                </div>
                                <div class="checkbox">
                                    <input type="radio" name="notice_type" id="add_shared_notice">
                                    <label for="add_shared_notice">Shared</label>
                                </div>
                            </div>
                        </div> --}}

                            <div class="col-12">
                                <label><strong>Assign To</strong></label>
                                <select name="" id="" class="form-control form-select" multiple>
                                    <option value="">Member 1</option>
                                    <option value="">Member 2</option>
                                    <option value="">Member 3</option>
                                    <option value="">Member 4</option>
                                    <option value="">Member 5</option>
                                    <option value="">Member 6</option>
                                    <option value="">Member 7</option>
                                    <option value="">Member 8</option>
                                    <option value="">Member 9</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label><strong>@lang('menu.add_file') </strong></label>
                                <input type="file" name="files" class="form-control">
                            </div>

                            <div class="mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                                                class="fas fa-spinner"></i></button>
                                        <button type="submit"
                                            class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal"
                                            class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static"
        data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>

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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            var noticeTable = $('#noticeTable').DataTable({
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
                "pageLength": parseInt(
                    "{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                "lengthMenu": [
                    [10, 25, 50, 100, 500, 1000, -1],
                    [10, 25, 50, 100, 500, 1000, "All"]
                ],
                ajax: {
                    url: "{{ route('notice_boards.index') }}",
                },
                columns: [{
                    'name': 'created_at',
                    'data': 'created_at'
                }, {
                    'name': 'title',
                    'data': 'title'
                }, {
                    'name': 'action',
                    'data': 'action'
                }],

            });
            noticeTable.buttons().container().appendTo('#exportButtonsContainer');
            // Add category by ajax
            $(document).on('submit', '#add_notice_form', function(e) {
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
                        $('#add_notice_form')[0].reset();
                        $('#addModal').hide();
                        $('.loading_button').hide();
                        noticeTable.ajax.reload();
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
                            noticeTable.ajax.reload();
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
