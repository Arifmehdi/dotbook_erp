 <!--begin::Form-->
 <form id="edit_schema_form" action="{{ route('invoices.schemas.update', $schema->id) }}" method="POST">
     @csrf
     <div class="form-group">
         <label><b>@lang('menu.preview') <span
                     id="e_schema_preview">#{{ $schema->prefix . '' . $schema->start_from }}</span></label>
     </div>

     <div class="form-group row">
         <div class="col-md-6">
             <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
             <input type="text" name="name" class="form-control form-control-sm" id="name"
                 placeholder="Schema name" value="{{ $schema->name }}" required />
             <span class="error error_e_name"></span>
         </div>

         <div class="col-md-6">
             <label><b>Format </b> <span class="text-danger">*</span></label>
             <select name="format" class="form-control form-control-sm form-select" id="e_format" required>
                 <option {{ $schema->format == 1 ? 'SELECTED' : '' }} value="1">FORMAT-XXXX</option>
                 <option {{ $schema->format == 2 ? 'SELECTED' : '' }} value="2">FORMAT-{{ date('Y') }}/XXXX
                 </option>
             </select>
             <span class="error error_e_format"></span>
         </div>
     </div>

     <div class="form-group row mt-1">
         <div class="col-md-6">
             <label><b>@lang('menu.prefix') </b> <span class="text-danger">*</span></label>
             <input type="text" name="prefix" {{ $schema->format == 2 ? 'readonly' : '' }}
                 class="form-control form-control-sm" id="e_prefix" placeholder="@lang('menu.prefix')"
                 value="{{ $schema->prefix }}" required />
             <span class="error error_e_prefix"></span>
         </div>

         <div class="col-md-6">
             <label><b>@lang('menu.start_from') </b></label>
             <input type="number" name="start_from" class="form-control form-control-sm" id="e_start_from"
                 placeholder="@lang('menu.start_from')" value="{{ $schema->start_from }}" />
         </div>
     </div>

     <div class="form-group mt-3">
         <div class="d-flex justify-content-end">
             <div class="loading-btn-box">
                 <button type="button" class="btn btn-sm loading_button display-none"><i
                         class="fas fa-spinner"></i></button>
                 <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                 <button type="reset" data-bs-dismiss="modal"
                     class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
             </div>
         </div>
     </div>
 </form>
