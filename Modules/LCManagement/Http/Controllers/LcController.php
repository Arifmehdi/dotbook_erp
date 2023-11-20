<?php

namespace Modules\LCManagement\Http\Controllers;

use App\Utils\Converter;
use App\Utils\InvoiceVoucherRefIdUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\LCManagement\Entities\LC;
use Yajra\DataTables\Facades\DataTables;

class LcController extends Controller
{
    protected $converter;

    protected $invoiceVoucherRefIdUtil;

    public function __construct(Converter $converter, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->converter = $converter;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    public function index(Request $request)
    {

        if (! auth()->user()->can('opening_lc_index')) {
            abort(403, 'Access denied.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();
            $lcs = '';
            $query = DB::table('lcs')
                ->leftJoin('suppliers', 'lcs.supplier_id', 'suppliers.id')
                ->leftJoin('currencies', 'lcs.currency_id', 'currencies.id')
                ->leftJoin('banks as advising_bank', 'lcs.advising_bank_id', 'advising_bank.id')
                ->leftJoin('banks as issuing_bank', 'lcs.issuing_bank_id', 'issuing_bank.id')
                ->leftJoin('banks as opening_bank', 'lcs.opening_bank_id', 'opening_bank.id');

            if ($request->from_date) {

                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                // $date_range = [$from_date . ' 00:00:00', $to_date . ' 00:00:00'];
                $date_range = [Carbon::parse($from_date), Carbon::parse($to_date)->endOfDay()];
                $query->whereBetween('lcs.opening_date', $date_range); // Final
            }

            $query->select(
                'lcs.*',
                'suppliers.name as sup_name',
                'currencies.code as currency_code',
                'advising_bank.name as advising_bank',
                'issuing_bank.name as issuing_bank',
                'opening_bank.name as opening_bank',
            );

            $lcs = $query->orderBy('lcs.opening_date', 'desc');

            return DataTables::of($lcs)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('opening_lc_view')) {
                        $html .= '<a class="dropdown-item details_button" href="'.route('manage.lc.show', [$row->id]).'"><i class="far fa-eye mr-1 text-primary"></i> View</a>';
                    }

                    if (auth()->user()->can('opening_lc_update')) {
                        $html .= '<a class="dropdown-item" id="edit" href="'.route('manage.lc.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }

                    if (auth()->user()->can('opening_lc_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('manage.lc.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('opening_date', function ($row) {
                    return date('d/m/Y', strtotime($row->opening_date));
                })
                ->editColumn('last_date', function ($row) {
                    return date('d/m/Y', strtotime($row->last_date));
                })
                ->editColumn('expire_date', function ($row) {
                    return date('d/m/Y', strtotime($row->expire_date));
                })

                ->editColumn('total_amount', fn ($row) => '<span class="total_amount" data-value="'.$row->total_amount.'">'.$this->converter->format_in_bdt($row->total_amount).'</span>')

                ->editColumn('lc_margin_amount', fn ($row) => '<span class="lc_margin_amount" data-value="'.$row->lc_margin_amount.'">'.$this->converter->format_in_bdt($row->lc_margin_amount).'</span>')

                ->editColumn('insurance_payable_amt', fn ($row) => '<span class="insurance_payable_amt" data-value="'.$row->insurance_payable_amt.'">'.$this->converter->format_in_bdt($row->insurance_payable_amt).'</span>')

                ->editColumn('mode_of_amount', fn ($row) => '<span class="mode_of_amount" data-value="'.$row->mode_of_amount.'">'.$this->converter->format_in_bdt($row->mode_of_amount).'</span>')

                ->editColumn('total_payable_amt', fn ($row) => '<span class="total_payable_amt" data-value="'.$row->total_payable_amt.'">'.$this->converter->format_in_bdt($row->total_payable_amt).'</span>')

                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('manage.lc.show', [$row->id]);
                    },
                ])

                ->setRowClass('clickable_row')

                ->rawColumns(['action', 'opening_date', 'last_date', 'expire_date', 'total_amount', 'lc_margin_amount', 'insurance_payable_amt', 'mode_of_amount', 'total_payable_amt'])
                ->make(true);
        }

        $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();

        $banks = DB::table('banks')->select('id', 'name')->get();

        $currencies = $lcs = DB::table('currencies')->select('id', 'code')->get();

        return view('lcmanagement::manage_lc.index', compact('suppliers', 'banks', 'currencies'));
    }

    public function show($id)
    {

        if (! auth()->user()->can('opening_lc_view')) {

            abort(403, 'Access denied.');
        }

        $lc = LC::with(['supplier:id,name,phone', 'advisingBank', 'openingBank', 'issuingBank', 'createdBy:id,prefix,name,last_name', 'currency:id,code'])->where('id', $id)->first();

        return view('lcmanagement::manage_lc.ajax_view.show', compact('lc'));
    }

    public function store(Request $request)
    {

        if (! auth()->user()->can('opening_lc_create')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'opening_date' => 'required',
            'last_date' => 'required',
            'expire_date' => 'required',
            // 'type' => 'required',
            'currency_id' => 'required',
            // 'lc_amount' => 'required',
            // 'currency_rate' => 'required',
            // 'total_amount' => 'required',
            // 'lc_margin_amount' => 'required',
            // 'supplier_id' => 'required',
            // 'advising_bank_id' => 'required',
            // 'issuing_bank_id' => 'required',
            // 'opening_bank_id' => 'required',
        ], [
            // 'supplier_id.required' => 'The supplier field is required.',
            // 'advising_bank_id.required' => 'The advising bank field is required.',
            // 'issuing_bank_id.required' => 'The issuing bank field is required.',
            // 'opening_bank_id.required' => 'The opening bank field is required.',
            'currency_id.required' => 'The currency field is required.',
        ]);

        $addLc = new LC();
        $addLc->lc_no = $request->lc_no ? $request->lc_no : 'LC'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('lcs'), 5, '0', STR_PAD_LEFT);
        $addLc->opening_date = date('Y-m-d H:i:s', strtotime($request->opening_date));
        $addLc->last_date = date('Y-m-d H:i:s', strtotime($request->last_date));
        $addLc->expire_date = date('Y-m-d H:i:s', strtotime($request->expire_date));
        $addLc->type = $request->type;
        $addLc->currency_id = $request->currency_id;

        // $addLc->lc_amount = $request->lc_amount;
        // $addLc->currency = $request->currency;
        // $addLc->currency_rate = $request->currency_rate;
        // $addLc->total_amount = $request->total_amount;
        // $addLc->lc_margin_amount = $request->lc_margin_amount;
        // $addLc->insurance_company = $request->insurance_company;
        // $addLc->insurance_payable_amt = $request->insurance_payable_amt;
        // $addLc->shipment_mode = $request->shipment_mode;
        // $addLc->mode_of_amount = $request->mode_of_amount;
        // $addLc->total_payable_amt = $request->total_payable_amt;
        // $addLc->supplier_id = $request->supplier_id;
        // $addLc->advising_bank_id = $request->advising_bank_id;
        // $addLc->issuing_bank_id = $request->issuing_bank_id;
        // $addLc->opening_bank_id = $request->opening_bank_id;
        $addLc->created_by_id = auth()->user()->id;
        $addLc->save();

        return response()->json('Successfully LC is opened.');
    }

