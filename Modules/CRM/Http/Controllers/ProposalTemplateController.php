<?php

namespace Modules\CRM\Http\Controllers;

use App\Interface\CodeGenerationServiceInterface;
use App\Interface\FileUploaderServiceInterface;
use App\Models\Customer;
use App\Models\CustomerDetails;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\QuotationUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Modules\Communication\Emails\CustomerBulkEmail;
use Modules\Communication\Entities\Email;
use Modules\Communication\Interface\EmailServiceInterface;
use Modules\CRM\Entities\IndividualLead;
use Modules\CRM\Entities\Proposal;
use Modules\CRM\Entities\ProposalComment;
use Modules\CRM\Entities\ProposalDetails;
use Modules\CRM\Entities\ProposalTemplate;
use Modules\CRM\http\Requests\ProposalTemplate\ProposalTemplateAddRequest;
use Modules\CRM\Http\Requests\ProposalTemplate\ProposalTemplateUpdateRequest;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class ProposalTemplateController extends Controller
{
    private $image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

    private $IndividualLeadService;

    private $businessLeadService;

    public $invoiceVoucherRefIdUtil;

    public $quotationUtil;

    private $emailService;

    public function __construct(EmailServiceInterface $emailService, IndividualLeadServiceInterface $IndividualLeadService, QuotationUtil $quotationUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil, BusinessLeadServiceInterface $businessLeadService)
    {
        $this->IndividualLeadService = $IndividualLeadService;
        $this->businessLeadService = $businessLeadService;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
        $this->quotationUtil = $quotationUtil;
        $this->emailService = $emailService;
    }

    public function commonTotal($amounts)
    {
        $subTotal = 0;
        foreach ($amounts as $amount) {
            $subTotal += $amount;
        }

        return $subTotal;
    }

    private function file_upload($files, $id)
    {
        $attachment_prefix = Carbon::now()->toDateString();
        $attachments_array = [];

        for ($i = 0; $i < count($files); $i++) {
            $attachment = $files[$i];
            $attachment_extension = $attachment->getClientOriginalExtension();
            $attachment_name = $attachment_prefix.'_'.$id.'_'.($i + 1).'.'.$attachment_extension;
            if (in_array($attachment_extension, $this->image_extensions)) {
                Image::make($attachment)->resize(250, 250)->save('uploads/proposal_template/'.$attachment_name);
            } else {
                $attachment->move(public_path('uploads/proposal_template/'), $attachment_name);
            }
            array_push($attachments_array, $attachment_name);
        }

        return $attachments_array;
    }

    public function index(Request $request)
    {
        // $this->authorize('crm_proposals_template_index');
        if ($request->ajax()) {
            $proposal_template = ProposalTemplate::with('individualLeadsUser', 'customer')->get();

            return DataTables::of($proposal_template)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    // $html .= '<a class="dropdown-item" href="' . route('crm.proposal_template.view', $row->id) . '" id="view"><i class="far fa-eye text-primary"></i> View</a>';
                    // $html .= '<a class="dropdown-item" href="' . route('crm.proposal_template.edit', $row->id) . '" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.proposal_template.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '<a class="dropdown-item" id="convert" href="'.route('crm.proposal_template.send', $row->id).'"><i class="fa fa-undo text-primary"></i> Send Email</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->addColumn('name', function ($row) {
                    return strip_tags($row->customer->name ?? $row->individualLeadsUser->name);
                })
                ->addColumn('type', function ($row) {
                    return strip_tags(ucwords($row->related));
                })
                ->editColumn('body', function ($row) {
                    return strip_tags($row->body);
                })
                ->rawColumns(['action', 'name', 'type'])
                ->smart(true)
                ->make(true);
        }

        $customerLeads = Customer::where('is_lead', 1)->get();
        $products = Product::get();
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $individualLeadsUser = IndividualLead::orderBy('id', 'desc')->get();

        return view('crm::proposal_template.index', compact('customerLeads', 'individualLeadsUser', 'products', 'suppliers'));
    }

    public function addProductModalView()
    {
        $units = DB::table('units')->select('id', 'name', 'code_name')->get();

        $warranties = DB::table('warranties')->select('id', 'name', 'type')->get();

        $taxes = DB::table('taxes')->select('id', 'tax_name', 'tax_percent')->get();

        $categories = DB::table('categories')->where('parent_category_id', null)->orderBy('id', 'DESC')->get();

        $brands = DB::table('brands')->get();

        return view('crm::proposal_template.ajax_view.add_product_modal_view', compact('units', 'warranties', 'taxes', 'categories', 'brands'));
    }

    public function getLeadsCustomers($type)
    {
        $leadsUser = '';
        // $businessLeadsUser = BusinessLead::orderBy('id', 'desc')->get();
        // $leadsUser = $businessLeadsUser->merge($individualLeadsUser);
        if ($type == 'lead') {
            $leadsUser = IndividualLead::orderBy('id', 'desc')->get();
        } elseif ($type == 'customer') {
            $leadsUser = Customer::where('is_lead', 1)->get();
        } else {
            $leadsUser = '';
        }

        return \response()->json(['leadsUser' => $leadsUser, 'type' => $type]);
    }

    public function productDetails($id)
    {
        $products = Product::with('variants')->where('id', $id)->first();

        return \response()->json($products);
    }

    public function findLeadsAddress(Request $request, $id, $type)
    {
        $address = '';
        if ($type == 'lead') {
            $address = IndividualLead::find($id);
        } elseif ($type == 'customer') {
            $address = Customer::find($id);
        } else {
            $address = '';
        }

        return \response()->json(['address' => $address, 'type' => $type]);
    }

    public function store(ProposalTemplateAddRequest $request, FileUploaderServiceInterface $fileUploaderService, CodeGenerationServiceInterface $generator)
    {
        $proposal_file = '';
        if ($request->hasFile('proposal_file')) {
            $proposal_file = $fileUploaderService->uploadMultiple($request->file('proposal_file'), 'uploads/proposal_template');
        }
        if ($request->rel_type == 'lead') {
            $leads = $request->customer_leads;
        } elseif ($request->rel_type == 'customer') {
            $customer = $request->customer_leads;
        } else {
            return false;
        }

        $totalItems = \count($request->name);

        $proposal_template = new \Modules\CRM\Entities\ProposalTemplate();
        $proposal_template->subject = $request->subject;
        $proposal_template->bcc = json_encode(explode(',', $request->bcc));
        $proposal_template->cc = json_encode(explode(',', $request->cc));
        $proposal_template->proposal_id = $generator->generateMonthWise(connection: 'crm', table: 'proposal_templates', column: 'proposal_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
        $proposal_template->body = $request->description;
        $proposal_template->attachments = $proposal_file;
        $proposal_template->related = $request->rel_type;
        $proposal_template->lead_id = isset($leads) ? $leads : null;
        $proposal_template->customer_id = isset($customer) ? $customer : null;
        $proposal_template->status = $request->status;
        $proposal_template->supplier_id = $request->assigned;
        $proposal_template->date = $request->date;
        $proposal_template->open_till = $request->open_till;
        $proposal_template->currency = $request->currency;
        $proposal_template->tags = $request->tags;
        $proposal_template->to = $request->proposal_to;
        $proposal_template->address = $request->address;
        $proposal_template->city = $request->city;
        $proposal_template->state = $request->state;
        $proposal_template->country = $request->country;
        $proposal_template->zip = $request->zip;
        $proposal_template->phone = $request->phone;
        $proposal_template->email = $request->email;
        $proposal_template->discount = $request->discount_sub_total;
        $proposal_template->sub_total = $request->sub_total;
        $proposal_template->total = $request->total_calculate;
        $proposal_template->discount_type = $request->discount_before_after_tax;
        $proposal_template->total_item = $totalItems;
        $proposal_template->total_qty = $this->commonTotal($request->qty);
        $proposal_template->save();
        $id = $proposal_template->id;
        // $proposal_template->comp_des_header = $request->
        // $proposal_template->comp_des_footer = $request->
        // $proposal_template->taxes =

        foreach ($request->name as $i => $name) {
            $proposal_details = new ProposalDetails();
            $proposal_details->name = $name;
            $proposal_details->proposal_template_id = $id;
            $proposal_details->item_id = $request->item_id[$i];
            $proposal_details->details = $request->details[$i];
            $proposal_details->qty = $request->qty[$i];
            $proposal_details->rate = $request->rate[$i];
            $proposal_details->tax = $request->tax[$i];
            $proposal_details->tax_type = $request->tax_type[$i];
            $proposal_details->discount = $request->discount[$i];
            $proposal_details->discount_type = $request->discount_type[$i];
            $proposal_details->amount = $request->amount[$i];
            $proposal_details->save();
        }

        return response()->json('Template created successfully');
    }

    public function total(Request $request)
    {
        return $this->commonTotal($request->amount);
    }

    public function view(Request $request, $id)
    {
        $proposal_template = ProposalTemplate::with('proposal_details')->find($id);
        $proposal = Proposal::where('proposal_template_id', $id)->first();
        $ProposalComments = ProposalComment::where('proposal_id', $proposal->id)->get();

        return view('crm::proposal_template.view', \compact('proposal_template', 'ProposalComments'));
    }

    public function send(Request $request, $id)
    {
        $proposal_template = ProposalTemplate::find($id);
        $proposal = new Proposal();
        $proposal->proposal_id = $proposal_template->proposal_id;
        $proposal->proposal_template_id = $id;
        $proposal->save();

        $emailArray = array_merge(json_decode($proposal_template->cc), json_decode($proposal_template->bcc));

        $subject = $proposal_template->subject;
        $body = $proposal_template->description;
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

        return Redirect::back()->with('msg', 'Send Mail');

    }

    public function edit(Request $request, $id)
    {
        $proposal_template = ProposalTemplate::with('proposal_details')->find($id);
        $products = Product::get();

        return view('crm::proposal_template.ajax_view.edit', \compact('proposal_template', 'products'));
    }

    public function decline(Request $request, $id)
    {
        $exist = ProposalTemplate::where('proposal_id', $id)->first();
        if ($exist && $exist->status == 1) {
            $exist->status = 2;
            $exist->save();

            return 'Proposal successfully declined';
        } else {
            return response()->json(['errorMsg' => 'Operation failed']);
        }
    }

    public function accept(Request $request, $id, CodeGenerationServiceInterface $generator)
    {

        $proposalTemplates = ProposalTemplate::where('id', $id)->first();

        if ($proposalTemplates->related == 'lead') {
            $indLeadsUser = IndividualLead::where('id', $proposalTemplates->lead_id)->first();
            $generalSettings = DB::table('general_settings')->first('prefix');
            $cusIdPrefix = json_decode($generalSettings->prefix, true)['customer_id'];

            $addCustomer = Customer::create([
                'contact_id' => $cusIdPrefix.str_pad($this->invoiceVoucherRefIdUtil->getLastId('customers'), 4, '0', STR_PAD_LEFT),
                'name' => $indLeadsUser->name,
                'phone' => $indLeadsUser->phone_numbers,
                'business_name' => $indLeadsUser->companies,
                'email' => $indLeadsUser->email_addresses,
                'address' => $indLeadsUser->address,
                'created_by_id' => auth()->user()->id,
            ]);

            $customerDetails = CustomerDetails::create([
                'customer_id' => $addCustomer->id,
                'contact_type' => 1,
            ]);
            $customerId = $addCustomer->id;
        } else {
            $customerId = $proposalTemplates->customer_id;
        }

        if ($proposalTemplates && $proposalTemplates->status == 1) {
            $proposalTemplates->status = 3;
            $proposalTemplates->sub_total = $request->subTotalAmount;
            $proposalTemplates->discount = $request->totalDiscount;
            $proposalTemplates->total = $request->totalAmount;
            $proposalTemplates->save();

            foreach ($request->details_id as $i => $id) {
                ProposalDetails::where('id', $id)->update([
                    'qty' => $request->qty[$i],
                    'amount' => $request->amount[$i],
                    'rate' => $request->rate[$i],
                ]);
            }

            $settings = DB::table('general_settings')->select(['id', 'business', 'prefix', 'send_es_settings'])->first();
            $defaultSchemas = DB::table('invoice_schemas')->where('is_default', 1)->first();
            $invoicePrefix = 'Q-';
            $quotationId = $generator->generateMonthWise(connection: 'mysql', table: 'sales', column: 'quotation_id', prefix: auth()->user()->user_id, splitter: '-', suffixSeparator: '-');
            $userId = auth()->user()->id;

            $addQuotation = new Sale();
            $addQuotation->quotation_id = $invoicePrefix.$quotationId;
            $addQuotation->admin_id = auth()->user()->id;
            $addQuotation->quotation_by_id = $userId;
            $addQuotation->customer_id = $customerId;
            $addQuotation->status = 4;
            $addQuotation->quotation_status = 1;
            $addQuotation->quotation_date = date('Y-m-d H:i:s', strtotime($proposalTemplates->date.date(' H:i:s')));
            $addQuotation->expire_date = $proposalTemplates->open_till ? date('Y-m-d H:i:s', strtotime($proposalTemplates->open_till.Carbon::now()->format('H:i:s'))) : null;
            $addQuotation->total_item = $proposalTemplates->total_item;
            $addQuotation->net_total_amount = $proposalTemplates->sub_total;
            $addQuotation->order_discount_type = 0;
            $addQuotation->order_discount = $proposalTemplates->discount;
            $addQuotation->order_discount_amount = $proposalTemplates->discount;
            $addQuotation->order_tax_percent = $proposalTemplates->order_tax ? $proposalTemplates->order_tax : 0.00;
            $addQuotation->order_tax_amount = $proposalTemplates->taxes ? $proposalTemplates->taxes : 0.00;
            $addQuotation->shipment_charge = 0.00;
            $addQuotation->total_payable_amount = $proposalTemplates->total;
            $addQuotation->sale_note = null;
            $addQuotation->save();

            $__index = 0;
            foreach ($request->item_id as $product_id) {
                $this->quotationUtil->addQuotationProduct(productId: $product_id, quotationId: $addQuotation->id, request: $proposalTemplates, index: $__index);
                $__index++;
            }

            // $quotation = Sale::with([
            //     'customer',
            //     'saleProducts',
            //     'saleProducts.product:id,name,product_code,is_manage_stock',
            //     'saleProducts.variant:id,variant_name,variant_code',
            //     'quotationBy:id,prefix,name,last_name',
            // ])->where('id', $addQuotation->id)->first();

            // $this->userActivityLogUtil->addLog(action:1, subject_type:29, data_obj:$quotation);
            // $customerCopySaleProducts = $this->saleUtil->customerCopySaleProductsQuery($quotation->id);

            // DB::beginTransaction();
            // try {
            //     DB::commit();
            // } catch (\Exception $e) {
            //     DB::rollback();
            // }
            return 'Proposal successfully accepted';
        } else {
            return 'Operation failed';
        }

    }

    public function comment(Request $request, $id)
    {
        $propoComment = new ProposalComment();
        $propoComment->proposal_id = $id;
        $propoComment->comments = $request->comment;
        $propoComment->save();
        $success = 'successfully comment send';

        return \response()->json(['success' => $success]);
    }

    public function getComment($id)
    {
        $comments = ProposalComment::where('proposal_id', $id)->get();

        return response()->json($comments);
    }

    public function delete_attachment(Request $request, $id, $attachment)
    {
        $proposal_template = ProposalTemplate::find($id);
        $attachments = json_decode($proposal_template->attachments, true);

        $key = array_search($attachment, $attachments);

        unlink(\public_path('uploads/proposal_template/'.$attachment));

        unset($attachments[$key]);

        $proposal_template->attachments = json_encode($attachments);
        $proposal_template->save();

        return ['message' => 'Attachment deleted!'];
    }

    public function update(ProposalTemplateUpdateRequest $request, $id, FileUploaderServiceInterface $fileUploaderService)
    {
        $proposal_template = ProposalTemplate::find($id);

        $proposal_template->subject = $request->subject;

        $proposal_template->cc = json_encode(explode(',', $request->cc));
        $proposal_template->bcc = json_encode(explode(',', $request->bcc));
        $proposal_template->body = $request->description;

        if ($request->hasFile('proposal_file')) {
            $oldFilesJsonArray = json_decode($proposal_template->attachments, true);
            $attachments_array_as_json = $fileUploaderService->uploadMultiple($request->file('proposal_file'), 'uploads/proposal_template');
            $newFilesAsArray = json_decode($attachments_array_as_json, true);
            $updatedAttachmentsJson = \json_encode(array_merge($oldFilesJsonArray, $newFilesAsArray));
            $proposal_template->attachments = $updatedAttachmentsJson;
        }

        $proposal_template->save();

        return response()->json('Template updated successfully');
    }

    public function delete(Request $request, $id)
    {
        $proposal_template = ProposalTemplate::with('proposal_details')->find($id);

        if ($proposal_template->attachments) {
            $attachments_array = \json_decode($proposal_template->attachments, true);
            foreach ($attachments_array as $attachment) {
                unlink(\public_path('uploads/proposal_template/'.$attachment));
            }
        }
        if (count($proposal_template->proposal_details) > 0) {
            ProposalDetails::where('proposal_template_id', $proposal_template->id)->delete();
        }
        $proposal_template->delete();

        return response()->json(['errorMsg' => 'Template delete successfully']);
    }
}
