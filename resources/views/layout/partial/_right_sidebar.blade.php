<div id="rightSidebar">
    <div class="sidebar-container position-relative">
        <div class="float-end" id="closeRightSidebar"><a href="#" style="color: #fff;"><i
                    class="fa-thin fa-circle-xmark fa-2x"></i></a></div>
        <h2>{{ __('User Profile') }}</h2>
        <div class="my-3 py-2 ">
            <ul class="d-flex 00flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="fa-thin fa-user"></i></span></li>
                <li class="my-1 me-2 ms-1 text-white py-2" style="font-size: 12px">
                    {{ auth()->user()->prefix . ' ' . auth()->user()->name . ' ' . auth()->user()->last_name }}
                    @if (auth()->user()->role_type == 1)
                        ({{ __('Super Admin') }})
                    @elseif(auth()->user()->role_type == 2)
                        ({{ __('Admin') }})
                    @else
                        ({{ auth()->user()?->roles()?->first()?->name }})
                    @endif
                </li>
            </ul>

        </div>
        <div class="my-3 border-top py-2">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="fa-thin fa-square-user"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.view', auth()->user()->id) }}">
                        <p class="text-white">@lang('menu.my_profile')</p>
                        <small class="email text-white">{{ auth()?->user()?->email }}</small>
                    </a>
                </li>
            </ul>
        </div>
        <div class="my-3 border-top py-2">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon text-white"><span class=""><i class="fa-thin fa-user-hair"></i></span></li>
                <li class="my-1 me-2 ms-1">
                    <a href="{{ route('users.profile.index') }}">
                        <p class="text-white">@lang('menu.change_profile')</p>
                        <small class="email text-white">@lang('menu.update_or_change_password')</small>
                    </a>
                </li>
            </ul>
        </div>

        <div class="my-3 border-top py-2">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon"><small class=""><i class="fa-thin fa-message-lines text-white"></i></small>
                </li>
                <li class="my-2 me-2 ms-1">
                    <a href="{{ route('feedback.index') }}">
                        <p class="text-white">@lang('menu.feedback')</p>
                    </a>
                </li>
            </ul>
        </div>
        <div class="my-3 border-top py-2">
            <ul class="d-flex flex-row justify-content-start">
                <li class="icon"><small class=""><i class="fa-thin fa-book-medical text-white"></i></small></li>
                <li class="my-2 me-2 ms-1">
                    <a href="{{ route('documentation.index') }}">
                        <p class="text-white">@lang('menu.documentation')</p>
                    </a>
                </li>
            </ul>
        </div>


        <div class="position-absolute bottom-btn-group"
            style="bottom: 0; left: 0; right: 0; border-top: 1px solid #fff;">
            <ul class="d-flex">
                <li class="d-lg-block d-none"><a href="#" class="text-white menu-style-switch"><span><i
                                class="fa-thin fa-bars"></i></span><span id="orientationName">Horizontal</span></a></li>
                <li><a href="#" class="text-white menu-theme"><span><i
                                class="fa-thin fa-moon-cloud"></i></span><span id="themeNameText">Light Nav</span></a>
                </li>
                <li><a href="{{ route('settings.general.index') }}" class="text-white"><span><i
                                class="fa-thin fa-gear"></i></span><span>Settings</span></a></li>

                @if (Route::has('documentation.index'))
                    <li class="d-lg-block d-none">

                        <a role="button" onclick="openFullscreen();" class="text-white addFullScrintBtn"> <span><i
                                    class="fas fa-thin fa-expand"></i></span><span>Fullscreen</span></a>
                        <a role="button" onclick="closeFullscreen();" class="text-white exitFullScrintBtn d-hide">
                            <span><i class="fa-thin fa-compress"></i></i></span><span>Restore</span>
                        </a>
                    </li>
                @endif

                <li><a href="#" class="text-white bg-danger" id="logout_option"><span><i
                                class="fa-thin fa-power-off"></i></span><span>Logout</span></a></li>
            </ul>
        </div>
    </div>
    <div class="shortcut-bar d-lg-block d-none">
        <div class="shorcut-box add-new-box">
            <span class="shortcut-wrap" data-bs-toggle="tooltip" data-bs-title="Add a new shortcut"
                data-bs-placement="right">
                <a href="#" data-bs-toggle="modal" data-bs-target="#sidebarShortcutModal">
                    <i class="fa-regular fa-plus"></i>
                </a>
            </span>
        </div>
        @foreach (\App\Models\ShortcutBookmark::latest()->limit(25)->get() as $shortcut)
            <div class='shorcut-box'>
                <div class='dropdown'>
                    <button class='shortcut-action' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                        <i class='fa-regular fa-ellipsis-vertical'></i>
                    </button>
                    <ul class='dropdown-menu'>
                        <li><button class='edit-shortcut'
                                data-url="{{ route('shortcut-bookmarks.edit', $shortcut->id) }}">Edit</button></li>
                        <li><button class='delete-shortcut'
                                data-url="{{ route('shortcut-bookmarks.destroy', $shortcut->id) }}">Remove</button>
                        </li>
                    </ul>
                </div>
                <span class='shortcut-wrap'>
                    <a href='{{ $shortcut->url }}' target='blank'><img class='icon'
                            src="https://www.google.com/s2/favicons?domain={{ trim($shortcut->url) }}"></a>
                </span>
            </div>
        @endforeach
    </div>

