<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"></h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span></a>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h1>{{ $announcement->title }}</h1>
                    <p class="py-1">@lang('menu.created_at') {{ $announcement->created_at }}, By {{ \App\Models\User::find($announcement->created_by)?->name ?? 'Admin' }}</p>
                    <hr>
                    <p class="pt-1 pb-2">
                        {!! $announcement->description !!}
                    </p>
                    <p class="py-3">
                        @isset($announcement->files)
                            <img src="{{ asset('/uploads/announcement/' . $announcement->files) }}" height="200" width="100%"/>
                        @endisset
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('announcements.print', $announcement->id) }}" target="_blank" class="btn btn-sm btn-primary float-end m-0 me-2" id="print_announcement"><i class="fas fa-print"></i> @lang('menu.print')</a>
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
