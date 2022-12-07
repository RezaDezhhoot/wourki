<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Libraries\Swal;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function showList()
    {
        $comment = new Comment();
        $commentQuery = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.status', '!=', 'deleted')
            ->whereNull('comment.parent_comment_id')
            ->orderBy('created_at' , 'desc');
        $commentsList = $commentQuery->paginate(15);
        $data['comments'] = $commentsList;

        return view('admin.comment.list-of-comment')->with($data);
    }

    public function showUserComment(Products $product)
    {
        $comments = new Comment();
        $commentQuery = $comments->dbSelect(Comment::FIELDS)
            ->where('comment.product_id', '=' , $product->id)
            ->where('comment.status', '!=', 'deleted')
            ->orderBy('created_at' , 'desc');
        $commentsList = $commentQuery->paginate(15);
        $data['comments'] = $commentsList;

        return view('admin.comment.list-of-user-comment')->with($data);
    }

    public function showApprovedComment()
    {
        $comment = new Comment();
        $commentQuery = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.status', '=', 'approved')
            ->orderBy('created_at' , 'desc');
        $commentsList = $commentQuery->paginate(15);
        $data['comments'] = $commentsList;

        return view('admin.comment.list-of-approved-comment')->with($data);
    }

    public function approvedComment(Comment $comment)
    {
        $comment->status = 'approved';
        $comment->save();

        Swal::success('تایید موفقیت آمیز نظر' , 'نظر با موفقیت تایید شد.');
        return redirect()->back();
    }

    public function deleteComment(Comment $comment)
    {
        $childComments = $comment->childComments;
        $childComments->each(function($childComment){
            $childComment->status = 'deleted';
            $childComment->save();
        });

        $comment->status = 'deleted';
        $comment->save();

        Swal::success('حذف موفقیت آمیز نظر' , 'نظر با موفقیت حذف شد.');
        return redirect()->back();
    }


    public function showRejectComment()
    {
        $comment = new Comment();
        $commentQuery = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.status', '=', 'rejected');
        $commentsList = $commentQuery->paginate(15);
        $data['comments'] = $commentsList;

        return view('admin.comment.list-of-rejected-comment')->with($data);
    }

    public function rejectComment(Comment $comment)
    {
        $comment->status = 'rejected';
        $comment->save();

        Swal::success('رد موفقیت آمیز نظر' , 'نظر با موفقیت رد شد.');
        return redirect()->back();
    }

    public function showPendingComment()
    {
        $comment = new Comment();
        $commentQuery = $comment->dbSelect(Comment::FIELDS)
            ->where('comment.status', '=', 'pending')
            ->orderBy('created_at' , 'desc');
        $commentsList = $commentQuery->paginate(15);
//        dd($commentsList);
        $data['comments'] = $commentsList;

        return view('admin.comment.list-of-pending-comment')->with($data);
    }

    public function pendingComment(Comment $comment)
    {
        $comment->status = 'pending';
        $comment->save();

        Swal::success('حذف موفقیت آمیز نظر' , 'نظر با موفقیت حذف شد.');
        return redirect()->back();
    }

    public function add(Request $request)
    {
//        dd($request->all());
        $this->validate($request, [
            'comment' => 'required|string|max:200',
            'productId' => 'required|exists:product,id'
        ],[
            'comment.required' => 'وارد کردن نظر الزامی است.',
            'comment.string' => 'نظر نامعتبر است.',
            'comment.max' => 'نظر طولانی تر از حد مجاز است.',
        ]);

        $user = auth()->guard('web')->user();
        $comment = new Comment();
        $comment->product_id = $request->productId;
        $comment->user_id = $user->id;
        $comment->comment = $request->comment;
        if ($request->has('parent_comment_id')){
            $comment->parent_comment_id = $request->parent_comment_id;
        }
        $comment->status = 'pending';
        $comment->save();
        Swal::success('ثبت موفقیت آمیز', 'نظر شما پس از تایید نشان داده خواهد شد.');
        return redirect()->back();
    }
}
