@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="container-fluid p-0">
        {{-- Header --}}
        <div class="sec-name">
            <div class="section-header">
                <h6>{{ __('Import Attendance') }}</h6>
            </div>
            <x-back-button/>
        </div>

        <div class="p-15">
            <div class="card mb-1">
                <div class="card-header">
                    <h6>Import Attendance From Text File</h6>
                </div>
                <div class="card-body">
                    <div class="row g-0">
                        <div class="col-xxl-6 col-lg-8">

                            <form action="{{  route('hrm.bulk_attendance_imports.from_text_file') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-2">
                                    <input type="file" class="form-control" name="file" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-5">
                                        <button class="btn btn-sm btn-success "> <i class="fa-sharp fa-solid fa-upload"></i> &nbsp; Import Attendance </button>
                                        <button type="btn" class="btn loading_button  d-none"><i class="fas fa-spinner"></i><b> Loading...</b></button>
                                    </div>
                                    <div  class="col-md-5 mr-0">
                                        <a href="{{ asset('import_template/AttendanceExample.txt') }}" target="_blank" style="float:right">See Import Guide</a>
                                    </div>
                                </div>
                                {{-- <div class="form-group mb-2">
                                    <input type="submit" class="btn btn-sm btn-primary">
                                </div> --}}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form_element rounded m-0">
                <div class="element-body">
                    <div class="heading">
                        <h4>@lang('menu.instructions')</h4>
                    </div>
                    <div class="top_note">
                        <p class="p-0 m-0"><b>@lang('menu.follow_instruct_import')</b></p>
                        <p>@lang('menu.column_follow_order')</p>
                    </div>

                    <div class="instruction_table"></div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('js')
<script>

</script>
@endpush
