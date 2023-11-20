<?php

namespace Modules\Communication\Imports;

use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;

class BulkMailImport implements ToCollection
{
    public $data = [];

    public function collection(Collection $collection)
    {
        return $collection;
        // dd($collection[3]);
        // if(isset($collection[3])) {
        //     $this->data[] = $collection[3];
        // }
        // $bulkMailsArray = array();

        // foreach ($collection as $i=>$c) {
        //     if($c[4] != null){
        //         array_push($bulkMailsArray,$c[4]);
        //     }
        // }
        // session_start();
        // $_SESSION["bulkMailArray"] = $bulkMailsArray;

        // dd( $bulksArray);
    }
}
