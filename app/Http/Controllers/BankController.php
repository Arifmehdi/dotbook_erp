<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BankController extends Controller
{
    protected $userActivityLogUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    // Bank main page/index page
    public function index(Request $request)
    {
        if (!auth()->user()->can('banks')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $banks = DB::table('banks')->orderBy('banks.name', 'asc')->get();

            return DataTables::of($banks)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('accounting.banks.edit', $row->id) . '" class="action-btn c-edit" id="edit" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('accounting.banks.delete', [$row->id]) . '" class="action-btn c-delete" id="delete" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })->rawColumns(['action'])->smart(true)->make(true);
        }

        return view('finance.accounting.banks.index');
    }

    public function create()
    {
        return view('finance.accounting.banks.ajax_view.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:banks,name',
        ]);

        $addBank = Bank::create([
            'name' => $request->name,
        ]);

        $this->userActivityLogUtil->addLog(action: 1, subject_type: 16, data_obj: $addBank);

        return $addBank;
    }

    public function edit($bankId)
    {
        $bank = DB::table('banks')->where('id', $bankId)->first();
        return view('finance.accounting.banks.ajax_view.edit', compact('bank'));
    }

    public function update(Request $request, $bankId)
    {
        $this->validate($request, [
            'name' => 'required:unique:banks,name'.$bankId,
        ]);

        $updateBank = Bank::where('id', $bankId)->first();

        $updateBank->update([
            'name' => $request->name,
        ]);

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 16, data_obj: $updateBank);

        return response()->json(__("Bank updated successfully"));
    }

    public function delete(Request $request, $bankId)
    {
        $deleteBank = Bank::with(['accounts'])->where('id', $bankId)->first();

        if (!is_null($deleteBank)) {

            if (count($deleteBank->accounts) > 0) {

                return response()->json('Can not be deleted, bank has one or more accounts.');
            }

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 16, data_obj: $deleteBank);

            $deleteBank->delete();
        }

        return response()->json(__('Bank deleted successfully'));
    }
}
