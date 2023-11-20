<?php

namespace Modules\CRM\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerContactPersonDetails;
use App\Models\CustomerDetails;
use App\Models\CustomerGroup;
use App\Models\CustomerOpeningBalance;
use App\Models\User;
use App\Utils\CustomerUtil;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\CRM\Interfaces\FileUploaderServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class CustomerController extends Controller
{
    public function __construct(
        private CustomerUtil $customerUtil,
        private InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil,
        private UserActivityLogUtil $userActivityLogUtil,
    ) {
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $customers = Customer::where('is_lead', 1)->get();

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.customers.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.customers.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('debit', function ($row) {
                    return 'incomplete';
                })
                ->editColumn('credit', function ($row) {
                    return 'incomplete';
                })
                ->editColumn('closing_balance', function ($row) {
                    return 'incomplete';
                })
                ->editColumn('status', function ($row) {
                    $html = '';
                    if ($row->status == 0) {
                        $html .= '<div class="form-check form-switch"><input class="form-check-input change_status" data-url="'.route('crm.customers.status', $row->id).'" style="width: 34px; border-radius: 10px; height: 14px !important; margin-left: -7px;" type="checkbox"></div>';
                    } else {
                        $html .= '<div class="form-check form-switch"><input class="form-check-input change_status" data-url="'.route('crm.customers.status', $row->id).'" style="width: 34px; border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px;" type="checkbox" checked=""></div>';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'debit', 'credit', 'closing_balance', 'status'])
                ->smart(true)
                ->make(true);
        }

        $total = [
            'customer' => DB::table('customers')->count(),
            'active_customer' => DB::table('customers')->where('status', 1)->count(),
            'inactive_customer' => DB::table('customers')->where('status', 0)->count(),
        ];

        $customer_group = CustomerGroup::all();

        return view('crm::customers.index', compact('customer_group', 'total'));
    }

    public function basicModal()
    {
        return view('crm::customers.ajax_view.customer_create_basic_modal');
    }

    public function detailedModal()
    {
        $groups = DB::table('customer_groups')->get();

        return view('crm::customers.ajax_view.customer_create_detailed_modal', compact('groups'));
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

        $customer_file = '';
        $customer_document = '';
        $alternative_file = '';

        if ($request->hasFile('customer_file')) {

            $customer_file = $fileUploaderService->upload($request->file('customer_file'), 'uploads/customer/');
        }

        if ($request->hasFile('alternative_file')) {

            $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/customer/alternative/');
        }

        if ($request->hasFile('customer_document')) {
            $customer_document = $fileUploaderService->uploadMultiple($request->file('customer_document'), 'uploads/customer/documents');
        }

        try {

            DB::beginTransaction();

            $generalSettings = DB::table('general_settings')->first('prefix');

            $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];

            $creditLimit = $request->credit_limit ? $request->credit_limit : 0;

            $addCustomer = Customer::create([
                'contact_id' => $request->contact_id ? $request->contact_id : $cusIdPrefix.str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, '0', STR_PAD_LEFT),
                'name' => $request->name,
                'phone' => $request->phone,
                'business_name' => $request->business_name,
                'email' => $request->contact_email,
                'nid_no' => $request->nid_no,
                'trade_license_no' => $request->trade_license_no,
                'known_person' => $request->known_person,
                'known_person_phone' => $request->known_person_phone,
                'alternative_phone' => $request->alternative_phone,
                'landline' => $request->landline,
                'date_of_birth' => $request->date_of_birth,
                'tax_number' => $request->tax_number,
                'customer_group_id' => $request->customer_group_id,
                'address' => $request->address,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'country' => $request->country,
                'state' => $request->state,
                'shipping_address' => $request->shipping_address,
                'opening_balance' => $request->opening_balance ? $request->opening_balance : 0.00,
                'total_sale_due' => $request->opening_balance ? $request->opening_balance : 0.00,
                'created_by_id' => auth()->user()->id,
            ]);

            $customerDetails = CustomerDetails::create([
                'customer_id' => $addCustomer->id,
                'contact_type' => $request->contact_type == 'company' ? 2 : 1,
                'total_employees' => $request->total_employees,
                'permanent_address' => $request->permanent_address,
                'customer_file' => $customer_file,
                'customer_document' => $customer_document,
                'alternative_file' => $alternative_file,
                'print_name' => $request->print_name,
                'print_ledger_name' => $request->print_ledger_name,
                'print_ledger_code' => $request->print_ledger_code,
                'billing_account' => $request->billing_account,
                'description' => $request->description,
                'customer_status' => $request->customer_status,
                'contact_mailing_name' => $request->contact_mailing_name,
                'contact_post_office' => $request->contact_post_office,
                'contact_police_station' => $request->contact_police_station,
                'contact_currency' => $request->contact_currency,
                'contact_fax' => $request->contact_fax,
                'primary_mobile' => $request->primary_mobile,
                'contact_send_sms' => $request->contact_send_sms,
                'contact_email' => $request->contact_email,
                'mailing_name' => $request->mailing_name,
                'mailing_address' => $request->mailing_address,
                'mailing_email' => $request->mailing_email,
                'shipping_name' => $request->shipping_name,
                'shipping_number' => $request->shipping_number,
                'shipping_email' => $request->shipping_email,
                'shipping_send_sms' => $request->shipping_send_sms,
                'alternative_address' => $request->alternative_address,
                'alternative_name' => $request->alternative_name,
                'alternative_post_office' => $request->alternative_post_office,
                'alternative_zip_code' => $request->alternative_zip_code,
                'alternative_police_station' => $request->alternative_police_station,
                'alternative_state' => $request->alternative_state,
                'alternative_city' => $request->alternative_city,
                'alternative_fax' => $request->alternative_fax,
                'alternative_send_sms' => $request->alternative_send_sms,
                'alternative_email' => $request->alternative_email,
                'tin_number' => $request->tin_number,
                'tax_number' => $request->tax_number,
                'tax_name' => $request->tax_name,
                'tax_category' => $request->tax_category,
                'tax_address' => $request->tax_address,
                'bank_name' => $request->bank_name,
                'bank_A_C_number' => $request->bank_A_C_number,
                'bank_currency' => $request->bank_currency,
                'bank_branch' => $request->bank_branch,
                'contact_telephone' => $request->contact_telephone,
                'partner_name' => $request->partner_name,
                'percentage' => $request->percentage,
                'sales_team' => $request->sales_team,
            ]);

            $check_part = $request->contact_person_name;

            if (isset($check_part)) {

                foreach ($check_part as $key => $item) {

                    $addContactPerson = new CustomerContactPersonDetails();
                    $addContactPerson->contact_person_name = $request->contact_person_name[$key];
                    $addContactPerson->contact_person_phon = $request->contact_person_phon[$key];
                    $addContactPerson->contact_person_dasignation = $request->contact_person_dasignation[$key];
                    $addContactPerson->contact_person_landline = $request->contact_person_landline[$key];
                    $addContactPerson->contact_person_alternative_phone = $request->contact_person_alternative_phone[$key];
                    $addContactPerson->contact_person_fax = $request->contact_person_fax[$key];
                    $addContactPerson->contact_person_email = $request->contact_person_email[$key];
                    $addContactPerson->contact_person_address = $request->contact_person_address[$key];
                    $addContactPerson->contact_person_post_office = $request->contact_person_post_office[$key];
                    $addContactPerson->contact_person_zip_code = $request->contact_person_zip_code[$key];
                    $addContactPerson->contact_person_police_station = $request->contact_person_police_station[$key];
                    $addContactPerson->contact_person_state = $request->contact_person_state[$key];
                    $addContactPerson->contact_person_city = $request->contact_person_city[$key];
                    $addContactPerson->customer_id = $addCustomer->id;
                    $addContactPerson->save();
                }
            }

            $addCustomerOpeningBalance = CustomerOpeningBalance::insert([
                'customer_id' => $addCustomer->id,
                'user_id' => auth()->user()->id,
                'amount' => $request->opening_balance ? $request->opening_balance : 0.00,
                'balance_type' => $request->balance_type,
            ]);

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 1, data_obj: $addCustomer);

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return $addCustomer;
    }

    public function update(Request $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required|unique:customers,phone,'.$id,
            'nid_no' => 'nullable|unique:customers,nid_no,'.$id,
            'trade_license_no' => 'nullable|unique:customers,trade_license_no,'.$id,
        ]);

        try {

            DB::beginTransaction();
            $creditLimit = $request->credit_limit ? $request->credit_limit : 0;

            $updateCustomer = Customer::with('customerDetails', 'customerContactPersons')->where('id', $id)->first();

            $updateCustomer->name = $request->name;
            $updateCustomer->phone = $request->phone;
            $updateCustomer->business_name = $request->business_name;
            $updateCustomer->email = $request->contact_email;
            $updateCustomer->nid_no = $request->nid_no;
            $updateCustomer->trade_license_no = $request->trade_license_no;
            $updateCustomer->known_person = $request->known_person;
            $updateCustomer->known_person_phone = $request->known_person_phone;
            $updateCustomer->alternative_phone = $request->alternative_phone;
            $updateCustomer->landline = $request->landline;
            $updateCustomer->date_of_birth = $request->date_of_birth;
            $updateCustomer->tax_number = $request->tax_number;
            $updateCustomer->customer_group_id = $request->customer_group_id;
            $updateCustomer->address = $request->address;
            $updateCustomer->city = $request->city;
            $updateCustomer->zip_code = $request->zip_code;
            $updateCustomer->country = $request->country;
            $updateCustomer->state = $request->state;
            $updateCustomer->shipping_address = $request->shipping_address;
            $updateCustomer->opening_balance = $request->opening_balance ? $request->opening_balance : 0.00;
            $updateCustomer->total_sale_due = $request->opening_balance ? $request->opening_balance : 0.00;
            $updateCustomer->created_by_id = auth()->user()->id;
            $updateCustomer->save();

            $customerDetails = '';
            if ($updateCustomer->customerDetails) {

                $customerDetails = $updateCustomer->customerDetails;
            } else {

                $customerDetails = new CustomerDetails();
            }

            $customerDetails->customer_id = $id;
            $customerDetails->contact_type = $request->contact_type;
            $customerDetails->total_employees = $request->total_employees;
            $customerDetails->permanent_address = $request->permanent_address;
            $customerDetails->print_name = $request->print_name;
            $customerDetails->print_ledger_name = $request->print_ledger_name;
            $customerDetails->print_ledger_code = $request->print_ledger_code;
            $customerDetails->billing_account = $request->billing_account;
            $customerDetails->description = $request->description;
            $customerDetails->customer_status = $request->customer_status;
            $customerDetails->contact_mailing_name = $request->contact_mailing_name;
            $customerDetails->contact_post_office = $request->contact_post_office;
            $customerDetails->contact_police_station = $request->contact_police_station;
            $customerDetails->contact_currency = $request->contact_currency;
            $customerDetails->contact_fax = $request->contact_fax;
            $customerDetails->primary_mobile = $request->primary_mobile;
            $customerDetails->contact_send_sms = $request->contact_send_sms;
            $customerDetails->contact_email = $request->contact_email;
            $customerDetails->mailing_name = $request->mailing_name;
            $customerDetails->mailing_address = $request->mailing_address;
            $customerDetails->mailing_email = $request->mailing_email;
            $customerDetails->shipping_name = $request->shipping_name;
            $customerDetails->shipping_number = $request->shipping_number;
            $customerDetails->shipping_email = $request->shipping_email;
            $customerDetails->shipping_send_sms = $request->shipping_send_sms;
            $customerDetails->alternative_address = $request->alternative_address;
            $customerDetails->alternative_name = $request->alternative_name;
            $customerDetails->alternative_post_office = $request->alternative_post_office;
            $customerDetails->alternative_zip_code = $request->alternative_zip_code;
            $customerDetails->alternative_police_station = $request->alternative_police_station;
            $customerDetails->alternative_state = $request->alternative_state;
            $customerDetails->alternative_city = $request->alternative_city;
            $customerDetails->alternative_fax = $request->alternative_fax;
            $customerDetails->alternative_send_sms = $request->alternative_send_sms;
            $customerDetails->alternative_email = $request->alternative_email;
            $customerDetails->tin_number = $request->tin_number;
            $customerDetails->tax_number = $request->tax_number;
            $customerDetails->tax_name = $request->tax_name;
            $customerDetails->tax_category = $request->tax_category;
            $customerDetails->tax_address = $request->tax_address;
            $customerDetails->bank_name = $request->bank_name;
            $customerDetails->bank_A_C_number = $request->bank_A_C_number;
            $customerDetails->bank_currency = $request->bank_currency;
            $customerDetails->bank_branch = $request->bank_branch;
            $customerDetails->contact_telephone = $request->contact_telephone;
            $customerDetails->partner_name = $request->partner_name;
            $customerDetails->percentage = $request->percentage;
            $customerDetails->sales_team = $request->sales_team;
            $customerDetails->save();

            $customer_file = '';
            $customer_document = '';
            $alternative_file = '';

            if ($request->hasFile('customer_file')) {

                if (is_file(public_path('uploads/customer/'.$updateCustomer?->customerDetails?->customer_file))) {

                    unlink(public_path('uploads/customer/'.$updateCustomer?->customerDetails?->customer_file));
                }

                $customer_file = $fileUploaderService->upload($request->file('customer_file'), 'uploads/customer/');
                $column_name = 'customer_file';
                $value = $customer_file;
                $this->updateFile($id, $column_name, $value);
            }

            if ($request->hasFile('alternative_file')) {

                if (is_file(public_path('uploads/customer/alternative/'.$updateCustomer?->customerDetails?->alternative_file))) {

                    unlink(public_path('uploads/customer/alternative/'.$updateCustomer?->customerDetails?->alternative_file));
                }

                $alternative_file = $fileUploaderService->upload($request->file('alternative_file'), 'uploads/customer/alternative/');
                $column_name = 'alternative_file';
                $value = $alternative_file;
                $this->updateFile($id, $column_name, $value);
            }

            if ($request->hasFile('customer_document')) {

                $newCustomerDocumentsString = $fileUploaderService->uploadMultiple($request->file('customer_document'), 'uploads/customer/documents/');
                $newCustomerDocumentsArray = json_decode($newCustomerDocumentsString);

                if ($updateCustomer?->customerDetails?->customer_document) {

                    $oldCustomerDocumentsArray = \json_decode($updateCustomer->customerDetails->customer_document, true);
                    $mergedFilesArray = array_merge($oldCustomerDocumentsArray, $newCustomerDocumentsArray);
                    $updateCustomer->customerDetails->customer_document = json_encode($mergedFilesArray);
                } else {

                    if ($updateCustomer?->customerDetails?->customer_document) {

                        $updateCustomer->customerDetails->customer_document = json_encode($newCustomerDocumentsArray);
                    }
                }
            }

            // delete old contact person data
            $customerContactPersons = $updateCustomer->customerContactPersons;
            if (count($customerContactPersons) > 0) {

                $customerContactPerson = CustomerContactPersonDetails::where('customer_id', $id)->delete();
            }

            $check_part = $request->contact_person_name;

            if (isset($check_part)) {

                foreach ($check_part as $key => $item) {

                    $addContactPerson = new CustomerContactPersonDetails();
                    $addContactPerson->contact_person_name = $request->contact_person_name[$key];
                    $addContactPerson->contact_person_phon = $request->contact_person_phon[$key];
                    $addContactPerson->contact_person_dasignation = $request->contact_person_dasignation[$key];
                    $addContactPerson->contact_person_landline = $request->contact_person_landline[$key];
                    $addContactPerson->contact_person_alternative_phone = $request->contact_person_alternative_phone[$key];
                    $addContactPerson->contact_person_fax = $request->contact_person_fax[$key];
                    $addContactPerson->contact_person_email = $request->contact_person_email[$key];
                    $addContactPerson->contact_person_address = $request->contact_person_address[$key];
                    $addContactPerson->contact_person_post_office = $request->contact_person_post_office[$key];
                    $addContactPerson->contact_person_zip_code = $request->contact_person_zip_code[$key];
                    $addContactPerson->contact_person_police_station = $request->contact_person_police_station[$key];
                    $addContactPerson->contact_person_state = $request->contact_person_state[$key];
                    $addContactPerson->contact_person_city = $request->contact_person_city[$key];
                    $addContactPerson->customer_id = $updateCustomer->id;
                    $addContactPerson->save();
                }
            }

            $userOpeningBalance = CustomerOpeningBalance::where('customer_id', $updateCustomer->id)->where('user_id', auth()->user()->id)->first();

            if ($userOpeningBalance) {

                $userOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
                $userOpeningBalance->balance_type = $request->balance_type;
                $userOpeningBalance->save();
            } else {

                $addCustomerOpeningBalance = new CustomerOpeningBalance();
                $addCustomerOpeningBalance->customer_id = $updateCustomer->id;
                $addCustomerOpeningBalance->user_id = auth()->user()->id;
                $addCustomerOpeningBalance->amount = $request->opening_balance ? $request->opening_balance : 0.00;
                $addCustomerOpeningBalance->balance_type = $request->balance_type;
                $addCustomerOpeningBalance->save();
            }

            $calcOpeningBalance = DB::table('customer_opening_balances')
                ->where('customer_id', $updateCustomer->id)
                ->select(DB::raw('SUM(amount) as op_amount'))
                ->groupBy('customer_id')->get();

            $updateCustomer->opening_balance = $calcOpeningBalance->sum('op_amount');
            $updateCustomer->save();
            $customer = DB::table('customers')
                ->where('id', $updateCustomer->id)
                ->select('name', 'phone', 'contact_id', 'total_sale_due')
                ->first();

            $this->customerUtil->updateCustomerLedger(
                voucher_type_id: 0,
                customer_id: $updateCustomer->id,
                date: $updateCustomer->created_at,
                trans_id: null,
                amount: $request->opening_balance ? $request->opening_balance : 0.00,
                fixed_date: $updateCustomer->created_at,
                user_id: auth()->user()->id,
                balance_type: $request->balance_type,
            );

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 1, data_obj: $customer);

        return response()->json('Customer updated successfully');
    }

    public function updateFile($id, $column_name, $value)
    {
        CustomerDetails::where('customer_id', $id)->update([
            $column_name => $value,
        ]);
    }

    public function status(Request $request, $id)
    {
        $status = Customer::find($id);

        if ($status->status == 1) {
            $status->status = 0;
        } else {
            $status->status = 1;
        }

        $status->save();

        return response()->json('Status Change Successfully');
    }

    public function edit(Request $request, $customerId)
    {
        $customer = Customer::with('customer_group', 'customerDetails', 'creditLimits', 'customerContactPersons', 'openingBalance')->where('id', $customerId)->first();
        $userOpeningBalance = DB::table('customer_opening_balances')->where('customer_id', $customer->id)->where('user_id', auth()->user()->id)->first();
        $users = User::where('allow_login', 1)->select('id', 'prefix', 'name', 'last_name', 'phone')->get();

        return view('crm::customers.ajax_view.edit', compact('customer', 'groups', 'users', 'userOpeningBalance'));
    }

    public function delete(Request $request, $customerId)
    {
        if (! auth()->user()->can('customer_delete')) {
            abort(403, 'Access Forbidden.');
        }

        $deleteCustomer = Customer::with(['ledgers'])->where('id', $customerId)->first();

        if (count($deleteCustomer->ledgers) > 1) {
            return response()->json(['errorMsg' => 'Customer can\'t be deleted. One or more entry has been created in ledger.']);
        }

        if (! is_null($deleteCustomer)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 1, data_obj: $deleteCustomer);
            $deleteCustomer->delete();
        }

        DB::statement('ALTER TABLE customers AUTO_INCREMENT = 1');

        return response()->json('Customer deleted successfully');
    }
}
