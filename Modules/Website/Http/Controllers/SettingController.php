<?php

namespace Modules\Website\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Image;
use Modules\Website\Entities\Contact;
use Modules\Website\Entities\DealerRequest;
use Modules\Website\Entities\GeneralSetting;
use Modules\Website\Entities\SeoSetting;
use Modules\Website\Entities\SocialLink;
use Session;
use Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function generalSettings()
    {
        $setting = GeneralSetting::first();

        return view('website::setting.general_setting', compact('setting'));
    }

    public function generalSettingsUpdate(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'site_name' => 'required',
            'app_url' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'website' => 'required|string',
        ]);
        if ($validator->fails()) {
            Session::flash('success', 'Something went wrong');

            return redirect()->back();
        }

        $settings = GeneralSetting::first();

        if (! $settings) {
            $settings = new GeneralSetting();
        }

        if ($request->hasFile('backend_logo')) {
            $post_image = $request->file('backend_logo');
            $post_imageName = hexdec(uniqid()).'.'.$post_image->getClientOriginalExtension();
            Image::make($post_image)->resize(230, 54)->save('uploads/website/setting/'.$post_imageName);
            $settings->backend_logo = route('dashboard.dashboard').'/uploads/website/setting/'.$post_imageName;
        }

        if ($request->hasFile('frontend_header_logo')) {
            $logo_image = $request->file('frontend_header_logo');
            $post_imageName = hexdec(uniqid()).'.'.$logo_image->getClientOriginalExtension();
            Image::make($logo_image)->resize(230, 54)->save('uploads/website/setting/'.$post_imageName);
            $settings->frontend_header_logo = route('dashboard.dashboard').'/uploads/website/setting/'.$post_imageName;
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = hexdec(uniqid()).'.'.$favicon->getClientOriginalExtension();
            Image::make($favicon)->resize(64, 64)->save('uploads/website/setting/'.$faviconName);
            $settings->favicon = route('dashboard.dashboard').'/uploads/website/setting/'.$faviconName;
        }

        if ($request->hasFile('frontend_footer_logo')) {
            $logo_image = $request->file('frontend_footer_logo');
            $post_imageName = hexdec(uniqid()).'.'.$logo_image->getClientOriginalExtension();
            Image::make($logo_image)->resize(230, 54)->save('uploads/website/setting/'.$post_imageName);
            $settings->frontend_footer_logo = route('dashboard.dashboard').'/uploads/website/setting/'.$post_imageName;
        }

        $settings->site_name = $request->site_name;
        $settings->app_url = $request->app_url;
        $settings->address1 = $request->address1;
        $settings->address2 = $request->address2;
        $settings->phone = $request->phone;
        $settings->email = $request->email;
        $settings->map = $request->map;
        $settings->description = $request->description;
        $settings->website = $request->website;
        $settings->fax = $request->fax;
        $settings->office_time = $request->office_time;
        $settings->office_days = $request->office_days;
        $settings->call_hour = $request->call_hour;
        $settings->get_in_touch = $request->get_in_touch;
        $settings->save();
        Session::flash('success', 'General setting has been updated successfully');

        return redirect()->back();
    }

    public function seoSettings()
    {
        $seo_setting = SeoSetting::first();

        return view('website::setting.seo_setting', compact('seo_setting'));
    }

    public function seoSettingsUpdate(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'meta_title' => 'required|string',
            'meta_tag' => 'required|string',
            'meta_description' => 'required|string',
            'meta_author' => 'required|string',
            'google_analytics' => 'required|string',
            'google_verification' => 'required|string',
            'bing_verification' => 'required|string',
            'alexa_analytics' => 'required|string',
        ]);
        if ($validator->fails()) {
            Session::flash('success', 'Something went wrong');

            return redirect()->back();
        }

        $seo = SeoSetting::first();
        if (! $seo) {
            $seo = new SeoSetting();
        }
        $seo->meta_title = $request->meta_title;
        $seo->meta_tag = $request->meta_tag;
        $seo->meta_description = $request->meta_description;
        $seo->meta_author = $request->meta_author;
        $seo->google_analytics = $request->google_analytics;
        $seo->google_verification = $request->google_verification;
        $seo->bing_verification = $request->bing_verification;
        $seo->alexa_analytics = $request->alexa_analytics;
        $seo->save();
        Session::flash('success', 'Seo setting has been updated successfully');

        return redirect()->back();
    }

    public function socialLink()
    {
        $socila_link = SocialLink::first();

        return view('website::setting.social_link', compact('socila_link'));
    }

    public function socialLinkUpdate(Request $request)
    {
        $social = SocialLink::first();
        if (! $social) {
            $social = new SocialLink();
        }
        $social->facebook = $request->facebook;
        $social->twitter = $request->twitter;
        $social->pinterest = $request->pinterest;
        $social->linkedin = $request->linkedin;
        $social->instagram = $request->instagram;
        $social->youtube = $request->youtube;
        $social->save();
        Session::flash('success', 'Social link has been updated successfully');

        return redirect()->back();
    }

    public function contact(Request $request)
    {
        if ($request->ajax()) {
            $contacts = Contact::get();

            return DataTables::of($contacts)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('website.contact.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('subject', function ($row) {
                    return $row->subject;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Seen';
                    } else {
                        $html = 'Unseen';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'email', 'phone', 'subject', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::setting.contact');
    }

    public function contactDelete($id)
    {
        $contact = Contact::find($id);
        $contact->delete();

        return response()->json('Contact has been delete successfully');
    }

    public function dealershipRequest(Request $request)
    {
        if ($request->ajax()) {
            $dealer_requests = DealerRequest::get();

            return DataTables::of($dealer_requests)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '<div class="btn-group" role="group">';
                    $html .= '<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>';
                    $html .= '<div class="dropdown-menu" aria-labelledby="btnGroupDrop1">';
                    $html .= '<a class="dropdown-item" id="delete" href="'.route('website.dealer.request.delete', [$row->id]).'"><i class="far fa-trash-alt text-primary"></i> Delete</a>';
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
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('shopname', function ($row) {
                    return $row->shopname;
                })
                ->editColumn('contact_address', function ($row) {
                    return $row->contact_address;
                })
                ->editColumn('nid', function ($row) {
                    return $row->nid;
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 1) {
                        $html = 'Acive';
                    } else {
                        $html = 'In-Active';
                    }

                    return $html;
                })
                ->rawColumns(['action', 'name', 'email', 'phone', 'shopname', 'contact_address', 'nid', 'status'])
                ->smart(true)
                ->make(true);
        }

        return view('website::setting.dealer_request');
    }

    public function dealerrequestDelete($id)
    {
        $dealer_requests = DealerRequest::find($id);
        $dealer_requests->delete();

        return response()->json('Dealer request has been delete successfully');
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
    public function store(Request $request)
    {
        //
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
        return view('website::edit');
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
