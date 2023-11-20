<form target="blank" id="first_form" method="GET"
    action="{{ route('hrm.employee.left-letter', ['type' => 'first', 'id' => $left_employee->id]  ) }}">
    @csrf
    <div class="form-group">
        <label for="exampleInputEmail1">Select Date</label>
        <input type="date" class="form-control" required="" name="first_date" id="first_date">
        <input type="hidden" class="form-control" required="" name="user_id" id="user_id">
        <small id="emailHelp" class="form-text text-muted">The last date of employee absence</small>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</form>
