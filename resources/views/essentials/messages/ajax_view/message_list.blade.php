@php
    use Carbon\Carbon;
@endphp

@foreach ($messages as $msg)
    <div class="message-box">
        <div class="user-block">
            <div class="left">
                <p class="p-0 user">{{ $msg->user_id == auth()->user()->id ? 'Me' : $msg->u_prefix.' '.$msg->u_name.' '.$msg->u_last_name }}</p>
                <p class="p-0 message-time"><i class="far fa-clock"></i> {{ Carbon::parse($msg->created_at)->diffForHumans() }}</p>
            </div>
            @if ($msg->user_id == auth()->user()->id)
                <a href="{{ route('messages.delete', $msg->id) }}" class="delete_message" id="delete"><i class="fa-regular fa-trash-can"></i></a>
            @endif    
        </div>

        <div class="message-text">
            <p>{{ $msg->description }}</p>
        </div>
    </div>
@endforeach