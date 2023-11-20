<script>
    var category_table = $('.data_tbl').DataTable({
        dom: "lBfrtip",
        buttons: [{
            extend: 'pdf',
            text: '<i class="fa-thin fa-file-pdf fa-2x"></i><br>@lang('menu.pdf')',
            className: 'pdf btn text-white btn-sm px-1',
            exportOptions: {
                columns: [0, 2, 3, 4]
            }
        }, {
            extend: 'excel',
            text: '<i class="fa-thin fa-file-excel fa-2x"></i><br>@lang('menu.excel')',
            className: 'pdf btn text-white btn-sm px-1',
            exportOptions: {
                columns: [0, 2, 3, 4]
            }
        }, {
            extend: 'print',
            text: '<i class="fa-thin fa-print fa-2x"></i><br>@lang('menu.print')',
            className: 'pdf btn text-white btn-sm px-1',
            exportOptions: {
                columns: [0, 2, 3, 4]
            }
        }, ],
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        processing: true,
        serverSide: true,
        searchable: true,
        ajax: "{{ route('product.categories.index') }}",
        columnDefs: [{
            "targets": [0, 1, 3],
            "orderable": false,
            "searchable": false
        }],
        columns: [{
            data: 'DT_RowIndex',
            name: 'DT_RowIndex'
        }, {
            data: 'photo',
            name: 'photo'
        }, {
            data: 'name',
            name: 'name'
        }, {
            data: 'code',
            name: 'code'
        }, {
            data: 'description',
            name: 'description'
        }, {
            data: 'action',
            name: 'action'
        }, ],
    });

    category_table.buttons().container().appendTo('#exportButtonsContainer');

    @if (auth()->user()->can('categories'))
        $("#exportButtonsContainer .dt-buttons").prepend(
            '<a href="#" class="btn text-white btn-sm" id="addCategory"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.new_category')</span></a>'
            );
    @endif


    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add category by ajax
        $(document).on('click', '#addCategory', function(e) {
            e.preventDefault();

            var url = "{{ route('product.categories.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#categoryAddOrEditModal').html(data);
                    $('#categoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#category_name').focus();
                    }, 500);
                },
                error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#editCategory', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#categoryAddOrEditModal').empty();
                    $('#categoryAddOrEditModal').html(data);
                    $('#categoryAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#category_name').focus().select();
                    }, 500);
                },
                error: function(err) {

                    $('.data_preloader').hide();
                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    } else if (err.status == 500) {

                        toastr.error('Server error. Please contact to the support team.');
                        return;
                    }
                }
            });
        });

        $(document).on('click', '#deleteCategory', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_category_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary',
                        'action': function() {
                            $('#delete_category_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-danger',
                        'action': function() {}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_category_form', function(e) {
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
                        category_table.ajax.reload(null, false);
                        $('#delete_category_form')[0].reset();
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

        $(document).on('click', '#tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');
            $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });
    });
</script>
