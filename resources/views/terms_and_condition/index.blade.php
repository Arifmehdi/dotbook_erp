@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'All Categories/SubCategories - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>@lang('menu.terms_and_condition')</h6>
                </div>
                <x-back-button />
            </div>
        </div>
        <div class="p-15">
            <div class="form_element rounded mt-0 mb-1">
                <div class="element-body">
                    <div class="tab_list_area">
                        <ul class="list-unstyled">
                            <li>
                                <a id="tab_btn" data-show="categories" class="tab_btn tab_active" href="#">
                                    <i class="fas fa-code-branch"></i>@lang('menu.categories')</a>
                            </li>
                            <li>
                                <a id="tab_btn" data-show="locations" class="tab_btn " href="#">
                                    <i class="fas fa-th-large"></i>@lang('menu.terms_and_condition')</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @include('terms_and_condition.categories.bodyPartial.index')
            @include('terms_and_condition.terms_condition.bodyPartial.index')
        </div>
    </div>
    <form id="category_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>

    <form id="deleted_terms_condition_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>
        // $('.categories').hide();
    </script>
    @include('terms_and_condition.categories.asset_category_script')
    @include('terms_and_condition.terms_condition.script')
@endpush
