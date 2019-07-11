<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class DashboardController extends Controller
{

    function memberList(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            return $this->getMemberList($request);
        }
        return response()->json($json);
    }


//    protected function
    protected
    function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return ['status' => false, 'message' => 400, 'error' => __('message.Invalid Login name or Password'), 'redirect' => true];
            }
        } catch (TokenExpiredException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Token Expired'), 'redirect' => true];
        } catch (TokenInvalidException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Invalid Token'), 'redirect' => true];
        } catch (JWTException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Unauthorized User'), 'redirect' => true];
        }

        $headtoken = collect(JWTAuth::getToken())->first() ?? 'invalid';
        if ($user->jwt_token_handle !== $headtoken)
            return ['status' => false, 'message' => 401, 'error' => __('message.Invalid Token'), 'redirect' => true];

        // the token is valid and we have found the user via the sub claim
        //check member vs customer
        if ($user->is_member)
            return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' =>__('message.success')];
        return [
            'status' => false, 'message' => 400, 'error' => __('message.not member')
        ];
    }

    protected function getMemberList($req)
    {
//        return response()->json($req);
        $old_name = $req->name ?? null;
        $old_surname = $req->surname ?? null;
        $old_username = $req->user_name ?? null;
        $old_start_date = $req->start_date ? Carbon::parse($req->start_date) : null;
        $old_end_date = $req->end_date ? Carbon::parse($req->end_date) : null;

        $membersList = User::where('is_member', 1);

        if ($old_name !== null) {
            $membersList = $membersList->where('name', 'like', '%' . $old_name . '%');
        }
        if ($old_surname !== null) {
            $membersList = $membersList->where('surname', 'like', '%' . $old_surname . '%');
        }
        if ($old_username !== null) {
            $membersList = $membersList->where('user_name', 'like', '%' . $old_username . '%');
        }
        if ($old_start_date !== null) {
            $membersList = $membersList->where('created_at', '>=', $old_start_date);
        }
        if ($old_end_date !== null) {
            $membersList = $membersList->where('created_at', '<=', $old_end_date);
        }
        $membersList = $membersList->get();

        return [
            'status' => true,
            'message' => 200,
            'old_name' => $old_name,
            'old_surname' => $old_surname,
            'old_username' => $old_username,
            'count' => count($membersList),
            'data' => $membersList
        ];
    }
}
