<style>
    @media print
    {
        table { page-break-after:auto }
        tr    { page-break-inside:avoid; page-break-after:auto }
        td    { page-break-inside:avoid; page-break-after:auto }
        thead { display:table-header-group }
        tfoot { display:table-footer-group }
    }

    div#footer {position:fixed; bottom:25px;left:0px;width:100%;height:0%;color:#CCC;background:#333; padding: 0; margin: 0;}

    @page {size:a4;margin-top: 0.8cm; margin-bottom: 35px; margin-left: 10px;margin-right: 10px;}
    .header, .header-space,
    .footer, .footer-space {height: 20px;}
    .header {position: fixed; top: 0;}
    .footer {position: fixed;bottom: 0;}
    .noBorder {border: 0px !important;}
    tr.noBorder td {border: 0px !important;}
    tr.noBorder {border: 0px !important;border-left: 1px solid transparent;border-bottom: 1px solid transparent;}

    h6 { font-size: 16px; }
    p {  font-size: 14px; }
    td {  color: black; }
</style>

@php
    $timeFormat = json_decode($generalSettings->business, true)['time_format'] == '24' ? 'H:i:s' : 'h:i:s a';
    $allTotalSale = 0;
    $allTotalReturn = 0;
    $allTotalReceived = 0;
    $allTotalOpDue = 0;
    $allTotalDue = 0;
    $allTotalReturnDue = 0;
@endphp

<div class="row">
    <div class="col-md-12 text-center">
        <div class="heading_area" style="border-bottom: 1px solid black;">
            <img style="height: auto; width:200px;" src="{{ asset('uploads/business_logo/' . json_decode($generalSettings->business, true)['business_logo']) }}" alt="logo" class="logo__img">
            <p style="text-transform: uppercase;"><strong>{{ json_decode($generalSettings->business, true)['shop_name'] }}</strong></p>
            <p style="width: 60%; margin:0 auto;">{{ json_decode($generalSettings->business, true)['address'] }}</p>
            <p style="width: 60%; margin:0 auto;"><strong>@lang('menu.phone') : </strong> {{ json_decode($generalSettings->business, true)['phone'] }}</p>
        </div>

        <h6 style="margin-top: 10px;"><strong>@lang('menu.customer_report') </strong></h6>
    </div>
</div>

<div class="row">
    <p><strong>@lang('menu.filtered_sr') : </strong>
        @php
            $user_name = '';
        @endphp
        @if (!auth()->user()->can('view_own_sale'))

            @if ($user_id)

                @php
                    $user = DB::table('users')->where('id', $user_id)->select('id', 'prefix', 'name', 'last_name', 'phone')->first();
                    $user_name = $user->prefix.' '.$user->name.' '.$user->last_name.'/'.$user->phone;
                @endphp
                @lang('menu.sr') {{ $user->prefix.' '.$user->name.' '.$user->last_name.'/'.$user->phone }}
            @else

                @lang('menu.all') @lang('menu.sr')
            @endif
        @else

            @php
                $user_name = auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name.'/'.auth()->user()->phone
            @endphp
            @lang('menu.sr') {{ auth()->user()->prefix.' '.auth()->user()->name.' '.auth()->user()->last_name.'/'.auth()->user()->phone}}
        @endif
    </p>
</div>

