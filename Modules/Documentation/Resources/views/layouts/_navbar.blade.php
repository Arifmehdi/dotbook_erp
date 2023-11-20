<nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
    <div class="container-fluid">
        @if(Route::has('documentation.index'))
        <a class="navbar-brand" href="{{ route('documentation.index') }}">@lang('menu.documentation')</a>
        @endif

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                @if(Route::has('documentation.developer_change_log'))
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href=" {{ route('documentation.developer_change_log') }}">Change Logs</a>
                    </li>
                @endif

                @if(Route::has('documentation.scale.index'))
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href=" {{ route('documentation.scale.index') }}">Scale Docs</a>
                    </li>
                @endif
            </ul>
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="@lang('menu.search')" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">@lang('menu.search')</button>
            </form>
        </div>
    </div>
</nav>
