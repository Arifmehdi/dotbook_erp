@props(['can' => [false, false, false]])
<div class="d-flex gap-2">
    <div class="page-heading-btn d-flex">

        @isset($before)
            {{ $before }}
        @endisset

        {{ $slot }}

        <div id="exportButtonsContainer"></div>

        @isset($after)
            {{ $after }}
        @endisset
    </div>
    <x-back-button />
</div>
