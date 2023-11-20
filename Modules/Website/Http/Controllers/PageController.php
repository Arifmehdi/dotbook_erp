<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Image;
use Modules\Website\Entities\AboutUs;
use Modules\Website\Entities\DirectorMessage;
use Modules\Website\Entities\History;
use Modules\Website\Entities\Page;
use Session;
use Str;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pages = Page::orderBy('id', 'DESC')->get();

            return DataTables::of($pages)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_page')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.pages.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_page')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.pages.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('url', function ($row) {
                    return $row->slug;
                })
                ->editColumn('position', function ($row) {
                    return ucwords(str_replace('_', ' ', $row->position));
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'image', 'title', 'url', 'position', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::page.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::page.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (! auth()->user()->can('web_add_page')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
        ]);

        $page = new Page;
        $page->title = $request->title;
        if ($request->slug) {
            $page->slug = $request->slug;
        } else {
            $page->slug = Str::of($request->title)->slug('-');
        }
        $page->description = $request->description;
        $page->position = $request->position;
        $page->status = $request->status ?? 0;
        $page->save();

        return response()->json('Page has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::page.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_add_page')) {
            abort(403, 'Access Forbidden.');
        }

        $page = Page::find($id);

        return view('website::page.edit', compact('page'));
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
            'title' => 'required|string',
        ]);

        $page = Page::find($id);
        $page->title = $request->title;
        if ($request->slug) {
            $page->slug = $request->slug;
        } else {
            $page->slug = Str::of($request->title)->slug('-');
        }
        $page->description = $request->description;
        $page->position = $request->position;
        $page->status = $request->status;
        $page->save();

        return response()->json('Page has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        return response()->json('Page has been delete successfully');
    }

    public function about_us()
    {
        $aboutus = AboutUs::first();

        return view('website::page.about_us', compact('aboutus'));
    }

    public function aboutusPost(Request $request)
    {
        if (! auth()->user()->can('web_about_us')) {
            abort(403, 'Access Forbidden.');
        }

        $aboutus = AboutUs::first();
        if (! $aboutus) {
            $aboutus = new AboutUs();
        }
        if ($request->hasFile('image')) {
            $about_image = $request->file('image');
            $about_imageName = hexdec(uniqid()).'.'.$about_image->getClientOriginalExtension();
            Image::make($about_image)->save('uploads/website/pages/'.$about_imageName);
            $aboutus->image = route('dashboard.dashboard').'/uploads/website/pages/'.$about_imageName;
        }
        $aboutus->about = $request->about;
        $aboutus->mission = $request->mission;
        $aboutus->vission = $request->vission;
        $aboutus->quality = $request->quality;
        $aboutus->ideas = $request->ideas;
        $aboutus->save();
        Session::flash('success', 'About us has been updated successfully');

        return redirect()->back();
    }

    public function history()
    {
        $history = History::first();

        return view('website::page.history', compact('history'));
    }

    public function historyUpdate(Request $request)
    {
        $history = History::first();
        if (! $history) {
            $history = new History();
        }
        if ($request->hasFile('image')) {
            $history_image = $request->file('image');
            $history_imageName = hexdec(uniqid()).'.'.$history_image->getClientOriginalExtension();
            Image::make($history_image)->save('uploads/website/pages/'.$history_imageName);
            $history->image = route('dashboard.dashboard').'/uploads/website/pages/'.$history_imageName;
        }
        $history->description = $request->description;
        $history->save();
        Session::flash('success', 'History has been updated successfully');

        return redirect()->back();
    }

    public function messageDirector()
    {
        $director_message = DirectorMessage::first();

        return view('website::page.message_director', compact('director_message'));
    }

    public function messageDirectorUpdate(Request $request)
    {
        $director_message = DirectorMessage::first();
        if (! $director_message) {
            $director_message = new DirectorMessage();
        }
        if ($request->hasFile('image')) {
            $history_image = $request->file('image');
            $history_imageName = hexdec(uniqid()).'.'.$history_image->getClientOriginalExtension();
            Image::make($history_image)->save('uploads/website/pages/'.$history_imageName);
            $director_message->image = route('dashboard.dashboard').'/uploads/website/pages/'.$history_imageName;
        }
        $director_message->name = $request->name;
        $director_message->message = $request->message;
        $director_message->save();
        Session::flash('success', 'Director message has been updated successfully');

        return redirect()->back();
    }
}
