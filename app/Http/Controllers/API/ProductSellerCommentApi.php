<?php

namespace App\Http\Controllers\API;

use App\Events\UserWriteComment;
use App\ProductSeller;
use App\ProductSellerComment;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductSellerCommentApi extends Controller
{
    public function userComments()
    {
        $user = auth()->guard('api')->user();
        $comments = ProductSellerComment::where('user_id', $user->id)
            ->join('product_seller' , 'product_seller.id' , '=' , 'product_seller_comment.product_seller_id')
            ->select('product_seller_comment.*' , 'product_seller.name as product' )
            ->whereNull('parent_comment_id')
            ->get();

        foreach ($comments as $index => $row) {
            $responses = ProductSellerComment::where('parent_comment_id', $row->id)
                ->get();
            $comments[$index]->responses = $responses;
        }

        return response()->json([
            'status' => 200,
            'comments' => $comments
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->guard('api')->user();
        ProductSellerComment::create([
            'user_id' => $user->id,
            'product_seller_id' => $request->product_seller_id,
            'comment' => $request->comment,
        ]);

        event(new UserWriteComment($user, ProductSeller::find($request->product_seller_id)));

        return response()->json(['status' => 200], 200);
    }

    public function productComment(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $comments = ProductSellerComment::join('users', 'users.id', '=', 'product_seller_comment.user_id')
            ->where('product_seller_id', $request->id)
            ->where('product_seller_comment.status', 'approved')
            ->offset($offset)
            ->limit($limit)
            ->select('product_seller_comment.*', 'users.first_name', 'users.last_name' , 'users.thumbnail_photo')
            ->get();
        foreach ($comments as $comment) {
            $comment->thumbnail_photo = url()->to('image/store_photos') . '/' . $comment->thumbnail_photo;
        }
        if (count($comments) > 0)
            return response()->json($comments, 200);
        else
            return response()->json([ "status" => 200], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
            'comment' => 'required',
        ], [
            'productId.required' => 'محصول الزامی است.',
            'comment.required' => 'متن نظر الزامی است.',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorsArr = [];
            foreach ($errors as $error) {
                $obj = new \stdClass();
                $obj->error = $error;
                $errorsArr[] = $obj;
            }
            return response()->json($errorsArr, 400);
        }

        $user = auth()->guard('api')->user();;
        $comment = new Comment();
        $comment->product_id = $request->productId;
        $comment->user_id = $user->id;
        $comment->comment = $request->comment;
        $comment->status = 'pending';
        if ($request->has('parent_comment_id')) {
            $comment->parent_comment_id = $request->parent_comment_id;
        } else {
            $comment->parent_comment_id = null;
        }
        $comment->save();
        if ($comment) {
            return response()->json(['message' => 'پیام با موفقیت ثبت شد درصورت تایید مدیر نشان داده خواهد شد'], 200);
        } else {
            return response()->json(['error' => 'پیام شما ثبت نشد'], 400);
        }
    }

    public function productComments(Request $request)
    {
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $user = auth()->guard('api')->user();
        $productsId = ProductSeller::join('store' , 'product_seller.store_id' ,'=' , 'store.id')
            ->where('store.user_id' , $user->id)->select('product_seller.id as id')->pluck('id')->toArray();
        $comments = ProductSellerComment::whereIn('product_seller_id', $productsId)
            ->whereNull('product_seller_comment.parent_comment_id');

        if ($request->filled('id')) {
            $comments->where('product_seller_id', $request->id);
        }
        $comments = $comments
            ->orderBy('id', 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        $comments->each(function ($comment) {
            $comment->first_name = User::where('id', $comment->user_id)->first()->first_name;
            $comment->last_name = User::where('id', $comment->user_id)->first()->last_name;
            $comment->product = ProductSeller::where('id', $comment->product_seller_id)->first()->name;
            $responses = ProductSellerComment::where('product_seller_comment.parent_comment_id', $comment->id)
                ->get();
            $comment->responses = $responses;
        });


        return response()->json($comments, 200);
    }

    public function changeStatus(Request $request)
    {
        if ($request->filled('status') && $request->status == 'approve') {
            ProductSellerComment::find($request->comment_id)->update(['status' => 'approved']);
            return response()->json(['status' => 200], 200);
        }

        if ($request->filled('status') && $request->status == 'reject') {
            ProductSellerComment::find($request->comment_id)->update(['status' => 'rejected']);
            return response()->json(['status' => 200], 200);
        }
    }

    public function respond(Request $request, ProductSellerComment $comment)
    {
        $validator = Validator::make($request->all(), [
            'body' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'comment' => ''
            ]);
        }
        $user = auth()->guard('api')->user();
        $childComment = $comment->responses()->create([
            'comment' => $request->body,
            'product_seller_id' => $comment->product_seller_id,
            'user_id' => $user->id,
            'status' => 'approved',
        ]);

        return response()->json([
            'status' => 200,
            'comment' => $childComment
        ]);
    }

}
