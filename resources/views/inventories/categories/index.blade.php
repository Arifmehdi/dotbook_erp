@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@section('title', 'All Categories/SubCategories - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.categories') / @lang('menu.sub_category')</h6>
                </div>
                <x-all-buttons>
                    <x-slot name="after">
                        <div id="exportButtonsContainer2" class="d-none"></div>
                        <x-help-button />
                    </x-slot>
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" data-show="categories" class="tab_btn tab_active tabButtonCategory" href="#">
                                    <i class="fas fa-th-large"></i> @lang('menu.categories')</a>
                            </li>

                            <li>
                                <a id="tab_btn" data-show="sub-categories" class="tab_btn tabButtonSubCategory" href="#">
                                    <i class="fas fa-code-branch"></i> @lang('menu.sub_category')</a>
                            </li>
                        </ul>
                    </div>
                    @include('inventories.categories.bodyPartials.categoriesBody')
                    @include('inventories.categories.bodyPartials.subCategoriesBody')
                </div>
            </div>
        </div>
    </div>

    <x-shortcut-key-bar.shortcut-key-bar
    :items="[
        ['key' => 'Alt + N', 'value' => __('menu.add_category')],
        ['key' => 'Alt + M', 'value' => __('menu.add_sub_category')],
    ]">
    </x-shortcut-key-bar.shortcut-key-bar>

    <form id="delete_category_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="delete_subcategory_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>$('.sub-categories').addClass('d-none');</script>
    @include('inventories.categories.jsPartials.categoriesBodyJs')
    @include('inventories.categories.jsPartials.subCategoriesBodyJs')
    <script>
        $('.tabButtonCategory').on('click', function() {

            $('#exportButtonsContainer2').addClass('d-none');
            $('#exportButtonsContainer').removeClass('d-none');
        })

        $('.tabButtonSubCategory').on('click', function() {

            $('#exportButtonsContainer').addClass('d-none');
            $('#exportButtonsContainer2').removeClass('d-none');
        })

        document.onkeyup = function () {
            var e = e || window.event; // for IE to cover IEs window event-object

            if (e.altKey && e.which == 78) {

                $('#addCategory').click();
                return false;
            }else if (e.altKey && e.which == 77) {

                $('#addSubCategory').click();
                return false;
            }
        }
    </script>
@endpush
