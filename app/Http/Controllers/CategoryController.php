<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Utils\InvoiceVoucherRefIdUtil;
use App\Utils\UserActivityLogUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    protected $userActivityLogUtil;

    protected $invoiceVoucherRefIdUtil;

    public function __construct(UserActivityLogUtil $userActivityLogUtil, InvoiceVoucherRefIdUtil $invoiceVoucherRefIdUtil)
    {
        $this->userActivityLogUtil = $userActivityLogUtil;
        $this->invoiceVoucherRefIdUtil = $invoiceVoucherRefIdUtil;
    }

    // Category main page/index page
    public function index(Request $request)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        if ($request->ajax()) {

            $categories = DB::table('categories')
                ->where('parent_category_id', null)
                ->orderBy('categories.name', 'asc')->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->editColumn('photo', function ($row) {
                    $imgSrc = (isset($row->photo) && file_exists(public_path('uploads/category/' . $row->photo))) ? asset('uploads/category/' . $row->photo) : asset('images/default.jpg');
                    return '<img loading="lazy" class="rounded img-thumbnail" style="height:30px; width:30px;"  src="' . $imgSrc . '">';
                })
                ->addColumn('action', function ($row) {

                    $html = '<div class="dropdown table-dropdown">';
                    $html .= '<a href="' . route('product.categories.edit', [$row->id]) . '" class="action-btn c-edit" id="editCategory" title="Edit"><span class="fas fa-edit"></span></a>';
                    $html .= '<a href="' . route('product.categories.delete', [$row->id]) . '" class="action-btn c-delete" id="deleteCategory" title="Delete"><span class="fas fa-trash "></span></a>';
                    $html .= '</div>';

                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {

                        return route('product.categories.edit', $row->id);
                    },
                ])->rawColumns(['photo', 'action'])->smart(true)->make(true);
        }

        $categories = DB::table('categories')->where('parent_category_id', null)->get();

        return view('inventories.categories.index', compact('categories'));
    }

    public function create()
    {

        return view('inventories.categories.ajax_view.category.create_modal');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) {
                return $query->where('parent_category_id', null);
            })],
            'code' => 'nullable|unique:categories,code',
            'photo' => 'nullable|image|max:2048',
        ]);

        $addCategory = '';
        $code = $request->code ? $request->code : str_split($request->name)[0] . $this->invoiceVoucherRefIdUtil->generateCategoryCode();

        if ($request->file('photo')) {

            $categoryPhoto = $request->file('photo');
            $categoryPhotoName = uniqid() . '.' . $categoryPhoto->getClientOriginalExtension();
            Image::make($categoryPhoto)->resize(250, 250)->save('uploads/category/' . $categoryPhotoName);

            $addCategory = Category::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $addCategory = Category::create([
                'name' => $request->name,
                'code' => $code,
                'description' => $request->description,
            ]);
        }

        if ($addCategory) {

            $this->userActivityLogUtil->addLog(action: 1, subject_type: 20, data_obj: $addCategory);
        }

        return $addCategory;
    }

    public function edit($id)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $category = DB::table('categories')->where('id', $id)->first();

        return view('inventories.categories.ajax_view.category.edit_modal', compact('category'));
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('categories')) {

            abort(403, 'Access Forbidden.');
        }

        $this->validate($request, [
            'name' => ['required', Rule::unique('categories')->where(function ($query) use ($id) {
                return $query->where('parent_category_id', null)->where('id', '!=', $id);
            })],
            'photo' => 'nullable|image|max:2048',
        ]);

        $updateCategory = Category::where('id', $id)->first();

        if ($request->hasFile('photo')) {

            if (isset($updateCategory->photo)) {
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
                'code' => $request->code ? $request->code : $updateCategory->code,
                'photo' => $categoryPhotoName,
            ]);
        } else {

            $updateCategory->update([
                'name' => $request->name,
                'code' => $request->code ? $request->code : $updateCategory->code,
                'description' => $request->description,
            ]);
        }

        $this->userActivityLogUtil->addLog(action: 2, subject_type: 20, data_obj: $updateCategory);

        return response()->json('Category updated successfully!');
    }

    public function delete(Request $request, $categoryId)
    {
        if (!auth()->user()->can('categories')) {
            abort(403, 'Access Forbidden.');
        }

        $deleteCategory = Category::with(['subCategories'])->where('id', $categoryId)->first();

        if (count($deleteCategory->subCategories) > 0) {

            return response()->json(['errorMsg' => 'Category can not be deleted. One or more sub-categories are belonging under this category.']);
        }

        if ($deleteCategory->photo) {
            if (file_exists(public_path('uploads/category/' . $deleteCategory->photo))) {
                unlink(public_path('uploads/category/' . $deleteCategory->photo));
            }
        }

        if (!is_null($deleteCategory)) {

            $this->userActivityLogUtil->addLog(action: 3, subject_type: 20, data_obj: $deleteCategory);

            $deleteCategory->delete();
        }

        $count = DB::table('categories')->count();

        if ($count == 0) {
            DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');
        }

        return response()->json('Category deleted successfully!');
    }
}
