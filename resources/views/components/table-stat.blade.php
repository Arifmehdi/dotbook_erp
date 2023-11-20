@props([
    'card_id' => 'info_item',
    'items' => [
        'id' => '',
        'name' => '',
        'value' => 0,
    ]
])

<div class="d-flex flex-wrap stat-box" id="{{ $card_id}} ">
    @foreach($items as $item)
    <div class="dot-shadow border-start">
        <p class="text-center fs-4" id="{{ $item['id'] }}">{{ $item['value'] }}</p>
        <h6 class="text-center">{{__($item['name']) }}</h6>
    </div>
    @endforeach
</div>