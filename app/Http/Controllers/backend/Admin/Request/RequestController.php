<?php

namespace App\Http\Controllers\backend\Admin\Request;

use App\Models\MerchantCashWithdraw;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\FeatureProduct;
use App\Models\FlashSale;
use App\Models\Members\MemberCashWithdraw;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class RequestController extends Controller
{
    private $_path = 'backend.admin.requests.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function memberCashWithdrawRequest()
    {
        $this->_data['members'] = MemberCashWithdraw::where('flag', 0)->where('status', 1)->get();
        return view('backend.admin.requests.member-cash-withdraw-request', $this->_data);
    }

    public function withdrawAcceptance($id)
    {
        MemberCashWithdraw::find($id)->update(['flag' => 1, 'updated_by' => Auth::guard('admin')->id(), 'withdraw_date' => Carbon::now()]);
        return redirect()->back()->with('success', 'Member Request Accepted !');

    }

    public function merchantCashWithdrawRequest()
    {
        $this->_data['merchants'] = MerchantCashWithdraw::where('flag', 0)->where('status', 1)->get();
        return view('backend.admin.requests.merchant-cash-withdraw-request', $this->_data);
    }

    public function merchantWithdrawAcceptance($id)
    {
        MerchantCashWithdraw::find($id)->update(['flag' => 1, 'updated_by' => Auth::guard('admin')->id(), 'withdraw_date' => Carbon::now()]);
        return redirect()->back()->with('success', 'Member Request Accepted !');

    }

    public function merchantFeaturedProductRequest()
    {
        $this->_data['featureproduct'] = FeatureProduct::all();
        $this->_data['products'] = Product::all();
        return view('backend.admin.requests.merchant-featured-product-request', $this->_data);
    }

    public function featuredProductAcceptance($id)
    {

        $feat = FeatureProduct::find($id);

        if ($feat) {

            $prod = Product::find($feat->product_id);

            if ($prod) {

                $feat->update(['flag' => 1]);
                $prod->update(['is_featured' => 1]);
                return redirect()->back()->with('success', __('message.Feature request accepted'));
            }
        }
        return redirect()->back()->with('fail', 'Invalid Id !');

    }

    public function deleteFeatureProduct($id)
    {
        if (FeatureProduct::find($id)->delete())
            return redirect()->back()->with('success', __('message.Product removed from feature'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    public function cancelFeatureProduct($id)
    {
        $prod = Product::find($id);
        $featpro = FeatureProduct::where('product_id', $id)->get();
        if ($prod) {
            foreach ($featpro as $feat) {
                $feat->update(['flag' => 1]);
            }

            $prod->update(['is_featured' => 0]);
        }
        return redirect()->back();
    }


}
        


