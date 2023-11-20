<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Task;
use Modules\CRM\Interfaces\TaskServiceInterface;

class TaskService implements TaskServiceInterface
{
    public function all()
    {
        // abort_if(!auth()->user()->can('crm_individual_leads_index'), 403);
        $subscription = Task::with('customers')->orderBy('id', 'desc')->get();

        return $subscription;
    }

    public function getTrashedItem()
    {
        $subscription = Task::with('customers')->onlyTrashed()->orderBy('id', 'desc')->get();

        return $subscription;
    }

    public function store($request)
    {
        $subscription = Task::with('customers')->create($request);

        return $subscription;
    }

    public function find($id)
    {
        $subscription = Task::with('customers')->find($id);

        return $subscription;
    }

    public function update($attribute, $id)
    {
        $subscription = Task::with('customers')->find($id);
        $subscription->update($attribute);

        return $subscription;
    }

    public function trash($id)
    {
        $subscription = Task::with('customers')->find($id);
        $subscription->delete($subscription);

        return $subscription;
    }

    public function bulkTrash($ids)
    {
        foreach ($ids as $id) {
            $subscription = Task::with('customers')->find($id);
            $subscription->delete($subscription);
        }

        return $subscription;
    }

    public function permanentDelete($id)
    {
        $subscription = Task::with('customers')->onlyTrashed()->find($id);
        $existingFiles = $subscription->files;

        $subscription->forceDelete();

        return $subscription;
    }

    public function bulkPermanentDelete($ids)
    {
        foreach ($ids as $id) {
            $subscription = Task::with('customers')->onlyTrashed()->find($id);
            $subscription->forceDelete($subscription);
        }

        return $subscription;
    }

    public function restore($id)
    {
        $subscription = Task::with('customers')->withTrashed()->find($id)->restore();

        return $subscription;
    }

    public function bulkRestore($ids)
    {
        foreach ($ids as $id) {
            $subscription = Task::with('customers')->withTrashed()->find($id);
            $subscription->restore($subscription);
        }

        return $subscription;
    }

    public function getRowCount()
    {
        $count = Task::with('customers')->count();

        return $count;
    }

    public function getTrashedCount()
    {
        $count = Task::with('customers')->onlyTrashed()->count();

        return $count;
    }
}
