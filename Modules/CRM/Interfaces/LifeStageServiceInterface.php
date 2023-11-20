<?php

namespace Modules\CRM\Interfaces;

interface LifeStageServiceInterface
{
    public function all();

    public function store($request);

    public function find($id);

    public function update($request, $id);

    public function destroy($id);
}
