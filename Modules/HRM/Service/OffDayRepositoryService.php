<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\ELPayment;
use Modules\HRM\Interface\EmployeeServiceInterface;

class OffDayRepositoryService
{
    private $employeeService;

    private $offdayService;

    public function __construct(EmployeeServiceInterface $employeeService)
    {

    }

    public function all()
    {
        abort_if(! auth()->user()->can('hrm_el_payments_index'), 403, 'Access Forbidden');
        $items = ELPayment::orderBy('id', 'desc')->get();

        return $items;
    }

    // public function store($request)
    public function store(array $attributes)
    {

        abort_if(! auth()->user()->can('hrm_el_payments_create'), 403, 'Access Forbidden');
        $leaveApplication = ELPayment::create($attributes);

        return $leaveApplication;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_el_payments_update'), 403, 'Access Forbidden');
        $leaveApplication = ELPayment::find($id);
        $updatedLeaveApplication = $leaveApplication->update($attributes);

        return $updatedLeaveApplication;
    }

    // with where condition get all
    public function whereAll()
    {
        abort_if(! auth()->user()->can('hrm_el_payments_index'), 403, 'Access Forbidden');
        $elPayment = ELPayment::where('status', 1)->orderBy('id', 'desc')->get();

        return $elpayment;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_el_payments_view'), 403, 'Access Forbidden');
        $leaveApplication = ELPayment::find($id);

        return $leaveApplication;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_el_payments_delete'), 403, 'Access Forbidden');
        $item = ELPayment::find($id);
        $item->delete($item);

        return $item;
    }

    //Get Trashed Itemlist
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $item = ELPayment::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ELPayment::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = ELPayment::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ELPayment::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        $item = ELPayment::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = ELPayment::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = ELPayment::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_leave_applications_index'), 403, 'Access Forbidden');
        $count = ELPayment::onlyTrashed()->count();

        return $count;
    }
}
