<?php

namespace Modules\Core\Interface;

interface BdDistrictServiceInterface
{
    public function all(?array $params);

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

    public function getDistrictByDivision($id);
}
