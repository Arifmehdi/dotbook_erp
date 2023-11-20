<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CRM\Http\Requests\Source\SourceStoreRequest;
use Modules\CRM\Http\Requests\Source\SourceUpdateRequest;
use Modules\CRM\Interfaces\SourceServiceInterface;
use Yajra\DataTables\Facades\DataTables;

class SourceController extends Controller
{
    private $source;

    public function __construct(SourceServiceInterface $source)
    {
        $this->source = $source;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $source = $this->source->all();

            return DataTables::of($source)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.source.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.source.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('crm::source.index');
    }

    public function store(SourceStoreRequest $request)
    {
        $source = $this->source->store($request->validated());

        return response()->json('Source created successfully');
    }

    public function edit(Request $request, $id)
    {
        $source = $this->source->find($id);

        return view('crm::source.ajax_view.index', compact('source'));
    }

    public function update(SourceUpdateRequest $request, $id)
    {
        $source = $this->source->update($request->validated(), $id);

        return response()->json('Source updated successfully');
    }

    public function delete($id)
    {
        $source = $this->source->destroy($id);

        return response()->json('Source deleted successfully');
    }
}
