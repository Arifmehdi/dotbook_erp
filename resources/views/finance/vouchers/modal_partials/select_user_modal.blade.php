<div class="modal fade" id="selectUserModal" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog double-col-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('menu.assign_sr')</h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>

            <div class="modal-body">
                <form id="select_user_form" action="" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            @if (!auth()->user()->can('view_own_sale'))
                                <div class="form-group">
                                    <label><b>@lang('menu.reference_by_sr')</b> <span class="text-danger">*</span></label>
                                    <div class="col-12">
                                        <input type="hidden" name="user_count" id="user_count" value="1">
                                        <select required name="user_id" id="selected_user_id" class="form-control select2 form-select">
                                            <option value="">@lang('menu.select_ac')</option>
                                            @foreach ($users as $user)
                                                <option data-user_name="{{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}" value="{{ $user->id }}">
                                                    {{ $user->prefix . ' ' . $user->name . ' ' . $user->last_name . '/' . $user->phone }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @else
                                <select required name="user_id" id="selected_user_id" class="form-control select2 form-select">
                                    <option selected data-user_name="{{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name . '/' . auth()->user()->phone }}" value="{{ auth()->user()->id }}">
                                        {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name . '/' . auth()->user()->phone }}
                                    </option>
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12 text-end">
                            <a href="#" class="btn btn-sm btn-success assignUserBtn" id="assignUserBtn" autofocus>@lang('menu.add')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
