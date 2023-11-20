<div class="account_summary_area">
    <div class="heading">
        <h5 class="py-1 pl-1 text-center">@lang('menu.account_summary')</h5>
    </div>

    <div class="account_summary_table">
        <table class="table modal-table table-sm">
            <tbody>
                <tr>
                    <th class="text-end"></th>
                    <th class="text-end">@lang('menu.debit')</th>
                    <th class="text-end">@lang('menu.credit')</th>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.opening_balance') :</th>
                    <th class="text-end debit_opening_balance" id="ledger_debit_opening_balance"></th>
                    <th class="text-end credit_opening_balance" id="ledger_credit_opening_balance"></th>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.current_total') :</th>
                    <th class="text-end debit" id="ledger_total_debit"></th>
                    <th class="text-end credit" id="ledger_total_credit"></th>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.closing_balance')  :</th>
                    <th class="text-end debit_closing_balance" id="ledger_debit_closing_balance"></th>
                    <th class="text-end credit_closing_balance" id="ledger_credit_closing_balance"></th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
