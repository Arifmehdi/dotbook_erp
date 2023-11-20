<div class="modal-content">
    <div class="modal-header">
        <h6 class="modal-title" id="exampleModalLabel">Edit Expoters</h6>
        <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
    </div>
    <div class="modal-body">
        <!--begin::Form-->
        <form id="edit_exporters_form" action="{{ route('lc.exporters.update', $exporters->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group row mt-1">

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.code') </strong> <i data-bs-toggle="tooltip" data-bs-placement="right" title="Leave empty to auto generate." class="fas fa-info-circle tp"></i></label>
                    <input type="text" name="e_code" class="form-control add_input" data-name="Code"
                        id="code" placeholder="@lang('menu.code')" value="{{ $exporters->exporter_id }}" disabled />
                    <span class="error error_e_code"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.name') </strong> <span class="text-danger">*</span></label>
                    <input type="text" required name="name" class="form-control add_input" data-name="Full Name"
                        id="name" placeholder="Full Name" value="{{ $exporters->name }}" />
                    <span class="error error_e_name"></span>
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.phone') </strong> <span class="text-danger">*</span></label>
                    <input type="tel" required name="phone" class="form-control add_input" data-name="Phone"
                        id="phone" placeholder="Phone" value="{{ $exporters->phone }}" />
                    <span class="error error_e_quantity"></span>
                </div>
            </div>

            <div class="form-group row mt-1">

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.business') </strong> </label>
                    <input type="text" name="business" class="form-control add_input" data-name="Business"
                        id="business" placeholder="@lang('menu.business')" value="{{ $exporters->business }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Alterbative Phone Number </strong> </label>
                    <input type="tel" name="alternative_number" class="form-control add_input" data-name="alternative_number" class="form-control"
                        id="alternative_number" placeholder="@lang('menu.alternative_number')" value="{{ $exporters->alternative_number }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>Land Line </strong> </label>
                    <input type="tel" name="land_line" class="form-control add_input" data-name="land_line" class="form-control" id="land_line" placeholder="Land Line" value="{{ $exporters->land_line }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.email') </strong> </label>
                    <input type="email" name="email" class="form-control add_input" data-name="email" id="email" placeholder="Email" value="{{ $exporters->email }}" />
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.date_of_birth') </strong> </label>
                    <input type="text" name="date_of_birth" class="form-control add_input" data-name="date_of_birth" id="date_of_birth" placeholder="Date of Birth" value="{{ $exporters->date_of_birth }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.id_proof_name') </strong> </label>
                    <input type="text" name="id_proof_name" class="form-control add_input" data-name="id_proof_name" id="id_proof_name" placeholder="@lang('menu.id_proof_name')" value="{{ $exporters->id_proof_name }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.id_proof_number') </strong> </label>
                    <input type="text" name="id_proof_number" class="form-control add_input" data-name="id_proof_number" id="id_proof_number" placeholder="@lang('menu.id_proof_number')" value="{{ $exporters->id_proof_number }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.tax_number') </strong> </label>
                    <input type="tel" name="tax_number" class="form-control add_input" data-name="tax_number" id="tax_number" placeholder="@lang('menu.tax_number')" value="{{ $exporters->tax_number }}" />
                </div>
            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.address') </strong> </label>
                    <input type="text" name="address" class="form-control add_input" data-name="address" id="address" placeholder="Address" value="{{ $exporters->address }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.city')</strong> </label>
                    <input type="text" name="city" class="form-control add_input" data-name="City" id="name" placeholder="City" value="{{ $exporters->city }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.state') </strong> </label>
                    <input type="text" name="state" class="form-control add_input" data-name="State" id="state" placeholder="@lang('menu.state')" value="{{ $exporters->state }}" />
                </div>

                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.zip_code')</strong> </label>
                    <input type="text" name="zip_code" class="form-control add_input" data-name="zip_code" id="zip_code" placeholder="Zip Code" value="{{ $exporters->zip_code }}" />
                </div>

            </div>

            <div class="form-group row mt-1">
                <div class="col-xl-3 col-md-6">
                    <label><strong>@lang('menu.country') </strong> </label>
                    <input type="text" name="country" class="form-control add_input" data-name="country" id="country" placeholder="@lang('menu.country')" value="{{ $exporters->country }}" />
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

<script>

    $('#edit_exporters_form').on('submit', function(e) {
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
                $('.exporterTable').DataTable().ajax.reload();
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

    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_purchase_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });


    new Litepicker({
        singleMode: true,
        element: document.getElementById('e_expire_date'),
        dropdowns: {
            minYear: new Date().getFullYear() - 50,
            maxYear: new Date().getFullYear() + 100,
            months: true,
            years: true
        },
        tooltipText: {
            one: 'night',
            other: 'nights'
        },
        tooltipNumber: (totalDays) => {
            return totalDays - 1;
        },
        format: 'DD-MM-YYYY'
    });

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

    $('.assetImageFileDelete').click(function() {
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
</script>
