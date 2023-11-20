<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\BusinessLead;
use Modules\CRM\Entities\Followups;
use Modules\CRM\Interfaces\BusinessLeadServiceInterface;

class BusinessLeadService implements BusinessLeadServiceInterface
{
    public function getFollowups()
    {
        return $followups = Followups::whereNotNull('business_id')->select('business_id')->get();
    }

    public function all()
    {
        abort_if(! auth()->user()->can('crm_business_leads_index'), 403);
        $business_leads = BusinessLead::with('followup_status')->whereNotIn('id', $this->getFollowups()->pluck('business_id'))->orderBy('id', 'desc')->get();

        return $business_leads;
    }

    public function allLeads()
    {
        $individual_leads = BusinessLead::with('followup_status')->orderBy('id', 'desc')->get();

        return $individual_leads;
    }

    public function getTrashedItem()
    {
        abort_if(! auth()->user()->can('crm_business_leads_index'), 403);
        $business_lead = BusinessLead::with('followup_status')->onlyTrashed()->orderBy('id', 'desc')->get();

        return $business_lead;
    }

    public function store($request)
    {
        abort_if(! auth()->user()->can('crm_business_leads_create'), 403);
        $business_lead = BusinessLead::with('followup_status')->create($request);

        return $business_lead;
    }

    public function find($id)
    {
        abort_if(! auth()->user()->can('crm_business_leads_view'), 403);
        $business_leads = BusinessLead::with('followup_status')->find($id);

        return $business_leads;
    }

    public function update($attribute, $id)
    {
        abort_if(! auth()->user()->can('crm_business_leads_update'), 403);
        $business_lead = BusinessLead::with('followup_status')->find($id);
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
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        $business_lead = BusinessLead::with('followup_status')->find($id);
        $business_lead->delete($business_lead);

        return $business_lead;
    }

    public function bulkTrash($ids)
    {
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = BusinessLead::with('followup_status')->find($id);
            $business_lead->delete($business_lead);
        }

        return $business_lead;
    }

    public function permanentDelete($id)
    {
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        $business_lead = BusinessLead::with('followup_status')->onlyTrashed()->find($id);
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
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = BusinessLead::with('followup_status')->onlyTrashed()->find($id);
            $business_lead->forceDelete($business_lead);
        }

        return $business_lead;
    }

    public function restore($id)
    {
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        $business_lead = BusinessLead::with('followup_status')->withTrashed()->find($id)->restore();

        return $business_lead;
    }

    public function bulkRestore($ids)
    {
        abort_if(! auth()->user()->can('crm_business_leads_delete'), 403);
        foreach ($ids as $id) {
            $business_lead = BusinessLead::with('followup_status')->withTrashed()->find($id);
            $business_lead->restore($business_lead);
        }

        return $business_lead;
    }

    public function getRowCount()
    {
        abort_if(! auth()->user()->can('crm_business_leads_index'), 403);
        $count = BusinessLead::with('followup_status')->whereNotIn('id', $this->getFollowups()->pluck('business_id'))->count();

        return $count;
    }

    public function getTrashedCount()
    {
        abort_if(! auth()->user()->can('crm_business_leads_index'), 403);
        $count = BusinessLead::with('followup_status')->onlyTrashed()->count();

        return $count;
    }

    public function deleteAdditionalFile($id, $file_name)
    {
        $businessLead = BusinessLead::with('followup_status')->where('id', $id)->first();
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
