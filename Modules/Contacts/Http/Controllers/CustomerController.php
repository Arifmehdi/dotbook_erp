<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\FileUploaderServiceInterface;
use App\Models\AccountGroup;
use App\Models\Customer;
use App\Utils\AccountLedgerUtil;
use App\Utils\AccountUtil;
use App\Utils\CustomerContactPersonUtil;
use App\Utils\CustomerDetailsUtil;
use App\Utils\CustomerOpeningBalanceUtil;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use App\Utils\UserWiseCustomerAmountUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerUtil $customerUtil,
        private AccountUtil $accountUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil,
        private UserWiseCustomerAmountUtil $userWiseCustomerAmountUtil,
        private CustomerDetailsUtil $customerDetailsUtil,
        private CustomerContactPersonUtil $customerContactPersonUtil,
        private CustomerOpeningBalanceUtil $customerOpeningBalanceUtil,
        private AccountLedgerUtil $accountLedgerUtil,
    ) {
    }

    public function index(Request $request)
    {
        if (! auth()->user()->can('customer_all')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            return $this->customerUtil->customerListTable($request);
        }

        $total = [
            'customer' => DB::table('customers')->count(),
            'active_customer' => DB::table('customers')->where('status', 1)->count(),
            'inactive_customer' => DB::table('customers')->where('status', 0)->count(),
        ];

        $users = '';
        if (auth()->user()->is_marketing_user == 0) {

            $users = DB::table('users')->select('id', 'prefix', 'name', 'last_name', 'phone')->get();
        }

        return view('contacts::customers.index', compact('total', 'users'));
    }

    public function basicModal()
    {
        if (! auth()->user()->can('customer_add')) {

            abort(403, 'Access Forbidden.');
        }

        $srUsers = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('contacts::customers.ajax_view.customer_create_basic_modal', compact('srUsers'));
    }

    public function detailedModal()
    {
        if (! auth()->user()->can('customer_add')) {

            abort(403, 'Access Forbidden.');
        }

        $groups = DB::table('customer_groups')->get();
        $srUsers = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('contacts::customers.ajax_view.customer_create_detailed_modal', compact('groups', 'srUsers'));
    }

    public function changeStat()
    {
        $statusChan = [
            'customer' => DB::table('customers')->count(),
            'active_customer' => DB::table('customers')->where('status', 1)->count(),
            'inactive_customer' => DB::table('customers')->where('status', 0)->count(),
        ];

        return response()->json($statusChan);
    }

    public function store(Request $request, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('customer_add')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,',
            'nid_no' => 'nullable|unique:customers,nid_no,',
            'trade_license_no' => 'nullable|unique:customers,trade_license_no,',
        ]);

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business', 'prefix')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $addons = DB::table('addons')->select('branches')->first();

            $user_id = $request->user_id ? $request->user_id : auth()->user()->id;
            $addCustomer = $this->customerUtil->addCustomer($request, $this->invoiceVoucherRefIdUtil, $gs);
            $this->customerDetailsUtil->addCustomerDetails($request, $addCustomer, $fileUploaderService);
            $this->customerContactPersonUtil->addCustomerContactPersons($addCustomer, $request);

            $customerAccountGroup = AccountGroup::where('sub_sub_group_number', 6)->where('is_reserved', 1)->first();
            $request->account_group_id = $customerAccountGroup->id;
            $addAccount = $this->accountUtil->addAccount($request, $addCustomer->id);
            $addCustomer->customer_account_id = $addAccount->id;

            if (isset($request->sr_user_ids)) {

                $index = 0;
                foreach ($request->sr_user_ids as $sr_user_id) {

                    if ($sr_user_id) {

                        $this->customerOpeningBalanceUtil->addCustomerOpeningBalance(customer_id: $addCustomer->id, account_id: $addAccount->id, opening_balance: $request->sr_opening_balances[$index], opening_balance_type: $request->sr_opening_balance_types[$index], user_id: $sr_user_id);

                        $this->accountLedgerUtil->addAccountLedger(
                            voucher_type_id: 0,
                            date: $openingBalanceDate,
                            account_id: $addAccount->id,
                            trans_id: $addAccount->id,
                            amount: $request->sr_opening_balances[$index] ? $request->sr_opening_balances[$index] : 0,
                            amount_type: $request->sr_opening_balance_types[$index],
                            user_id: $sr_user_id,
                        );
                    }
                    $index++;
                }
            }

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 1, data_obj: $addCustomer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCustomer;
    }

    public function viewCustomer($customerId)
    {
        $customer = Customer::with('customer_group', 'customerDetails', 'customerContactPersons')->where('id', $customerId)->firstOrFail();

        return view('contacts::customers.show', compact('customer'));
    }

    public function viewCustomerPdf($customerId)
    {
        $customer = Customer::with('customer_group', 'customerDetails', 'customerContactPersons')->where('id', $customerId)->firstOrFail();
        $pdf = PDF::loadview('contacts::customers.view-customer-pdf', compact('customer'));
        $pdf->stream("{$customer->name}-view.pdf");
    }

    public function edit($customerId)
    {
        if (! auth()->user()->can('customer_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $customer = Customer::with('customer_group', 'customerDetails', 'customerContactPersons', 'openingBalances')
            ->where('id', $customerId)->first();

        $groups = DB::table('customer_groups')->get();
        $srUsers = DB::table('users')->where('is_marketing_user', 1)->select('id', 'prefix', 'name', 'last_name')->get();

        return view('contacts::customers.ajax_view.edit', compact('customer', 'groups', 'srUsers'));
    }

    public function update(Request $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        if (! auth()->user()->can('customer_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,'.$id,
            'nid_no' => 'nullable|unique:customers,nid_no,'.$id,
            'trade_license_no' => 'nullable|unique:customers,trade_license_no,'.$id,
        ]);

        try {

            DB::beginTransaction();

            $gs = DB::table('general_settings')->select('business')->first();
            $openingBalanceDate = date('Y-m-d', strtotime(json_decode($gs->business, true)['start_date']));

            $customer = Customer::with('customerDetails', 'customerContactPersons', 'account')->where('id', $id)->first();
            $updateCustomer = $this->customerUtil->updateCustomer($request, $customer);

            $this->customerDetailsUtil->updateCustomerDetails($request, $customer, $fileUploaderService);
            $this->customerContactPersonUtil->updateCustomerContactPersons($customer, $request);

            $account = '';
            $customerAccountGroup = AccountGroup::where('sub_sub_group_number', 6)->where('is_reserved', 1)->first();
            if ($customer->account) {
                $request->account_group_id = $customerAccountGroup->id;
                $account = $this->accountUtil->updateAccount($request, $customer->account);
            } else {
                $account = $this->accountUtil->addAccount($request, $customer->id);
                $request->account_group_id = $customerAccountGroup->id;
            }

            if (isset($request->sr_user_ids)) {

                $index = 0;
                foreach ($request->sr_user_ids as $sr_user_id) {

                    if ($sr_user_id) {

                        $this->customerOpeningBalanceUtil->updateCustomerOpeningBalance(customer_id: $updateCustomer->id, account_id: $account->id, opening_balance: $request->sr_opening_balances[$index], opening_balance_type: $request->sr_opening_balance_types[$index], user_id: $sr_user_id);

                        $this->accountLedgerUtil->updateAccountLedger(
                            voucher_type_id: 0,
                            date: $openingBalanceDate,
                            account_id: $account->id,
                            trans_id: $account->id,
                            amount: $request->sr_opening_balances[$index] ? $request->sr_opening_balances[$index] : 0,
                            amount_type: $request->sr_opening_balance_types[$index],
                            user_id: $sr_user_id,
                        );
                    }
                    $index++;
                }
            }

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $customer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Customer updated successfully');
    }

    public function delete(Request $request, $customerId)
    {
        if (! auth()->user()->can('customer_delete')) {
            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();

            $deleteCustomer = Customer::with(['account', 'account.accountLedgersWithOutOpeningBalances'])->where('id', $customerId)->first();
            $account = $deleteCustomer?->account;
            $ledgers = $deleteCustomer?->account?->accountLedgersWithOutOpeningBalances;

            if (isset($ledgers) && count($ledgers) > 0) {

                return response()->json(['errorMsg' => 'Customer can\'t be deleted. One or more entry has been created in the ledger.']);
            }

            if (! is_null($deleteCustomer)) {

                $this->userActivityLogUtil->addLog(action: 3, subject_type: 1, data_obj: $deleteCustomer);
                $deleteCustomer->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        DB::statement('ALTER TABLE customers AUTO_INCREMENT = 1');

        return response()->json('Customer deleted successfully');
    }

    // Change status method
    public function changeStatus($customerId)
    {
        if (! auth()->user()->can('customer_status_change')) {
            return response()->json(['errorMsg' => 'Access Forbidden']);
        }

        $statusChange = Customer::where('id', $customerId)->first();

        if ($statusChange->status == 1) {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $statusChange);
            $statusChange->status = 0;
            $statusChange->save();

            return response()->json('Customer deactivated successfully');
        } else {

            $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $statusChange);
            $statusChange->status = 1;
            $statusChange->save();

            return response()->json('Customer activated successfully');
        }
    }

    // Customer view method
    public function manage(Request $request, $customerId)
    {
        if (! auth()->user()->can('customer_manage')) {
            abort(403, 'Access Forbidden.');
        }

        $customer = DB::table('customers')
            ->where('customers.id', $customerId)
            ->leftJoin('accounts', 'customers.id', 'accounts.customer_id')
            ->select('customers.*', 'accounts.id as customer_account_id')
            ->first();

        $userIds = DB::table('account_ledgers')->where('account_ledgers.account_id', $customer->customer_account_id)
            ->select('account_ledgers.user_id')->distinct()->pluck('user_id');

        $users = DB::table('users')->whereIn('id', $userIds)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('contacts::customers.manage', compact('customer', 'users'));
    }

    public function customerSaleAndOrdersUserWise(Request $request, $customerId)
    {
        $userWiseCustomerInvoiceAndOrders = $this->userWiseCustomerAmountUtil->userWiseCustomerInvoiceAndOrders($customerId, $request->user_id);

        return view('contacts::customers.ajax_view.partials.sales_and_orders_list_for_customer_payment', compact('userWiseCustomerInvoiceAndOrders'));
    }
}
