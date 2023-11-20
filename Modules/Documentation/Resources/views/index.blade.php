@extends('layout.master')

@push('css')
<style>
    .sub_nav {
        /* left: -201px !important; */
    }

</style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container-fluid p-0">
        <div class="sec-name">
            <h6>Documentations</h6>
            <x-back-button />
        </div>
        <div class="p-15">
            <div class="form_element rounded">
                <div class="list-group">
                    <li class="list-group-item">
                        <a href="#">User Manual</a>
                    </li>

                    <li class="list-group-item">
                        <a href="{{ route('documentation.scale.index')}}">Scale Documentation</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Deveoper Documentation</a>
                    </li>

                    @if(config('app.debug'))
                    <li class="list-group-item">
                        <a href="{{ route('documentation.developer_change_log') }}">Deveoper Change Log</a>
                    </li>
                    @endif

                    <li class="list-group-item">
                        <a href="#">API Documentation</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Software Change Log</a>
                    </li>
                    <li class="list-group-item">
                        <a href="#">Support and FAQ</a>
                    </li>

                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="{{ asset('js/script.js') }}"></script> --}}
<script>
    // toggleSecondNav();
</script>
@endsection
