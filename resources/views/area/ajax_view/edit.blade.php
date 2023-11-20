<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel"></h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_area_form" action="{{ route('core.area.update', $area->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">
                <h6 class="text-center text-muted">@lang('menu.division_wise')</h6>
                <div class="col-md-6">
                    <label><strong>@lang('menu.division') </strong></label>
                    <select class="form-control add-input form-select" name="division" id="edit_division_id" required>
                        <option value="">>>--@lang('menu.select_division')--<<< /option>
                                @foreach ($divisions as $division)
                        <option {{ $area->division == $division->id ? 'SELECTED' : '' }} value="{{ $division->id }}">
                            {{ $division->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label><strong>@lang('menu.district') </strong></label>
                    <select class="form-control add-input district form-select" name="district" id="edit_district"
                        required multiple>
                        @foreach ($districts as $district)
                            <option {{ $area->district == $district->id ? 'SELECTED' : '' }}
                                value="{{ $district->id }}">{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label><strong>@lang('menu.sub_district') </strong></label>
                    <select class="form-control add-input thanas form-select" name="thanas" id="edit_thanas" required>
                        @foreach ($thanas as $thanas)
                            <option {{ $area->thanas == $thanas->id ? 'SELECTED' : '' }} value="{{ $thanas->id }}">
                                {{ $thanas->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label><strong>@lang('menu.union') </strong></label>
                    <select class="form-control add-input unions form-select" name="unions" id="edit_unions">
                        @foreach ($unions as $unions)
                            <option {{ $area->unions == $unions->id ? 'SELECTED' : '' }} value="{{ $unions->id }}">
                                {{ $unions->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label><strong>@lang('menu.postal_code') </strong></label>
                    <input type="text" name="postalcode" class="form-control" value="{{ $area->postalcode }}">
                </div>
                <div class="col-md-6">
                    <label><strong>@lang('menu.area') </strong></label>
                    <input type="text" class="form-control" name="area" value="{{ $area->area }}" required>
                </div>
                <div class="mt-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <div class="loading-btn-box">
                            <button type="button" class="btn btn-sm loading_button display-none float-end"><i
                                    class="fas fa-spinner"></i></button>
                            <button type="submit"
                                class="btn btn-sm btn-success submit_button float-start  float-end">@lang('menu.save')</button>
                            <button type="reset" data-bs-dismiss="modal"
                                class="btn btn-sm btn-danger float-start float-end me-2">@lang('menu.close')</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function() {

        $(document).on('click', "#edit_division_id", function() {
            let division_value = this.value;
            $.get('/district?district=' + division_value, function(data) {
                $('#edit_district').html(data);
            })
        });

        $("#edit_district").change(function() {
            let thana_value = this.value;
            $.get('/thanas?thanas=' + thana_value, function(data) {
                $('#edit_thanas').html(data);
            })
        })
        $("#edit_thanas").change(function() {
            let union_value = this.value;
            $.get('/unions?unions=' + union_value, function(data) {
                $('#edit_unions').html(data);
            })
        })
    });

    // update form
    $('#edit_area_form').on('submit', function(e) { // clisk Edit Button
        e.preventDefault(); // what
        var url = $(this).attr('action'); // get the url with id. Just alert the url and you will know.
        $.ajax({
            url: url,
            type: 'post',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {

                toastr.success(data);
                $('.loading_button').hide();
                $('#editModal').modal('hide');
                $('#areaTable').DataTable().ajax.reload();
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
</script>
