<?php

namespace App\Http\Controllers;

use App\Helpers\TextHelper;
use App\Libraries\Swal;
use App\Message;
use App\Process\PrNotification;
use App\Setting;
use App\SupportLastMessage;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use Log;
use Throwable;

class MessageController extends Controller
{
    public function userList(Request $request)
    {
//        $users = User::where('banned', 0)->paginate(15);
        $usersList = User::where('users.banned' , '=', 0)
            ->leftJoin('store' , 'store.user_id' , '=' , 'users.id')
            ->leftJoin('marketer' , 'marketer.user_id' , '=' , 'users.id')
            ->leftJoin('support_last_message' , 'support_last_message.user_id' , '=' , 'users.id')
            ->leftJoin('message' , 'message.user_id' , '=' , 'users.id')
            ->select('users.id' , 'users.first_name' , 'users.last_name' , 'users.mobile' , 'users.email' , 'users.reagent_code' , 'users.created_at','users.register_from',
            'users.updated_at' , 'store.name as store_name' , 'store.id as store_id' , 'support_last_message.last_message' , 'support_last_message.last_message_datetime' , 'support_last_message.view')
            ->orderBy('support_last_message.last_message_datetime' , 'desc')
            ->groupBy('users.id');
        if ($request->filled('user') && $request->user != 'all')
            $usersList->where('users.id' , $request->user);
        if ($request->filled('store') && $request->store != 'all')
            $usersList->where('store.id' , $request->store);
        if($request->filled('user_type') && in_array($request->user_type , ['regular_user' , 'marketer'])){
            if($request->user_type == 'marketer'){
                $usersList = $usersList->whereNotNull('marketer.user_id');
            }else{
                $usersList = $usersList->whereNull('marketer.user_id');
            }
        }
        if($request->filled('user_mobile')){
            $usersList->where('users.mobile' , 'like' , "%". $request->user_mobile ."%");
        }
        if($request->has('filter_unread_msg')){
            $usersList->where('message.view' , '=' , 0);
        }
        $usersList = $usersList->paginate(15)->appends([
            'user' => $request->user,
            'store' => $request->store,
            'user_type' => $request->user_type,
            'user_mobile' => $request->user_mobile,
            'filter_unread_msg' => $request->filter_unread_msg
        ]);
        foreach($usersList as $index => $row){
            $usersList[$index]->unread_message_count = $row->messages()->where('view' , 0)->count();
        }

        return view('admin.message.user_lists', compact( 'usersList'));
    }

    public function messages(User $user)
    {
        $messages = Message::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->paginate(15);
        $messages->where('user_id', '!=', null)->each(function ($message) {
            $message->view = 1;
            $message->save();
        });
        $last_message = SupportLastMessage::where('user_id' , $user->id)
            ->get();
        $userMessage = Message::where('user_id' , $user->id)
            ->get();
        foreach($last_message as $msg){
            $msg->view = 1;
            $msg->save();
        }
        foreach($userMessage as $index => $msg){
            $msg->view = 1;
            $msg->save();
        }
        return view('admin.message.index', compact('messages', 'user'));
    }

    public function adminStore(User $user, Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'file' => 'file|image',
        ], [
            'message.required' => 'متن پیام الزامی است.',
        ]);
        $messageStr = $request->message;
        DB::beginTransaction();
        try{
        $filename = null;
        if($request->file('file')){
        //saving file
        $file = $request->file('file');
        $image = Image::make($file->getRealPath());
        $image->resize(500,null, function ($constraint) {
                    $constraint->aspectRatio();
                });
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $image->save(storage_path('app'.DIRECTORY_SEPARATOR.'chat-images'. DIRECTORY_SEPARATOR . $filename));
        }
            $message = Message::create([
                'user_id' => null,
                'receiver_id' => $user->id,
                'message' => $messageStr,
                'attached_file' => $filename,
                'view' => 0
            ]);
            SupportLastMessage::updateOrCreate([
                'user_id' => $user->id,
            ],
                [
                    'last_message' => $messageStr,
                    'last_message_datetime' => $message->created_at,
                    'view' => 1
                ]
            );
        //sending sms
        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=کاربر&template=newMessage";
        $client = new Client();
        $client->get($url);
        DB::commit();
    }
    catch(Throwable $e){
        DB::rollBack();
        // Swal::error('خطا' , 'مشکلی در ارسال پیام به وجود آمده است');
        Swal::error('خطا' , $e->getMessage());
    }

