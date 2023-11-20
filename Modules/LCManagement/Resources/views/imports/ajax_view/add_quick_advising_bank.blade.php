<div class="modal-dialog double-col-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">Add Advising Bank</h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
            <!--begin::Form-->
            <form id="add_quick_advising_form" action="{{ route('lc.advising.bank.store') }}" method="post">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <b>@lang('menu.name') :</b>  <span class="text-danger">*</span>
                        <input type="text" name="name" class="form-control ab_add_input" data-name="Advising name" id="name" placeholder="Advising Bank name"/>
                        <span class="error error_ab_name" style="color: red;"></span>
                    </div>
                </div>

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
        </div>
    </div>
</div>

<script>
    // Add Advising Bank by ajax
    $('#add_quick_advising_form').on('submit', function(e){
        e.preventDefault();

        $('.loading_button').show();
        var url = $(this).attr('action');
        var request = $(this).serialize();
        var inputs = $('.ab_add_input');

        $('.error').html('');
        var countErrorField = 0;

        $.each(inputs, function(key, val) {

            var inputId = $(val).attr('id');
            var idValue = $('#'+inputId).val();

            if(idValue == ''){

                countErrorField += 1;
                var fieldName = $('#'+inputId).data('name');
                $('.error_ab_'+inputId).html(fieldName+' is required.');
            }
        });

        if(countErrorField > 0){

            $('.loading_button').hide();
            return;
        }

        $('.submit_button').prop('type', 'button');

        $.ajax({
            url:url,
            type:'post',
            data: request,
            success:function(data){

                $('#quickAddModal').modal('hide');
                $('.submit_button').prop('type', 'submit');
                toastr.success('Advising Bank is added Successfully.');
                $('.loading_button').hide();
                $('#advising_bank_id').append('<option value="'+data.id+'">'+ data.name +'</option>');
                $('#advising_bank_id').val(data.id);
            },error: function(err) {

                $('.submit_button').prop('type', 'sumbit');
                $('.loading_button').hide();
                $('.error').html('');

                if (err.status == 0) {

                    toastr.error('Net Connetion Error. Reload This Page.');
                }else if (err.status == 500) {

                    toastr.error('Server error please contact to the support.');
                }
            }
        });
    });

</script>
