<?php

namespace Modules\LCManagement\Http\Controllers;

use Illuminate\Http\Request;
use Modules\LCManagement\Entities\AdvisingBank;

class AdvisingBankController extends Controller
{
    public function addQuickAdvisingBankModal()
    {
        return view('lcmanagement::imports.ajax_view.add_quick_advising_bank');
        // return view('lc.imports.ajax_view.add_quick_advising_bank');
    }

    // Store Advising
    public function store(Request $request)
    {

        //    lee_lc_5
        // which permission should i give here?
        $this->validate($request, [
            'name' => 'required',
        ]);

        return $addAdvisingBank = AdvisingBank::create([
            'name' => $request->name,
        ]);
    }
}
