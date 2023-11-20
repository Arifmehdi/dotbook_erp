<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Utils\FileUploadUtil;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Website\Entities\Banner;
use Session;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index()
    {
        $banner = Banner::first();

        return view('website::banner.index', compact('banner'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('website::banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request, FileUploadUtil $FileUploadUtil)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $banners = Banner::first();
        if (! $banners) {
            $banners = new Banner();
        }
        if ($request->hasFile('banner1')) {
            $banners->banner1 = $FileUploadUtil->upload($request->file('banner1'), 'uploads/website/banner');
        }
        if ($request->hasFile('banner2')) {
            $banners->banner2 = $FileUploadUtil->upload($request->file('banner2'), 'uploads/website/banner');
        }
        if ($request->hasFile('banner3')) {
            $banners->banner3 = $FileUploadUtil->upload($request->file('banner3'), 'uploads/website/banner');
        }
        if ($request->hasFile('banner4')) {
            $banners->banner4 = $FileUploadUtil->upload($request->file('banner4'), 'uploads/website/banner');
        }
        if ($request->hasFile('banner5')) {
            $banners->banner5 = $FileUploadUtil->upload($request->file('banner5'), 'uploads/website/banner');
        }
        if ($request->hasFile('banner6')) {
            $banners->banner6 = $FileUploadUtil->upload($request->file('banner6'), 'uploads/website/banner');
        }

        $banners->title = $request->title;
        $banners->save();
        Session::flash('success', 'Banner us has been updated successfully');

        return redirect()->back();
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('website::banner.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('website::banner.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