</div>


<!-- Modal -->
<div class="modal fade" id="sidebarShortcutModal" tabindex="-1" aria-labelledby="sidebarShortcutModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="sidebarShortcutModalLabel">Add a shortcut to sidebar</h6>
                <a href="#" role="button" type="button" class="close-btn" data-bs-dismiss="modal"
                    aria-label="Close">
                    <span class="fas fa-times"></span>
                </a>
            </div>
            <div class="modal-body">
                <form action="{{ route('shortcut-bookmarks.store') }}" method="POST" id="shortcut-bookmarks-form">
                    @csrf
                    <div class="form-group mb-3">
                        <input type="text" name="shortcut_name" class="form-control" id="shortcutName"
                            placeholder="Shortcut name" required>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="shortcut_url" class="form-control" id="shortcutUrl"
                            placeholder="Shortcut url" required>
                    </div>
                    <div class="form-group d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-sm btn-success save-shortcut" data-bs-dismiss="modal"
                            disabled>
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sidebarShortcutModalEdit" tabindex="-1" aria-labelledby="sidebarShortcutModalEditLabel"
    aria-hidden="true">
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/litepicker/2.0.11/litepicker.min.js"
        integrity="sha512-1BVjIvBvQBOjSocKCvjTkv20xVE8qNovZ2RkeiWUUvjcgSaSSzntK8kaT4ZXXlfW5x1vkHjJI/Zd1i2a8uiJYQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".menu-theme").on('click', function(e) {
                e.preventDefault();
                let isLightTheme = window.localStorage.getItem('isLightTheme');
                if (isLightTheme == 'true') {
                    window.localStorage.setItem('isLightTheme', false);
                    document.body.classList.remove('light-nav');
                    document.getElementById('themeNameText').innerHTML = 'Light Nav';
                }
                if (isLightTheme == 'false' || isLightTheme == null || isLightTheme == undefined) {
                    window.localStorage.setItem('isLightTheme', true);
                    document.body.classList.add('light-nav');
                    document.getElementById('themeNameText').innerHTML = 'Dark Nav';
                }
            })

            $("#shortcutUrl").on("change", function() {
                if ($("#shortcutUrl").is(":valid")) {
                    $(".save-shortcut").prop("disabled", false);
                } else {
                    $(".save-shortcut").prop("disabled", true);
                }
            });

            $(".save-shortcut").on("click", function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: "{{ route('shortcut-bookmarks.store') }}",
                    data: $('#shortcut-bookmarks-form').serialize(),
                    success: function(res) {
                        toastr.success(res.message);
                        var {
                            id,
                            url,
                            name
                        } = res.data;
                        var removeUrl = "{{ route('shortcut-bookmarks.destroy', ':id') }}";
                        removeUrl = removeUrl.replace(':id', id);
                        var editUrl = "{{ route('shortcut-bookmarks.edit', ':id') }}";
                        editUrl = editUrl.replace(':id', id);

                        $(".shortcut-bar").prepend(`
                        <div class='shorcut-box'>
                            <div class='dropdown'>
                                <button class='shortcut-action' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='fa-regular fa-ellipsis-vertical'></i>
                                </button>
                                <ul class='dropdown-menu'>
                                    <li><button class='edit-shortcut' data-url="${editUrl}">Edit</button></li>
                                    <li><button class='delete-shortcut' data-url="${removeUrl}">Remove</button></li>
                                </ul>
                            </div>
                            <span class='shortcut-wrap'>
                                <a href='#' target='blank'><img class='icon' src=''></a>
                            </span>
                        </div>
                        `);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        toastr.error(errorMessage);
                        return;
                    }
                });
            });

            $(".delete-shortcut").click(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).data('url'),
                    type: 'DELETE',
                    success: function(res) {
                        toastr.success(res);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        toastr.error(errorMessage);
                        return;
                    }
                })
            });

            $(".edit-shortcut").click(function(e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).data('url'),
                    success: function(res) {
                        $('#sidebarShortcutModalEdit').html(res);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value[0] + '<br>';
                        });
                        toastr.error(errorMessage);
                        return;
                    }
                })
            })
        });
    </script>
@endpush
