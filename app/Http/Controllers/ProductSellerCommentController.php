<?php

namespace App\Http\Controllers;

use App\Events\UserWriteComment;
use App\Http\Requests\web\filterCommentRequest;
use App\Libraries\Swal;
use App\ProductSeller;
use App\ProductSellerComment;
use App\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductSellerCommentController extends Controller
{
    public function list(filterCommentRequest $request)
    {
        $stores = Store::where('status', 'approved')
            ->orderBy('name', 'asc')
            ->select('name', 'id')
            ->get();
        $comments = ProductSellerComment::join('users', 'users.id', '=', 'product_seller_comment.user_id')
            ->join('product_seller', 'product_seller.id', '=', 'product_seller_comment.product_seller_id')
            ->join('store', 'store.id', '=', 'product_seller.store_id')
            ->select('store.name as store_name', 'store.id as store_id', 'product_seller.name as product_name', 'product_seller_comment.status',
                'product_seller_comment.created_at', 'product_seller_comment.comment', 'product_seller.id as product_id', 'product_seller_comment.id as comment_id')
            ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name');

        if ($request->filled('store_name')) {
            $comments->where('store_id', $request->store_name);
        }
        if ($request->filled('product_name')) {
            $comments->where('product_id', $request->product_name);
        }
        if ($request->filled('status') && $request->status == 'approved') {
            $comments->where('product_seller_comment.status', 'approved');
        } elseif ($request->filled('status') && $request->status == 'rejected') {
            $comments->where('product_seller_comment.status', 'rejected');
        } elseif ($request->filled('status') && $request->status == 'pending') {
            $comments->where('product_seller_comment.status', 'pending');
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $startDate = Carbon::createFromTimestamp($request->start_date_ts)->format('Y/m/d');
            $endDate = Carbon::createFromTimestamp($request->end_date_ts)->format('Y/m/d');
            $comments->where('product_seller_comment.created_at', '>=', $startDate)
                ->where('product_seller_comment.created_at', '<=', $endDate);
        }
        if ($request->filled('user_full_name')) {
            $comments->where(function ($query) use ($request) {
                $query->where('users.first_name', 'like', "%" . $request->user_full_name . "%")
                    ->orWhere('users.last_name', 'like', "%" . $request->user_full_name . "%");
            });
        }
        if ($request->filled('user_mobile')) {
            $comments->where('users.mobile', 'like', "%" . $request->user_mobile . "%");
        }
        $comments = $comments->orderBy('product_seller_comment.id', 'desc')
            ->paginate(20);
        return view('admin.product_seller_comment.index', compact('stores', 'comments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|numeric|exists:product_seller,id',
            'comment' => 'required|string',
        ]);
        ProductSellerComment::create([
            'user_id' => auth()->guard('web')->user()->id,
            'product_seller_id' => $request->product_id,
            'comment' => $request->comment,
        ]);
        event(new UserWriteComment(auth()->guard('web')->user(), ProductSeller::find($request->product_id)));
        Swal::success('تبریک.', 'نظر با موفقیت ثبت شد بعد تایید نمایش داده خواهد شد.');
        return back();
    }

    public function productComment(Request $request, $product)
    {
        $stores = Store::where('status', 'approved')
            ->orderBy('name', 'asc')
            ->select('name', 'id')
            ->get();
        $comments = ProductSellerComment::join('users', 'users.id', '=', 'product_seller_comment.user_id')
            ->join('product_seller', 'product_seller.id', '=', 'product_seller_comment.product_seller_id')
            ->join('store', 'store.id', '=', 'product_seller.store_id')
            ->select('store.name as store_name', 'store.id as store_id', 'product_seller.name as product_name', 'product_seller_comment.status',
                'product_seller_comment.created_at', 'product_seller_comment.comment', 'product_seller.id as product_id', 'product_seller_comment.id as comment_id')
            ->selectRaw('concat(users.first_name , " " , users.last_name) as full_name')
            ->where('product_seller.id', $product);

        if ($request->filled('store_name')) {
            $comments->where('store_id', $request->store_name);
        }
        if ($request->filled('product_name')) {
            $comments->where('product_id', $request->product_name);
        }
        if ($request->filled('status') && $request->status == 'approved') {
            $comments->where('product_seller_comment.status', 'approved');
        } elseif ($request->filled('status') && $request->status == 'rejected') {
            $comments->where('product_seller_comment.status', 'rejected');
        } elseif ($request->filled('status') && $request->status == 'pending') {
            $comments->where('product_seller_comment.status', 'pending');
        }
        if ($request->filled('start_date_ts') && $request->filled('end_date_ts')) {
            $startDate = Carbon::createFromTimestamp($request->start_date_ts)->format('Y/m/d');
            $endDate = Carbon::createFromTimestamp($request->end_date_ts)->format('Y/m/d');
            $comments->where('product_seller_comment.created_at', '>=', $startDate)
                ->where('product_seller_comment.created_at', '<=', $endDate);
        }
        $comments = $comments->orderBy('product_seller_comment.id', 'desc')
            ->paginate(20);
        return view('admin.product_seller_comment.product_comment', compact('stores', 'comments'));
    }

    public function userComments()
    {
        $user = auth()->guard('web')->user();
        $comments = null;
        if (count($user->stores) > 0) {
            $productsId = ProductSeller::join('store' , 'product_seller.store_id' , '=' , 'store.id')
            ->where('store.user_id' , $user->id)->select('product_seller.id')
            ->pluck('id')->toArray();
            $comments = ProductSellerComment::whereIn('product_seller_id', $productsId)
                ->whereNull('parent_comment_id')
                ->get();
            $comments->each(function ($comment) {
                $comment->product_id = $comment->product->id;
                $comment->userFirstName = $comment->user->first_name;
                $comment->userLastName = $comment->user->last_name;
                $comment->product = $comment->product->name;

                $comment->responses;
            });
        }

        return view('frontend.my-account.comments.index', compact('comments'));
    }

    public function makeApprove(Request $request)
    {
        $comment = ProductSellerComment::find($request->comment);
        $comment->update(['status' => 'approved']);
        return response()->json([ "status" => 200], 200);
    }

    public function makeReject(Request $request)
    {
        $comment = ProductSellerComment::find($request->comment);
        $comment->update(['status' => 'rejected']);
        return response()->json([ "status" => 200], 200);
    }

    public function respond(Request $request){
        $this->validate($request , [
            'parent_comment_id' => 'required|numeric|exists:product_seller_comment,id',
            'product_id' => 'required|numeric|exists:product_seller,id',
            'response' => 'required|string'
        ] , [
            'parent_comment_id.required' => 'نظر والد نامعتبر است.',
            'parent_comment_id.numeric' => 'نظر والد نامعتبر است.',
            'parent_comment_id.exists' => 'نظر والد نامعتبر است.',
            'product_id.required' => 'محصول الزامی است.',
            'product_id.numeric' => 'محصول نامعتبر است.',
            'product_id.exists' => 'محصول نامعتبر است.',
            'response.required' => 'وارد کردن پاسخ الزامی است.',
            'response.string' => 'پاسخ نامعتبر است.'
        ]);

        $user = auth()->guard('web')->user();
        $comment = new ProductSellerComment();
        $comment->user_id = $user->id;
        $comment->product_seller_id = $request->product_id;
        $comment->comment = $request->response;
        $comment->status = 'approved';
        $comment->parent_comment_id = $request->parent_comment_id;
        $comment->save();

        $parent = ProductSellerComment::find($request->parent_comment_id);
        $parent->status = 'approved';
        $parent->save();

        Swal::success('ثبت نظر' , 'نظر شما با موفقیت ثبت شد.');
        return redirect()->back();
    }
}
