<style>
    #shortcut-section {
        position: fixed;
        bottom: 0;
        right: 0;
        left: 40px;
        height: 30px;
        background: #fff;
        border-top: 1px solid rgba(0, 0, 0, .2);
        overflow: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
        z-index: 1055;
    }
    #shortcut-section::-webkit-scrollbar {
        display: none;
    }
    #shortcut-section.menu-expanded {
        left: 220px;
    }
    .horizontal-menu-active #shortcut-section {
        left: 0 !important;
    }

    #shortcut-section .shortcut-list {
        min-width: max-content;
        display: flex;
        padding: 4px 10px;
        gap: 10px;
    }

    #shortcut-section .shortcut-list li {
        border: 1px solid rgba(0, 0, 0, .1);
        border-radius: 3px;
        background: rgba(0, 0, 0, .05);
        padding: 0 5px;
        height: 21px;
        line-height: 18px;
        font-size: 12px;
    }
    #shortcut-section .shortcut-list span {
        color: #4383be;
        font-weight: 600;
        text-decoration: underline;
    }
</style>

@props(['items'])
<div class="has-vertical" id="shortcut-section">
    <ul class="shortcut-list">
        @foreach ($items as $item)
            <li><span>{{ $item['key'] }} :</span> {{ $item['value'] }}</li>
        @endforeach
    </ul>
</div>
