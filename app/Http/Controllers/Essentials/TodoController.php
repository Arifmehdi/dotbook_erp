<?php

namespace App\Http\Controllers\Essentials;

use App\Http\Controllers\Controller;
use App\Models\Essential\Todo;
use App\Models\Essential\TodoUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();

        if ($addons->todo == 0) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $generalSettings = DB::table('general_settings')->first();

            $todos = '';
            $query = DB::table('todos')->leftJoin('users', 'todos.admin_id', 'users.id');

            if ($request->priority) {
                $query->where('todos.priority', $request->priority);
            }

            if ($request->status) {
                $query->where('todos.status', $request->status);
            }

            if ($request->from_date) {
                $from_date = date('Y-m-d', strtotime($request->from_date));
                $to_date = $request->to_date ? date('Y-m-d', strtotime($request->to_date)) : $from_date;
                $date_range = [$from_date.' 00:00:00', $to_date.' 00:00:00'];
                $query->whereBetween('todos.due_date', $date_range);
            }

            $query->select(
                'todos.*',
                'users.prefix',
                'users.name as a_name',
                'users.last_name',
            );

            $todos = $query->orderBy('todos.id', 'desc');

            return DataTables::of($todos)
                ->addColumn('action', function ($row) {

                    $html = '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Action
                    </button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="show" href="'.route('todo.show', [$row->id]).'"><i class="far fa-eye mr-1 text-primary"></i> View</a>';

                    $html .= '<a class="dropdown-item" id="edit" href="'.route('todo.edit', [$row->id]).'"><i class="far fa-edit text-primary"></i> Edit</a>';

                    $html .= '<a class="dropdown-item" id="change_status" href="'.route('todo.status.modal', [$row->id]).'"><i class="fas fa-pen-nib text-primary"></i> Change Status</a>';

                    $html .= '<a class="dropdown-item" id="delete" href="'.route('todo.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('due_date', function ($row) {

                    return date('d/m/Y', strtotime($row->due_date));
                })
                ->editColumn('priority', function ($row) {

                    if ($row->priority == 'High') {

                        return '<span class="badge bg-danger">'.$row->priority.'</span>';
                    } elseif ($row->priority == 'Low') {

                        return '<span class="badge bg-warning">'.$row->priority.'</span>';
                    } elseif ($row->priority == 'Medium') {

                        return '<span class="badge bg-secondary">'.$row->priority.'</span>';
                    } else {

                        return '<span class="badge bg-1">'.$row->priority.'</span>';
                    }
                })
                ->editColumn('status', function ($row) {

                    if ($row->status == 'New') {

                        return '<span class="badge bg-primary">'.$row->status.'</span>';
                    } elseif ($row->status == 'In-Progress') {

                        return '<span class="badge bg-secondary">'.$row->status.'</span>';
                    } elseif ($row->status == 'On-Hold') {

                        return '<span class="badge bg-warning">'.$row->status.'</span>';
                    } else {

                        return '<span class="badge bg-info">'.$row->status.'</span>';
                    }
                })
                ->editColumn('assigned_by', function ($row) {

                    return $row->prefix.' '.$row->a_name.' '.$row->last_name;
                })
                ->rawColumns(['action', 'date', 'name', 'assigned_by', 'priority', 'status'])
                ->make(true);
        }

        $users = User::all();

        return view('essentials.todo.index', compact('users'));
    }

    public function store(Request $request)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        if (! auth()->user()->can('assign_todo')) {

            return response()->json(['errorMsg' => 'You do\'t have any permission to assign the todo.']);
        }

        $this->validate($request, [
            'task' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ]);

        // Generate invoice ID
        $i = 4;
        $a = 0;
        $IdNo = '';
        while ($a < $i) {
            $IdNo .= rand(1, 9);
            $a++;
        }

        $addTodo = Todo::insertGetId([
            'todo_id' => date('my').$IdNo,
            'task' => $request->task,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),
            'description' => $request->description,
            'admin_id' => auth()->user()->id,
            'created_at' => date('Y-m-d'),
        ]);

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                TodoUsers::insert([
                    'todo_id' => $addTodo,
                    'user_id' => $user_id,
                ]);
            }
        }

        return response()->json('Todo created successfully.');
    }

    public function edit($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with(['todo_users'])->where('id', $id)->first();
        $users = DB::table('users')->get(['id', 'prefix', 'name', 'last_name']);

        return view('essentials.todo.ajax_view.edit', compact('todo', 'users'));
    }

    public function update(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'task' => 'required',
            'priority' => 'required',
            'status' => 'required',
        ]);

        $updateTodo = Todo::with('todo_users')->where('id', $id)->first();

        foreach ($updateTodo->todo_users as $user) {
            $user->is_delete_in_update = 1;
            $user->save();
        }

        $updateTodo->update([
            'task' => $request->task,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => date('Y-m-d', strtotime($request->due_date)),
            'description' => $request->description,
            'admin_id' => auth()->user()->id,
        ]);

        if (count($request->user_ids) > 0) {
            foreach ($request->user_ids as $user_id) {
                $existsUser = TodoUsers::where('todo_id', $id)
                    ->where('user_id', $user_id)->first();
                if ($existsUser) {
                    $existsUser->is_delete_in_update = 0;
                    $existsUser->save();
                } else {
                    TodoUsers::insert([
                        'todo_id' => $id,
                        'user_id' => $user_id,
                    ]);
                }
            }
        }

        $deleteUsers = TodoUsers::where('todo_id', $id)->where('is_delete_in_update', 1)->get();
        foreach ($deleteUsers as $deleteUser) {
            $deleteUser->delete();
        }

        return response()->json('Todo update successfully.');
    }

    public function changeStatusModal($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with('todo_users')->where('id', $id)->first(['id', 'status']);

        return view('essentials.todo.ajax_view.change_status', compact('todo'));
    }

    public function changeStatus(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::where('id', $id)->first();
        $todo->update([
            'status' => $request->status,
        ]);

        return response()->json('Todo status changed successfully.');
    }

    public function show($id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $todo = Todo::with(['admin', 'todo_users', 'todo_users.user'])->where('id', $id)->first();

        return view('essentials.todo.ajax_view.show', compact('todo'));
    }

    public function delete(Request $request, $id)
    {
        $addons = DB::table('addons')->select('todo')->first();
        if ($addons->todo == 0) {
            abort(403, 'Access Forbidden.');
        }

        $deleteTodo = Todo::where('id', $id)->first();
        if (! is_null($deleteTodo)) {
            $deleteTodo->delete();
        }

        return response()->json('Todo deleted successfully.');
    }
}
