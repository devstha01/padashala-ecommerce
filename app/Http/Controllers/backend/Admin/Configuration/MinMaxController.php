<?php

namespace App\Http\Controllers\backend\Admin\Configuration;

use App\Models\WithdrawConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MinMaxController extends Controller
{
    private $_path = 'backend.admin.configs.min-max.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function withdrawConfig()
    {
        $this->_data['config'] = WithdrawConfig::where('name', 'wallet_withdraw')->first();
        $this->_data['url'] = 'wallet_withdraw';
        $this->_data['name'] = __('dashboard.Withdrawal Config');
        return view($this->_path . 'minmax-config', $this->_data);
    }

    function cashConfig()
    {
        $this->_data['config'] = WithdrawConfig::where('name', 'transfer_ecash')->first();
        $this->_data['url'] = 'transfer_ecash';
        $this->_data['name'] = __('dashboard.Cash Wallet Config') . '| ' . __('dashboard.Member-Member Wallet Transfer');
        return view($this->_path . 'minmax-config', $this->_data);
    }

    function voucherConfig()
    {
        $this->_data['config'] = WithdrawConfig::where('name', 'transfer_evoucher')->first();
        $this->_data['url'] = 'transfer_evoucher';
        $this->_data['name'] = __('dashboard.Voucher Wallet Config'). '| ' . __('dashboard.Member-Member Wallet Transfer');
        return view($this->_path . 'minmax-config', $this->_data);
    }

    function rpointConfig()
    {
        $this->_data['config'] = WithdrawConfig::where('name', 'transfer_r_point')->first();
        $this->_data['url'] = 'transfer_r_point';
        $this->_data['name'] = __('dashboard.R Wallet Config'). '| ' . __('dashboard.Member-Member Wallet Transfer');
        return view($this->_path . 'minmax-config', $this->_data);
    }

    function chipConfig()
    {
        $this->_data['config'] = WithdrawConfig::where('name', 'transfer_chip')->first();
        $this->_data['url'] = 'transfer_chip';
        $this->_data['name'] = __('dashboard.Chip Config'). '| ' . __('dashboard.Member-Member Wallet Transfer');
        return view($this->_path . 'minmax-config', $this->_data);
    }

    function withdrawConfigPost(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'type' => 'required',
            'min' => 'required|numeric|min:0',
            'max' => 'required|numeric|min:0',
        ]);

        if ($validate->fails())
            return redirect()->back()->with('fail', $validate->errors()->first());

        $withdr = WithdrawConfig::where('name', $request->type)->first();
        if (!$withdr)
            WithdrawConfig::create([
                'name' => $request->type,
                'min' => $request->min,
                'max' => $request->max,
            ]);
        else
            $withdr->update([
//                'name' => 'wallet_withdraw',
                'min' => $request->min,
                'max' => $request->max,
            ]);
        return redirect()->back()->with('success', $request->name . __('message.updated successfully'));
    }

}
