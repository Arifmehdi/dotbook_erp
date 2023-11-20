@extends('layout.master')
@push('css')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="sec-name">
            <h6>Open Cash Register</h6>
            <x-back-button />
        </div>
        <div class="container-fluid p-0">
            <form action="{{ route('sales.cash.register.store') }}" method="POST">
                @csrf
                <section class="p-15">
                    <div class="form_element m-0">
                        <div class="element-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"> <b>@lang('menu.opening_balance') </b> <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-8">
                                            <input required type="number" step="any" name="cash_in_hand" class="form-control" placeholder="Enter Amount" value="0.00">
                                            <span class="error">{{ $errors->first('cash_in_hand') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.cash_counter') </b> </label>
                                        <div class="col-8">
                                            <select required name="counter_id" class="form-control form-select">
                                                <option value="">@lang('menu.sales') @lang('menu.cash_counter')</option>
                                                @foreach ($cashCounters as $cc)
                                                    <option {{ old('counter_id') == $cc->id ? 'SELECTED' : '' }} value="{{ $cc->id }}">
                                                        {{ $cc->counter_name . ' (' . $cc->short_name . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error">{{ $errors->first('counter_id') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.business_location') </b></label>
                                        <div class="col-8">
                                            <input readonly type="text" class="form-control" value="{{ json_decode($generalSettings->business, true)['shop_name'] }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <label class="col-4"><b>@lang('menu.sale_account') </b> </label>
                                        <div class="col-8">
                                            <select required name="sale_account_id" class="form-control add_input form-select" id="sale_account_id" data-name="Sale A/c">
                                                @foreach ($saleAccounts as $saleAccount)
                                                    <option value="{{ $saleAccount->id }}">
                                                        {{ $saleAccount->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="error">{{ $errors->first('sale_account_id') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="submitBtn">
                                <div class="row justify-content-center">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-sm px-3 btn-success ">
                                            @lang('menu.submit')
                                        </button>
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
@push('scripts')
@endpush
