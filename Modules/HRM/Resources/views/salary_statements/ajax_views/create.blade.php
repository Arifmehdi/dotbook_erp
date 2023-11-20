<form id="singleAddForm" action="{{ route('hrm.salary-settlements.store') }}" method="Post">
    @csrf
    <div class="form-group row mb-3 text-center">
        <div class="col-md-3">
            <label>{{ __('Employee') }} :</label>
            <strong>{{ $employee->name }} - {{ $employee->employee_id }}</strong>
        </div>
        <div class="col-md-3">
            <label>{{ __('Current Salary') }} :</label>
            <strong>{{ $employee->salary }}</strong>
            <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <input type="hidden" name="previous" id="previousSalary" value="{{ $employee->salary }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('Extra') }} :</label>
            <strong>{{ $employee->beneficialSalary }}</strong>
            <input type="hidden" id="beneficialSalary" value="{{ $beneficialSalary }}">
        </div>
        <div class="col-md-3">
            <label>{{ __('Gross Salary') }} :</label>
            <strong>{{ $total }}</strong>
            <input type="hidden" id="grossSalary" value="{{ $total }}">
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">{{ __('Salary Type') }}:</label>
        <div class="col-lg-9">
            <select class="form-control mb-3 form-select" name="salary_type" id="salaryType" required>
                <option value="1">{{ __('Increment') }}</option>
                <option value="2">{{ __('Decrement') }}</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">{{ __('Amount Type') }}:</label>
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
        <label class="col-lg-3 col-form-label text-end">{{ __('Amount') }}:</label>
        <div class="col-lg-9">
            <input type="number" class="form-control mb-3" id="incrementAmount" name="how_much_amount"
                placeholder="Increment Amount" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">{{ __('Remarks') }}:</label>
        <div class="col-lg-9">
            <input type="text" class="form-control mb-3" name="remarks" placeholder="Remarks" />
        </div>
    </div>
    <div class="form-group row">
        <label class="col-lg-3 col-form-label text-end">{{ __('Updated Basic Salary') }}:</label>
        <div class="col-lg-9">
            <input type="text" class="form-control mb-3" id="afterUpdate" readonly />
        </div>
    </div>
    <div class="form-group row mt-3">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" id="submit"
                    class="btn btn-sm btn-success float-end submit_button">{{ __('Save Changes') }}</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">Close</button>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    // call jquery method
    $(document).ready(function() {
        $(document).on('keyup', '#incrementAmount', function() {
            var previousSalary = $("#previousSalary").val();
            var grossSalary = $("#grossSalary").val();
            var salaryType = $("#salaryType").val();
            var amountType = $("#amountType").val();
            var beneficialSalary = $("#beneficialSalary").val();
            var incrementAmount = $(this).val();
            if (salaryType == 1 && amountType == 1) {
                var total = (parseFloat(previousSalary) + parseFloat(incrementAmount));
            }
            if (salaryType == 1 && amountType == 2) {
                var totalSum = (parseFloat(previousSalary) * parseFloat(incrementAmount) / 100);
                var total = (parseFloat(previousSalary) + parseFloat(totalSum));
            }
            if (salaryType == 2 && amountType == 1) {
                var total = (parseFloat(previousSalary) - parseFloat(incrementAmount));
            }
            if (salaryType == 2 && amountType == 2) {
                var totalSum = (parseFloat(previousSalary) * parseFloat(incrementAmount) / 100);
                var total = (parseFloat(previousSalary) - parseFloat(totalSum));
            }

            if (salaryType == 1 && amountType == 3) {
                var totalSum = (parseFloat(grossSalary) * parseFloat(incrementAmount) / 100);
                var totalBenificial = (parseFloat(grossSalary) + parseFloat(totalSum));
                var total = (parseFloat(totalBenificial) - parseFloat(beneficialSalary));
            }

            if (salaryType == 2 && amountType == 3) {
                var totalSum = (parseFloat(grossSalary) * parseFloat(incrementAmount) / 100);
                var totalBenificial = (parseFloat(grossSalary) - parseFloat(totalSum));
                var total = (parseFloat(totalBenificial) - parseFloat(beneficialSalary));
            }
            var afterUpdate = $("#afterUpdate").val(total);
            var totalRound = Math.round($("#afterUpdate").val());
            $("#afterUpdate").val(totalRound);
        });

        $(document).on('change', '#amountType, #salaryType', function() {
            $("#afterUpdate").val('');
            $("#incrementAmount").val('');
        });
    });
</script>
