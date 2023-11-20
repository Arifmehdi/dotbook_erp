<?php

namespace Modules\CRM\Interfaces;

interface AppointmentServiceInterface
{
    public function all();

    public function store($request);

    public function find($id);

    public function update($request, $id);

    public function destroy($id);
}
