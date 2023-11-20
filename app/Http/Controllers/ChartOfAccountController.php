<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\Converter;
use App\Utils\CustomerUtil;
use App\Utils\SupplierUtil;
use App\Utils\UserActivityLogUtil;

class ChartOfAccountController extends Controller
{
    protected $accountUtil;

    protected $converter;

    protected $userActivityLogUtil;

    protected $customerUtil;

    protected $supplierUtil;

    protected $accountLedgerUtil;

    public function __construct(
        AccountUtil $accountUtil,
        Converter $converter,
        UserActivityLogUtil $userActivityLogUtil,
        CustomerUtil $customerUtil,
        SupplierUtil $supplierUtil,
        AccountLedgerUtil $accountLedgerUtil,
    ) {
        $this->accountUtil = $accountUtil;
        $this->converter = $converter;
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->customerUtil = $customerUtil;
        $this->supplierUtil = $supplierUtil;
        $this->accountLedgerUtil = $accountLedgerUtil;
    }

    public function index()
    {
        if (! auth()->user()->can('chart_of_accounts_index')) {

            abort(403, 'Access Forbidden.');
        }

        return view('finance.accounting.chart_of_accounts.index');
    }

    public function chartAccountList()
    {
        if (! auth()->user()->can('chart_of_accounts_index')) {

            abort(403, 'Access Forbidden.');
        }

        $groups = AccountGroup::with(
            [
                'subgroupsAccounts:id,name,parent_group_id',
                'subgroupsAccounts.accounts:id,name,phone,account_number,account_group_id',
            ]
        )->select('id', 'name', 'parent_group_id')->where('is_main_group', 1)->cursor();

        return view('finance.accounting.chart_of_accounts.ajax_view.chart_of_accounts_list', compact('groups'));
    }
}
