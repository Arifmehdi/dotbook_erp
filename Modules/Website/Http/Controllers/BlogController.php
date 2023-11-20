<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\Blog;
use Modules\Website\Entities\BlogCategories;
use Modules\Website\Entities\Comment;
use Str;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $blogs = Blog::orderBy('id', 'DESC')->get();

            return DataTables::of($blogs)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_blog')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.blog.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_blog')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.blog.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('category', function ($row) {
                    if ($row->blog_categories) {
                        return $row->blog_categories->name;
                    }

                    return 'Nothing Found';
                })
                ->editColumn('image', function ($row) {
                    if ($row->image) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->image).'">';
                    } else {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset('images/default.jpg').'">';
                    }

                    return $html;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->editColumn('slug', function ($row) {
                    return $row->slug;
                })
                ->rawColumns(['action', 'title', 'category', 'image', 'status', 'slug'])
                ->smart(true)
                ->make(true);
        }

        return view('website::blog.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $blog_categories = BlogCategories::where('status', 1)->get();

        return view('website::blog.create', compact('blog_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_blog')) {
            abort(403, 'Access Forbidden.');
        }

        $request->validate([
            'title' => 'required|string',
            'category_id' => 'required',
        ]);

        $blog = new Blog();

        if ($request->hasFile('image')) {
            $blog->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/blog');
        }

        $blog->title = $request->title;
        $blog->user_id = Auth::user()->id;
        $blog->blog_categories_id = $request->category_id;
        $blog->slug = Str::of($request->title)->slug('-');
        $blog->description = $request->description;
        $blog->tags = $request->tags;
        $blog->status = $request->status ?? 0;
        $blog->save();

        return response()->json('Blog has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::blog.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_blog')) {
            abort(403, 'Access Forbidden.');
        }

        $blog_categories = BlogCategories::where('status', 1)->get();
        $blog = Blog::find($id);

        return view('website::blog.edit', compact('blog_categories', 'blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id, FileUploadUtil $FileUploadUtil)
    {
        $request->validate([
            'title' => 'required|string',
            'category_id' => 'required',
        ]);

        $blog = Blog::find($id);
        if ($request->hasFile('image')) {
            $blog->image = $FileUploadUtil->upload($request->file('image'), 'uploads/website/blog');
        }
        $blog->title = $request->title;
        $blog->user_id = Auth::user()->id;
        $blog->blog_categories_id = $request->category_id;
        $blog->slug = Str::of($request->title)->slug('-');
        $blog->description = $request->description;
        $blog->tags = $request->tags;
        $blog->status = $request->status ?? 0;
        $blog->save();

        return response()->json('Blog has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);
        $blog->delete();

        return response()->json('Blog has been delete successfully');
    }

    public function blog_comments(Request $request)
    {
        if ($request->ajax()) {
            $comments = Comment::with('blog')->orderBy('id', 'DESC')->get();

            return DataTables::of($comments)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == 1) {
                        $status = 0;
                        $text = 'In-Active';
                        $icon = 'far fa-thumbs-down';
                    } else {
                        $status = 1;
                        $text = 'Active';
                        $icon = 'far fa-thumbs-up';
                    }

                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_comment')) {
                        $html .= '<a class="dropdown-item" id="status" href="'.route('website.comments.status', [$row->id, $status]).'" data-id="'.$text.'"><i class="'.$icon.'"></i> '.$text.'</a>';
                    }
                    if (auth()->user()->can('web_delete_comment')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.comments.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('blog', function ($row) {
                    return $row->blog->title;
                })
                ->editColumn('website', function ($row) {
                    return $row->website;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status) {
                        $html = 'Active';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'email', 'blog', 'website', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::blog.comments.index');
    }

    public function blog_comments_status($id, $status)
    {
        $comment = Comment::find($id);
        $comment->status = $status;
        $comment->save();

        return response()->json('Blog status has been updated successfully');
    }

    public function blog_comments_delete($id)
    {
        $comment = Comment::find($id);
        $comment->delete();

        return response()->json('Blog comment has been delete successfully');
    }
}
