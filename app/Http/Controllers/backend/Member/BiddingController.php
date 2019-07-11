<?php

namespace App\Http\Controllers\backend\Member;

use App\Http\Controllers\Controller;
use App\Models\Bidding;
use App\Models\Category;

class BiddingController extends Controller
{

    private $_path = 'backend.member.bidding';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('member');
    }

    public function getBidProduct()
    {
        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['all_categories'] = collect($categories);
        $this->_data['home_categories'] = collect($categories)->take(8);
        $this->_data['products'] = Bidding::orderBy('id','DESC')->get();
        return view($this->_path . '.bidding-products', $this->_data);
    }

    public function getProductDetail($slug){
        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['all_categories'] = collect($categories);
        $this->_data['home_categories'] = collect($categories)->take(8);
        $this->_data['products'] = Bidding::where('slug',$slug)->first();
        return view($this->_path . '.bidding-product-detail', $this->_data);
    }


}
