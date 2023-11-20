@extends('layout.master')
@push('css')
@endpush
@section('content')
<div class="body-wraper">
    <div class="main__content">
        <div class="sec-name">
            <div class="name-head">
                <h5>@lang('menu.create_permission')</h5>
            </div>
            <div> <a href="{{ url()->previous() }}" class="btn text-white btn-sm  float-end back-button"><i class="fa-thin fa-left-to-line fa-2x"></i><br>@lang('menu.back')</a></div>
        </div>
    </div>

    <div class="row px-3 mt-1">
        <div class="col-md-4">
            <div class="card" id="add_form">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>@lang('menu.create_permission')</h6>
                    </div>
                </div>

                <div class="form-area px-3 pb-2">
                    <form  action="{{ route('debug.permission_gui.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label><b>@lang('menu.name') </b> <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control add_input" data-name="Permission name" id="name" placeholder="Permission Name" />
                                <span class="error error_name"></span>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <div class="col-md-12 d-flex justify-content-end">
                                <div class="loading-btn-box">
                                    <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                    <button type="submit" class="btn btn-sm btn-success float-end">@lang('menu.save')</button>
                                    <button type="reset" class="btn btn-sm btn-danger float-end me-2">@lang('menu.reset')</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="section-header">
                    <div class="col-md-6">
                        <h6>@lang('menu.all') @lang('menu.permission')</h6>
                    </div>
                </div>
                <!--begin: Datatable-->
                <div class="">
                    <div class="data_preloader">
                        <h6><i class="fas fa-spinner text-primary"></i> @lang('menu.processing')</h6>
                    </div>
                    <div class="">
                        <div class="table-responsive h-350" id="data-list">
                            <table class="display data_tbl data__table">
                                <thead>
                                    <tr>
                                        <th class="text-startx">@lang('menu.serial')</th>
                                        <th class="text-startx">@lang('menu.name')</th>
                                        <th class="text-startx">@lang('menu.actions')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($permission_gui as $key => $pg)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $pg->name }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <form id="deleted_form" action="" method="post">
                    @method('DELETE')
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    // Get all category by ajax
    // function getAllCateogry(){
    //     $('.data_preloader').show();
    //     $.ajax({
    //         url:"{{ route('debug.permission_gui.index') }}",
    //         type:'get',
    //         success:function(data){
    //             $('#data-list').html(data);
    //             $('.data_preloader').hide();
    //         }
    //     });
    // }
    // getAllCateogry();

    // $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // $(document).ready(function(){
    //     $('#add_category_form').on('submit', function(e){
    //         e.preventDefault();
    //         $('.loading_button').show();
    //         var url = $(this).attr('action');
    //         var request = $(this).serialize();
    //         var inputs = $('.add_input');
    //             $('.error').html('');
    //             var countErrorField = 0;
    //         $.each(inputs, function(key, val){
    //             var inputId = $(val).attr('id');
    //             var idValue = $('#'+inputId).val();
    //             if(idValue == ''){
    //                 countErrorField += 1;
    //                 var fieldName = $('#'+inputId).data('name');
    //                 $('.error_'+inputId).html(fieldName+' is required.');
    //             }
    //         });

    //         if(countErrorField > 0){
    //             $('.loading_button').hide();
    //             return;
    //         }

    //         $.ajax({
    //             url:url,
    //             type:'post',
    //             data: request,
    //             success:function(data){
    //                 toastr.success(data);
    //                 $('#add_category_form')[0].reset();
    //                 $('.loading_button').hide();
    //                 getAllCateogry();
    //             }
    //         });
    //     });
    // });

</script>
@endpush
