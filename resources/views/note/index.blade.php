@extends('layout.master')
@section('title', 'Note - ')

@push('css')
@endpush

@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>{{ __('Note') }}</h6>
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
            <div class="card note-group-view d-none">
                <div class="card-header alert-primary px-3 d-flex justify-content-between align-items-center">
                    <h6>Test group 1 <span class="small">17, Dec 2022</span></h6>
                    <div class="btn-box-2 pt-0">
                        <a href="#" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#addNoteModal"><i class="fa-solid fa-plus"></i> Add New</a>
                        <button class="btn btn-sm btn-secondary px-2 close-note-group"><i class="fa-solid fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card personal-notice mb-0">
                                <div class="card-header px-3">
                                    <h6>Personal Note</h6>
                                </div>
                                <div class="card-body">
                                    {{-- <div class="table-responsive h-350" id="data-list">

                                    <table class="display data_tbl data__table" id="noticeTable">
                                        <thead>
                                            <tr>
                                                <th class="text-start">@lang('menu.date')</th>
                                                <th class="text-start">@lang('menu.title')</th>
                                                <th class="text-start">@lang('menu.action')</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div> --}}

                                    <div class="note-card-row">
                                        <div class="notice-card">
                                            <div class="notice-title alert-primary">
                                                <span><span class="color-box"></span>Test Note <span class="small">17, Dec 2022</span></span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-secondary">
                                                <span><span class="color-box"></span>Test Note <span class="small">17, Dec
                                                        2022</span></span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-success">
                                                <span><span class="color-box"></span>Test Note <span class="small">17, Dec
                                                        2022</span></span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-danger">
                                                <span><span class="color-box"></span>Test Note <span class="small">17, Dec
                                                        2022</span></span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-warning">
                                                <span><span class="color-box"></span>Test Note <span class="small">17,
                                                        Dec 2022</span></span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card shared-notice mb-0">
                                <div class="card-header px-3">
                                    <h6>Shared Note</h6>
                                </div>
                                <div class="card-body">
                                    <div class="note-card-row">
                                        <div class="notice-card">
                                            <div class="notice-title alert-primary">
                                                <span><span class="color-box"></span>Test Note</span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-secondary">
                                                <span><span class="color-box"></span>Test Note</span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-success">
                                                <span><span class="color-box"></span>Test Note</span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-danger">
                                                <span><span class="color-box"></span>Test Note</span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                        <div class="notice-card">
                                            <div class="notice-title alert-warning">
                                                <span><span class="color-box"></span>Test Note</span>
                                                <div class="dropdown">
                                                    <button class="btn-flush" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-pen-to-square"></i> Edit</a></li>
                                                        <li><a href="#" class="dropdown-item"><i class="fa-light fa-trash-can"></i> Delete</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="notice-body">
                                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Autem
                                                    consectetur natus porro unde ipsam beatae provident excepturi sit cumque
                                                    tempore cupiditate quisquam incidunt reiciendis, minima sequi quos
                                                    deleniti inventore placeat.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row note-group-list">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header px-3">
                            <h6>Note Groups</h6>
                        </div>
                        <div class="card-body note-groups">
                            <div class="row g-3">
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-primary" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-secondary" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-success" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-danger" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-warning" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-info" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-light" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                                <div class="col-xxl-3 col-md-4">
                                    <div class="note-group-single alert-dark" role="button">
                                        <span class="note-date">17, Dec 2022</span>
                                        <span class="group-title">Test group 1</span>
                                        <span class="note-qty">8 Notes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog three-col-modal" role="document">
            <div class="modal-content" style="margin-left: 2%; margin-top: 6%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">{{ __('Add Note Group') }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">

                    <form id="add_note_form" action="{{ route('note.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
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

                            <div class="mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none float-end"><i class="fas fa-spinner"></i></button>
                                        <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="true" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog four-col-modal" role="document" id="edit-content">

        </div>
    </div>


    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog three-col-modal">
            <div class="modal-content" style="margin-left: 2%; margin-top: 6%;">
                <div class="modal-header">
                    <h6 class="modal-title" id="addNoteModalLabel">{{ __('Add Note') }}</h6>
                    <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
                </div>
                <div class="modal-body">
                    <form id="add_single_note_form" action="{{ route('note.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row mt-1">
                            <div class="col-md-12">
                                <label><strong>@lang('menu.title') </strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" required>
                            </div>

                            <div class="col-md-12">
                                <label><strong>@lang('menu.description')</strong></label>
                                <textarea name="description" rows="7" class="form-control ckEditor" contenteditable="true" id="description" placeholder="Description"></textarea>
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

                            <div class="col-12 pt-3">
                                <div class="note-modal-checkbox">
                                    <div class="checkbox">
                                        <input type="radio" name="note_type" id="add_personal_note" checked>
                                        <label for="add_personal_note">Personal</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="radio" name="note_type" id="add_shared_note">
                                        <label for="add_shared_note">Shared</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 assign-to d-none">
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

                            <div class="mt-3">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none float-end"><i class="fas fa-spinner"></i></button>
                                        <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js" integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                    url: "{{ route('note.index') }}",
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
            $(document).on('submit', '#add_note_form', function(e) {
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
                        $('#add_note_form')[0].reset();
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


            $('[name=note_type]').on('change', function() {
                if ($('#add_shared_note').is(':checked')) {
                    $('.assign-to').removeClass('d-none');
                    $(this).parents('#add_single_note_form').find('.submit_button').text('Save & Send');
                } else {
                    $('.assign-to').addClass('d-none');
                    $(this).parents('#add_single_note_form').find('.submit_button').text('Save');
                }
            });
            $(document).on('reset', '#add_single_note_form', function() {
                $('.assign-to').addClass('d-none');
            })


            $('.note-group-single').on('click', function() {
                $('.note-group-list').hide();
                $('.note-group-view').removeClass('d-none');
            });
            $('.close-note-group').on('click', function() {
                $('.note-group-list').show();
                $('.note-group-view').addClass('d-none');
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
