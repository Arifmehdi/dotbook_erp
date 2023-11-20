<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Followups;
use Modules\CRM\Interfaces\FollowupServiceInterface;

class FollowupService implements FollowupServiceInterface
{
    public function all()
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->orderBy('id', 'desc')->get();

        return $followups;
    }

    public function getTrashedItem()
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->onlyTrashed()->orderBy('id', 'desc')->get();

        return $followups;
    }

    public function store($request)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->create($request);

        return $followups;
    }

    public function find($id)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->find($id);

        return $followups;
    }

    public function update($attribute, $id)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->find($id);
        $followups->update($attribute);

        return $followups;
    }

    public function trash($id)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->find($id);
        $followups->delete($followups);

        return $followups;
    }

    public function bulkTrash($ids)
    {
        foreach ($ids as $id) {
            $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->find($id);
            $followups->delete($followups);
        }

        return $followups;
    }

    public function permanentDelete($id)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->onlyTrashed()->find($id);
        $existingFiles = $followups->files;

        $followups->forceDelete();

        return $followups;
    }

    public function bulkPermanentDelete($ids)
    {
        foreach ($ids as $id) {
            $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->onlyTrashed()->find($id);
            $followups->forceDelete($followups);
        }

        return $followups;
    }

    public function restore($id)
    {
        $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->withTrashed()->find($id)->restore();

        return $followups;
    }

    public function bulkRestore($ids)
    {
        foreach ($ids as $id) {
            $followups = Followups::with('categories', 'individual_lead', 'busilness_lead')->withTrashed()->find($id);
            $followups->restore($followups);
        }

        return $followups;
    }

    public function getRowCount()
    {
        $count = Followups::with('categories', 'individual_lead', 'busilness_lead')->count();

        return $count;
    }

    public function getGroupByRowCount()
    {
        $count = Followups::groupBy('individual_id')->count();

        return $count;
    }

    public function getTrashedCount()
    {
        $count = Followups::with('categories', 'individual_lead', 'busilness_lead')->onlyTrashed()->count();

        return $count;
    }
}
