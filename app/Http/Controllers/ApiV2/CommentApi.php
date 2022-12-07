<?php

namespace App\Http\Controllers\ApiV2;

use App\Product_seller_photo;
use App\ProductSellerComment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentApi extends Controller
{
    public function getUserComments(Request $request){
        $validator = Validator::make($request->all() , [
            'offset' => 'nullable|numeric|min:0',
            'limit' => 'nullable|numeric|min:1'
        ]);
        if($validator->fails()){
            return response()->json([
                'status' => 422,
                'entire' => [
                    'errors' => $validator->errors()->all()
                ]
            ] , 422);
        }
        $user = auth()->guard('api')->user();
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $comments = ProductSellerComment::where('user_id' , $user->id)
            ->join('product_seller' , 'product_seller.id' , '=' , 'product_seller_comment.product_seller_id')
            ->select('product_seller.*' , 'product_seller_comment.comment' , 'product_seller_comment.status as comment_status')
            ->offset($offset)
            ->limit($limit)
            ->get();

        foreach($comments as $index => $comment){
            $photos = Product_seller_photo::where('seller_product_id' , $comment->id)
                ->get();
            foreach($photos as $pIndex => $pRow){
                $photos[$pIndex]->file_name = url()->to('/image/product_seller_photo') . '/' . $pRow->file_name;
            }
            $comments[$index]->photos = $photos;
        }

        return response()->json([
            'status' => 200,
            'entire' => [
                'comments' => $comments
            ]
        ] , 200);

    }
}
