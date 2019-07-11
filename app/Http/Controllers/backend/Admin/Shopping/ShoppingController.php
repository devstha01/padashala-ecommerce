<?php

namespace App\Http\Controllers\backend\Admin\Shopping;

use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingBonusAuto;
use App\Models\Commisions\ShoppingBonusSpecial;
use App\Models\Commisions\ShoppingBonusStandard;
use App\Models\Commisions\ShoppingLog;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Package;
use App\Models\WithdrawConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ShoppingController extends Controller
{
    private $_path = 'backend.admin.shopping.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }


    function listView()
    {
        $this->_data['merchant_rates'] = ShoppingMerchant::all();
        $this->_data['rates'] = Shopping::all()->keyBy('key');
        $this->_data['standard'] = ShoppingBonusStandard::orderBy('package_id', 'ASC')->orderBy('generation_position', 'ASC')->get();
        $this->_data['auto'] = ShoppingBonusAuto::orderBy('generation_position', 'ASC')->get();
        $this->_data['special'] = ShoppingBonusSpecial::orderBy('generation_position', 'ASC')->get();
        return view($this->_path . 'shopping-list', $this->_data);
    }

    function editShopping(Request $request)
    {
        $status = true;
        foreach ($request->input as $item) {
            if (!is_numeric($item['value'])) {
                $status = false;
                $message = __('message.Not Saved! Value should be Numeric.');
            }
            if (intval($item['value']) > 100) {
                $status = false;
                $message = __('message.Not Saved! Value should be Less than 100.');
            }
            if (intval($item['value']) < 0) {
                $status = false;
                $message = __('message.Not Saved! Value should be Greater than 0.');
            }
        }
        if ($status) {
            foreach ($request->input as $input) {
                $updateShopping = Shopping::where('key', $input['name'])->first();
                if ($updateShopping) {
                    $updateShopping->update(['value' => $input['value']]);
                }
            }
            return response()->json(['status' => true, 'message' => __('message.Successfully saved')]);
        }
        return response()->json(['status' => false, 'message' => $message]);
    }

    function editMerchantRate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'merchant_rate' => 'required|numeric|min:0|max:100',
            'admin_rate' => 'required|numeric|min:0|max:100',
            'merchant_id' => 'required'
        ]);
        if ($validate->fails()) {
            $validate->errors()->add('id', $request->merchant_id);
            return redirect()->back()->with('errors', $validate->getMessageBag());
        }
        $merchant = ShoppingMerchant::where('merchant_id', $request->merchant_id)->first();
        if ($merchant) {
            $merchant->update([
                'merchant_rate' => number_format((float)$request->merchant_rate, 2, '.', ''),
                'admin_rate' => number_format((float)$request->admin_rate, 2, '.', '')
            ]);
            return redirect()->back();
        }
        $validate->errors()->add('merchant_id', __('message.Merchant Not found'));
        return redirect()->back()->with('errors', $validate->getMessageBag());
    }

    protected function LogShopping($merchant_id, $total)
    {
        $merchant_rate = (ShoppingMerchant::where('merchant_id', $merchant_id)->first()->merchant_rate ?? 95) / 100;
        $rate = Shopping::all()->keyBy('key');

        $merchant_amount = $total * $merchant_rate;

        $minus_merchant_amount = $total - $merchant_amount;

        $shopping_bonus_amount = (($rate['shopping_bonus_rate']->value) / 100) * $minus_merchant_amount;

        $admin_amount = (($rate['admin_rate']->value) / 100) * ($minus_merchant_amount - $shopping_bonus_amount);
        $bonus_amount = (($rate['bonus_rate']->value) / 100) * ($minus_merchant_amount - $shopping_bonus_amount);

        ShoppingLog::create([
            'merchant_id' => $merchant_id,
            'admin_id' => 1,
            'total' => $total,
            'merchant' => $merchant_amount,
            'shopping_bonus' => $shopping_bonus_amount,
            'administration' => $admin_amount,
            'bonus' => $bonus_amount
        ]);

    }

    function editShoppingRate(Request $request)
    {
        $this->_data['type'] = $request->type ?? null;
        $this->_data['packages'] = Package::all();
        switch ($this->_data['type']) {
            case 'standard':
                $this->_data['bonus'] = ShoppingBonusStandard::find($request->id);
                if (!$this->_data['bonus'])
                    return redirect()->back();
                return view($this->_path . 'edit-shopping-rate', $this->_data);
                break;
            case 'auto':
                $this->_data['bonus'] = ShoppingBonusAuto::find($request->id);
                if (!$this->_data['bonus'])
                    return redirect()->back();
                return view($this->_path . 'edit-shopping-rate', $this->_data);
                break;
            case 'special':
                $this->_data['bonus'] = ShoppingBonusSpecial::find($request->id);
                if (!$this->_data['bonus'])
                    return redirect()->back();
                return view($this->_path . 'edit-shopping-rate', $this->_data);
                break;
            default:
                return redirect()->back();
                break;
        }
    }

    function addShoppingRate(Request $request)
    {
        $this->_data['type'] = $request->type ?? null;
        $this->_data['packages'] = Package::all();
        return view($this->_path . 'add-shopping-rate', $this->_data);
    }

    function createShoppingRate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'generation' => 'required|numeric',
            'percentage' => 'required|numeric',
        ]);

        if ($validate->fails())
            return redirect()->back()->with('fail', $validate->errors()->first());

        $type = $request->type ?? null;
        session()->flash('active-tab', 'standard');
