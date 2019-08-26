<?php

namespace App\Http\Controllers\backend\Admin\Merchant;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StandardProductController extends Controller
{
    private $_path = 'backend.admin.merchant-master.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function allProducts()
    {
        $this->_data['products'] = Product::where('status', 1)->latest()->get();
        $this->_data['active_tab'] = 'all';
        return view($this->_path . 'product-list', $this->_data);
    }

    function standardProducts()
    {
        $this->_data['products'] = Product::where('standard_product', 1)->where('status', 1)->latest()->get();
        $this->_data['active_tab'] = 'standard';
        return view($this->_path . 'product-list', $this->_data);
    }

    function normalProducts()
    {
        $this->_data['products'] = Product::where('standard_product', 0)->where('status', 1)->latest()->get();
        $this->_data['active_tab'] = 'normal';
        return view($this->_path . 'product-list', $this->_data);
    }

    function inactiveProducts()
    {
        $this->_data['products'] = Product::where('status', 0)->latest()->get();
        $this->_data['active_tab'] = 'inactive';
        return view($this->_path . 'product-list', $this->_data);
    }

    function standardStatus($id)
    {
        $product = Product::find($id);
        if ($product)
            if ($product->update(['standard_product' => $product->standard_product ? 0 : 1]))
                return redirect()->back()->with('success', 'Product standard updated');
        return redirect()->back()->with('fail', 'Something went wrong');
    }


}
