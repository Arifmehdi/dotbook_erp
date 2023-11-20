<?php

namespace Modules\CRM\Interfaces;

interface IndividualLeadServiceInterface
{
    public function all();

    public function allLeads();

    public function store($request);

    public function update($request, $id);

    public function find($id);

    public function trash($id);

    public function getTrashedItem();

    public function bulkTrash($request);

    public function permanentDelete($id);

    public function bulkPermanentDelete($request);

    public function restore($id);

    public function bulkRestore($request);

    public function getRowCount();

    public function getTrashedCount();

    public function deleteAdditionalFile($id, $file_name);
}
