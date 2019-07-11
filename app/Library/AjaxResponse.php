<?php namespace App\Library;
class AjaxResponse
{
    public static function sendResponseData($code = 201, $status = "", $msg = "", $message = '')
    {

        switch ($code) {
            case "201":
                $responseData = [
                    'status' => $status,
                    'errors' => $msg
                ];
                break;
            case "200":
                $responseData = [
                    'status' => $status,
                    'url' => $msg,
                    'message-detail' => $message,
                ];
                break;
            case "422":

                if (is_array(reset($msg)))
                    $error = reset($msg)[0] ?? false;
                else
                    $error = collect($msg)->first();

                $responseData = [
                    'status' => $status,
                    'errors' => $msg,
                    'error' => $error
                ];
                break;
            default:
                $responseData = [
                    'response' => $status,
                    'response_code' => $msg,
                ];;
        }
        return response()->json($responseData);


    }

}


