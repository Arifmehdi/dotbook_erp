@extends('layout.master')
@section('title', 'Organogram - ')
<link rel="stylesheet" href="{{asset('plugins/treantjs/Treant.css')}}">
<link rel="stylesheet" href="{{asset('plugins/treantjs/customization/basic-example.css')}}">
@push('css')
<style>
.nodeExample1 img{
    width: unset;
}
#dragBox{
    width: 100%;
    margin: 20px auto;
    overflow: hidden;
    line-height: 25px;
    font-size: 14px;
}

#dragBox .chart{
    margin: 10px auto;
    width: fit-content;
    padding: 10px;
    cursor: grab;
    -moz-user-select:none;
    -webkit-user-select:none;
    -ms-user-select:none;
    -khtml-user-select:none;
    user-select:none;
}
.Treant > .node img{
    width: 65px;
}
.left-sidebar .fa,
.left-sidebar .fa-brands,
.left-sidebar .fa-duotone,
.left-sidebar .fa-light,
.left-sidebar .fa-regular,
.left-sidebar .fa-solid,
.left-sidebar .fa-thin,
.left-sidebar .fab,
.left-sidebar .fad,
.left-sidebar .fal,
.left-sidebar .far,
.left-sidebar .fas,
.left-sidebar .fat,
.shortcut-bar .fa,
.shortcut-bar .fa-brands,
.shortcut-bar .fa-duotone,
.shortcut-bar .fa-light,
.shortcut-bar .fa-regular,
.shortcut-bar .fa-solid,
.shortcut-bar .fa-thin,
.shortcut-bar .fab,
.shortcut-bar .fad,
.shortcut-bar .fal,
.shortcut-bar .far,
.shortcut-bar .fas,
.shortcut-bar .fat {
    line-height: unset;
}
</style>

@endpush

@section('content')
<div class="body-wraper">
    <div class="sec-name">
        <div class="section-header">
            <h6>{{ __('Company Organogram') }}</h6>
        </div>
        <x-back-button/>
    </div>
    <div id="dragBox">
        <div class="chart" id="basic-example"></div>
    </div>
</div>


@endsection

@push('js')
<script src="{{asset('plugins/treantjs/vendor/raphael.js')}}"></script>
<script src="{{asset('plugins/treantjs/Treant.js')}}"></script>
<script src="{{asset('plugins/dragjs/jquery.dragscroll.js')}}"></script>
{{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script> --}}
<script>

    $.ajax({
        url: "{{ route('hrm.v1.organogram.data') }}",
        type: 'GET',
        success: function(data){
            new Treant(data);
        },
    })

   // $('#dragBox .chart').dragScroll();

    $('.nodeExample1').on('click', function(event) {

    })

        // window.addEventListener("resize", setHeightToDragBox);

        // function setHeightToDragBox() {
        //     //$('#dragBox').css('height', document.body.clientHeight - 50);
        // }

</script>

@endpush
