@extends('layout.master')
@section('title','Import Employee - ')
@push('css')
@endpush
@section('content')
    @include('layout.partial._session_message')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <form id="add_employee_form" action="{{ route('hrm.employee-import.store') }}" enctype="multipart/form-data" method="POST">
                @csrf
                <section class="mt-5x">
                    <div class="row g-0">
                        <div class="col-12 p-0">
                            <div class="form_elemen">
                                <div class="sec-name">
                                    <h6>{{ __('Import Employee') }}</h6>
                                    <x-back-button />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid p-0">
                        <div class="p-15">
                            <div class="row">

                                <div class="col-12">
                                    <div class="form_element rounded m-0">
                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>@lang('menu.file_to_import') </b> </label>
                                                        <div class="col-8">
                                                            <input type="file" name="import_file" class="form-control" required>
                                                            <span class="error" style="color: red;">
                                                                {{ $errors->first('import_file') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <div class="col-8">
                                                            <button class="btn btn-sm btn-primary"><i class="fa-regular fa-cloud-arrow-up"></i> &nbsp; @lang('menu.upload')</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <label for="inputEmail3" class="col-4"><b>@lang('menu.download_sample') </b> </label>
                                                        <div class="col-8">
                                                            <a href="{{ asset('import_template/employee_import_demo.xlsx') }}" class="btn btn-sm btn-success" download><i class="fa-sharp fa-solid fa-download"></i> &nbsp; @lang('menu.download_template_click')</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_element rounded m-0 mt-2">
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
                    </div>
                </section>
            </form>
        </div>
    </div>
@endsection
@push('js')
<script>

</script>
@endpush
