@extends('layout.master')
@push('css')
    <style>
        .top-menu-area ul li {display: inline-block;margin-right: 3px;}
        .top-menu-area a {border: 1px solid lightgray;padding: 1px 5px;border-radius: 3px;font-size: 11px;}
    </style>
@endpush
@section('title', 'HRM Leaves - ')
@section('content')
    <div class="body-wraper">
        <div class="main__content">
            <div class="sec-name">
                <div>
                    <h6>@lang('menu.manufacturing_settings')</h6>
                </div>
                <x-all-buttons>
                    @if (auth()->user()->can('process_view'))
                    <div>
                        <a href="{{ route('manufacturing.process.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin  fa-dumpster-fire  fa-2x"></i><br> @lang('menu.process')</span></a>
                    </div>
                    @endif
                    @if (auth()->user()->can('production_view'))
                    <div>
                        <a href="{{ route('manufacturing.productions.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-shapes fa-2x"></i><br> @lang('menu.productions')</span></a>
                    </div>
                    @endif
                    @if (auth()->user()->can('manuf_settings'))
                    <div>
                        <a href="{{ route('manufacturing.settings.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-sliders fa-2x"></i><br> @lang('menu.manufacturing_setting')</span></a>
                    </div>
                    @endif
                    @if (auth()->user()->can('manuf_report'))
                    <div>
                        <a href="{{ route('manufacturing.report.index') }}" class="text-white btn text-white btn-sm"><span><i class="fa-thin fa-file-lines fa-2x"></i><br> @lang('menu.manufacturing_report')</span></a>
                    </div>
                    @endif
                    <x-help-button />
                </x-all-buttons>
            </div>
        </div>

        <div class="p-15">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="section-header">
                            <div class="col-md-6">
                                <h6>@lang('menu.settings')</h6>
                            </div>
                        </div>

                        <form id="update_settings_form" action="{{ route('manufacturing.settings.store') }}" method="post" class="p-3">
                            @csrf
                            <div class="form-group row">
                                <div class="col-md-3">
                                    <label><strong>@lang('menu.production_reference_prefix') </strong></label>
                                    @php
                                        $voucherPrefix = '';
                                        if(isset(json_decode($generalSettings->mf_settings, true)['production_ref_prefix'])){
                                            $voucherPrefix = json_decode($generalSettings->mf_settings, true)['production_ref_prefix'];
                                        }
                                    @endphp
                                    <input type="text" name="production_ref_prefix" class="form-control"
                                        autocomplete="off" placeholder="Production Reference prefix"
                                        value="{{ $voucherPrefix }}">
                                </div>

                                <div class="col-md-4">
                                    <div class="row mt-1">
                                        <p class="checkbox_input_wrap mt-4">
                                            <input type="checkbox"
                                                @if(isset(json_decode($generalSettings->mf_settings, true)['enable_editing_ingredient_qty']))
                                                    {{ json_decode($generalSettings->mf_settings, true)['enable_editing_ingredient_qty'] == '1' ? 'CHECKED' : '' }}
                                                @endif
                                                name="enable_editing_ingredient_qty"> &nbsp; <b>@lang('menu.enable_editing_ingredients_quantity_in_production')</b>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="row mt-1">
                                        <p class="checkbox_input_wrap mt-4">
                                            <input type="checkbox"
                                                @if(isset(json_decode($generalSettings->mf_settings, true)['enable_updating_product_price']))
                                                    {{ json_decode($generalSettings->mf_settings, true)['enable_updating_product_price'] == '1' ? 'CHECKED' : '' }}
                                                @endif
                                                name="enable_updating_product_price"> &nbsp; <b>@lang('menu.update_selling_finalizing_production')</b>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <div class="loading-btn-box">
                                        <button type="button" class="btn btn-sm loading_button display-none"><i class="fas fa-spinner"></i></button>
                                        <button class="btn w-auto btn-success submit_button float-end">@lang('menu.save_change')</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

<script>
    // Setup ajax for csrf token.
    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    // call jquery method
    $(document).ready(function(){
        // Update settings by ajax
        $('#update_settings_form').on('submit', function(e){
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url:url,
                type:'post',
                data: request,
                success:function(data){
                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    });
</script>
@endpush
