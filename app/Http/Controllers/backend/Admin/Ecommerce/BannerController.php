<?php

namespace App\Http\Controllers\backend\Admin\Ecommerce;

use App\Models\HomeBanner;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{


    private $_data = [];
    private $_path = 'backend.admin.banner.';


    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function bannerList()
    {
        $this->_data['homebanners'] = HomeBanner::all();
        return view('backend.admin.banner.banner-list', $this->_data);
    }


    function addBanner()
    {
        $this->_data['homebanners'] = HomeBanner::all();
        return view('backend.admin.banner.add-banner', $this->_data);
    }

    function saveBanner(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required',
            'type' => 'required',
            'url' => 'required',
        ]);

        if (empty($request->w1)) return redirect()->back()->with('fail', __('message.Invalid image dimensions'));

        $validated['url'] = $request->url;
        $validated['type'] = $request->type;
        if ($request->type === 'link')
            $validated['slug'] = '-';
        else
            $validated['slug'] = $request->slug ?? '-';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('image/homebanner');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(825, 412)->save($destinationPath . '/' . $validated['image']);
        }

        if (HomeBanner::create($validated))
            return redirect()->to(route('admin-banner'))->with('success', __('message.Banner added successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function editBanner($id)
    {
        $this->_data['homebanner'] = HomeBanner::find($id);
        return view('backend.admin.banner.edit-banner', $this->_data);
    }

    function updateBanner($id, Request $request)
    {

        $validated = $request->validate([
//            'image' => 'required',
            'url' => 'required',
            'type' => 'required',
        ]);

        $validated['url'] = $request->url;
        $validated['type'] = $request->type;
        if ($request->type === 'link')
            $validated['slug'] = '-';
        else
            $validated['slug'] = $request->slug ?? '-';

        $homebanner = HomeBanner::find($id);
        if ($request->hasFile('image')) {
            if (empty($request->w1)) return redirect()->back()->with('fail', __('message.Invalid image dimensions'));
            $image = $request->file('image');
            $validated['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('image/homebanner');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(825, 412)->save($destinationPath . '/' . $validated['image']);
            $old_img = public_path('image/homebanner/' . $homebanner->image);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }
        }

        if ($homebanner->update($validated)) {
            return redirect()->back()->with('success', __('message.Banner updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function destroy($id)
    {
        if (HomeBanner::find($id)->delete())
            return redirect()->back()->with('success', __('message.Banner removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function statusBanner($id)
    {
        $banner = HomeBanner::find($id);
        if ($banner->update(['status' => ($banner->status == 1) ? 0 : 1]))
            return redirect()->back()->with('success', __('message.Banner status updated'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }
}
