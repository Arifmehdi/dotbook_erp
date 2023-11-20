<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Utils\UserActivityLogUtil;
use DB;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    protected $userActivityLogUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('categories')) {
            abort(403, 'Access Forbidden.');
        }
        $img_url = asset('uploads/category/');

        if ($request->ajax()) {

            $subCategories = DB::table('categories')
                ->join('categories as parentcat', 'parentcat.id', 'categories.parent_category_id')
                ->select('parentcat.name as parentname', 'categories.*')
                ->whereNotNull('categories.parent_category_id')->orderBy('categories.name', 'asc');

            return DataTables::of($subCategories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) use ($img_url) {
                    $imgSrc = (isset($row->photo) && file_exists(public_path('uploads/category/' . $row->photo))) ? asset('uploads/category/' . $row->photo) : asset('images/default.jpg');
                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $imgSrc . '">';
                })
                ->addColumn('action', function ($row) {
                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.subcategories.edit', [$row->id]) . '" class="action-btn c-edit" id="editSubcategory" data-id="' . $row->id . '"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('product.subcategories.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteSubcategory" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->rawColumns(['photo', 'action'])
                ->make(true);
        }
    }

    public function create($fixedParentCategoryId = null)
    {

        $fixedParentCategory = '';
        if (isset($fixedParentCategoryId)) {

            $fixedParentCategory = DB::table('categories')->where('id', $fixedParentCategoryId)->first();
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get();

        return view('inventories.categories.ajax_view.sub_category.create_modal', compact('categories', 'fixedParentCategory'));
    }

    public function edit($id)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $subcategory = DB::table('categories')->where('id', $id)->first();
        $categories = DB::table('categories')->where('parent_category_id', null)->get();

        return view('inventories.categories.ajax_view.sub_category.edit_modal', compact('categories', 'subcategory'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent category field is required']);

        $addSubCategory = '';
        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $addSubCategory = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : null,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $addSubCategory = Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : null,
            ]);
        }
        if ($addSubCategory) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 21, data_obj: $addSubCategory);
        }

        return $addSubCategory;
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => 'required',
            'parent_category_id' => 'required',
            'photo' => 'sometimes|image|max:2048',
        ], ['parent_category_id.required' => 'Parent category field is required']);

        $updateCategory = Category::where('id', $id)->first();

        if ($request->file('photo')) {

            if ($updateCategory->photo) {

                if (file_exists(public_path('uploads/category/' . $updateCategory->photo))) {

                    unlink(public_path('uploads/category/' . $updateCategory->photo));
                }
            }

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : null,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $updateCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'parent_category_id' => $request->parent_category_id ? $request->parent_category_id : null,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 21, data_obj: $updateCategory);

        return response()->json('Sub-Category updated successfully.');
    }

    public function delete(Request $request, $categoryId)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $deleteCategory = Category::find($categoryId);

        if ($deleteCategory->photo) {

            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {

                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 21, data_obj: $deleteCategory);
            $deleteCategory->delete();
        }

        $count = DB::table('categories')->count();
        if ($count == 0) {

            DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');
        }

        return response()->json('Subcategory deleted Successfully');
    }
}
