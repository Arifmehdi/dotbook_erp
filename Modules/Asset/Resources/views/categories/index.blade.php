@extends('layout.master')
@push('css')
    <link href="{{ asset('css/tab.min.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('title', 'All Assets/Categories - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div class="name-head">
                    <h6>Asset Categories</h6>
                </div>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm btn-info float-end">
                    <i class="fas fa-long-arrow-alt-left text-white"></i> Back
                </a>
            </div>
        </div>


        @include('asset::categories.bodyPartial.index')
    </div>

    <form id="category_deleted_form" action="" method="post">
        @method('DELETE')
        @csrf
    </form>
@endsection
@push('scripts')
    @include('asset::categories.asset_category_script')
@endpush
