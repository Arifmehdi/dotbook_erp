<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-full-display w-70">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel"></h6>
                <a href="#" class="close-btn" data-bs-dismiss="modal" aria-label="Close"><span class="fas fa-times"></span>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="modal-body">
                        <div class="row">
                            <h1>{{ $visit->title }}</h1>
                            <hr>
                            <p class="pt-1 pb-2">
                                {!! $visit->description !!}
                            </p>
                            @if (isset($visit->attachments))
                            <p class="py-3">
                                @php
                                    $attachments = $visit->attachments;
                                    $extension = pathinfo($attachments, PATHINFO_EXTENSION);
                                @endphp
                                @if($extension == 'pdf')
                                    <iframe src="{{ asset('/uploads/visits/' . $visit->attachments) }}" width="100%" height="600"></iframe>
                                @else
                                    <img src="{{ asset('/uploads/visits/' . $visit->attachments) }}" height="200" width="100%" />
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="row pt-2">
                        <div class="col-md-12 d-flex justify-content-end">
                            {{-- route('hrm.visit.print', $visit->id --}}
                            <button type="reset" data-bs-dismiss="modal" class="btn btn-sm btn-danger me-2">@lang('menu.close')</button>
                            <a href="#" target="_blank" class="btn btn-sm btn-primary" id="print_visit"><i class="fas fa-print"></i> @lang('menu.print')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