//        $notification = new PrNotification();
//        $notification
//            ->setTitle('وورکی')
//            ->setBody('فروشنده گرامی شما یک پیام از طرف مدیر دارید.')
//            ->addData('type', 'newMessage')
//            ->addData('message', $message->message)
//            ->setUser($user)
//            ->send();

        return back();
    }

    public function batchSend(Request $request)
    {
        $request->validate([
            'file' => 'file|image|max:3000'
        ]);
        if ($request->has('allUser'))
            $users = User::all();
        elseif ($request->has('allStore')) {
            $users = User::join('store', 'store.user_id', '=', 'users.id')
                ->select('users.*')
                ->groupBy('users.id')
                ->get();
        }elseif($request->has('allServiceStore')){
            $users = User::join('store', 'store.user_id', '=', 'users.id')
                ->where('store_type' , 'service')
                ->select('users.*')
                ->groupBy('users.id')
                ->get();
        } elseif ($request->has('allProductStore')) {
            $users = User::join('store', 'store.user_id', '=', 'users.id')
                ->where('store_type', 'product')
                ->select('users.*')
                ->get();
        } elseif ($request->has('allMarkets')) {
            $users = User::join('store', 'store.user_id', '=', 'users.id')
                ->where('store_type', 'market')
                ->select('users.*')
                ->groupBy('users.id')
                ->get();
        } else {
            $request->validate([
                'userId' => 'required|array|min:1',
            ], [
                'userId.required' => 'انتخاب کاربر الزامی است.',
                'userId.min' => 'انتخاب حداقل یک کاربر الزامی است.',
            ]);
            $users = User::whereIn('id', $request->userId)->get();
        }
            try{
                    $msgs = [];
                    $filename = null;
                    if ($request->file('file')) {
                        //saving file
                        $file = $request->file('file');
                        $image = Image::make($file->getRealPath());
                        $image->resize(500, null, function ($constraint) { $constraint->aspectRatio(); });
                        $filename = date('YmdHi') . $file->getClientOriginalName();
                        $image->save(storage_path('app' . DIRECTORY_SEPARATOR . 'chat-images' . DIRECTORY_SEPARATOR . $filename));
                    }
                    foreach ($users as $user) {
                        $msg = [];
                        $msg['user_id'] = null;
                        $msg['receiver_id'] = $user->id;
                        $msg['message'] = $request->message;
                        $msg['view'] = 0;
                        $msg['attached_file'] = $filename;
                        $msg['created_at'] = Carbon::now()->toDateTimeString();
                        $msg['updated_at'] = Carbon::now()->toDateTimeString();
                        $msgs[] = $msg;
                        if($request->send_sms){
                        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=کاربر&template=newMessage";
                        $client = new Client();
                        $client->get($url);
                        }
                    }
                    DB::table('message')->insert($msgs);
                    // sending sms 
                    // $gcmCodes = User::whereIn('id', $users)->pluck('gcm_code')->toArray();
                    // $notification = new PrNotification();
                    // $notification
                    //     ->setTitle('وورکی')
                    //     ->setBody('فروشنده گرامی شما یک پیام از طرف مدیر دارید.')
                    //     ->addData('type', 'input')
                    //     ->addData('message', $request->message)
                    //     ->addUsers($gcmCodes)
                    //     ->send();
                DB::commit();
                Swal::success('موفقیت آمیز.', 'پیام با موفقیت به کاربران ارسال شد.');
                return back();
            }
            catch(Throwable $e){
                DB::rollBack();
                Swal::error('خطا' , $e->getMessage());
                return back();
            }
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ], [
            'message.required' => 'متن پیام الزامی است.',
        ]);
        $user = auth()->guard('web')->user();
        $msg = Message::create([
            'user_id' => $user->id,
            'receiver_id' => null,
            'message' => $request->message,
            'view' => 0
        ]);
        SupportLastMessage::updateOrCreate([
            'user_id' => $user->id,
        ] , [
            'last_message' => $request->message,
            'last_message_datetime' => $msg->created_at,
            'view' => 0
        ]);
        return back();
    }

    public function userMessage()
    {
        $user = auth()->guard('web')->user();
        $messages = Message::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest()
            ->paginate(15);
        $messages->where('user_id', null)->each(function ($message) {
            $message->view = 1;
            $message->save();
        });
        $helpText = Setting::first()->support_page_help_text;
        return view('frontend.my-account.messages.index', compact('messages', 'user'  ,'helpText'));
    }

    public function delete(Message $message)
    {
        $message->delete();
        return back();
    }

    public function sendQuickMessage(Request $request){
        $user = User::find($request->user_id);
        if(!$user){
            Swal::error('خطا', 'کاربر مورد نظر یافت نشد');
            return redirect()->back();
        }
        $message = new Message();
        $message->user_id = null;
        $message->receiver_id = $request->user_id;
        $message->message = $request->message;
        $message->view = 0;
        $message->save();

        $lastMessage = new SupportLastMessage();
        $lastMessage->user_id = $request->user_id;
        $lastMessage->last_message = $request->message;
        $lastMessage->last_message_datetime = $message->created_at;
        $lastMessage->save();
        $url = "https://api.kavenegar.com/v1/" . config('app.kaveh_negar.api_key') . "/verify/lookup.json?receptor=" . $user->mobile . "&token=کاربر&template=newMessage";
        $client = new Client();
        $client->get($url);
        Swal::success('ارسال پیام' , 'پیام به کاربر ارسال شد.');
        return redirect()->back();

    }
    public function downloadImage($message_id){
        
        $message = Message::find($message_id);
        if(!$message || !$message->attached_file || !auth()->guard('admin')->check()) return abort(404);
        return response()->download(storage_path('app'.DIRECTORY_SEPARATOR.'chat-images'.DIRECTORY_SEPARATOR. $message->attached_file));
    }
    public function userDownloadImage(Request $request , $message_id){
        if(!auth()->guard('api')->user()) return abort(404);
        $message = Message::find($message_id);
        if (!$message || !$message->attached_file ) return abort(404);
        if($message->user_id != auth()->guard('api')->user()->id && $message->receiver_id != auth()->guard('api')->user()->id) return abort(404);
        return response()->download(storage_path('app' . DIRECTORY_SEPARATOR . 'chat-images' . DIRECTORY_SEPARATOR . $message->attached_file));
    }
    public function showBatchDelete(){
        return view('admin.message.delete');
    }
    public function batchDelete(Request $request){
        $request->validate([
            'message' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ] , [
            // 'message.required' => 'لطفا قسمتی از پیام را بنویسید',
            'start_date.required' => 'لطفا تاریخ پایان و شروع را وارد نمایید',
            'end_date.required' => 'لطفا تاریخ پایان و شروع را وارد نمایید'
        ]);
        try{
        $messages = Message::whereDate('updated_at' , '>=' , Carbon::parse($request->start_date))
            ->whereNull('user_id')
            ->whereDate('updated_at' , '<=' , Carbon::parse($request->end_date));
        if($request->has('message') && $request->message != ''){
            $messages->where('message' ,  'LIKE' , '%'.$request->message.'%');
        }
        $count = $messages->count();
        $messages->delete();
        }
        catch(Throwable $e){
            Swal::error('خطا' , $e->getMessage());
            return back();
        }
        Swal::success('موفق' , 'تعداد '.strval($count).' پیام که از سمت ادمین فرستاده شده بود حذف شد');
        return back();
    }
}
