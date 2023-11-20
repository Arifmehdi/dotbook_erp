<style>
    .uploaded-image-preview {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        padding-top: 20px;
    }
    .single-img-box {
        width: calc(100% / 6 - 8.4px);
        height: 70px;
        border: 1px solid #323232;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
    }
    .single-img-box img {
        max-height: 100%;
    }
    .single-img-box .img-close {
        position: absolute;
        top: 5px;
        right: 5px;
        text-align: center;
        width: 20px;
        height: 20px;
        line-height: 22px;
        background: rgba(255,255,255,0.5);
        border: 0;
        border-radius: 50%;
        opacity: 0;
        transition: .3s;
    }
    .single-img-box:hover .img-close {
        opacity: 1;
    }
</style>
<form id="update_form" action="{{ route('crm.individual-leads.update', $individualLeads->id) }}" method="POST" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="form-group row mt-1">
        <div class="col-md-4">
            <label><strong>Name </strong> <span class="text-danger">*</span></label>
            <input required type="text" name="name" class="form-control" value="{{ $individualLeads->name }}" placeholder="Name" />
            <span class="error error_name"></span>
        </div>
        <div class="col-md-4">
            <label><strong>Address </strong> <span class="text-danger">*</span></label>
            <input required type="text" name="address" class="form-control" value="{{ $individualLeads->address }}" placeholder="Address" />
            <span class="error error_address"></span>
        </div>
        <div class="col-md-4">
            <label><strong>E-mail </strong> </label>
            <input type="email" name="email_addresses" id="email_addresses" class="form-control" value="{{ $individualLeads->email_addresses }}" placeholder="E-mail" />
        </div>
    </div>

    <div class="form-group row mt-1">

        <div class="col-md-4">
            <label><strong>Phone Numbers </strong> </label>
            <input type="text" name="phone_numbers" class="form-control" id="phone_numbers" value="{{ $individualLeads->phone_numbers }}" placeholder="Phone Numbers" />
        </div>

        <div class="col-md-4">
            <label><strong>Companies </strong> </label>
            <input type="text" name="companies" class="form-control" id="companies" value="{{ $individualLeads->companies }}" placeholder="Companies" />
        </div>

        <div class="col-md-4">
            <label><strong>Files </strong> </label>
            <input type="file" name="files[]" class="form-control" id="files" placeholder="Files" multiple/>
            <span class="error error_files"></span>
        </div>

    </div>

    <div class="form-group row mt-1">
        <div class="col-md-6">
            <label><strong>Description </strong> </label>
            <input type="text" name="description" class="form-control" id="description" value="{{ $individualLeads->description }}" placeholder="Description" />
        </div>

        <div class="col-md-6">
            <label><strong>Addditional Informations </strong> </label>
            <input type="text" name="additional_information" class="form-control" id="additional_information" value="{{ $individualLeads->additional_information }}" placeholder="Additional Information" />
        </div>
    </div>

    @isset($files_array)
        <div class="uploaded-image-preview">
            @foreach ($files_array as $file)
                <div class="single-img-box"><a href="#" class="img-close deleteAdditionalFile" data-url="{{ route('crm.individual-leads.additional-file.delete', [$individualLeads->id, $file]) }}"><i class="fas fa-times"></i></a><img src="{{ asset('uploads/leads/individual_leads/'. $file) }}" id="" alt=""></div>
            @endforeach
        </div>
    @endisset

    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">@lang('menu.save')</button>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>
<script>
    $('#update_form').on('submit', function(e) {
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
                $('.loading_button').hide();
                toastr.success(data);
                $('#editModal').modal('hide');
                $('.individual_leads_table').DataTable().ajax.reload();
            },
            error: function(err) {
                $('.loading_button').hide();
                $('.error').html('');
                if (err.status == 0) {
                    toastr.error('Net Connetion Error. Reload This Page.');
                    return;
                } else if (err.status == 500) {
                    toastr.error('Server error. Please contact to the support team.');
                    return;
                }
                $.each(err.responseJSON.errors, function(key, error) {
                    $('.error_e_' + key + '').html(error[0]);
                });
            }
        });
    });
    $(document).ready(function(){
        $('.deleteAdditionalFile').click(function() {
            let url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'get',
                success: function(data) {
                    toastr.error(data.message);
                },
                error: function(data) {
                    toastr.error(data.message);
                    return;
                }
            });

            $(this).parent().hide();
        });
    });
</script>
