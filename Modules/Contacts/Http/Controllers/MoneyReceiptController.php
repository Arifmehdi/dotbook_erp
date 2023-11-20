<?php

namespace Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Interface\CodeGenerationServiceInterface;
use App\Models\Customer;
use App\Models\MoneyReceipt;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Contacts\Services\MoneyReceiptService;

class MoneyReceiptController extends Controller
{
    protected $moneyReceiptService;

    public function __construct(MoneyReceiptService $moneyReceiptService)
    {
        $this->moneyReceiptService = $moneyReceiptService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index($customerId)
    {
        $customer = Customer::with('receipts')->where('id', $customerId)->first();

        return view('contacts::customers.money_receipts.index', compact('customer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create($customerId)
    {
        $customer = Customer::where('id', $customerId)->first();

        return view('contacts::customers.money_receipts.create', compact('customer'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, $customerId, CodeGenerationServiceInterface $codeGenerationService)
    {
        try {

            DB::beginTransaction();

            $addMoneyReceipt = $this->moneyReceiptService->addMoneyReceipt($customerId, $request, $codeGenerationService);

            $receipt = DB::table('money_receipts')
                ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
                ->select('money_receipts.*', 'customers.name as cus_name')->where('money_receipts.id', $addMoneyReceipt->id)->first();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return view('contacts::customers.money_receipts.print_receipt', compact('receipt'));
    }

    public function moneyReceiptPrint($receiptId)
    {
        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->select('money_receipts.*', 'customers.name as cus_name')->where('money_receipts.id', $receiptId)->first();

        return view('contacts::customers.money_receipts.print_receipt', compact('receipt'));
    }

    public function changeStatusModal($receiptId)
    {
        $paymentMethods = PaymentMethod::get();

        $receipt = DB::table('money_receipts')->where('id', $receiptId)->first();
        $accounts = DB::table('accounts')->get();

        return view('contacts::customers.money_receipts.change_status_modal', compact('paymentMethods', 'receipt', 'accounts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($receiptId)
    {
        $receipt = DB::table('money_receipts')
            ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
            ->select('money_receipts.*', 'customers.name as cus_name', 'customers.phone as cus_phone', 'customers.business_name as cus_business')->where('money_receipts.id', $receiptId)->first();

        return view('contacts::customers.money_receipts.edit', compact('receipt'));
    }

    public function update(Request $request, $receiptId)
    {
        try {

            DB::beginTransaction();

            $updateMoneyReceipt = $this->moneyReceiptService->updateMoneyReceipt($request, $receiptId);

            $receipt = DB::table('money_receipts')
                ->leftJoin('customers', 'money_receipts.customer_id', 'customers.id')
                ->select('money_receipts.*', 'customers.name as cus_name')
                ->where('money_receipts.id', $receiptId)->first();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return view('contacts::customers.money_receipts.print_receipt', compact('receipt'));
    }

    public function delete($receiptId)
    {
        try {

            DB::beginTransaction();

            $delete = MoneyReceipt::find($receiptId);

            if (! is_null($delete)) {

                $delete->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Successfully money receipt voucher is deleted');
    }

    public function changeStatus(Request $request, $receiptId)
    {
        $prefixSettings = DB::table('general_settings')->select(['id', 'prefix'])->first();
        $paymentInvoicePrefix = json_decode($prefixSettings->prefix, true)['sale_payment'];

        $receipt = MoneyReceipt::where('id', $receiptId)->first();
        $receipt->received_amount = $request->amount;
        $receipt->payment_method_id = $request->payment_method_id;
        $receipt->status = 'Completed';
        $receipt->save();

        $customer = Customer::where('id', $receipt->customer_id)->first();
        $customer->total_paid += $request->amount;
        $customer->total_sale_due -= $request->amount;
        $customer->save();

        return response()->json('Successfully money receipt voucher is completed.');
    }
}
