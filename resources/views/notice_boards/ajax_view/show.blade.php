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
                            @if (isset($notice->files))
                                <p class="py-3">
                                    <img src="{{ asset('/uploads/notice/' . $notice->files) }}" height="200"
                                        width="100%" />
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-3">
                <div class="row">
                    <div class="col-md-12">
                        <a href="{{ route('notice_boards.print', $notice->id) }}" target="_blank"
                            class="btn btn-sm btn-primary float-end" id="print_notice" style="padding: 6px;"><i
                                class="fas fa-print"></i> @lang('menu.print')</a>
                        <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger">@lang('menu.close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
