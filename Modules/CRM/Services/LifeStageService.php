<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\LifeStage;
use Modules\CRM\Interfaces\LifeStageServiceInterface;

class LifeStageService implements LifeStageServiceInterface
{
    public function all()
    {
        $lifeStage = LifeStage::all()->sortByDesc('id');

        return $lifeStage;
    }

    public function store($lifeStage)
    {
        $storeLifeStage = LifeStage::create($lifeStage);

        return $storeLifeStage;
    }

    public function find($id)
    {
        $source = LifeStage::find($id);

        return $source;
    }

    public function update($lifeStage, $id)
    {
        $UpdateSource = LifeStage::find($id);
        $UpdateSource->update($lifeStage);

        return $UpdateSource;
    }

    public function destroy($id)
    {
        $lifeStage = LifeStage::find($id);
        $lifeStage->delete();

        return $lifeStage;
    }
}
