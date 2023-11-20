<?php

namespace Modules\HRM\Service;

use Modules\HRM\Entities\PaymentType;
use Modules\HRM\Interface\PaymentTypesServiceInterface;

class PaymentTypeService implements PaymentTypesServiceInterface
{
    public function all()
    {
        abort_if(! auth()->user()->can('hrm_payments_types_index'), 403, 'Access Forbidden');
        $leaveType = PaymentType::orderBy('id', 'desc')->get();

        return $leaveType;
    }

    // public function store($request)
    public function store(array $attributes)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_create'), 403, 'Access Forbidden');
        $leaveType = PaymentType::create($attributes);

        return $leaveType;
    }

    public function find(int $id)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_view'), 403, 'Access Forbidden');
        $leaveType = PaymentType::find($id);

        return $leaveType;
    }

    public function update(array $attributes, int $id)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_update'), 403, 'Access Forbidden');
        $leaveType = PaymentType::find($id);
        $updatedLeaveType = $leaveType->update($attributes);

        return $updatedLeaveType;
    }

    //with where condition getall
    public function allowedPayment()
    {
        abort_if(! auth()->user()->can('hrm_payments_types_index'), 403, 'Access Forbidden');
        $paymentType = PaymentType::where('status', 1)->orderBy('id', 'desc')->get();

        return $paymentType;
    }

    //Get Trashed Itemlist
    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('hrm_payments_types_index'), 403, 'Access Forbidden');
        $item = PaymentType::onlyTrashed()->orderBy('id', 'desc')->get();

        return $item;
    }

    //Move To Trash
    public function trash(int $id)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        $item = PaymentType::find($id);
        $item->delete($item);

        return $item;
    }

    //Bulk Move To Trash
    public function bulkTrash(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = PaymentType::find($id);
            $item->delete($item);
        }

        return $item;
    }

    //Permanent Delete
    public function permanentDelete(int $id)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        $item = PaymentType::onlyTrashed()->find($id);
        $item->forceDelete();

        return $item;
    }

    //Bulk Permanent Delete
    public function bulkPermanentDelete(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = PaymentType::onlyTrashed()->find($id);
            $item->forceDelete($item);
        }

        return $item;
    }

    //Restore Trashed Item
    public function restore(int $id)
    {

        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        $item = PaymentType::withTrashed()->find($id)->restore();

        return $item;
    }

    //Bulk Restore Trashed Item
    public function bulkRestore(array $ids)
    {
        abort_if(! auth()->user()->can('hrm_payments_types_delete'), 403, 'Access Forbidden');
        foreach ($ids as $id) {
            $item = PaymentType::withTrashed()->find($id);
            $item->restore($item);
        }

        return $item;
    }

    //Get Row Count
    public function getRowCount()
    {
        abort_if(! auth()->user()->can('hrm_payments_types_index'), 403, 'Access Forbidden');
        $count = PaymentType::all()->count();

        return $count;
    }

    //Get Trashed Item Count
    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('hrm_payments_types_index'), 403, 'Access Forbidden');
        $count = PaymentType::onlyTrashed()->count();

        return $count;
    }
}
