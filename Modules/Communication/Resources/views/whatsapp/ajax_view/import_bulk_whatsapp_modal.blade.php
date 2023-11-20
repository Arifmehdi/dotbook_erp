<style>
    #submit_bulk_whatsapp_number_form .form-group label {
        text-align: right;
    }
</style>

<div class="modal-dialog col-30-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Import whatsapp Number Address <span class="type_name"></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <form id="submit_bulk_whatsapp_number_form" action="{{ route('communication.whatsapp.manual-service.phone-number-import-store') }}" enctype="multipart/form-data" method="POST">
                @csrf

                <div class="row mt-1">
                    <div class="col-md-12">
                        <div class="input-group">

                            <label for="inputEmail3" class="col-3"><b>@lang('menu.file_to_import') </b> </label>

                            <div class="col-6" style="margin: 5px">
                                <input type="file" name="import_file" id="import_file" class="form-control" required>
                                <span class="error error_import_file"  style="color: red;"></span>
                            </div>

                            <div class="col-2">
                                <button type="submit" class="btn btn-sm btn-primary float-start mt-1">@lang('menu.upload')</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-md-12">
                        <div class="input-group">
                            <label for="inputEmail3" class="col-3"><b>@lang('menu.download') Format :</b> </label>
                            <div class="col-7">
                                <a href="{{ asset('import_template/bulk_phone_import.xlsx') }}" class="btn btn-sm btn-success" download>@lang('menu.download_template_file_click_here')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $('#submit_bulk_whatsapp_number_form').on('submit',function(e) {
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
            success: function(responseData) {
                bulkImportedPhoneNumber = responseData.nullCheckPhone;
                whatsapp_number_table.destroy();
                whatsapp_number_table = $('#whatsappNumberBodyTable').DataTable({
                    serverSide: false,
                    pageLength: parseInt("{{ json_decode($generalSettings->system, true)['datatable_page_entry'] }}"),
                    lengthMenu: [
                        [10, 25, 50, 100, 500, 1000, -1],
                        [10, 25, 50, 100, 500, 1000, "All"]
                    ],
                    data: responseData.data,
                    columns: [
                        {data: 'check', name: 'check'},
                        {data: 'phone', name: 'phone'}
                    ]
                });

                $('.error').html('');
                toastr.success('successfully imported bulk Whatsapp number.');
                $('.loading_button').hide();
                $('#add_bulk_phone_modal').modal('hide');
                $('.submit_button').prop('type', 'submit');
            }, error : function(err) {
                $('.loading_button').hide();
                $('.submit_button').prop('type', 'submit');
                $('.error').html('');

                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server Error. Please contact to the support team.');
                    return;
                }
                $.each(err.responseJSON.errors, function(key, error) {

                    $('.error_' + key + '').html(error[0]);
                });
            }
        });
    });


</script>
