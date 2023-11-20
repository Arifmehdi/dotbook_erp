@if (isset($holidays->attachment))
<p class="py-3">
    @php
        $attachments = $holidays->attachment;
        $extension = pathinfo($attachments, PATHINFO_EXTENSION);
    @endphp
    @if($extension == 'pdf')
        <iframe src="{{ asset('/uploads/application/' . $holidays->attachment) }}" width="100%" height="600"></iframe>
    @else
        <img src="{{ asset('/uploads/application/' . $holidays->attachment) }}" height="200" width="100%" />
    @endif
</p>
@endif

