@props(['route' => null, 'routeParams' => null, 'can' => null, 'role' => null, 'prefix' => null])
@isset($can)
    @can($can)
        <li>
            @isset($before)
                {{ $before }}
            @endisset
            @isset($routeParams)
                <a href="{{ Route::has($route) ? route($route, $routeParams) : '#' }}" {{ isset($role) ? "role=\"$role\"" : null }} {{ $attributes->merge(['class' => 'submenu-link ' . request()->is(route($route, $routeParams) ? 'active' : '')]) }}>
                    {{ $slot }}
                </a>
            @else
                <a href="{{ Route::has($route) ? route($route) : '#' }}" {{ isset($role) ? "role=$role" : null }} {{ $attributes->merge([
                    'class' => 'submenu-link ' . (request()->routeIs($route) || request()->is($prefix) ? 'active' : ''),
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
        <a href="{{ Route::has($route) ? route($route) : '#' }}" {{ isset($role) ? "role=$role" : null }} {{ $attributes->merge(['class' => 'submenu-link ' . (request()->routeIs($route) || request()->is($prefix) ? 'active' : '')]) }}>
            {{ $slot }}
        </a>
        @isset($after)
            {{ $after }}
        @endisset
    </li>
@endisset
