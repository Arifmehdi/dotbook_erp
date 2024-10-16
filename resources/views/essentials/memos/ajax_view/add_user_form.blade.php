<form id="add_user_form" action="{{ route('memos.add.users', $memo) }}" method="post">
    @csrf
    <div class="form-group row">
        <div class="col-md-12">
            <label><b>@lang('menu.users') </b></label>
            <select required name="user_ids[]" class="form-control select2 form-select" id="user_ids" multiple="multiple">
                <option disabled value=""> @lang('menu.select_please') </option>
                @foreach ($users as $user)
                    @if ($user->id != auth()->user()->id)
                        <option
                            @foreach ($memo->memo_users as $mamo_user)
                            {{ $user->id == $mamo_user->user_id ? 'SELECTED' : '' }} @endforeach
                            value="{{ $user->id }}">{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group row mt-2">
        <div class="col-md-12 d-flex justify-content-end">
            <div class="loading-btn-box">
                <button type="button" class="btn btn-sm loading_button display-none"><i
                        class="fas fa-spinner"></i></button>
                <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.update')</button>
                <button type="reset" data-bs-dismiss="modal"
                    class="btn btn-sm btn-danger float-end me-2">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</form>

<script>
    $('.select2').select2();
</script>
