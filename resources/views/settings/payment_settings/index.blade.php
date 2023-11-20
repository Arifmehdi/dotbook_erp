@extends('layout.master')
@push('css')
@endpush
@section('content')
    <div class="body-wraper">
        <div class="container-fluid p-0">
            <div class="sec-name">
                <h6>@lang('menu.payment_method') @lang('menu.settings') </h6>
                <a href="{{ url()->previous() }}" class="btn text-white btn-sm float-end back-button"><i
                        class="fa-thin fa-left-to-line fa-2x"></i><br> @lang('menu.back')</a>
            </div>
            <section class="p-15">
                <div class="row">
                    <div class="col-12">
                        <div class="form_element rounded m-0">

                            <div class="element-body">
                                <form id="payment_method_settings_form"
                                    action="{{ route('settings.payment.method.settings.update') }}" method="POST">
                                    @csrf
                                    <p class="m-0 p-0"><b>@lang('menu.business_location') :</b>
                                        {{ json_decode($generalSettings->business, true)['shop_name'] }}
                                    </p>
                                    <div class="form_element">
                                        <div class="element-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-sm">
                                                                <thead>
                                                                    <th class="text-startx">@lang('menu.serial')</th>
                                                                    <th class="text-startx">@lang('menu.payment_method')</th>
                                                                    <th class="text-startx">@lang('menu.default_account')</th>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($methods as $method)
                                                                        <tr>
                                                                            <td class="text-start">
                                                                                <b>{{ $loop->index + 1 }}.</b>
                                                                            </td>
                                                                            <td class="text-start">
                                                                                {{ $method->name }}
                                                                                <input type="hidden" name="method_ids[]"
                                                                                    value="{{ $method->id }}">
                                                                            </td>
                                                                            <td class="text-start">
                                                                                <select name="account_ids[]"
                                                                                    class="form-control form-select">
                                                                                    <option value="">None</option>
                                                                                    @foreach ($accounts as $ac)
                                                                                        @php
                                                                                            $presettedAc = DB::table('payment_method_settings')
                                                                                                ->where('payment_method_id', $method->id)
                                                                                                ->where('account_id', $ac->id)
                                                                                                ->first();
                                                                                        @endphp
                                                                                        <option
                                                                                            {{ $presettedAc ? 'SELECTED' : '' }}
                                                                                            value="{{ $ac->id }}">
                                                                                            @php
                                                                                                $acNo = $ac->account_number ? ', (A/c No : ' . $ac->account_number . ')' : ', (A/c No : N/A';
                                                                                                $bank = $ac->bank ? ', (Bank : ' . $ac->bank . ')' : ', (Bank : N/A)';
                                                                                            @endphp
                                                                                            {{ $ac->name . $acNo . $bank }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <td colspan="2"></td>
                                                                        <td class="d-flex justify-content-end">
                                                                            <div class="loading-btn-box">
                                                                                <button type="button"
                                                                                    class="btn btn-sm loading_button display-none"><i
                                                                                        class="fas fa-spinner"></i></button>
                                                                                <button type="submit"
                                                                                    class="btn btn-success submit_button">@lang('menu.save')</button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>

                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Add user by ajax
        $('#payment_method_settings_form').on('submit', function(e) {
            e.preventDefault();
            $('.loading_button').show();
            var url = $(this).attr('action');
            var request = $(this).serialize();
            $.ajax({
                url: url,
                type: 'post',
                data: request,
                success: function(data) {

                    toastr.success(data);
                    $('.loading_button').hide();
                }
            });
        });
    </script>
@endpush
