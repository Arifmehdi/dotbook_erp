<?php

namespace Modules\CRM\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CRM\Entities\LifeStage;
use Modules\CRM\Http\Requests\LifeStage\LifeStageRequest;
use Yajra\DataTables\Facades\DataTables;

class LifeStageController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $life_stage = LifeStage::all()->sortByDesc('id');

            return DataTables::of($life_stage)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" href="'.route('crm.life.stage.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('crm.life.stage.delete', $row->id).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['action'])
                ->smart(true)
                ->make(true);
        }

        return view('crm::life_stage.index');
    }

    public function store(LifeStageRequest $request)
    {
        $life_stage = new LifeStage();
        $life_stage->name = $request->name;
        $life_stage->description = $request->description;
        $life_stage->save();

        return response()->json('Life Stage created successfully');
    }

    public function edit(Request $request, $id)
    {
        $life_stage = LifeStage::find($id);

        return view('crm::life_stage.ajax_view.index', compact('life_stage'));
    }

    public function update(LifeStageRequest $request, $id)
    {
        $life_stage = LifeStage::find($id);
        $life_stage->name = $request->name;
        $life_stage->description = $request->description;
        $life_stage->save();

        return response()->json('Life Stage updated successfully');
    }

    public function delete($id)
    {
        $life_stage = LifeStage::find($id);
        $life_stage->delete();

        return response()->json('Life Stage deleted successfully');
    }
}
