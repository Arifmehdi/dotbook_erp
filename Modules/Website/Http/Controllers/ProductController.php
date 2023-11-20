<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Product;
use Modules\Website\Entities\ProductCategory;
use Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category')->orderBy('id', 'DESC')->get();

            return DataTables::of($products)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    if (auth()->user()->can('web_edit_product')) {
                        $html .= '<a class="dropdown-item" href="'.route('website.products.edit', $row->id).'" id="edit"><i class="far fa-edit text-primary"></i> Edit</a>';
                    }
                    if (auth()->user()->can('web_delete_product')) {
                        $html .= '<a class="dropdown-item" id="delete" href="'.route('website.products.destroy', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
                    }
                    $html .= '</div>';
                    $html .= '</div>';

                    return $html;
                })
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('category_id', function ($row) {
                    return $row->category->name;
                })
                ->editColumn('thumbnail', function ($row) {
                    if ($row->thumbnail) {
                        $html = '<img loading="lazy" class="rounded" style="height:30px; width:30px;" src="'.asset($row->thumbnail).'">';
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
                ->rawColumns(['action', 'title', 'category_id', 'thumbnail', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $categories = ProductCategory::where('status', 1)->orderBy('id', 'DESC')->get();

        return view('website::products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        if (! auth()->user()->can('web_add_product')) {
            abort(403, 'Access Forbidden.');
        }
        $request->validate([
            'title' => 'required|string',
            'category_id' => 'required',
        ]);

        $product = new Product();
        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/product');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $uploadnam = $FileUploadUtil->upload($request->file('images'), 'uploads/website/product');
                array_push($images, $uploadname);
            }
            $product->image = json_encode($images);
        }
        $product->category_id = $request->category_id;
        $product->slug = Str::of($request->title)->slug('-');
        $product->title = $request->title;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return response()->json('Product has been created successfully');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::products.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (! auth()->user()->can('web_edit_product')) {
            abort(403, 'Access Forbidden.');
        }

        $product = Product::find($id);
        $categories = ProductCategory::where('status', 1)->orderBy('id', 'DESC')->get();

        return view('website::products.edit', compact('product', 'categories'));
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

        $product = Product::find($id);

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $FileUploadUtil->upload($request->file('thumbnail'), 'uploads/website/product');
        } else {
            $product->thumbnail = $request->get('thumbnail');
        }

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $uploadnam = $FileUploadUtil->upload($request->file('images'), 'uploads/website/product');
                array_push($images, $uploadname);
            }
            $product->image = json_encode($images);
        } else {
            foreach ($request->get('images') as $key => $uploadname) {
                array_push($images, $uploadname);
            }
        }

        $product->image = json_encode($images);

        $product->category_id = $request->category_id;
        $product->slug = Str::of($request->title)->slug('-');
        $product->title = $request->title;
        $product->description = $request->description;
        $product->status = $request->status;
        $product->save();

        return response()->json('Product has been update successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();

        return response()->json('Product has been delete successfully');
    }
}
