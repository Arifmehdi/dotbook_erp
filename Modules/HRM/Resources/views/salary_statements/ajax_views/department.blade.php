<form id="add_form" action="{{ route('hrm.settlements.department_wise.store') }}" method="post">
    @csrf
    <div class="form-group row mb-3 text-center">
        <label class="col-md-6 text-end">Department :</label>
        <div class="col-md-6 text-start">
            <strong>{{ $name }}</strong>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Salary Type:</label>
        <div class="col-lg-9">
            <select class="form-control mb-3 form-select" name="salary_type" id="salaryType" required>
                <option value="1">Increment</option>
                <option value="2">Decrement</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Amount Type:</label>
        <div class="col-lg-9">
            <select class="form-control mb-3 form-select" id="amountType" name="amount_type" required>
                <option disabled="" selected="">Select</option>
                <option value="1">Fixed Amount</option>
                <option value="2">Percentage on Basic</option>
                <option value="3">Percentage on Gross</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Amount:</label>
        <div class="col-lg-9">
            <input type="number" class="form-control mb-3" id="incrementAmount" name="how_much_amount"
                placeholder="Increment Amount" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">Remarks:</label>
        <div class="col-lg-9">
            <input type="text" class="form-control mb-3" name="remarks" placeholder="Remarks" />
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" id="submit" class="btn btn-sm btn-success float-end submit_button">Save
                    Changes</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">Close</button>
            </div>
        </div>
    </div>
</form>
