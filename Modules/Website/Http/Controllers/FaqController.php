<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Faq;
use Yajra\DataTables\Facades\DataTables;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $faqs = Faq::orderBy('id', 'DESC')->get();

            return DataTables::of($faqs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_faq')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.faq.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_faq')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.faq.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('question', function ($row) {
                    return $row->question;
                })
                ->editColumn('answer', function ($row) {
                    return $row->answer;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'question', 'answer', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::faq.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::faq.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (auth()->user()->can('web_add_faq')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faqs = new Faq();
        $faqs->question = $request->question;
        $faqs->answer = $request->answer;
        $faqs->status = $request->status ?? 0;
        $faqs->save();

        return response()->json('Faq has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::faq.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (auth()->user()->can('web_edit_faq')) {
            abort(403, 'Access Forbidden.');
        }

        $faq = Faq::find($id);

        return view('website::faq.edit', compact('faq'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $faqs = Faq::find($id);
        $faqs->question = $request->question;
        $faqs->answer = $request->answer;
        $faqs->status = $request->status ?? 0;
        $faqs->save();

        return response()->json('Faq has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $faqs = Faq::find($id);
        $faqs->delete();

        return response()->json('Faq has been delete successfully');
    }
}
