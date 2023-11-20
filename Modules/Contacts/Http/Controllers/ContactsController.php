<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\InvoiceVoucherRefIdUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Contacts\Entities\Contact;
use Modules\Contacts\Imports\ContactsImport;
use Modules\Contacts\Interfaces\ContactServiceInterface;
use Modules\CRM\Entities\Source;
use Modules\CRM\Interfaces\FileUploaderServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ContactsController extends Controller
{
    private $contactService;

    private $fileUploaderService;

    public function __construct(ContactServiceInterface $contactService, FileUploaderServiceInterface $fileUploaderService)
    {
        $this->contactService = $contactService;
        $this->fileUploaderService = $fileUploaderService;
    }

    public function index2(Request $request)
    {
        return view('contacts::contacts.index2');
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $contactCollection = $this->contactService->getTrashedItem();
        } else {
            $contactCollection = $this->contactService->all();
        }
        $rowCount = $this->contactService->getRowCount();
        $trashedCount = $this->contactService->getTrashedCount();

        if ($request->ajax()) {

            if ($request->filter_action_field) {
                $contactCollection = $contactCollection->where('contact_related', $request->filter_action_field);
            }

            return DataTables::of($contactCollection)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="contacts_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })

                ->addColumn('action', function ($row) {
                    $icon3 = '<i class="fa-regular fa-headset"></i>';

                    if ($row->trashed()) {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('contacts.restore', $row->id).'" class="action-btn c-edit restore" title="restore"><i class="fa-solid fa-recycle"></i></a>';
                        $html .= '<a href="'.route('contacts.permanent-delete', $row->id).'" class="action-btn c-delete delete" title="Delete"><i class="fa-solid fa-trash-check"></i></a>';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="dropdown table-dropdown">';
                        $html .= '<a href="'.route('contacts.edit', $row->id).'" class="action-btn c-edit edit" title="Edit"><span class="fas fa-edit"></span></a></a>';
                        $html .= '<a href="'.route('contacts.view', $row->id).'" class="action-btn c-edit view" title="View"><span class="fas fa-eye"></span></a></a>';
                        $html .= '<a href="'.route('contacts.destroy', $row->id).'" class="action-btn c-delete delete"  title="Delete"><span class="fas fa-trash "></span></a>';
                        $html .= '</div>';

                        return $html;
                    }

                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 1) {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.change.status', [$row->id]).'" style="width: 34px;
                                  border-radius: 10px; height: 14px !important;  background-color: #2ea074; margin-left: -7px" type="checkbox" checked  />';
                        $html .= '</div>';

                        return $html;
                    } else {
                        $html = '<div class="form-check form-switch">';
                        $html .= '<input class="form-check-input change_status" data-url="'.route('contacts.change.status', [$row->id]).'" style="width: 34px;
                        border-radius: 10px; height: 14px !important; margin-left: -7px" type="checkbox" />';
                        $html .= '</div>';

                        return $html;
                    }
                })

                ->rawColumns(['action', 'check', 'status'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        $totalContacts = $this->contactService->filterWiseCount('contact_related', 'Contacts');
        $totalLeads = $this->contactService->filterWiseCount('contact_related', 'Leads');
        $totalSuppliers = $this->contactService->filterWiseCount('contact_related', 'Suppliers');
        $totalCustomers = $this->contactService->filterWiseCount('contact_related', 'Customers');
        $totalActive = $this->contactService->filterWiseCount('status', 1);
        $totalInActive = $this->contactService->filterWiseCount('status', 0);

        return view('contacts::contacts.index', compact('totalContacts', 'totalLeads', 'totalSuppliers', 'totalCustomers', 'totalActive', 'totalInActive'));
    }

    public function totalStatus()
    {
        $totalContacts = $this->contactService->filterWiseCount('contact_related', 'Contacts');
        $totalLeads = $this->contactService->filterWiseCount('contact_related', 'Leads');
        $totalSuppliers = $this->contactService->filterWiseCount('contact_related', 'Suppliers');
        $totalCustomers = $this->contactService->filterWiseCount('contact_related', 'Customers');
        $totalActive = $this->contactService->filterWiseCount('status', 1);
        $totalInActive = $this->contactService->filterWiseCount('status', 0);

        return response()->json(['totalContacts', 'totalLeads', 'totalSuppliers', 'totalCustomers', 'totalActive', 'totalInActive']);
    }

    public function basicModal()
    {
        return view('contacts::contacts.ajax_view.contact_create_basic_modal');
    }

    public function detailedModal()
    {
        $references = Source::get();

        return view('contacts::contacts.ajax_view.contact_create_detailed_modal', compact('references'));
    }

    public function create()
    {
        return view('contacts::create');
    }

    public function store(Request $request, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:crm.contacts,phone,',
            'nid_no' => 'nullable|unique:crm.contacts,nid_no,',
            'trade_license_no' => 'nullable|unique:crm.contacts,trade_license_no,',
        ]);

        $contact_file = '';
        $contact_document = '';
        $alternative_file = '';

        if ($request->hasFile('contact_file')) {
            $contact_file = $this->fileUploaderService->upload($request->file('contact_file'), 'uploads/contacts/');
        }

        if ($request->hasFile('alternative_file')) {
            $alternative_file = $this->fileUploaderService->upload($request->file('alternative_file'), 'uploads/contacts/alternative/');
        }

        if ($request->hasFile('contact_document')) {
            $contact_document = $this->fileUploaderService->uploadMultiple($request->file('contact_document'), 'uploads/contacts/documents');
        }

        $generalSettings = DB::table('general_settings')->first('prefix');
        $supIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];
        $contact_auto_id = $supIdPrefix.str_pad($invoiceVoucherRefIdUtil->getLastId('customers'), 4, '0', STR_PAD_LEFT);

        // dd($contact_document, gettype($contact_document));
        // $request->request->add(['contact_auto_id' => $contact_auto_id]);
        // $request->contact_auto_id = $contact_auto_id;

        $request['contact_auto_id'] = $contact_auto_id;
        // $request['contact_file'] = $contact_file;
        // $request['alternative_file'] = $alternative_file;
        // $request['contact_document'] = $contact_document;
        $this->contactService->store($request);

        return response()->json(['success' => 'Contact Create Successfully Done!']);
    }

    public function show($id)
    {
        return view('contacts::show');
    }

    public function changeStatus($id)
    {
        $statusChange = $this->contactService->find($id);
        if ($statusChange->status == 1) {
            $statusChange->status = 0;
            $statusChange->save();

            return response()->json('Contact deactivated successfully');
        } else {
            $statusChange->status = 1;
            $statusChange->save();

            return response()->json('Contact activated successfully');
        }
    }

    public function edit($id)
    {
        $references = Source::get();
        $findContact = $this->contactService->find($id);

        return view('contacts::contacts.ajax_view.edit', compact('references', 'findContact'));
    }

    public function view($id)
    {
        $references = Source::get();
        $findContact = $this->contactService->find($id);

        return view('contacts::contacts.ajax_view.view', compact('references', 'findContact'));
    }

    public function import()
    {
        return view('contacts::contacts.import');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'import_file' => 'required|mimes:csv,xlx,xlsx,xls',
        ]);
        Excel::import(new ContactsImport, $request->import_file);

        return redirect()->back()->with('success', 'Successfully imported!');
    }

    public function update(Request $request, $id)
    {
        $findContact = $this->contactService->find($id);
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:crm.contacts,phone,'.$id,
            'nid_no' => 'nullable|unique:crm.contacts,nid_no,'.$id,
            'trade_license_no' => 'nullable|unique:crm.contacts,trade_license_no,'.$id,
        ]);

        $contact_file = '';
        $contact_document = '';
        $alternative_file = '';

        if ($request->hasFile('contact_document')) {
            $contact_document = $this->fileUploaderService->uploadMultiple($request->file('contact_document'), 'uploads/contacts/documents');
        }

        if ($request->hasFile('contact_file')) {
            if (is_file(public_path('uploads/contacts/'.$findContact?->contact_file))) {
                unlink(public_path('uploads/contacts/'.$findContact->contact_file));
            }
            $contact_file = $this->fileUploaderService->upload($request->file('contact_file'), 'uploads/contacts');
        }

        if ($request->hasFile('alternative_file')) {
            if (is_file(public_path('uploads/contacts/alternative'.$findContact?->alternative_file))) {
                unlink(public_path('uploads/contacts/alternative'.$findContact->alternative_file));
            }
            $alternative_file = $this->fileUploaderService->upload($request->file('alternative_file'), 'uploads/contacts/alternative');
        }

        if ($request->hasFile('contact_document')) {
            $newContactsDocumentsString = $this->fileUploaderService->uploadMultiple($request->file('contact_document'), 'uploads/contacts/documents/');
            $newContactsDocumentsArray = json_decode($newContactsDocumentsString);
            if ($findContact?->contact_document) {
                $oldContactsDocumentsArray = \json_decode($findContact->contact_document, true);
                $mergedFilesArray = array_merge($oldContactsDocumentsArray, $newContactsDocumentsArray);
                $contact_document = json_encode($mergedFilesArray);
            } else {
                $contact_document = json_encode($newContactsDocumentsArray);
            }
        }

        // $request['contact_file'] = $contact_file;
        // $request['alternative_file'] = $alternative_file;
        // $request['contact_document'] = $contact_document;
        $this->contactService->update($request->all(), $id);

        return response()->json(['success' => 'Contact Info Updated Successfully!']);
    }

    public function destroy($id)
    {
        $contactService = $this->contactService->trash($id);

        return response()->json('contact deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->contacts_id)) {
            if ($request->action_type == 'move_to_trash') {
                $contactService = $this->contactService->bulkTrash($request->contacts_id);

                return response()->json('contact deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $contactService = $this->contactService->bulkRestore($request->contacts_id);

                return response()->json('contact restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $contactService = $this->contactService->bulkPermanentDelete($request->contacts_id);

                return response()->json('contact permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function restore($id)
    {
        $contactService = $this->contactService->restore($id);

        return response()->json('contact restore successfully');
    }

    public function permanentDelete($id)
    {
        $contactService = $this->contactService->permanentDelete($id);

        return response()->json('contact permanently delete successfully');
    }
}
