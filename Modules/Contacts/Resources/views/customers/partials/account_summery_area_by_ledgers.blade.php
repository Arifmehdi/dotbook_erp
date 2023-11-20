<div class="account_summary_area">
    <div class="heading py-1">
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
                    <td class="text-end fw-bold debit_opening_balance" id="ledger_debit_opening_balance"></td>
                    <td class="text-end fw-bold credit_opening_balance" id="ledger_credit_opening_balance"></td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.current_total') :</th>
                    <td class="text-end fw-bold debit" id="ledger_total_debit"></td>
                    <td class="text-end fw-bold credit" id="ledger_total_credit"></td>
                </tr>

                <tr>
                    <th class="text-end">@lang('menu.closing_balance')  :</th>
                    <td class="text-end fw-bold debit_closing_balance text-success fw-bold" id="ledger_debit_closing_balance"></td>
                    <td class="text-end fw-bold credit_closing_balance text-danger fw-bold" id="ledger_credit_closing_balance"></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
