<?php

namespace Modules\Contacts\Interfaces;

interface ContactServiceInterface
{
    public function all();

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

    public function filterWiseCount($collumn, $type);

    public function getTrashedCount();
}
