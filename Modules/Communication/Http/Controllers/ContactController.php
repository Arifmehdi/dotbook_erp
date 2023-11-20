<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Communication\Entities\CommunicationContact;
use Modules\Communication\Entities\ContactGroup;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $groups = ContactGroup::all();
        $numbers = CommunicationContact::all();

        return view('communication::contacts.index', compact('groups', 'numbers'));
    }
}
