@props(['route' => '#', 'can' => null,])

@isset($can)
    @can($can)
        <li>
            <a href="{{ route($route) }}" {{ $attributes->merge(['class' => 'submenu-link '  . request()->rotueIs($route) ? 'active' : '']) }}>
                {{ $slot }}
            </a>
        </li>
    @endcan
@endisset
