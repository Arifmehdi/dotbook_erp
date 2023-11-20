<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Proposal;
use Modules\CRM\Interfaces\ProposalServiceInterface;

class ProposalService implements ProposalServiceInterface
{
    public function all()
    {
        // abort_if(!auth()->user()->can('proposal_index'), 403);
        $business_leads = Proposal::orderBy('id', 'desc')->get();

        return $business_leads;
    }

    public function getTrashedItem()
    {
        // abort_if(!auth()->user()->can('proposal_index'), 403);
        $business_lead = Proposal::onlyTrashed()->orderBy('id', 'desc')->get();

        return $business_lead;
    }

    public function store($request)
    {
        // abort_if(!auth()->user()->can('proposal_create'), 403);
        $business_lead = Proposal::create($request);

        return $business_lead;
    }

    public function find($id)
    {
        // abort_if(!auth()->user()->can('proposal_view'), 403);
        $business_leads = Proposal::find($id);

        return $business_leads;
    }

    public function update($attribute, $id)
    {
        // abort_if(!auth()->user()->can('proposal_update'), 403);
        $business_lead = Proposal::find($id);
        if (isset($attribute['files']) && isset($business_lead->files)) {
            $existingFiles = $business_lead->files;
            $newlyUploadedFilesArray = json_decode($attribute['files']);
            $oldFilesArray = \json_decode($existingFiles);
            $oldFilesArray = (array) $oldFilesArray;
            $final_array = array_merge($oldFilesArray, $newlyUploadedFilesArray);
            $attribute['files'] = \json_encode($final_array);
        }
        $business_lead->update($attribute);

        return $business_lead;
    }

    public function trash($id)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        $business_lead = Proposal::find($id);
        $business_lead->delete($business_lead);

        return $business_lead;
    }

    public function bulkTrash($ids)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = Proposal::find($id);
            $business_lead->delete($business_lead);
        }

        return $business_lead;
    }

    public function permanentDelete($id)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        $business_lead = Proposal::onlyTrashed()->find($id);
        $existingFiles = $business_lead->files;

        if (isset($existingFiles)) {
            $oldFilesArray = \json_decode($existingFiles);
            $oldFilesArray = (array) $oldFilesArray;
            foreach ($oldFilesArray as $key => $file) {
                try {
                    unlink(\public_path('uploads/leads/business_leads/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($oldFilesArray[$key]);
                }
            }
        }

        $business_lead->forceDelete();

        return $business_lead;
    }

    public function bulkPermanentDelete($ids)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = Proposal::onlyTrashed()->find($id);
            $business_lead->forceDelete($business_lead);
        }

        return $business_lead;
    }

    public function restore($id)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        $business_lead = Proposal::withTrashed()->find($id)->restore();

        return $business_lead;
    }

    public function bulkRestore($ids)
    {
        // abort_if(!auth()->user()->can('proposal_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = Proposal::withTrashed()->find($id);
            $business_lead->restore($business_lead);
        }

        return $business_lead;
    }

    public function getRowCount()
    {
        // abort_if(!auth()->user()->can('proposal_index'), 403);
        $count = Proposal::count();

        return $count;
    }

    public function getTrashedCount()
    {
        // abort_if(!auth()->user()->can('proposal_index'), 403);
        $count = Proposal::onlyTrashed()->count();

        return $count;
    }

    public function deleteAdditionalFile($id, $file_name)
    {
        $businessLead = Proposal::where('id', $id)->first();
        $filesJson = $businessLead->files;
        $filesArray = json_decode($filesJson, true);

        foreach ($filesArray as $key => $file) {
            if ($file_name == $file) {
                try {
                    unlink(\public_path('uploads/leads/business_leads/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($filesArray[$key]);
                }
            }
        }

        $businessLead->files = json_encode($filesArray);

        return $businessLead;
    }
}
