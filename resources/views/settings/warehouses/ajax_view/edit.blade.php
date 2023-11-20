 <!--begin::Form-->
 <form id="edit_warehouse_form" action="{{ route('settings.warehouses.update', $w->id) }}" method="POST"
     enctype="multipart/form-data">
     @csrf
     <div class="form-group">
         <label><b>@lang('menu.warehouse_name') </b><span class="text-danger">*</span></label>
         <input type="text" name="name" class="form-control edit_input" data-name="Warehouse name" id="e_name"
             placeholder="@lang('menu.warehouse_name')" value="{{ $w->warehouse_name }}" required />
         <span class="error error_e_name"></span>
     </div>

     <div class="form-group mt-1">
         <label><b>@lang('menu.warehouse_code') </b> <span class="text-danger">*</span> <i data-bs-toggle="tooltip"
                 data-bs-placement="top" title="Warehouse code must be unique."
                 class="fas fa-info-circle tp"></i></label>
         <input type="text" name="code" class="form-control edit_input" data-name="Warehouse code" id="e_code"
             placeholder="@lang('menu.warehouse_code')" value="{{ $w->warehouse_code }}" required />
         <span class="error error_e_code"></span>
     </div>

     <div class="form-group mt-1">
         <label><b>@lang('menu.phone') </b> <span class="text-danger">*</span></label>
         <input type="text" name="phone" class="form-control edit_input" data-name="Phone number" id="e_phone"
             placeholder="Phone number" value="{{ $w->phone }}" required />
         <span class="error error_e_phone"></span>
     </div>

     <div class="form-group mt-1">
         <label><b>@lang('menu.address') </b> </label>
         <textarea name="address" class="form-control ckEditor" placeholder="Warehouse address" id="e_address" rows="3">{{ $w->address }}</textarea>
     </div>

     <div class="form-group row mt-2">
         <div class="col-md-12 d-flex justify-content-end">
             <div class="loading-btn-box">
                 <button type="button" class="btn btn-sm loading_button display-none"><i
                         class="fas fa-spinner"></i></button>
                 <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save_change')</button>
                 <button type="button" class="btn btn-sm btn-danger float-end me-2"
                     id="close_form">@lang('menu.close')</button>
             </div>
         </div>
     </div>
 </form>

 <script>
     $('.edit-select2').select2({
         placeholder: "Select under business location",
         allowClear: true
     });

     //Edit warehouse by ajax
     $('#edit_warehouse_form').on('submit', function(e) {
         e.preventDefault();

         $('.loading_button').show();
         var url = $(this).attr('action');
         var request = $(this).serialize();
         var inputs = $('.edit_input');
         $('.error').html('');
         var countErrorField = 0;

         $.each(inputs, function(key, val) {

             var inputId = $(val).attr('id');
             var idValue = $('#' + inputId).val()

             if (idValue == '') {

                 countErrorField += 1;
                 var fieldName = $('#' + inputId).data('name');
                 $('.error_' + inputId).html(fieldName + ' is required.');
             }
         });

         if (countErrorField > 0) {
             $('.loading_button').hide();
             return;
         }
         $.ajax({
             url: url,
             type: 'post',
             data: request,
             success: function(data) {
                 toastr.success(data);
                 $('.loading_button').hide();
                 table.ajax.reload();
                 $('#add_form').show();
                 $('#edit_form').hide();
             }
         });
     });
 </script>
