<style>
    #submit_customer_basic_form .form-group label {
        text-align: right;
    }

</style>
<div class="modal-dialog col-60-modal" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title" id="exampleModalLabel">@lang('menu.buyer_requisition') <span><small class="text-info">({{ $requisition->created_at->diffForHumans() }})</small></span></h6>
            <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
        </div>
        <div class="modal-body">
                <div class="form-group row mt-1">
                    <div class="col-lg-12">
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="name"><b>@lang('menu.company')</b></label>
                            <div class="col-sm-9">
                                {{ $requisition->company }}
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="email"><b>@lang('menu.email')</b></label>
                            <div class="col-sm-9">
                                {{ $requisition->company }}
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="phone"><b>@lang('menu.phone')</b></label>
                            <div class="col-sm-9">
                                {{ $requisition->phone }}
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="address"><b>@lang('menu.address')</b></label>
                            <div class="col-sm-9">
                                {{ $requisition->address }}
                            </div>
                        </div>
                        <div class="form-group row p-1">
                            <label class="col-sm-3" for="query"><b>@lang('menu.query')</b></label>
                            <div class="col-sm-9">
                                {{ $requisition->message }}
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
