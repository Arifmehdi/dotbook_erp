<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Communication\Entities\CommunicationContact;
use Modules\Communication\Entities\CommunicationStatus;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Entities\Sms;
use Modules\Communication\Entities\SmsServer;
use Modules\Communication\Entities\SmsTemplate;
use Modules\Communication\Imports\BulkPhoneNumberImport;
use Modules\Communication\Interface\CommunicationStatusServiceInterface;
use Modules\Communication\Interface\SmsServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ManualSmsController extends Controller
{
    private $smsService;

    private $communicationStatus;

    public function __construct(SmsServiceInterface $smsService, CommunicationStatusServiceInterface $communicationStatus)
    {
        $this->smsService = $smsService;
        $this->communicationStatus = $communicationStatus;
    }

    public function smsManual(Request $request)
    {
        if ($request->ajax()) {
            $plucked = $this->communicationStatus->getCommunicationSmsStatus();
            $customers = Customer::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $suppliers = Supplier::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $users = User::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->whereNotIn('phone_number', $plucked)->select('phone_number as phone', 'id')->orderBy('phone_number')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $phone = $customersSuppliersUsers->merge($CommunicationContact);

            return DataTables::of($phone)
                ->addColumn('phone', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.sms.manual-service-sms-active', [$row->phone, $row->id]).'">'.$row['phone'].'</a></div>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="phone_id[]" data-phone="'.$row->phone.'" value="'.$row->phone.'" class="mt-2 checkPhoneId">
                    </div>';

                    return $html;
                })
                ->rawColumns(['phone', 'check'])
                ->make(true);
        }

        $total = [
            'servers' => SmsServer::all(),
            'bodes' => SmsTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::sms.sms-manual-service', $total);
    }

    public function smsManualStatusWisePhoneNumberList(Request $request, $statusType)
    {
        if ($statusType == 'active') {
            $plucked = $this->communicationStatus->getCommunicationSmsStatus();
            $customers = Customer::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $suppliers = Supplier::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $users = User::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->whereNotIn('phone_number', $plucked)->select('phone_number as phone', 'id')->orderBy('phone_number')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $phone = $customersSuppliersUsers->merge($CommunicationContact);
        } elseif ($statusType == 'inactive') {
            $plucked = $this->communicationStatus->getCommunicationSmsStatus();
            $customers = Customer::whereNotNull('phone')->whereIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $suppliers = Supplier::whereNotNull('phone')->whereIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $users = User::whereNotNull('phone')->whereIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->whereIn('phone_number', $plucked)->select('phone_number as phone', 'id')->orderBy('phone_number')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $phone = $customersSuppliersUsers->merge($CommunicationContact);
        } else {
            $phone = '';
        }

        if ($request->ajax()) {
            return DataTables::of($phone)
                ->addColumn('phone', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.sms.manual-service-sms-active', [$row->phone, $row->id]).'">'.$row['phone'].'</a></div>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="phone_id[]" data-phone="'.$row->phone.'" value="'.$row->phone.'" class="mt-2 checkPhoneId">
                    </div>';

                    return $html;
                })
                ->rawColumns(['phone', 'check'])
                ->make(true);
        }
        $total = [
            'servers' => SmsServer::all(),
            'bodes' => SmsTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::sms.sms-manual-service', $total);
    }

    public function smsManualSmsList(Request $request, $filterType, $filterKey)
    {
        if ($request->ajax()) {
            $phone = '';
            $FOR_ALL = $filterType === 'all';
            $FOR_CUSTOMER = $filterType === 'customers';
            $FOR_SUPPLIER = $filterType === 'suppliers';
            $FOR_USER = $filterType === 'users';
            $FOR_CONTACT_GROUP = $filterType === 'contact_groups';

            if ($FOR_ALL) {
                $plucked = $this->communicationStatus->getCommunicationSmsStatus();
                $customers = Customer::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $suppliers = Supplier::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $users = User::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->whereNotIn('phone_number', $plucked)->select('phone_number as phone', 'id')->orderBy('phone_number')->get();
                $customersAndSuppliers = $customers->merge($suppliers);
                $customersSuppliersUsers = $customersAndSuppliers->merge($users);
                $phone = $customersSuppliersUsers->merge($CommunicationContact);
            } elseif ($FOR_CUSTOMER) {
                $plucked = $this->communicationStatus->getCommunicationSmsStatus();
                $customers = Customer::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $phone = $customers;
            } elseif ($FOR_SUPPLIER) {
                $plucked = $this->communicationStatus->getCommunicationSmsStatus();
                $suppliers = Supplier::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $phone = $suppliers;
            } elseif ($FOR_USER) {
                $plucked = $this->communicationStatus->getCommunicationSmsStatus();
                $users = User::whereNotNull('phone')->whereNotIn('phone', $plucked)->select('phone', 'id')->orderBy('phone')->get();
                $phone = $users;
            } elseif ($FOR_CONTACT_GROUP) {
                $plucked = $this->communicationStatus->getCommunicationSmsStatus();
                $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->whereNotIn('phone_number', $plucked)->select('id', 'phone_number as phone')->where('group_id', $filterKey)->orderBy('phone_number')->get();
                $phone = $CommunicationContact;
            }

            return DataTables::of($phone)
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="phone_id[]" data-phone="'.$row->phone.'" value="'.$row->phone.'" class="mt-2 checkPhoneId">
                    </div>';

                    return $html;
                })
                ->addColumn('phone', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.sms.manual-service-sms-active', [$row->phone, $row->id]).'">'.$row['phone'].'</a></div>';

                    return $html;
                })
                ->rawColumns(['phone', 'check'])
                ->make(true);
        }
        $total = [
            'servers' => SmsServer::all(),
            'bodes' => SmsTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::sms.sms-manual-service', $total);
    }

    public function importPhoneModal()
    {
        return view('communication::sms.ajax_view.import_bulk_sms_modal');
    }

    public function importPhoneStore(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        $rawMessage = Excel::toArray(new BulkPhoneNumberImport, $request->import_file)[0];
        unset($rawMessage[0]);
        $phones = array_column($rawMessage, 4);
        $nullCheckPhone = array_filter($phones, fn ($phone) => null !== $phone);
        $finalPhoneArray = [];
        $str = '';
        foreach ($nullCheckPhone as $key => $value) {
            $str .= "$value <br>";
            $checkHTML = '<div class="icheck-primary text-center">';
            $checkHTML .= '    <input type="checkbox" name="phone_id[]" value="'.$key.'">';
            $checkHTML .= '</div>';
            $arrayItem = ['check' => $checkHTML, 'phone' => $value];
            array_push($finalPhoneArray, $arrayItem);
        }

        return response()->json(['data' => $finalPhoneArray, 'nullCheckPhone' => $nullCheckPhone]);
    }

    public function manualPhoneStatus(Request $request, $phone)
    {
        $findPhone = CommunicationStatus::where('sms_status', $phone)->first();
        if ($findPhone) {
            $findPhone->delete();
            $status = 'Active';
        } else {
            CommunicationStatus::insert([
                'sms_status' => $phone,
            ]);
            $status = 'Inactive';
        }
        $total = [
            'servers' => SmsServer::all(),
            'bodes' => SmsTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return response()->json("This {$phone} Phone Number is {$status}");
    }

    public function manualSmsSend(Request $request)
    {
        // return $request->all();
        $checkedPhone = $request->checkedPhoneId;
        $checkedPhoneArray = [];
        if ($checkedPhone != null) {
            $checkedPhoneArray = \explode(',', $checkedPhone);
        }
        $filterType = $request->phoneFilterType;
        $FOR_ALL = $filterType === 'all';
        $FOR_CUSTOMER = $filterType === 'customers';
        $FOR_SUPPLIER = $filterType === 'suppliers';
        $FOR_USER = $filterType === 'users';
        $FOR_CONTACT_GROUP = $filterType === 'contact_groups';

        if ($FOR_ALL) {
            $customers = Customer::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $suppliers = Supplier::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $users = User::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->select('id', 'phone_number as phone')->orderBy('phone_number')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $phones = $customersSuppliersUsers->merge($CommunicationContact);
        } elseif ($FOR_CUSTOMER) {
            $customers = Customer::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $phones = $customers;
        } elseif ($FOR_SUPPLIER) {
            $suppliers = Supplier::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $phones = $suppliers;
        } elseif ($FOR_USER) {
            $users = User::whereNotNull('phone')->select('id', 'phone')->orderBy('phone')->get();
            $phones = $users;
        } elseif ($FOR_CONTACT_GROUP) {
            $filterKey = $request->phoneFilterKey;
            $CommunicationContact = CommunicationContact::whereNotNull('phone_number')->select('id', 'phone_number as phone')->where('group_id', $filterKey)->orderBy('phone_number')->get();
            $phones = $CommunicationContact;
        }
        $servers = SmsServer::where('id', $request->sms_serve_id)->first();
        $bodes = SmsTemplate::where('id', $request->sms_format_body_id)->first();
        if ($bodes) {
            $request->sms_subject = $bodes->sms_subject;
            $request->body_format = $bodes->body_format;
        }
        $request->validate([
            'to.*' => 'required',
            'sms_subject' => 'required',
        ]);
        $phoneArray = [];
        if ($request->bulkImportedPhoneNumber != 0) {
            $phoneArray = explode(',', $request->bulkImportedPhoneNumber);
        } elseif (\count($checkedPhoneArray) > 0) {
            $phoneArray = $checkedPhoneArray;
        } else {
            foreach ($phones as $phone) {
                array_push($phoneArray, $phone->phone);
            }
        }
        $phoneArray = array_merge($request->to, $phoneArray);
        $trimmedNumbers = array_map(fn ($item) => trim($item), $phoneArray);
        $message = trim(\html_entity_decode(\strip_tags($request->sms_subject.' '.$request->body_format)));

        $this->smsService->sendMultiple($message, $trimmedNumbers);
        $sms = new Sms;
        $sms->to = implode(',', $trimmedNumbers);
        $sms->message = $message;
        $sms->save();

        return response()->json('Sms sent successfully');
    }
}
