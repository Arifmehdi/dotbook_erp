<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\BuyerRequisition;
use Modules\Website\Entities\Client;
use Str;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::orderBy('id', 'DESC')->get();

            return DataTables::of($clients)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_client')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.clients.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_client')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.clients.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->image).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->editColumn('designation', function ($row) {
                    return $row->designation;
                })
                ->rawColumns(['action', 'image', 'name', 'email', 'phone', 'slug', 'designation'])
                ->smart(true)
                ->make(true);
        }

        $total_clients = Client::count();

        return view('website::clients.index', compact('total_clients'));
    }

    public function client_create_basic_modal()
    {
        return view('website::clients.create_basic_modal');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_client')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $clients = new Client();
        if ($request->hasFile('image')) {
            $clients->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/client');
        }

        $clients->name = $request->name;
        $clients->email = $request->email;
        $clients->phone = $request->phone;
        $clients->designation = $request->designation;
        $clients->slug = Str::of($request->name)->slug('-');
        $clients->comments = $request->comments;
        $clients->save();

        return response()->json('Client has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_client')) {
            abort(403, 'Access Forbidden.');
        }

        $client = Client::find($id);

        return view('website::clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_edit_client')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $clients = Client::findOrFail($id);
        if ($request->hasFile('image')) {
            $clients->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/client');
        }
        $clients->name = $request->name;
        $clients->designation = $request->designation;
        $clients->comments = $request->comments;
        $clients->save();

        return response()->json('Client has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $clients = Client::find($id);
        $clients->delete();

        return response()->json('Client delete successfully');
    }

    public function buyerRequisition(Request $request)
    {
        if ($request->ajax()) {

            $requisitions = BuyerRequisition::get();

            return DataTables::of($requisitions)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';

                    if (auth()->user()->can('web_requisition_show')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.buyer-requisition.show', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Show</a>';
                    }
                    if (auth()->user()->can('web_requisition_delete')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.buyer-requisition.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }

                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('company', function ($row) {
                    return $row->company.' ('.$row->created_at->diffForHumans().')';
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('address', function ($row) {
                    return $row->address;
                })
                ->rawColumns(['action', 'company', 'name', 'email', 'phone', 'address'])
                ->smart(true)
                ->make(true);
        }

        return view('website::clients.requisition.index');
    }

    public function buyerRequisitionShow($id)
    {
        $requisition = BuyerRequisition::find($id);

        return view('website::clients.requisition.show', compact('requisition'));
    }

    public function buyerRequisitionDestroy($id)
    {
        $requisition = BuyerRequisition::find($id);
        $requisition->delete();

        return response()->json('Buyer requisition delete successfully');
    }
}
