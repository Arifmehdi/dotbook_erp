<?php

namespace Modules\CRM\Services;

use Modules\CRM\Entities\Source;
use Modules\CRM\Interfaces\SourceServiceInterface;

class SourceService implements SourceServiceInterface
{
    public function all()
    {
        $sources = Source::all()->sortByDesc('id');

        return $sources;
    }

    public function store($source)
    {
        $source = Source::create($source);

        return $source;
    }

    public function find($id)
    {
        $source = Source::find($id);

        return $source;
    }

    public function update($source, $id)
    {
        $UpdateSource = Source::find($id);
        $UpdateSource->update($source);

        return $UpdateSource;
    }

    public function destroy($id)
    {
        $source = Source::find($id);
        $source->delete();

        return $source;
    }
}
