<?php

namespace Modules\Communication\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Communication\Emails\CustomerBulkEmail;
use Modules\Communication\Entities\CommunicationContact;
use Modules\Communication\Entities\CommunicationStatus;
use Modules\Communication\Entities\ContactGroup;
use Modules\Communication\Entities\Email;
use Modules\Communication\Entities\EmailServer;
use Modules\Communication\Entities\EmailTemplate;
use Modules\Communication\Imports\BulkMailImport;
use Modules\Communication\Interface\CommunicationStatusServiceInterface;
use Modules\Communication\Interface\EmailServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ManualMailController extends Controller
{
    private $emailService;

    private $communicationStatus;

    public function __construct(EmailServiceInterface $emailService, CommunicationStatusServiceInterface $communicationStatus)
    {
        $this->emailService = $emailService;
        $this->communicationStatus = $communicationStatus;
    }

    public function emailManual(Request $request)
    {
        if ($request->ajax()) {

            $plucked = $this->communicationStatus->getCommunicationEmailStatus();
            $customers = Customer::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $suppliers = Supplier::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $users = User::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $email = $customersSuppliersUsers->merge($CommunicationContact);

            return DataTables::of($email)
                ->addColumn('email', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.email.manual-service-mail-active', [$row->email, $row->id]).'">'.$row['email'].'</a></div>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="email_id[]" data-mail="'.$row->email.'" value="'.$row->email.'" class="mt-2 checkMailId">
                    </div>';

                    return $html;
                    // <input type="checkbox" name="email_id[]" data-mail="'.$row->email.'" value="' . $row->id .'=='. $row->email.'" class="mt-2 checkMailId">
                })
                // ->addColumn('status', function ($row) {
                //     $html = '';
                //     if ($row['status'] == 1) {
                //         $html .= '<div class="text-center"><a class="" href="' . route('communication.email.body.important', [$row->id, 1]) . '" id="status"><i class="fa-solid fa-star fa-lg"></i></a></div>';
                //     } else {
                //         $html .= '<div class="text-center"><a class="" href="' . route('communication.email.body.important', [$row->id, 2]) . '" id="status"><i class="fa-thin fa-star fa-lg"></i></a></div>';
                //     }
                //     return $html;
                // })
                ->rawColumns(['email', 'check'])
                ->make(true);
        }

        $total = [
            'servers' => EmailServer::all(),
            'bodes' => EmailTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::email.email-manual-service', $total);
    }

    public function emailManualStatusWiseMailList(Request $request, $statusType)
    {
        if ($statusType == 'active') {
            $plucked = $this->communicationStatus->getCommunicationEmailStatus();
            $customers = Customer::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $suppliers = Supplier::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $users = User::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $email = $customersSuppliersUsers->merge($CommunicationContact);
        } elseif ($statusType == 'inactive') {
            $plucked = $this->communicationStatus->getCommunicationEmailStatus();
            $customers = Customer::whereNotNull('email')->whereIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $suppliers = Supplier::whereNotNull('email')->whereIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $users = User::whereNotNull('email')->whereIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('email')->whereIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $email = $customersSuppliersUsers->merge($CommunicationContact);
        } else {
            $email = '';
        }

        if ($request->ajax()) {
            return DataTables::of($email)
                ->addColumn('email', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.email.manual-service-mail-active', [$row->email, $row->id]).'">'.$row['email'].'</a></div>';

                    return $html;
                })
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="email_id[]" data-mail="'.$row->email.'" value="'.$row->email.'" class="mt-2 checkMailId">
                    </div>';

                    return $html;
                })
                ->rawColumns(['email', 'check'])
                ->make(true);
        }
        $total = [
            'servers' => EmailServer::all(),
            'bodes' => EmailTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::email.email-manual-service', $total);
    }

    public function emailManualMailList(Request $request, $filterType, $filterKey)
    {
        if ($request->ajax()) {
            $email = '';
            $FOR_ALL = $filterType === 'all';
            $FOR_CUSTOMER = $filterType === 'customers';
            $FOR_SUPPLIER = $filterType === 'suppliers';
            $FOR_USER = $filterType === 'users';
            $FOR_CONTACT_GROUP = $filterType === 'contact_groups';

            if ($FOR_ALL) {
                $plucked = $this->communicationStatus->getCommunicationEmailStatus();
                $customers = Customer::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $suppliers = Supplier::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $users = User::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $CommunicationContact = CommunicationContact::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $customersAndSuppliers = $customers->merge($suppliers);
                $customersSuppliersUsers = $customersAndSuppliers->merge($users);
                $email = $customersSuppliersUsers->merge($CommunicationContact);
            } elseif ($FOR_CUSTOMER) {
                $plucked = $this->communicationStatus->getCommunicationEmailStatus();
                $customers = Customer::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $email = $customers;
            } elseif ($FOR_SUPPLIER) {
                $plucked = $this->communicationStatus->getCommunicationEmailStatus();
                $suppliers = Supplier::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $email = $suppliers;
            } elseif ($FOR_USER) {
                $plucked = $this->communicationStatus->getCommunicationEmailStatus();
                $users = User::whereNotNull('email')->whereNotIn('email', $plucked)->select('email', 'id')->orderBy('email')->get();
                $email = $users;
            } elseif ($FOR_CONTACT_GROUP) {
                $plucked = $this->communicationStatus->getCommunicationEmailStatus();
                $CommunicationContact = CommunicationContact::whereNotNull('email')->whereNotIn('email', $plucked)->select('id', 'email')->where('group_id', $filterKey)->orderBy('email')->get();
                $email = $CommunicationContact;
            }

            return DataTables::of($email)
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                    <input type="checkbox" name="email_id[]" data-mail="'.$row->email.'" value="'.$row->email.'" class="mt-2 checkMailId">
                    </div>';

                    return $html;
                })
                ->addColumn('email', function ($row) {
                    $html = '';
                    $html .= '<div class="active_deactive_status"><a class="active_inactive_status" href="'.route('communication.email.manual-service-mail-active', [$row->email, $row->id]).'">'.$row['email'].'</a></div>';

                    return $html;
                })
                ->rawColumns(['email', 'check'])
                ->make(true);
        }
        $total = [
            'servers' => EmailServer::all(),
            'bodes' => EmailTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return view('communication::email.email-manual-service', $total);
    }

    public function importMailModal()
    {
        return view('communication::email.ajax_view.import_bulk_mail_modal');
    }

    public function importMailStore(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        $rawEmails = Excel::toArray(new BulkMailImport, $request->import_file)[0];
        unset($rawEmails[0]);
        $emails = array_column($rawEmails, 4);
        $nullCheckEmail = array_filter($emails, fn ($email) => null !== $email);
        $finalEmailArray = [];
        $str = '';
        foreach ($nullCheckEmail as $key => $value) {
            $str .= "$value <br>";
            $checkHTML = '<div class="icheck-primary text-center">';
            $checkHTML .= '    <input type="checkbox" name="email_id[]" value="'.$key.'">';
            $checkHTML .= '</div>';
            $arrayItem = ['check' => $checkHTML, 'email' => $value];
            array_push($finalEmailArray, $arrayItem);
        }

        return response()->json(['data' => $finalEmailArray, 'nullCheckEmail' => $nullCheckEmail]);
    }

    public function manualMailStatus(Request $request, $email)
    {
        // return $email;
        $findMail = CommunicationStatus::where('mail_status', $email)->first();
        if ($findMail) {
            $findMail->delete();
            $status = 'Active';
        } else {
            CommunicationStatus::insert([
                'mail_status' => $email,
            ]);
            $status = 'Inactive';
        }
        $total = [
            'servers' => EmailServer::all(),
            'bodes' => EmailTemplate::all(),
            'ContactGroup' => ContactGroup::all(),
            'supplier' => DB::table('suppliers')->count(),
            'active_supplier' => DB::table('suppliers')->where('status', 1)->count(),
            'inactive_supplier' => DB::table('suppliers')->where('status', 0)->count(),
        ];

        return response()->json("This {$email} Mail is {$status}");
        // return view('communication::email.email-manual-service', $total);
    }

    public function manualMailSend(Request $request)
    {
        $emailArray = $request->checkedMailId;
        $checkedEmailArray = \explode(',', $emailArray);
        $filterType = $request->mailFilterType;
        $FOR_ALL = $filterType === 'all';
        $FOR_CUSTOMER = $filterType === 'customers';
        $FOR_SUPPLIER = $filterType === 'suppliers';
        $FOR_USER = $filterType === 'users';
        $FOR_CONTACT_GROUP = $filterType === 'contact_groups';

        if ($FOR_ALL) {
            $customers = Customer::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $suppliers = Supplier::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $users = User::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $CommunicationContact = CommunicationContact::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $customersAndSuppliers = $customers->merge($suppliers);
            $customersSuppliersUsers = $customersAndSuppliers->merge($users);
            $email = $customersSuppliersUsers->merge($CommunicationContact);
        } elseif ($FOR_CUSTOMER) {
            $customers = Customer::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $email = $customers;
        } elseif ($FOR_SUPPLIER) {
            $suppliers = Supplier::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $email = $suppliers;
        } elseif ($FOR_USER) {
            $users = User::whereNotNull('email')->select('id', 'email')->orderBy('email')->get();
            $email = $users;
        } elseif ($FOR_CONTACT_GROUP) {
            $filterKey = $request->mailFilterKey;
            $CommunicationContact = CommunicationContact::whereNotNull('email')->select('id', 'email')->where('group_id', $filterKey)->orderBy('email')->get();
            $email = $CommunicationContact;
        }
        $servers = EmailServer::where('id', $request->email_serve_id)->first();
        $bodes = EmailTemplate::where('id', $request->email_format_body_id)->first();
        if ($bodes) {
            $request->email_subject = $bodes->mail_subject;
            $request->email_body = $bodes->body_format;
        }
        $request->validate([
            'to.*' => 'required',
            'email_subject' => 'required',
        ]);
        $emailArray = [];
        $emailArrayCCBcc = [];
        if ($request->bulkImportedMailVar != 0) {
            $emailArray = explode(',', $request->bulkImportedMailVar);
        } elseif ($checkedEmailArray != '') {
            $emailArray = $checkedEmailArray;
        } else {
            foreach ($email as $email) {
                array_push($emailArray, $email->email);
            }
        }
        if ($request->email_bcc != null) {
            array_push($emailArrayCCBcc, $request->email_bcc);
        }
        if ($request->email_cc != null) {
            array_push($emailArrayCCBcc, $request->email_cc);
        }
        $emailArray = array_merge($request->to, array_merge($emailArrayCCBcc, $emailArray));
        $subject = $request->email_subject;
        $body = $request->email_body;
        // $body = \stripslashes(\strip_tags($request->email_body));
        $trimmedEmails = array_map(fn ($item) => trim($item), $emailArray);
        $mailData = [
            'subject' => $subject,
            'body' => $body,
        ];

        $this->emailService->sendMultiple($trimmedEmails, new CustomerBulkEmail($mailData));
        $emailsAsString = implode(',', $trimmedEmails);
        $mails = new Email;
        $mails->mail = $emailsAsString;
        $mails->subject = $subject;
        $mails->message = $body;
        $mails->save();

        return response()->json('Mail sent successfully');
    }
}
