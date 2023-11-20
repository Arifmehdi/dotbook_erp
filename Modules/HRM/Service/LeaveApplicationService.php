<?php

namespace Modules\HRM\Service;

use App\Interface\FileUploaderServiceInterface;
use Modules\HRM\Entities\LeaveApplication;
use Modules\HRM\Interface\LeaveApplicationRepositoryInterface;
use Modules\HRM\Interface\LeaveApplicationServiceInterface;

class LeaveApplicationService implements LeaveApplicationServiceInterface
{
    public function __construct(
        private LeaveApplicationRepositoryInterface $leaveApplicationRepository,
        private FileUploaderServiceInterface $uploader,
    ) {
    }

    public function getMonthLeaves(string $month, int $year): iterable
    {
        return $this->leaveApplicationRepository->getMonthLeaves($month, $year);
    }

    public function getEmployeeLeaves(string $user_id, string $month, int $year): iterable
    {
        return $this->leaveApplicationRepository->getEmployeeLeaves($user_id, $month, $year);
    }

    public function getEmployeesLeaves(array $user_ids, string $month, int $year): iterable
    {
        return $this->leaveApplicationRepository->getEmployeesLeaves($user_ids, $month, $year);
    }

    public function getUniqueLeaves(string $userId, iterable $leaves): array
    {
        return $this->leaveApplicationRepository->getUniqueLeaves($userId, $leaves);
    }

    // ========================= leave application before code  in the below============
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $items = LeaveApplication::orderBy('id', 'desc')->get();

        return $items;
    }

    public function allLeaveApplication(array $filter_request)
    {

        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $items = LeaveApplication::query();

        if (isset($filter_request['employee_id'])) {
            $items->where('employee_id', $filter_request['employee_id']);
        }
        if ($filter_request['leave_type_id']) {
            $items->where('leave_type_id', $filter_request['leave_type_id']);
        }
        if ($filter_request['type']) {
            $items->where('status', $filter_request['type']);
        }
        if ($filter_request['date_range']) {
            $date_range = explode('-', $filter_request['date_range']);
            $from_date = date('Y-m-d', strtotime(trim($date_range[0])));
            $to_date = date('Y-m-d', strtotime(trim($date_range[1])));
            $items->where('from_date', '>=', $from_date);
            $items->where('to_date', '<=', $to_date);
        }

        $items->orderBy('id', 'desc')->get();

        return $items;
    }

    // public function store($request)
    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_create'), 403, 'Access Forbidden');
        if (isset($attributes['attachment'])) {
            $attributes['attachment'] = $this->uploader->upload($attributes['attachment'], 'uploads/application/');
        }

        $leaveApplication = LeaveApplication::create($attributes);

        return $leaveApplication;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_update'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::find($id);
        if (isset($attributes['attachment'])) {
            if (isset($leaveApplication->attachment)) {
                unlink(public_path('uploads/application/'.$leaveApplication->attachment));
            }
            $attributes['attachment'] = $this->uploader->upload($attributes['attachment'], 'uploads/application/');
        }
        $updatedLeaveApplication = $leaveApplication->update($attributes);

        return $updatedLeaveApplication;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_view'), 403, 'Access Forbidden');
        $leaveApplication = LeaveApplication::find($id);

        return $leaveApplication;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::find($id);
        $item->delete($item);

        return $item;
    }

    //Get Trashed ItemList
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $item = LeaveApplication::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::onlyTrashed()->find($id);
        if (isset($item['attachment'])) {

            if (isset($item['attachment']) && ! empty($item['attachment']) && file_exists('uploads/application/'.$item['attachment'])) {

                unlink(public_path('uploads/application/'.$item['attachment']));
            }
        }
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = LeaveApplication::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = LeaveApplication::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = LeaveApplication::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = LeaveApplication::onlyTrashed()->count();

        return $count;
    }
}
