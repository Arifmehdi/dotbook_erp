<script>
    var subCatetable = $('.data_tbl2').DataTable({
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
        processing: true,
        serverSide: true,
        searchable: true,
        "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
        "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1],
            [10, 25, 50, 100, 500, 1000, "All"]
        ],
        ajax: "{{ route('product.subcategories.index') }}",
        columnDefs: [{
            "targets": [0, 1, 3, 4],
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
            data: 'parentname',
            name: 'parentname'
        }, {
            data: 'description',
            name: 'description'
        }, {
            data: 'action',
            name: 'action'
        }, ]
    });

    subCatetable.buttons().container().appendTo('#exportButtonsContainer2');

    @if (auth()->user()->can('categories'))
        $("#exportButtonsContainer2 .dt-buttons").prepend(
            '<a href="#" class="btn text-white btn-sm" id="addSubCategory"><span><i class="fa-thin fa-circle-plus fa-2x"></i><br>@lang('menu.add_sub_category')</span></a>'
            );
    @endif

    $(document).ready(function() {
        // Add Subcategory by ajax
        $(document).on('click', '#addSubCategory', function(e) {
            e.preventDefault();

            var url = "{{ route('product.subcategories.create') }}";

            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#subcategoryAddOrEditModal').html(data);
                    $('#subcategoryAddOrEditModal').modal('show');

                    setTimeout(function() {

                        $('#subcategory_name').focus();
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

        $(document).on('click', '#editSubcategory', function(e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('.data_preloader').show();
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {

                    $('#subcategoryAddOrEditModal').empty();
                    $('#subcategoryAddOrEditModal').html(data);
                    $('#subcategoryAddOrEditModal').modal('show');
                    $('.data_preloader').hide();
                    setTimeout(function() {

                        $('#subcategory_name').focus().select();
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

        $(document).on('click', '#deleteSubcategory', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#delete_subcategory_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation',
                'content': 'Are you sure, you want to delete?',
                'buttons': {
                    'Yes': {
                        'class': 'yes btn-danger',
                        'action': function() {
                            $('#delete_subcategory_form').submit();
                        }
                    },
                    'No': {
                        'class': 'no btn-primary ',
                        'action': function() {}
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#delete_subcategory_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                async: false,
                data: request,
                success: function(data) {
                    subCatetable.ajax.reload();
                    toastr.error(data);
                    $('#deleted_form')[0].reset();
                },
                error: function(err) {
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });
    });
</script>
