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
                    <h6>Assets Settings</h6>
                </div>
                <x-back-button />
            </div>
        </div>
        <div class="p-15">
            <div class="card">
                <div class="card-body">
                    <div class="tab_list_area">
                        <ul class="list-unstyled">

                            @can('asset_categories_view')
                                <li>
                                    <a data-show="categories" class="tab_btn tab_active" href="#">
                                        Categories</a>
                                </li>
                            @endcan

                            @can('asset_locations_view')
                                <li>
                                    <a data-show="locations" class="tab_btn" href="#">
                                        Locations</a>
                                </li>
                            @endcan

                            @can('asset_units_view')
                                <li>
                                    <a data-show="units" class="tab_btn" href="#">
                                        Units</a>
                                </li>
                            @endcan

                            @can('asset_manufacturer_view')
                                <li>
                                    <a data-show="manufacturers" class="tab_btn" href="#">
                                        Manufacturer</a>
                                </li>
                            @endcan


                            @can('asset_components_index')
                                <li>
                                    <a data-show="components" class="tab_btn" href="#">
                                        Components</a>
                                </li>
                            @endcan


                            @can('asset_licenses_categories_index')
                                <li>
                                    <a data-show="licenses_categories" class="tab_btn" href="#">
                                        Licenses Category</a>
                                </li>
                            @endcan


                        </ul>
                    </div>
                    @include('asset::categories.bodyPartial.index')
                    @include('asset::locations.bodyPartial.index')
                    @include('asset::units.bodyPartial.index')
                    @include('asset::manufacturers.bodyPartial.index')
                    @include('asset::components.bodyPartial.index')
                    @include('asset::licenses.category.bodyPartial.index')
                </div>
            </div>
        </div>
    </div>
    <form id="category_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="deleted_location_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="deleted_units_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="manufacturer_delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="components_delete_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
    <form id="licenses_category_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    <script>
        $(document).on('click', '.tab_btn', function(e) {
            e.preventDefault();
            $('.tab_btn').removeClass('tab_active');
            $('.tab_contant').addClass('d-none');
            var show_content = $(this).data('show');
            $('.' + show_content).removeClass('d-none');
            $(this).addClass('tab_active');
        });
    </script>
    @include('asset::categories.asset_category_script')
    @include('asset::locations.asset_location_script')
    @include('asset::units.asset_units_script')
    @include('asset::manufacturers.manufacturers_script')
    @include('asset::components.components_script')
    @include('asset::licenses.category.licenses_category_script')
@endpush
