<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountGroupController extends Controller
{
    public function index()
    {
        if (! auth()->user()->can('account_groups_index')) {

            abort(403, 'Access Forbidden.');
        }

        return view('finance.accounting.groups.index');
    }

    public function groupList()
    {
        if (! auth()->user()->can('account_groups_index')) {

            abort(403, 'Access Forbidden.');
        }

        $groups = AccountGroup::with(
            'subgroups',
        )->where('is_main_group', 1)->get();

        return view('finance.accounting.groups.ajax_view.list', compact('groups'));
    }

    public function create()
    {
        if (! auth()->user()->can('account_groups_add')) {

            abort(403, 'Access Forbidden.');
        }

        $groupBuilder = new AccountGroup;

        $query2 = $groupBuilder;

        $formGroups = $query2->with('parentGroup')->where('is_main_group', 0)->get();

        return view('finance.accounting.groups.ajax_view.create', compact('formGroups'));
    }
    
    public function edit($id)
    {
        if (! auth()->user()->can('account_groups_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $formGroups = AccountGroup::with('parentGroup')->where('is_main_group', 0)->get();
        $gp = AccountGroup::with('parentGroup')->where('id', $id)->first();

        return view('finance.accounting.groups.ajax_view.edit', compact('formGroups', 'gp'));
    }

    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('account_groups_edit')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_group_id' => 'required',
        ]);

        try {

            DB::beginTransaction();

            $parentGroup = DB::table('account_groups')->where('id', $request->parent_group_id)->first();

            $updateGroup = AccountGroup::where('id', $id)->first();
            $updateGroup->name = $request->name;

            $updateGroup->is_default_tax_calculator = $request->is_default_tax_calculator;
            $updateGroup->is_allowed_bank_details = $request->is_allowed_bank_details;

            if ($updateGroup->is_reserved == 0) {

                $updateGroup->parent_group_id = $request->parent_group_id;
                $updateGroup->is_bank_or_cash_ac = $parentGroup->is_bank_or_cash_ac;
                $updateGroup->main_group_number = $parentGroup->main_group_number;
                $updateGroup->sub_group_number = $parentGroup->sub_group_number;
                $updateGroup->sub_sub_group_number = $parentGroup->sub_sub_group_number;
                $updateGroup->main_group_name = $parentGroup->main_group_name;
                $updateGroup->sub_group_name = $parentGroup->sub_group_name;
                $updateGroup->sub_sub_group_name = $parentGroup->sub_sub_group_name;
            }

            $updateGroup->save();

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Account Group Updated Successfully');
    }

    public function delete(Request $request, $id)
    {
        if (! auth()->user()->can('account_groups_delete')) {

            abort(403, 'Access Forbidden.');
        }

        try {

            DB::beginTransaction();
            // database queries here. Access any $var_N directly
            $deleteGroup = AccountGroup::with('subGroups', 'accounts')->where('id', $id)->first();

            if (! is_null($deleteGroup)) {

                if ($deleteGroup->is_reserved == 1 || count($deleteGroup->subGroups) > 0 || count($deleteGroup->accounts)) {

                    return response()->json(['errorMsg' => 'Account Group can not be deleted']);
                }

                $deleteGroup->delete();
            }

            DB::commit();
        } catch (Exception $e) {

            DB::rollBack();
        }

        return response()->json('Account Group is deleted Successfully');
    }
}
