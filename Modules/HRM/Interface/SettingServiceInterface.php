<?php

namespace Modules\HRM\Interface;

interface SettingServiceInterface
{
    public function all();

    public function update($request, $id);
}
