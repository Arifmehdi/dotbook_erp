<script>
    var table = $('.licensesCategoryTable').DataTable({
        dom: "lBfrtip"
        , processing: true
        , serverSide: true
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('assets.licenses.category.index') }}"
        , columns: [{
                data: 'DT_RowIndex'
                , name: 'DT_RowIndex'
            }
            , {
                data: 'name'
                , name: 'name'
            }
            , {
                data: 'action'
                , name: 'action'
            }
        , ]
    , });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {


        // Add category by ajax
        $(document).on('submit', '#add_licenses_category_form', function(e) {
            e.preventDefault();

            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');

            $.ajax({
                url: url
                , type: 'post'
                , data: new FormData(this)
                , contentType: false
                , cache: false
                , processData: false
                , success: function(data) {

                    $('.error').html('');
                    toastr.success(data);
                    $('#add_licenses_category_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.licensesCategoryTable').DataTable().ajax.reload();
                }
                , error: function(err) {

                    $('.loading_button').hide();
                    $('.error').html('');
                    $('.submit_button').prop('type', 'submit');

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

        // pass editable data to edit modal fields
        $(document).on('click', '#edit_licenses_category', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url
                , type: 'get'
                , success: function(data) {
                    $('#edit_licenses_category_form_body').html(data);
                    $('#add_licenses_category_form_div').hide();
                    $('#edit_licenses_category_form').show();
                    $('.data_preloader').hide();
                    document.getElementById('name').focus();
                }
                , error: function(err) {
                    $('.data_preloader').hide();
                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                    } else {
                        toastr.error('Server Error, Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#update_licenses_category_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#licenses_category_deleted_form').attr('action', url);
            $.confirm({
                'title': 'Edit Confirmation'
                , 'content': 'Are you sure to edit?'
                , 'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary'
                        , 'action': function() {
                            $('#update_licenses_category_form').submit();
                        }
                    }
                    , 'No': {
                        'class': 'no btn-danger'
                        , 'action': function() {
                            
                        }
                    }
                }
            });
        });

        // edit Category by ajax
        $(document).on('submit', '#update_licenses_category_form', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $.ajax({
                url: url
                , type: 'post'
                , data: new FormData(this)
                , contentType: false
                , cache: false
                , processData: false
                , success: function(data) {
                    toastr.success(data);
                    $('.licensesCategoryTable').DataTable().ajax.reload();
                    $('.loading_button').hide();
                    $('#add_licenses_category_form_div').show();
                    $('#edit_licenses_category_form').hide();
                    $('.error').html('');
                }
                , error: function(err) {
                    $('.loading_button').hide();
                    $('.error').html('');

                    if (err.status == 0) {
                        toastr.error('Net Connetion Error. Reload This Page.');
                        return;
                    }

                    $.each(err.responseJSON.errors, function(key, error) {
                        $('.error_e_' + key + '').html(error[0]);
                    });
                }
            });
        });


        // Data delete part
        $(document).on('click', '#delete_licenses_category', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#licenses_category_deleted_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation'
                , 'content': 'Are you sure?'
                , 'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary'
                        , 'action': function() {
                            $('#licenses_category_deleted_form').submit();
                        }
                    }
                    , 'No': {
                        'class': 'no btn-danger'
                        , 'action': function() {

                        }
                    }
                }
            });
        });

        //data delete by ajax
        $(document).on('submit', '#licenses_category_deleted_form', function(e) {
            e.preventDefault();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url
                , type: 'post'
                , data: request
                , success: function(data) {

                    if ($.isEmptyObject(data.errorMsg)) {

                        toastr.error(data);
                        $('.licensesCategoryTable').DataTable().ajax.reload();
                        $('#licenses_category_deleted_form')[0].reset();
                    } else {

                        toastr.error(data.errorMsg);
                    }
                }
                , error: function(err) {

                    if (err.status == 0) {

                        toastr.error('Net Connetion Error. Please check the connection.');
                    } else if (err.status == 500) {

                        toastr.error('Server Error. Please contact to the support team.');
                    }
                }
            });
        });

        $(document).on('click', '#licenses_category_close_form', function() {
            $('#add_licenses_category_form_div').show();
            $('#edit_licenses_category_form').hide();
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
