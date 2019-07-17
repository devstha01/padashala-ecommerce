<?php

namespace App\Http\Controllers\backend;

use App\Http\Traits\MinMaxConfig;
use App\Models\Color;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class ResponseController extends Controller
{
    use MinMaxConfig;

    function getSubCategory(Request $request)
    {
        $data['sub'] = SubCategory::where('category_id', $request->category_id)->where('status', 1)->get() ?? [];
//        $categories = SubCategory::where('category_id', $request->category_id)->where('status', 1)->get() ?? [];
//        $data = [];
//        foreach ($categories as $category) {
//            $catStatus = false;
//            if (count($category->getSubChildCategory->where('status', 1)) !== 0)
//                $catStatus = true;
//
//            if ($catStatus)
//                $data[] = $category;
//        }
//        $data['sub'] = collect($data);
        return response()->json($data);
    }

    function getSubChildCategory(Request $request)
    {
        $data['sub'] = SubChildCategory::where('sub_category_id', $request->sub_category_id)->where('status', 1)->get() ?? false;
        return response()->json($data);
    }

    function colorsList()
    {
        return response()->json(Color::all());
    }

    function minmaxCheck(Request $request)
    {
        return $this->validationMinMax($request->wallet, $request->amount);
    }

    function viewNotification($type)
    {
        switch ($type) {
            case'member':
                return redirect()->back();
                break;
            case'merchant':
                break;
            case'admin':
                break;
            default:
                return redirect()->back();
                break;
        }
        return view('backend.master.layouts');
    }

}

