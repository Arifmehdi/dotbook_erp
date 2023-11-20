<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Followups;
use Modules\CRM\Entities\IndividualLead;
use Modules\CRM\Interfaces\IndividualLeadServiceInterface;

class IndividualLeadService implements IndividualLeadServiceInterface
{
    public function getFollowups()
    {
        return $followups = Followups::whereNotNull('individual_id')->select('individual_id')->get();
    }

    public function all()
    {
        abort_if(! auth()->user()->can('crm_individual_leads_index'), 403);
        $individual_leads = IndividualLead::with('followup_status')->whereNotIn('id', $this->getFollowups()->pluck('individual_id'))->orderBy('id', 'desc')->get();

        return $individual_leads;
    }

    public function allLeads()
    {
        $individual_leads = IndividualLead::with('followup_status')->orderBy('id', 'desc')->get();

        return $individual_leads;
    }

    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('crm_individual_leads_index'), 403);
        $individual_leads = IndividualLead::with('followup_status')->onlyTrashed()->whereNotIn('id', $this->getFollowups()->pluck('individual_id'))->orderBy('id', 'desc')->get();

        return $individual_leads;
    }

    public function store($request)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_create'), 403);

        $individual_lead = IndividualLead::with('followup_status')->create($request);

        return $individual_lead;
    }

    public function find($id)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_view'), 403);
        $individual_leads = IndividualLead::with('followup_status')->find($id);

        return $individual_leads;
    }

    public function update($attribute, $id)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_update'), 403);
        $individual_lead = IndividualLead::with('followup_status')->find($id);
        if (isset($attribute['files']) && isset($individual_lead->files)) {
            $existingFiles = $individual_lead->files;
            $newlyUploadedFilesArray = json_decode($attribute['files']);
            $oldFilesArray = \json_decode($existingFiles);
            $oldFilesArray = (array) $oldFilesArray;
            $final_array = array_merge($oldFilesArray, $newlyUploadedFilesArray);
            $attribute['files'] = \json_encode($final_array);
        }
        $individual_lead->update($attribute);

        return $individual_lead;
    }

    public function trash($id)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        $individual_lead = IndividualLead::with('followup_status')->find($id);
        $individual_lead->delete($individual_lead);

        return $individual_lead;
    }

    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        foreach ($ids as $id) {
            $individual_lead = IndividualLead::with('followup_status')->find($id);
            $individual_lead->delete($individual_lead);
        }

        return $individual_lead;
    }

    public function permanentDelete($id)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        $individual_lead = IndividualLead::with('followup_status')->onlyTrashed()->find($id);
        $existingFiles = $individual_lead->files;

        if (isset($existingFiles)) {
            $oldFilesArray = \json_decode($existingFiles);
            $oldFilesArray = (array) $oldFilesArray;
            foreach ($oldFilesArray as $key => $file) {
                try {
                    unlink(\public_path('uploads/leads/individual_leads/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($oldFilesArray[$key]);
                }
            }
        }

        $individual_lead->forceDelete();

        return $individual_lead;
    }

    public function bulkPermanentDelete($ids)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        foreach ($ids as $id) {
            $individual_lead = IndividualLead::with('followup_status')->onlyTrashed()->find($id);
            $individual_lead->forceDelete($individual_lead);
        }

        return $individual_lead;
    }

    public function restore($id)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        $individual_lead = IndividualLead::with('followup_status')->withTrashed()->find($id)->restore();

        return $individual_lead;
    }

    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('crm_individual_leads_delete'), 403);
        foreach ($ids as $id) {
            $individual_lead = IndividualLead::with('followup_status')->withTrashed()->find($id);
            $individual_lead->restore($individual_lead);
        }

        return $individual_lead;
    }

    public function getRowCount()
    {
        abort_if(! auth()->user()->can('crm_individual_leads_index'), 403);
        $count = IndividualLead::with('followup_status')->whereNotIn('id', $this->getFollowups()->pluck('individual_id'))->count();

        return $count;
    }

    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('crm_individual_leads_index'), 403);
        $count = IndividualLead::with('followup_status')->onlyTrashed()->count();

        return $count;
    }

    public function deleteAdditionalFile($id, $file_name)
    {
        $individualLead = IndividualLead::with('followup_status')->where('id', $id)->first();
        $filesJson = $individualLead->files;
        $filesArray = json_decode($filesJson, true);

        foreach ($filesArray as $key => $file) {
            if ($file_name == $file) {
                try {
                    unlink(\public_path('uploads/leads/individual_leads/'.$file));
                } catch (\Exception$e) {
                } finally {
                    unset($filesArray[$key]);
                }
            }
        }

        $individualLead->files = json_encode($filesArray);

        return $individualLead;
    }
}
