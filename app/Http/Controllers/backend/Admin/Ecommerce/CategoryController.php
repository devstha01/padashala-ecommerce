<?php

namespace App\Http\Controllers\backend\Admin\Ecommerce;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    private $_data = [];
    private $_path = 'backend.admin.e-commerce.category.';

    public function __construct()
    {
        $this->middleware('admin');
        session()->flash('cat', 0);
        session()->flash('sub', 0);
    }

    function viewCategory()
    {

        $this->_data['categories'] = Category::all();
        return view($this->_path . 'table-category', $this->_data)->with(['title' => __('message.E-commerce | Category Control')]);
    }

    function addSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
//            'id' => 'required',
            'type' => 'required'
        ]);

        if ($request->type === 'category') {
            $input['name'] = $request->name;
            $input['share_percentage'] = $request->category_share??0;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->name);
            do {
                $check = Category::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);
            $input['slug'] = $slug;


            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);
            }
            if ($id = Category::create($input)->id) {
                return redirect()->back()->with('success', __('message.Category added successfully'));
            }

        } elseif ($request->type === 'sub-category') {
            $input['category_id'] = $request->id;
            $input['name'] = $request->name;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->name);
            do {
                $check = SubCategory::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);
            $input['slug'] = $slug;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);
            }
            if ($id = SubCategory::create($input)->id) {
                session()->flash('cat', $request->id);

                return redirect()->back()->with('success', __('message.Sub Category added successfully'));
            }

        } elseif ($request->type === 'sub-child-category') {
            $input['sub_category_id'] = $request->id;
            $input['name'] = $request->name;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->name);
            do {
                $check = SubChildCategory::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);

            $input['slug'] = $slug;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);
            }
            if ($id = SubChildCategory::create($input)->id) {
                session()->flash('cat', SubCategory::find($request->id)->category_id);
                session()->flash('sub', $request->id);

                return redirect()->back()->with('success', __('message.Sub Child Category added successfully'));
            }
        }
        return redirect()->back()->with('fail', __('message.Failed to add category subs'));
    }

    function editCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'id' => 'required',
            'type' => 'required'
        ]);
        if ($request->type === 'category') {
            $cat = Category::find($request->id);
            $input['name'] = $request->name;
            $input['share_percentage'] = $request->category_share??0;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);
                $old_img = public_path('image/admin/category/' . $cat->image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }

            }
            if ($cat->update($input)) {
                return redirect()->back()->with('success', __('message.Category updated successfully'));
            }
        } elseif ($request->type === 'sub-category') {
            $sub = SubCategory::find($request->id);
            $input['name'] = $request->name;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);

                $old_img = public_path('image/admin/category/' . $sub->image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }

            }
            if ($sub->update($input)) {
                session()->flash('cat', SubCategory::find($request->id)->category_id);
                return redirect()->back()->with('success', __('message.Sub Category updated successfully'));
            }

        } elseif ($request->type === 'sub-child-category') {
            $subC = SubChildCategory::find($request->id);
            $input['name'] = $request->name;
//            $input['ch_name'] = $request->ch_name;
//            $input['trch_name'] = $request->trch_name;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('image/admin/category');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $img = Image::make($image->getRealPath());
                $img->resize(450, 450, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['image']);
                $old_img = public_path('image/admin/category/' . $subC->image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }

            }
            if ($subC->update($input)) {
                session()->flash('cat', SubChildCategory::find($request->id)->getParentSubCategory->getParentCategory->id);
                session()->flash('sub', SubChildCategory::find($request->id)->sub_category_id);

                return redirect()->back()->with('success', __('message.Sub Child Category updated successfully'));
            }
        }
        return redirect()->back()->with('fail', __('message.Failed to update!'));
    }

//    function deleteCategory($id)
//    {
//        if ($category = Category::find($id)) {
//            foreach ($category->getSubCategory as $subCategory) {
//                foreach ($subCategory->getSubChildCategory as $subChildCategory) {
//                    $subChildCategory->delete();
//                }
//                $subCategory->delete();
//            }
//            $category->delete();
//            return redirect()->back()->with('success', __('message.Category deleted successfully'));
//        }
//        return redirect()->back()->with('fail', 'Failed to delete category!');
//    }
//
//    function deleteSubCategory($id)
//    {
//        if ($subCategory = SubCategory::find($id)) {
//            foreach ($subCategory->getSubChildCategory as $subChildCategory) {
//                $subChildCategory->delete();
//            }
//            $subCategory->delete();
//            return redirect()->back()->with('success', 'Sub Category deleted successfully ');
//        }
//        return redirect()->back()->with('fail', 'Failed to delete sub-category!');
//    }
//
//    function deleteSubChildCategory($id)
//    {
//        if ($subChildCategory = SubChildCategory::find($id)) {
//            $subChildCategory->delete();
//            return redirect()->back()->with('success', 'Sub Child Category deleted successfully ');
//        }
//        return redirect()->back()->with('fail', 'Failed to delete sub-child-category!');
//    }


    function changeStatus($type, $id)
    {
        if ($type === 'category') {
            $db = Category::find($id);
        } elseif ($type === 'sub-category') {
            session()->flash('cat', SubCategory::find($id)->category_id);
            $db = SubCategory::find($id);
        } elseif ($type === 'sub-child-category') {
            session()->flash('cat', SubChildCategory::find($id)->getParentSubCategory->getParentCategory->id);
            session()->flash('sub', SubChildCategory::find($id)->sub_category_id);
            $db = SubChildCategory::find($id);
        }
        if ($db->status === 1) {
            $db->update(['status' => 0]);
        } else {
            $db->update(['status' => 1]);
        }
        return redirect()->back()->with('success', __('message.:type status changed for :name', ['type' => $type, 'name' => $db->name]));
    }

    function highlightCategory($id)
    {
        $db = Category::find($id);
        $db->update(['is_highlighted' => $db->is_highlighted ? 0 : 1]);
        return redirect()->back()->with('info', 'Category highlight updated');
    }
}
