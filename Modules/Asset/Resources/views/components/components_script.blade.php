<script>
    var table = $('.componentsTable').DataTable({
        // dom: "lBfrtip",
        processing: true
        , serverSide: true
        , "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}")
        , "lengthMenu": [
            [10, 25, 50, 100, 500, 1000, -1]
            , [10, 25, 50, 100, 500, 1000, "All"]
        ]
        , ajax: "{{ route('assets.components.index') }}"
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


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $(document).ready(function() {

        $(document).on('submit', '#add_components_form', function(e) {
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
                    $('#add_components_form')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');

                    $('.componentsTable').DataTable().ajax.reload();
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


        $(document).on('click', '#edit_components', function(e) {
            e.preventDefault();
            $('.data_preloader').show();
            var url = $(this).attr('href');
            $.ajax({
                url: url
                , type: 'get'
                , success: function(data) {
                    $('#edit_components_form').html(data);
                    $('#add_form_components').hide();
                    $('#edit_components_form_body').show();
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


        $(document).on('submit', '#update_components_form', function(e) {
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
                    $('.componentsTable').DataTable().ajax.reload();
                    $('.loading_button').hide();
                    $('#add_form_components').show();
                    $('#edit_components_form_body').hide();
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

        $(document).on('click', '#update_components_btn', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#components_delete_form').attr('action', url);
            $.confirm({
                'title': 'Edit Confirmation'
                , 'content': 'Are you sure to edit?'
                , 'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary'
                        , 'action': function() {
                            $('#update_components_form').submit();
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

        $(document).on('click', '#delete_components', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            $('#components_delete_form').attr('action', url);
            $.confirm({
                'title': 'Delete Confirmation'
                , 'content': 'Are you sure?'
                , 'buttons': {
                    'Yes': {
                        'class': 'yes btn-primary'
                        , 'action': function() {
                            $('#components_delete_form').submit();
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



        $(document).on('submit', '#components_delete_form', function(e) {
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
                        $('.componentsTable').DataTable().ajax.reload();
                        $('#components_delete_form')[0].reset();
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

        $(document).on('click', '#components_close_form', function() {
            $('#add_form_components').show();
            $('#edit_components_form_body').hide();
        });
    });

</script>
