<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ReagentCode;
use Illuminate\Http\Request;

class ReagentCodeApi extends Controller
{
    public function userReagentCode(Request $request)
    {
        $offset = $request->has('offset') ? $request->offset : 0;
        $limit = $request->has('limit') ? $request->limit : 1;
        $user = auth()->guard('api')->user();
        $userReagent = ReagentCode::where('reagent_code.reagent_code' , $user->reagent_code)
            ->with(['user'])
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();

        $arr = [];
        foreach($userReagent as $index => $row){
            $obj = new \stdClass();
            $obj->user = $row->user;
            unset($userReagent[$index]->user);
            $obj->reagent = $row;
            $arr[] = $obj;
        }
        return response()->json($arr);
    }
    /*
     {
        "user": {
            "id": 104,
            "first_name": "alireaz",
            "last_name": "rajaei",
            "mobile": "09134628260",
            "mobile_confirmed": 1,
            "verify_mobile_token": null,
            "shaba_code": null,
            "reagent_code": "5bfe91a3365fc",
            "gcm_code": "0",
            "reset_password_token": null,
            "email": null,
            "verify_forget_password_token": null,
            "banned": 0,
            "created_at": "2018-11-28 16:31:23",
            "updated_at": "2018-11-28 16:31:47"
        },
        "reagent": {
            "id": 19,
            "user_id": 104,
            "reagent_code": "5bdedd0ac2e2d",
            "reagent_user_fee": 100,
            "reagented_user_fee": 200,
            "type": "reagent",
            "checkout": 1,
            "created_at": "2018-11-28 16:31:23",
            "updated_at": "2018-11-28 16:31:23"
        }
    },
    {
        "user": {
            "id": 104,
            "first_name": "alireaz",
            "last_name": "rajaei",
            "mobile": "09134628260",
            "mobile_confirmed": 1,
            "verify_mobile_token": null,
            "shaba_code": null,
            "reagent_code": "5bfe91a3365fc",
            "gcm_code": "0",
            "reset_password_token": null,
            "email": null,
            "verify_forget_password_token": null,
            "banned": 0,
            "created_at": "2018-11-28 16:31:23",
            "updated_at": "2018-11-28 16:31:47"
        },
        "reagent": {
            "id": 20,
            "user_id": 104,
            "reagent_code": "5bdedd0ac2e2d",
            "reagent_user_fee": 500,
            "reagented_user_fee": 0,
            "type": "create_store",
            "checkout": 0,
            "created_at": "2018-11-28 16:33:49",
            "updated_at": "2018-11-28 16:33:49"
        }
    }
    */
}
