<form id="add_form" action="{{ route('hrm.increments.store') }}" method="Post">
    @csrf
    {{-- <div class="form-group row mb-3 text-center">
		<label class="col-md-4 text-end">Employee :</label>
		<div class="col-md-3 text-start">
			<strong>{{ $employee->name }} - {{ $employee->employee_id }}</strong>
		</div>
		<label class="col-md-2 text-end">Current Salary :</label>
		<div class="col-md-3 text-start">
			<strong>{{ $employee->salary }}</strong>
			<input type="hidden" name="employee_id" value="{{ $employee->id }}">
			<input type="hidden" name="previous" value="{{ $employee->salary }}">
		</div>
	</div> --}}
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Salary Type:</label>
        <div class="col-lg-9">
            <select class="form-control mb-3 form-select" name="salary_type" required>
                <option value="1">Increment</option>
                <option value="2">Decrement</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Amount Type:</label>
        <div class="col-lg-9">
            <select class="form-control mb-3 form-select" name="increment_type" required>
                <option disabled="" selected="">>==choose one==<< /option>
                <option value="1">Fixed </option>
                <option value="2">Percentage (%)</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Amount:</label>
        <div class="col-lg-9">
            <input type="number" class="form-control mb-3" name="increment_amount" placeholder="Increment Amount"
                required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Remarks:</label>
        <div class="col-lg-9">
            <input type="text" class="form-control mb-3" name="increment_details" placeholder="Remarks" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">After Updated Salary:</label>
        <div class="col-lg-9">
            <input type="text" class="form-control mb-3" name="after_updated" readonly />
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end submit_button">Save Changes</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">Close</button>
            </div>
        </div>
    </div>
</form>


<script type="text/javascript">
    // call jquery method
    $(document).ready(function() {
        // Add Award by ajax
        $('#add_form').on('submit', function(e) {
            e.preventDefault();
            $('.loader').removeClass("d-none");
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    toastr.success(data, 'Succeed');
                    $('#add_form')[0].reset();
                    $('#increment_modal').modal('hide');
                    $('.datatable').DataTable().ajax.reload();
                    $('.loader').addClass("d-none");
                }

            });
        });
    });
</script>
