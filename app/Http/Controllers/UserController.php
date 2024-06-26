<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    protected $util;

    protected $invoiceVoucherRefIdUtil;

    public function __construct(Util $util, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->util = $util;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    // Users index view
    public function index(Request $request)
    {
        if (! auth()->user()->can('user_view')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $users = '';
            $users = DB::table('users')->select('users.*')->orderBy('id', 'desc');

            return DataTables::of($users)
                ->addColumn('action', function ($row) {
                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item details_button" href="'.route('users.show', [$row->id]).'"><i class="far fa-eye text-primary"></i> View</a>';
                    $html .= '<a class="dropdown-item" id="edit" href="'.route('users.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit </a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('users.delete', [$row->id]).'"><i class="fas fa-trash-alt text-primary"></i> Delete </a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })

                ->editColumn('role_name', function ($row) {

                    $user = User::find($row->id);

                    return $user?->roles->first()?->name ?? 'N/A';
                })
                ->editColumn('username', function ($row) {

                    if ($row->username) {

                        return $row->username;
                    } else {

                        return '...';
                    }
                })
                ->editColumn('name', function ($row) {

                    return $row->prefix.' '.$row->name.' '.$row->last_name;
                })
                ->editColumn('allow_login', function ($row) {

                    if ($row->allow_login == 1) {

                        return '<span  class="badge badge-sm bg-success">Allowed</span>';
                    } else {

                        return '<span  class="badge badge-sm bg-danger">Not-Allowed</span>';
                    }
                })
                ->rawColumns(['action', 'role_name', 'name', 'username', 'allow_login'])
                ->make(true);
        }

        return view('users.index');
    }

    // Create user view
    public function create()
    {
        if (! auth()->user()->can('user_add')) {

            abort(403, 'Access Forbidden.');
        }

        return view('users.create');
    }

    // Add/Store user
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);

        if (isset($request->allow_login)) {

            $this->validate($request, [
                'username' => 'required',
                'password' => 'required|confirmed',
            ]);
        }

        $addUser = new User();
        $addUser->user_id = 1000 + $this->invoiceVoucherRefIdUtil->getLastId('users');
        $addUser->prefix = $request->prefix;
        $addUser->name = $request->first_name;
        $addUser->last_name = $request->last_name;
        $addUser->email = $request->email;
        $addUser->status = 1;
        $addUser->is_marketing_user = isset($request->is_marketing_user) ? 1 : 0;

        if (isset($request->allow_login)) {

            $addUser->allow_login = 1;
            $addUser->username = $request->username;
            $addUser->password = Hash::make($request->password);

            $roleId = $request->role_id ?? 3;
            $role = Role::find($roleId);

            if ($role->name == 'superadmin') {

                $addUser->role_type = 1;
                $addUser->assignRole($role->name);
            } elseif ($role->name == 'admin') {

                $addUser->role_type = 2;
                $addUser->assignRole($role->name);
            } else {

                $addUser->role_type = 3;
                $addUser->assignRole($role->name);
            }
        } else {

            $addUser->allow_login = 0;
        }

        $addUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $addUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;
        $addUser->date_of_birth = $request->date_of_birth;
        $addUser->gender = $request->gender;
        $addUser->marital_status = $request->marital_status;
        $addUser->blood_group = $request->blood_group;
        $addUser->phone = $request->phone;
        $addUser->facebook_link = $request->facebook_link;
        $addUser->twitter_link = $request->twitter_link;
        $addUser->instagram_link = $request->instagram_link;
        $addUser->guardian_name = $request->guardian_name;
        $addUser->id_proof_name = $request->id_proof_name;
        $addUser->id_proof_number = $request->id_proof_number;
        $addUser->permanent_address = $request->permanent_address;
        $addUser->current_address = $request->current_address;
        $addUser->bank_ac_holder_name = $request->bank_ac_holder_name;
        $addUser->bank_ac_no = $request->bank_ac_no;
        $addUser->bank_name = $request->bank_name;
        $addUser->bank_identifier_code = $request->bank_identifier_code;
        $addUser->bank_branch = $request->bank_branch;
        $addUser->tax_payer_id = $request->tax_payer_id;
        $addUser->save();

        session()->flash('successMsg', 'User created successfully');

        return response()->json('User created successfully');
    }

    // User Edit view
    public function edit($userId)
    {
        if (! auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $user = User::with(['roles'])->where('id', $userId)->first();

        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(Request $request, $userId)
    {
        // \Log::info($request->role_id);
        if (! auth()->user()->can('user_edit')) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'first_name' => 'required',
            'email' => 'unique:users,email,'.$userId,
        ]);

        $updateUser = User::where('id', $userId)->first();

        if (isset($request->allow_login)) {

            $this->validate($request, [
                'username' => 'required',
            ]);

            if (! $updateUser->password) {

                $this->validate($request, [
                    'password' => 'required|confirmed',
                ]);
            } else {

                $this->validate($request, [
                    'password' => 'sometimes|confirmed',
                ]);
            }
        }
        \Log::info('validation passed');
        $updateUser->prefix = $request->prefix;
        $updateUser->name = $request->first_name;
        $updateUser->last_name = $request->last_name;
        $updateUser->status = isset($request->is_active) ? 1 : 0;
        $updateUser->allow_login = $request->allow_login;
        $updateUser->email = $request->email;
        $updateUser->is_marketing_user = isset($request->is_marketing_user) ? 1 : 0;

        if (isset($request->allow_login)) {

            $updateUser->allow_login = 1;
            $updateUser->username = $request->username;
            $updateUser->password = $request->password ? Hash::make($request->password) : $updateUser->password;

            $roleId = $request->role_id ?? 3;
            $role = Role::find($roleId);
            $roleName = $role->name;

            switch ($roleName) {
                case 'superadmin':
                    $updateUser->role_type = 1;
                    break;
                case 'admin':
                    $updateUser->role_type = 2;
                    break;
                default:
                    $updateUser->role_type = 3;
                    break;
            }

            $updateUser->syncRoles([$roleName]);

        } else {

            $updateUser->allow_login = 0;
        }

        $updateUser->sales_commission_percent = $request->sales_commission_percent ? $request->sales_commission_percent : 0;
        $updateUser->max_sales_discount_percent = $request->max_sales_discount_percent ? $request->max_sales_discount_percent : 0;
        $updateUser->date_of_birth = $request->date_of_birth;
        $updateUser->gender = $request->gender;
        $updateUser->marital_status = $request->marital_status;
        $updateUser->blood_group = $request->blood_group;
        $updateUser->phone = $request->phone;
        $updateUser->facebook_link = $request->facebook_link;
        $updateUser->twitter_link = $request->twitter_link;
        $updateUser->instagram_link = $request->instagram_link;
        $updateUser->guardian_name = $request->guardian_name;
        $updateUser->id_proof_name = $request->id_proof_name;
        $updateUser->id_proof_number = $request->id_proof_number;
        $updateUser->permanent_address = $request->permanent_address;
        $updateUser->current_address = $request->current_address;
        $updateUser->bank_ac_holder_name = $request->bank_ac_holder_name;
        $updateUser->bank_ac_no = $request->bank_ac_no;
        $updateUser->bank_name = $request->bank_name;
        $updateUser->bank_identifier_code = $request->bank_identifier_code;
        $updateUser->bank_branch = $request->bank_branch;
        $updateUser->tax_payer_id = $request->tax_payer_id;
        $updateUser->save();

        session()->flash('successMsg', 'Successfully user updated');

        return response()->json('User updated successfully');
    }

    // Delete user
    public function delete($userId)
    {
        if (! auth()->user()->can('user_delete')) {
            abort(403, 'Access Forbidden.');
        }

        $deleteUser = User::find($userId);

        if ($deleteUser->role_type == 1) {

            return response()->json('Super-admin can not be deleted');
        }

        if (! is_null($deleteUser)) {

            $deleteUser->delete();
        }

        return response()->json('Successfully deleted');
    }

    public function show($userId)
    {
        $user = User::with(['roles'])->where('id', $userId)->firstOrFail();

        return view('users.show', compact('user'));
    }

    // All Roles For user create form
    public function allRoles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }
}
