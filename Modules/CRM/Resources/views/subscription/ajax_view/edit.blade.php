<form id="update_form" method="POST" action="{{ route('crm.subscription.update', $subscription->id) }}"
    enctype="multipart/form-data">
    @csrf
    <div class="form-group row mt-1">
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Billing Plan </strong> <span class="text-danger">*</span></label>
                <input required type="text" name="billing_plan" class="form-control" placeholder="Billing Plan"
                    value="{{ $subscription->billing_plan }}" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Customer </strong> <span class="text-danger">*</span></label>
                <select name="customer_id" id="customer_id" class="form-control " data-show-subtext="1" data-base="1"
                    data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true">
                    @forelse ($customers as $customer)
                        <option value="{{ $customer->id }}"
                            {{ $subscription->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}
                        </option>
                    @empty
                        <option value="">Customer Not Found</option>
                    @endforelse
                </select>
                <span class="error error_customer"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Project </strong> <span class="text-danger">*</span></label>
                <select name="project_id" id="project_id" class="form-control " data-show-subtext="1" data-base="1"
                    data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true">
                    <option value="1" {{ $subscription->project_id == 1 ? 'selected' : '' }}>Project 1</option>
                    <option value="2" {{ $subscription->project_id == 2 ? 'selected' : '' }}>Project 2</option>
                    <option value="3" {{ $subscription->project_id == 3 ? 'selected' : '' }}>Project 3</option>
                    <option value="4" {{ $subscription->project_id == 4 ? 'selected' : '' }}>Project 4</option>
                </select>
                <span class="error error_project"></span>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label for="first_billing_date"><strong>First Billing Date</strong> </label>
                <input type="text" name="date" class="form-control" id="first_billing_date"
                    placeholder="Subscription Name" autocomplete="off" value="{{ $subscription->date }}" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Subscription Name </strong> </label>
                <input type="text" name="subscription_name" class="form-control" id="subscription_name"
                    placeholder="Subscription Name" value="{{ $subscription->subscription_name }}" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Quantity</strong> </label>
                <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Quantity"
                    value="{{ $subscription->quantity }}" />
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>currency</strong> </label>
                <select name="currency" id="currency" class="form-control " data-show-subtext="1" data-base="1"
                    data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true">
                    <option value=""></option>
                    <option value="1" selected data-subtext="$"
                        {{ $subscription->currency == 1 ? 'selected' : '' }}>USD</option>
                    <option value="2" data-subtext="€" {{ $subscription->currency == 2 ? 'selected' : '' }}>EUR
                    </option>
                    <option value="3" data-subtext="DH" {{ $subscription->currency == 3 ? 'selected' : '' }}>MAD
                    </option>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Tax</strong></label>
                <select name="tax" id="tax" class="form-control " data-show-subtext="1" data-base="1"
                    data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true">
                    <option value="0" {{ $subscription->tax == 0 ? 'selected' : '' }}> N/A</option>
                    <option value="2" {{ $subscription->tax == 2 ? 'selected' : '' }}>Tax 2%</option>
                    <option value="2.5" {{ $subscription->tax == 2.5 ? 'selected' : '' }}>Tax 2.5%</option>
                    <option value="3" {{ $subscription->tax == 3 ? 'selected' : '' }}>Tax 3%</option>
                </select>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Terms & Conditions</strong> </label>
                <textarea class="form-control ckEditor" name="terms" id="terms" cols="30" rows="5"
                    placeholder="Terms & Conditions">{{ $subscription->terms }}</textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label><strong>Description</strong> </label>
                <textarea class="form-control ckEditor" name="description" id="description" cols="30" rows="5"
                    placeholder="Description">{{ $subscription->description }}</textarea>
            </div>
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">Update</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
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
                $('.subscription_table').DataTable().ajax.reload();
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
