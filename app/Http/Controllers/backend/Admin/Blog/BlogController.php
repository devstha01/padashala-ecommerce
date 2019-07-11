<?php

namespace App\Http\Controllers\backend\Admin\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class BlogController extends Controller
{
    private $_path = 'backend.admin.blog.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');
        
        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function index()
    {
        $this->_data['blogs']=Blog::all();
        return view('backend.admin.blog.index',$this->_data);
    }

    public function createContent()
    {
        $this->_data['blogs']=Blog::all();
        return view('backend.admin.blog.create',$this->_data);
    }

    public function saveContent(Request $request)
    {
        
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date_published' => 'required',
            'author' => 'required',
            
        ]);
        
        $validated['slug'] = str_slug($request->name) . '-' . strtotime(Carbon::now());
        $validated['title'] = $request->title;
        $validated['description'] = $request->description;
        $validated['date_published'] = $request->date_published;
        $validated['author'] = $request->author;


        

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = time() . '.' . $image->getClientOriginalName();
            $destinationPath = public_path('image/blog');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(1310, 1000, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $validated['image']);
        }


        if (Blog::create($validated))
            return redirect()->to(route('admin-blog'))->with('success', __('message.Content added successfully'));
        return redirect()->back()->with('fail',__('message.Failed to add contents'));
    }

    public function editContent($id)
    {
        $this->_data['blog']=Blog::find($id);
        return view('backend.admin.blog.edit',$this->_data);
    }

    public function updateContent(Request $request,$id)
    {
       
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'date_published' => 'required',
            'author' => 'required',
        ]);
        $validated['slug'] = str_slug($request->name) . '-' . strtotime(Carbon::now());
        $validated['description'] = $request->description;
        $validated['title'] = $request->title;
        $validated['date_published'] = $request->date_published;
        $validated['author'] = $request->author;
       
       
        $blog = Blog::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('image/blog');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(600, 600, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $validated['image']);
            $old_img = public_path('image/blog/' . $blog->image);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }
        }

        if ($blog->update($validated)) {
            return redirect()->to(route('admin-blog'))->with('success', __('message.Content updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to update content'));
    }

    function destroy($id)
    {
        if (Blog::find($id)->delete())
            return redirect()->back()->with('success', __('message.Content removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }
}
