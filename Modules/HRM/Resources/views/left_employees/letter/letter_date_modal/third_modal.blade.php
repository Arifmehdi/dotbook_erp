<form target="blank" method="GET"
    action="{{ route('hrm.employee.left-letter', ['type' => 'third', 'id' => $employee->id]  ) }}">
    @csrf
    <div class="form-group">
        <label for="exampleInputEmail1">Select Date</label>
        <input type="date" class="form-control" required="" name="first_date" id="first_date">
        <input type="hidden" class="form-control" required="" name="user_id" id="user_id">
        <small id="emailHelp" class="form-text text-muted">The last date of employee absence</small>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">First Letter Send Date</label>
        <input type="date" class="form-control" required="" name="second_date" id="second_date">
        <small id="emailHelp" class="form-text text-muted">Select the date when you send the first letter</small>
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Second Letter Send Date</label>
        <input type="date" class="form-control" required="" name="third_date" id="third_date">
        <small id="emailHelp" class="form-text text-muted">Select the date when you send the second letter</small>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</form>

