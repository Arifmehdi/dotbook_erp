<script>
    var terms_condition_table = $('.TermsConditionTable').DataTable({
            "processing": true,
            dom: "lBfrtip",
            "pageLength": parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
            "lengthMenu": [
                [10, 25, 50, 100, 500, 1000, -1],
                [10, 25, 50, 100, 500, 1000, "All"]
            ],
            "ajax": {
                "url": "{{ route('terms.index') }}",
                "data": function(d) {
                    d.category_id = $('#f_category_id').val();
                }
            },
            columns: [{
                    data: 'action'
                },
                {
                    data: 'title',
                    name: 'Title'
                },
                {
                    data: 'category',
                    name: 'Category'
                },
                {
                    data: 'description',
                    name: 'Description'
                },
                {
                    data: 'creator',
                    name: 'Creator'

                },
                {
                    data: 'updater',
                    name: 'Creator'
                }
            ],
            fnDrawCallback: function() {

                $('.data_preloader').hide();
            }
        });

    // Setup ajax for csrf token.
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // call jquery method
    $(document).ready(function() {
        // Add Terms Condition by ajax
        $(document).on('submit', '#add_terms_condition', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            $('.submit_button').prop('type', 'button');

            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('.error').html('');
                    toastr.success(data);
                    $('#add_terms_condition')[0].reset();
                    $('.loading_button').hide();
                    $('.submit_button').prop('type', 'submit');
                    $('.TermsConditionTable').DataTable().ajax.reload();
                    $('#addModal').modal('hide');
                },
                error: function(err) {
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
    });

    $(document).on('click', '#delete_terms_condition', function(e) {
        e.preventDefault();

        var url = $(this).attr('href');

        $('#deleted_terms_condition_form').attr('action', url);

        $.confirm({
            'title': 'Delete Confirmation',
            'content': 'Are you sure?',
            'buttons': {
                'Yes': {
                    'class': 'yes btn-primary',
                    'action': function() {
                        $('#deleted_terms_condition_form').submit();
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

    $(document).on('submit', '#deleted_terms_condition_form', function(e) {
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
                    $('.TermsConditionTable').DataTable().ajax.reload();
                    $('#deleted_terms_condition_form')[0].reset();
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


    $(document).on('click', '#edit', function(e) {
        e.preventDefault();
        $('.data_preloader').show();
        var url = $(this).attr('href');

        $.ajax({
            url: url,
            type: 'get',
            success: function(data) {
                $('.data_preloader').hide();
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

    $(document).on('submit', '#filter_tc_form', function(e) {
        e.preventDefault();

        $('.data_preloader').show();
        terms_condition_table.ajax.reload();
    });
</script>
