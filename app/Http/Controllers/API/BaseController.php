<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message){
        $response =[
            'success'=>true,
            'message'=>$message,
            'data'=>$result,


        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404){
        $response =[
            'success'=>true,
            'data'=>$error,
        ];

        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }


}
