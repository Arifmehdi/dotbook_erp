@props(['prefix' => null])
<ul class="submenu-group" style="display: {{ (isset($prefix) && request()->is($prefix)) ? 'block' : 'none' }}">
    {{ $slot }}
</ul>
