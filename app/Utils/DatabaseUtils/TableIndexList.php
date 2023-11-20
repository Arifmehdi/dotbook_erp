<?php

namespace App\Utils\DatabaseUtils;

use Illuminate\Support\Facades\Schema;

class TableIndexList
{
    public static function getIndexes($table_name): array
    {
        $sm = Schema::getConnection()->getDoctrineSchemaManager();
        $indexesFound = $sm->listTableIndexes($table_name);

        return array_keys($indexesFound);
    }
}