//        session()->flash('active-tab', $type);
        switch ($type) {
            case 'standard':
                $exist = ShoppingBonusStandard::where('generation_position', $request->generation)->where('package_id', $request->package)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                ShoppingBonusStandard::create(['package_id' => $request->package, 'generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus added successfully'));
                break;
            case 'auto':
                $exist = ShoppingBonusAuto::where('generation_position', $request->generation)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                ShoppingBonusAuto::create(['generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus added successfully'));
                break;
            case 'special':
                $exist = ShoppingBonusSpecial::where('generation_position', $request->generation)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                ShoppingBonusSpecial::create(['generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus added successfully'));
                break;
                break;
            default:
                return redirect()->back();
                break;
        }
    }

    function submitShoppingRate(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'generation' => 'required|numeric',
            'percentage' => 'required|numeric',
        ]);

        if ($validate->fails())
            return redirect()->back()->with('fail', $validate->errors()->first());

        $type = $request->type ?? null;
        switch ($type) {
            case 'standard':
                $bonus = ShoppingBonusStandard::find($request->id);
                if (!$bonus) return redirect()->back();
                $exist = ShoppingBonusStandard::where('id', '!=', $bonus->id)->where('generation_position', $request->generation)->where('package_id', $request->package)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                $bonus->update(['package_id' => $request->package, 'generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->back()->with('success', __('message.Bonus updated successfully'));
                break;
            case 'auto':
                $bonus = ShoppingBonusAuto::find($request->id);
                if (!$bonus) return redirect()->back();
                $exist = ShoppingBonusAuto::where('id', '!=', $request->id)->where('generation_position', $request->generation)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                $bonus->update(['generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->back()->with('success', __('message.Bonus updated successfully'));
                break;
            case 'special':
                $bonus = ShoppingBonusSpecial::find($request->id);
                if (!$bonus) return redirect()->back();
                $exist = ShoppingBonusSpecial::where('id', '!=', $request->id)->where('generation_position', $request->generation)->first();
                if ($exist) return redirect()->back()->with('fail', __('message.Bonus exists for this generation'));
                $bonus->update(['generation_position' => $request->generation, 'percentage' => $request->percentage]);
                return redirect()->back()->with('success', __('message.Bonus updated successfully'));
                break;
                break;
            default:
                return redirect()->back();
                break;
        }
    }

    function deleteShoppingRate(Request $request)
    {
        $type = $request->type ?? null;
//        session()->flash('active-tab', $type);
        session()->flash('active-tab', 'standard');
        switch ($type) {
            case 'standard':
                $bonus = ShoppingBonusStandard::find($request->id);
                if (!$bonus) return redirect()->back();
                $bonus->delete();
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus removed successfully'));
                break;
            case 'auto':
                $bonus = ShoppingBonusAuto::find($request->id);
                if (!$bonus)
                    return redirect()->back();
                $bonus->delete();
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus removed successfully'));
                break;
            case 'special':
                $bonus = ShoppingBonusSpecial::find($request->id);
                if (!$bonus)
                    return redirect()->back();
                $bonus->delete();
                return redirect()->to(route('admin-shopping-list'))->with('success', __('message.Bonus removed successfully'));
                break;
            default:
                return redirect()->back();
                break;
        }
    }




    function updateSingleShoppingRate(Request $request)
    {
        $status = true;
        foreach ($request->input as $item) {
            if (!is_numeric($item['value'])) {
                $status = false;
                $message = __('message.Not Saved! Value should be Numeric.');
            }
            if (intval($item['value']) > 100) {
                $status = false;
                $message = __('message.Not Saved! Value should be Less than 100.');
            }
            if (intval($item['value']) < 0) {
                $status = false;
                $message = __('message.Not Saved! Value should be Greater than 0.');
            }
        }
        if ($status) {
            foreach ($request->input as $input) {

                switch (strtolower($input['type'])) {
                    case'standard':
                        $updateShopping = ShoppingBonusStandard::where('generation_position', $input['generation'])
                            ->where('package_id', $input['package'])->first();
                        break;
                    case'auto':
                        $updateShopping = ShoppingBonusAuto::where('generation_position', $input['generation'])->first();
                        break;
                    case'special':
                        $updateShopping = ShoppingBonusSpecial::where('generation_position', $input['generation'])->first();
                        break;
                    default:
                        break;
                }
                if ($updateShopping) {
                    $updateShopping->update(['percentage' => $input['value']]);
                } else {
                    switch (strtolower($input['type'])) {
                        case'standard':
                            ShoppingBonusStandard::create([
                                'generation_position' => $input['generation'],
                                'package_id' => $input['package'],
                                'percentage' => $input['value']
                            ]);
                            break;
                        case'auto':
                            ShoppingBonusAuto::create([
                                'generation_position' => $input['generation'],
                                'percentage' => $input['value']
                            ]);
                            break;
                        case'special':
                            ShoppingBonusSpecial::create([
                                'generation_position' => $input['generation'],
                                'percentage' => $input['value']
                            ]);
                            break;
                        default:
                            break;
                    }
                }
            }
            return response()->json(['status' => true, 'message' => __('message.Successfully saved')]);
        }
        return response()->json(['status' => false, 'message' => $message]);
    }
}
