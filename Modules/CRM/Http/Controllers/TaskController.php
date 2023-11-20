<?php

namespace Modules\CRM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
// use Modules\CRM\Entities\Task;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\CRM\Http\Requests\Tasks\TaskStoreRequest;
use Modules\CRM\Http\Requests\Tasks\TaskUpdateRequest;
use Modules\CRM\Interfaces\TaskServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class TaskController extends Controller
{
    public $TaskService;

    public function __construct(TaskServiceInterface $TaskService)
    {
        $this->TaskService = $TaskService;
    }

    public function index(Request $request)
    {
        if ($request->showTrashed == 'true') {
            $subscription = $this->TaskService->getTrashedItem();
        } else {
            $subscription = $this->TaskService->all();
        }

        $rowCount = $this->TaskService->getRowCount();
        $trashedCount = $this->TaskService->getTrashedCount();

        if ($request->ajax()) {
            return DataTables::of($subscription)
                ->addIndexColumn()
                ->addColumn('check', function ($row) {
                    $html = '';
                    $html .= '<div class="icheck-primary text-center">
                                    <input type="checkbox" name="subscriptions_id[]" value="'.$row->id.'" class="mt-2 check1">
                                </div>';

                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $action1 = '';
                    $action2 = '';
                    $type = '';
                    $icon1 = '';
                    $icon2 = '';
                    if ($row->trashed()) {
                        $action1 = 'restore';
                        $action2 = 'permanent-delete';
                        $type = 'restore';
                        $icon1 = '<i class="fa-solid fa-recycle"></i>';
                        $icon2 = '<i class="fa-solid fa-trash-check"></i>';
                    } else {
                        $action1 = 'edit';
                        $action2 = 'destroy';
                        $type = 'Edit';
                        $icon1 = '<span class="fas fa-edit"></span></a>';
                        $icon2 = '<span class="fas fa-trash "></span>';
                    }

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="'.route('crm.subscription.'.$action1, $row->id).'" class="action-btn c-edit '.$action1.'" id="'.$action1.'_business_leads" title="'.$type.'">'.$icon1.'</a>';
                    $html .= '<a href="'.route('crm.subscription.'.$action2, $row->id).'" class="action-btn c-delete delete" id="delete_department" title="Delete">'.$icon2.'</a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action', 'check'])
                ->with([
                    'allRow' => $rowCount,
                    'trashedRow' => $trashedCount,
                ])
                ->smart(true)
                ->make(true);
        }

        $customers = Customer::get();

        return view('crm::task.index', compact('customers', 'subscription'));
    }

    public function store(TaskStoreRequest $request)
    {
        $subscription = $this->TaskService->store($request->validated());

        return response()->json('Subscription created successfully');
    }

    public function show($id)
    {
        return view('crm::task.show');
    }

    public function edit($id)
    {
        $subscription = $this->TaskService->find($id);
        $customers = Customer::get();

        return view('crm::task.ajax_view.edit', compact('customers', 'subscription'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Renderable
     */
    public function update(TaskUpdateRequest $request, $id)
    {
        $subscription = $this->TaskService->update($request->validated(), $id);
    }

    public function destroy($id)
    {
        $individualLeads = $this->TaskService->trash($id);

        return response()->json('Subscription deleted successfully');
    }

    public function bulkAction(Request $request)
    {
        if (isset($request->subscriptions_id)) {
            if ($request->action_type == 'move_to_trash') {
                $individualLeads = $this->TaskService->bulkTrash($request->subscriptions_id);

                return response()->json('Subscription deleted successfully');
            } elseif ($request->action_type == 'restore_from_trash') {
                $individualLeads = $this->TaskService->bulkRestore($request->subscriptions_id);

                return response()->json('Subscription restored successfully');
            } elseif ($request->action_type == 'delete_permanently') {
                $individualLeads = $this->TaskService->bulkPermanentDelete($request->subscriptions_id);

                return response()->json('Subscription permanently deleted successfully');
            } else {
                return response()->json('Action is not specified.');
            }
        } else {
            return response()->json(['message' => 'No Item is Selected.'], 401);
        }
    }

    public function restore($id)
    {
        $individualLeads = $this->TaskService->restore($id);

        return response()->json('Subscription restore successfully');
    }

    public function permanentDelete($id)
    {
        $individualLeads = $this->TaskService->permanentDelete($id);

        return response()->json('Subscription permanently delete successfully');
    }
}
