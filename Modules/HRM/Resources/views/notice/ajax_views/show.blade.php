<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display w-70">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"></h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span
                        class="fas fa-times"></span>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="modal-body">
                        <div class="row">
                            <h1>{{ $notice->title }}</h1>
                            <p class="py-1">@lang('menu.created_at'): {{ $notice->created_at }}, By:
                                {{ \App\Models\User::find($notice->created_by)?->name ?? 'Admin' }}</p>
                            <hr>
                            <p class="pt-1 pb-2">
                                {!! $notice->description !!}
                            </p>
                            @if (isset($notice->attachment))
                                <p class="py-3">
                                    <img src="{{ asset('/uploads/notice/' . $notice->attachment) }}" height="200"
                                        width="100%" />
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3">
                <a href="{{ route('hrm.notice.print', $notice->id) }}" target="_blank" class="btn btn-sm btn-primary float-end m-0 me-2" id="print_notice">
                    <i class="fas fa-print"></i> @lang('menu.print')
                </a>
                <a role="button" type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</a>
            </div>
        </div>
    </div>
</div>
