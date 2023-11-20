@props(['href' => '#', 'can' => null, 'is_modal' => true, 'text' => 'Add New', 'modal' => '#addModal'])

@can($can)
<a href="{{ $href }}" @if($is_modal == true) data-bs-toggle="modal" data-bs-target="{{ $modal }}" @endif {{ $attributes->merge(['class' => 'btn text-white btn-sm add']) }}>
    <span>
        <i class="fa-thin fa-circle-plus fa-2x"></i>
        <br> {{ __($text) }}
    </span>
</a>
@endcan