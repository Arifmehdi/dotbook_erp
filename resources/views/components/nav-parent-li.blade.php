@props(['route' => null, 'routeParams' => null, 'can' => null, 'role' => null, 'prefix' => null])
@isset($can)
    @can($can)
        <li>
            @isset($before)
                {{ $before }}
            @endisset
            @isset($routeParams)
                <a href="{{ isset($route) ? route($route, $routeParams) : '#' }}" {{ isset($role) ? "role=\"$role\"" : null }}
                    {{ $attributes->merge(['class' => 'submenu-link ' . request()->is(route($route, $routeParams) ? 'open' : '')]) }}>
                    {{ $slot }}
                </a>
            @else
                <a href="{{ isset($route) ? route($route) : '#' }}" {{ isset($role) ? "role=$role" : null }}
                    {{ $attributes->merge([
                        'class' => 'submenu-link ' . (request()->routeIs($route) || request()->is($prefix) ? 'open' : ''),
                    ]) }}>
                    {{ $slot }}
                </a>
            @endisset
            @isset($after)
                {{ $after }}
            @endisset
        </li>
    @endcan
@else
    <li>
        @isset($before)
            {{ $before }}
        @endisset
        <a href="{{ isset($route) ? route($route) : '#' }}" {{ isset($role) ? "role=$role" : null }}
            {{ $attributes->merge(['class' => 'submenu-link ' . (request()->routeIs($route) || request()->is($prefix) ? 'open' : '')]) }}>
            {{ $slot }}
        </a>
        @isset($after)
            {{ $after }}
        @endisset
    </li>
@endisset