<div class="row" style="margin-top: 15px;">
    <div class="col-12">
        <table class="table report-table table-sm table-bordered print_table">
            <thead>
                <tr>
                    <th class="text-start">@lang('menu.customer')</th>
                    <th class="text-end">@lang('menu.opening_balance')</th>
                    <th class="text-end">@lang('menu.total_sale')</th>
                    <th class="text-end">@lang('menu.total_return')</th>
                    <th class="text-end">@lang('menu.total_collection')</th>
                    <th class="text-end">@lang('menu.total_due')</th>
                    <th class="text-end">@lang('menu.total_refundable')</th>
                </tr>
            </thead>
            <tbody class="sale_print_product_list">
                @php $userAmount = new App\Utils\UserWiseCustomerAmountUtil(); @endphp
                @foreach ($customerReports as $customer)
                    <tr><td colspan="7">{!! '<strong>'.$customer->name.'<strong>'.'-<b>'.$customer->phone.'</b>' !!}</td></tr>
                    @php
                        $totalSale = 0;
                        $totalReturn = 0;
                        $totalReceived = 0;
                        $totalOpDue = 0;
                        $totalDue = 0;
                        $totalReturnDue = 0;
                    @endphp

                    @if ($user_id)
                        @php
                            $amount = $userAmount->userWiseCustomerAmountSummery($customer->id, $customer->customer_account_id, $user_id, openingBlAndCrLimit : false );
                        @endphp

                        <tr>
                            <td>@lang('menu.sr') {{ $user_name }}</td>
                            <td class="text-end">
                                @php
                                    $formattedOpeningBalance = App\Utils\Converter::format_in_bdt($amount['opening_balance']);
                                    $showOpeningBalance = $formattedOpeningBalance < 0 ? Str::of($formattedOpeningBalance)->replace('-', '')->wrap('(', ')') : $formattedOpeningBalance;
                                @endphp
                                {{ $showOpeningBalance }}
                            </td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_sale']) }}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_return']) }}</td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_received']) }}</td>
                            <td class="text-end">
                                @php
                                    $formattedTotalSaleDue = App\Utils\Converter::format_in_bdt($amount['total_sale_due']);
                                    $showTotalSaleDue = $formattedTotalSaleDue < 0 ? Str::of($formattedTotalSaleDue)->replace('-', '')->wrap('(', ')') : $formattedTotalSaleDue;
                                @endphp

                                {{ $showTotalSaleDue }}
                            </td>
                            <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_sale_return_due']) }}</td>
                        </tr>
                        @php
                            $totalSale += $amount['total_sale'];
                            $totalReturn += $amount['total_return'];
                            $totalReceived += $amount['total_received'];
                            $totalOpDue += $amount['opening_balance'];
                            $totalDue += $amount['total_sale_due'];
                            $totalReturnDue += $amount['total_sale_return_due'];
                        @endphp
                    @else
                        @php
                            $users = DB::table('account_ledgers')->where('account_ledgers.account_id', $customer->customer_account_id)
                            ->leftJoin('users', 'account_ledgers.user_id', 'users.id')
                            ->select('account_ledgers.user_id', 'users.prefix', 'users.name', 'users.last_name', 'users.phone')->distinct()->get();
                        @endphp

                        @foreach ($users as $user)

                            @php
                                $amount = $userAmount->userWiseCustomerAmountSummery($customer->id, $customer->customer_account_id, $user->user_id, openingBlAndCrLimit : false);
                            @endphp

                            <tr>
                                <td>@lang('menu.sr') {{ $user->prefix.' '.$user->name.' '.$user->last_name.'/'.$user->phone }}</td>
                                <td class="text-end">
                                    @php
                                        $formattedOpeningBalance = App\Utils\Converter::format_in_bdt($amount['opening_balance']);
                                        $showOpeningBalance = $formattedOpeningBalance < 0 ? Str::of($formattedOpeningBalance)->replace('-', '')->wrap('(', ')') : $formattedOpeningBalance;
                                    @endphp
                                    {{ $showOpeningBalance }}
                                </td>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_sale']) }}</td>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_return']) }}</td>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_paid']) }}</td>
                                <td class="text-end">
                                    @php
                                        $formattedTotalSaleDue = App\Utils\Converter::format_in_bdt($amount['total_sale_due']);
                                        $showTotalSaleDue = $formattedTotalSaleDue < 0 ? Str::of($formattedTotalSaleDue)->replace('-', '')->wrap('(', ')') : $formattedTotalSaleDue;
                                    @endphp

                                    {{ $showTotalSaleDue }}
                                </td>
                                <td class="text-end">{{ App\Utils\Converter::format_in_bdt($amount['total_sale_return_due']) }}</td>
                            </tr>

                            @php
                                $totalSale += $amount['total_sale'];
                                $totalReturn += $amount['total_return'];
                                $totalReceived += $amount['total_received'];
                                $totalOpDue += $amount['opening_balance'];
                                $totalDue += $amount['total_sale_due'];
                                $totalReturnDue += $amount['total_sale_return_due'];
                            @endphp
                        @endforeach
                    @endif
                    @php
                        $allTotalSale += $totalSale;
                        $allTotalReturn += $totalReturn;
                        $allTotalReceived += $totalReceived;
                        $allTotalOpDue += $totalOpDue;
                        $allTotalDue += $totalDue;
                        $allTotalReturnDue += $totalReturnDue;
                    @endphp
                    <tr>
                        <th class="text-end">@lang('menu.total') :</th>
                        <th class="text-end">
                            @php
                                $formattedTotalOpDue = App\Utils\Converter::format_in_bdt($totalOpDue);
                                $showTotalOpDue = $formattedTotalOpDue < 0 ? Str::of($formattedTotalOpDue)->replace('-', '')->wrap('(', ')') : $formattedTotalOpDue;
                            @endphp
                            {{ $showTotalOpDue }}
                        </th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($totalSale) }}</th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReturn) }}</th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReceived) }}</th>
                        <th class="text-end">
                            @php
                                $formattedTotalDue = App\Utils\Converter::format_in_bdt($totalDue);
                                $showTotalDue = $formattedTotalDue < 0 ? Str::of($formattedTotalDue)->replace('-', '')->wrap('(', ')') : $formattedTotalDue;
                            @endphp
                            {{ $showTotalDue }}
                        </th>
                        <th class="text-end">{{ App\Utils\Converter::format_in_bdt($totalReturnDue) }}</th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-6"></div>
    <div class="col-6">
        <table class="table report-table table-sm table-bordered print_table">
            <tbody>
                <tr>
                    <th class="text-end">@lang('menu.opening_balance') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $formattedAllTotalOpDue = App\Utils\Converter::format_in_bdt($allTotalOpDue);
                            $showAllTotalOpDue = $formattedAllTotalOpDue < 0 ? Str::of($formattedAllTotalOpDue)->replace('-', '')->wrap('(', ')') : $formattedAllTotalOpDue;
                        @endphp
                        {{ $showAllTotalOpDue }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_sale') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalSale) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_return') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturn) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_collection') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReceived) }}</td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_receivable') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">
                        @php
                            $formattedAllTotalDue = App\Utils\Converter::format_in_bdt($allTotalDue);
                            $showAllTotalDue = $formattedAllTotalDue < 0 ? Str::of($formattedAllTotalDue)->replace('-', '')->wrap('(', ')') : $formattedAllTotalDue;
                        @endphp
                        {{ $showAllTotalDue }}
                    </td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.total_returnable') : {{ json_decode($generalSettings->business, true)['currency'] }}</th>
                    <td class="text-end">{{ App\Utils\Converter::format_in_bdt($allTotalReturnDue) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div id="footer">
    <div class="row">
        <div class="col-4 text-start">
            <small>@lang('menu.print_date') : {{ date(json_decode($generalSettings->business, true)['date_format']) }}</small>
        </div>

        <div class="col-4 text-center">
            @if (config('company.print_on_sale'))
                <small>@lang('menu.powered_by') <b>@lang('menu.speedDigit_software_solution').</b></small>
            @endif
        </div>

        <div class="col-4 text-end">
            <small>@lang('menu.print_time') :{{ date($timeFormat) }}</small>
        </div>
    </div>
</div>
