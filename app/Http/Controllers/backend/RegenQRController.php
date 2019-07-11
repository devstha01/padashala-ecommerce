<?php

namespace App\Http\Controllers\backend;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RegenQRController extends Controller
{
    function memberQR(Request $request)
    {
        $user = User::find($request->user_id ?? 0);
        if ($user) {
            $mkString = 'user:' . $user->id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);
            $user->update(['qr_image' => $qr_name]);
            return redirect()->back()->with('success', 'QR Code regenerate success');
        }
        return redirect()->back()->with('fail', 'Invalid request');
    }

    function merchantQR(Request $request)
    {
        $user = Merchant::find($request->merchant_id ?? 0);
        if ($user) {
            $mkString = 'merchant:' . $user->id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/merchant/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);
            $user->update(['qr_image' => $qr_name]);
            return redirect()->back()->with('success', 'QR Code regenerate success');
        }
        return redirect()->back()->with('fail', 'Invalid request');
    }


}
