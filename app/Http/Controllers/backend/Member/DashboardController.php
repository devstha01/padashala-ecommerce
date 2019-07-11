<?php

namespace App\Http\Controllers\backend\Member;

use App\Models\Members\MemberBankInfo;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Members\MemberAsset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('member');
//        'middleware' => ''
    }

    private $_path = 'backend.member.dashboard';

    public function dashboard()
    {


        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        return view($this->_path . '.dashboard', compact('member', 'wallet'))->with([
            'title' => __('message.Member Panel')
        ]);
    }

    public function confirmTransactionPassword(Request $request)
    {

        $TransactionPassword = $request->transactionpassword;
        return response()->json(Hash::check($TransactionPassword, Auth::user()->transaction_password, []));


    }

    function success()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();

        $success_title = session('success_title') ?? false;
        $success_brief = session('success_brief') ?? false;
        $success_detail = session('success_detail') ?? false;

        if (!$success_title) return redirect(route('member/dashboard'));
        return view($this->_path . '.success', compact('wallet', 'success_title', 'success_brief', 'success_detail'));
    }


    public function editBank($id)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $users = User::find($id);
        if ($users->is_member !== 1) return redirect()->back();
        $wallet = MemberAsset::where('member_id', $users->id)->first();
        $banks = MemberBankInfo::where('member_id', $id)->first();
        return view('frontend.auth.bank-edit', compact('banks','wallet'))->with('title', __('message.Golden Gate'));
    }

    public function updateBank(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        if (Auth::user()->is_member !== 1) return redirect()->back();

        $input = $request->validate([
            'bank_name' => 'required',
            'acc_name' => 'required|string',
            'acc_number' => 'required|numeric',
            'contact_number' => 'required|numeric',
        ]);

        $input['member_id'] = Auth::user()->id;

        session()->flash('update', false);
        $first = MemberBankInfo::where('member_id', Auth::user()->id)->first();
        if ($first) {
            if ($first->update($input)) {
                session()->flash('update', true);
                return redirect()->back()->with('success', __('message.Bank Info updated successfully'));
            }
        } else {
            if (MemberBankInfo::create($input)) {
                session()->flash('update', true);
                return redirect()->back()->with('success', __('message.Bank info registered successfully'));
            }
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }
}
