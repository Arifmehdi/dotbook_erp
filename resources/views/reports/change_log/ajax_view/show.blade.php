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
                    <h1>{{ $changelog->title }}</h1>
                    <p class="py-1">@lang('menu.created_at'): {{ $changelog->created_at }}, By: {{ \App\Models\User::find($changelog->created_by)?->name ?? 'Admin' }}</p>
                    <hr>
                    <p class="pt-1">
                        {!! $changelog->description !!}
                    </p>
                </div>
            </div>
            <div class="modal-footer ">
                <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger m-0">@lang('menu.close')</button>
            </div>
        </div>
    </div>
</div>
