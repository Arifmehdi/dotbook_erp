<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Communication\Entities\CommunicationContact;
use Modules\Communication\Entities\ContactGroup;
use Yajra\DataTables\Facades\DataTables;

class NumberController extends Controller
{
    public function index(Request $request)
    {
        $numbers = CommunicationContact::all();
        if ($request->ajax()) {
            return DataTables::of($numbers)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="javascript:;" class="action-btn c-edit" id="edit_number" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="'.route('communication.contacts.number.destroy', $row->id).'" class="action-btn c-delete" id="delete_number" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('group', function ($row) {
                    return $row->group->name;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return route('communication.contacts.number.edit', $row->id);
                    },
                ])
                ->rawColumns(['action', 'group'])
                ->smart(true)
                ->make(true);
        }
        $groups = ContactGroup::all();

        return view('communication::contacts.list.index', [
            'groups' => $groups,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'email' => 'required',
            'mailing_address' => 'required',
            'whatsapp_number' => 'required',
            'name' => 'required',
            'group_name' => 'required',
        ]);

        $number = new CommunicationContact();

        $number->phone_number = $request->phone_number;
        $number->email = $request->email;
        $number->whatsapp_number = $request->whatsapp_number;
        $number->mailing_address = $request->mailing_address;
        $number->group_id = $request->group_name;
        $number->name = $request->name;
        $number->save();

        return response()->json('CommunicationContact insert successfully');
    }

    public function edit(Request $request)
    {
        $groups = ContactGroup::all();
        $number = CommunicationContact::find($request->id);

        return view('communication::contacts.list.ajax_view_unit.edit_modal_body', compact('number', 'groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone_number' => 'required',
            'email' => 'required',
            'mailing_address' => 'required',
            'whatsapp_number' => 'required',
            'name' => 'required',
            'group_name' => 'required',
        ]);

        $number = CommunicationContact::find($request->id);
        $number->group_id = $request->group_name;
        $number->name = $request->name;
        $number->phone_number = $request->phone_number;
        $number->email = $request->email;
        $number->whatsapp_number = $request->whatsapp_number;
        $number->mailing_address = $request->mailing_address;
        $number->save();

        return response()->json('CommunicationContact updated successfully');
    }

    public function destroy(Request $request)
    {

        $number = CommunicationContact::find($request->id);
        $number->delete();

        return response()->json('CommunicationContact delete successfully');
    }
}