    public function edit($id)
    {

        if (! auth()->user()->can('opening_lc_update')) {

            abort(403, 'Access denied.');
        }

        $lc = LC::where('id', $id)->first();

        // $suppliers = DB::table('suppliers')->select('id', 'name', 'phone')->get();

        $banks = DB::table('banks')->select('id', 'name')->get();
        $currencies = $lcs = DB::table('currencies')->select('id', 'code')->get();

        return view('lcmanagement::manage_lc.ajax_view.edit', compact(
            'lc',
            // 'suppliers',
            'banks',
            'currencies',
        ));
    }

    public function update(Request $request, $id)
    {

        if (! auth()->user()->can('opening_lc_update')) {

            abort(403, 'Access denied.');
        }

        $this->validate($request, [
            'opening_date' => 'required',
            'last_date' => 'required',
            'expire_date' => 'required',
            // 'type' => 'required',
            'currency_id' => 'required',
            // 'lc_amount' => 'required',
            // 'currency_rate' => 'required',
            // 'total_amount' => 'required',
            // 'lc_margin_amount' => 'required',
            // 'supplier_id' => 'required',
            // 'advising_bank_id' => 'required',
            // 'issuing_bank_id' => 'required',
            // 'opening_bank_id' => 'required',
        ], [
            // 'supplier_id.required' => 'The supplier field is required.',
            // 'advising_bank_id.required' => 'The advising bank field is required.',
            // 'issuing_bank_id.required' => 'The issuing bank field is required.',
            // 'opening_bank_id.required' => 'The opening bank field is required.',
            'currency_id.required' => 'The currency field is required.',
        ]);

        $updateLc = LC::where('id', $id)->first();

        $updateLc->lc_no = $request->lc_no ? $request->lc_no : 'LC'.str_pad($this->invoiceVoucherRefIdUtil->getLastId('lcs'), 5, '0', STR_PAD_LEFT);
        $updateLc->opening_date = date('Y-m-d H:i:s', strtotime($request->opening_date));
        $updateLc->last_date = date('Y-m-d H:i:s', strtotime($request->last_date));
        $updateLc->expire_date = date('Y-m-d H:i:s', strtotime($request->expire_date));
        $updateLc->type = $request->type;
        $updateLc->currency_id = $request->currency_id;
        // $updateLc->lc_amount = $request->lc_amount;
        // $updateLc->currency = $request->currency;
        // $updateLc->currency_rate = $request->currency_rate;
        // $updateLc->total_amount = $request->total_amount;
        // $updateLc->lc_margin_amount = $request->lc_margin_amount;
        // $updateLc->insurance_company = $request->insurance_company;
        // $updateLc->insurance_payable_amt = $request->insurance_payable_amt;
        // $updateLc->shipment_mode = $request->shipment_mode;
        // $updateLc->mode_of_amount = $request->mode_of_amount;
        // $updateLc->total_payable_amt = $request->total_payable_amt;
        // $updateLc->supplier_id = $request->supplier_id;
        // $updateLc->advising_bank_id = $request->advising_bank_id;
        // $updateLc->issuing_bank_id = $request->issuing_bank_id;
        // $updateLc->opening_bank_id = $request->opening_bank_id;
        $updateLc->updated_by_id = auth()->user()->id;
        $updateLc->save();

        return response()->json('Successfully LC is opened.');
    }

    public function delete($id)
    {

        if (! auth()->user()->can('opening_lc_delete')) {

            abort(403, 'Access denied.');
        }

        $deleteLc = LC::where('id', $id)->first();
        $deleteLc->delete();

        return response()->json('Successfully LC is deleted.');
    }
}
