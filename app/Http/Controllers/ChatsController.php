<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Libraries\Swal;
use App\Message;
use App\PrivateMessage;
use App\ProductSeller;
use App\Setting;
use App\Store;
use App\SupportLastMessage;
use App\TempFile;
use App\User;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Throwable;
use URL;
use Validator;
use Verta;

class ChatsController extends Controller
{
    public function getRules(){
        $rules = Setting::first()->chat_rules;
        return view('frontend.my-account.chats.rules' , compact('rules'));
    }
    public function block(Request $request , $chat_id){
        $request->validate([
            'block' => 'required|boolean'
        ]);
        $user = auth()->guard('api')->user();
        $chat = Chat::find($chat_id);
        if(!$chat){
            return response()->json(['error' => 'not found'] , 404);
        }
        if($request->block){
            if($chat->sender_id == $user->id){
                $chat->blocked_by_sender = true;
            }
            if($chat->receiver_id == $user->id){
                $chat->blocked_by_receiver = true;
            }
        }
        else{
            if ($chat->sender_id == $user->id) {
                $chat->blocked_by_sender = false;
            }
            if ($chat->receiver_id == $user->id) {
                $chat->blocked_by_receiver = false;
            }
        }
        $chat->save();
        return response()->json([ "status" => 200] , 200);

    }
    public function adminBlock(Request $request , $user_id){
        $request->validate([
            'block' => 'required|boolean'
        ]);
        $user = User::find($user_id);
        $user->chats_blocked = $request->block;
        $user->save();
        if($request->block == true)
        Swal::success('موفقیت آمیز','کاربر با موفقیت بلاک شد');
        else
        Swal::success('موفقیت آمیز', 'کاربر با موفقیت آنبلاک شد');
        return redirect()->back();
    }
    public function deleteMessage(Request $request , $id){
        PrivateMessage::find($id)->delete();
        return response()->json([ "status" => 200] , 200);
    }
    public function getChats(Request $request){
        $user = auth()->guard('web')->user();
        if($user->chats_blocked)
            $blocked = true;
        else $blocked =false;
        $chats = Chat::whereNull('chats.deleted_at')->where(function($query) use($user){
            return $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })->leftJoin('private_messages', 'chats.id', '=', 'private_messages.chat_id')
            ->groupBy('chats.id')
            ->select('chats.*')
            ->orderByDesc(DB::raw('MAX(private_messages.persian_datetime)'))
            ->with('sender')->with('receiver')->get();
        foreach ($chats as $chat) {
            $contact_is_sender = $chat->sender_id == auth()->guard('web')->user()->id ? false : true;
            $chat->contact = !$contact_is_sender ? $chat->receiver : $chat->sender;
            if($chat->contact)
            $chat->contact->thumbnail_photo = $chat->contact->thumbnail_photo;
            $chat->newMessages = PrivateMessage::query()->whereNull('private_messages.deleted_at')->where('read' , false)->where('chat_id' , $chat->id)->where('is_sent' , $contact_is_sender ? true : false)->count();
        }
        $adminNewMessages = Message::whereNull('user_id')
            ->where('receiver_id', auth()->guard('web')->user()->id)
            ->where('view', 0)->count();
        $setting = Setting::first();
        $noChatMessage = $setting->no_chat_message;
        $noMessages = $setting->no_messages;
        return view('frontend.my-account.chats.index' ,compact('chats' , 'adminNewMessages' , 'blocked' , 'noChatMessage' , 'noMessages'));
    }
    //these api's are for admin panel to check users messages
    public function getChatsForAdmin(Request $request , $user_id){
        $user = User::find($user_id);
        if (!$user) {
            return response()->json(['error' => 'not found'], 404);
        }
        $chats = Chat::whereNull('chats.deleted_at')->where(function ($query) use ($user) {
            return $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })
            ->leftJoin('private_messages', 'chats.id', '=', 'private_messages.chat_id')
            ->groupBy('chats.id')
            ->select('chats.*')
            ->orderByDesc(DB::raw('MAX(private_messages.persian_datetime)'))
            ->with('sender')->with('receiver')->get();
        foreach ($chats as $chat) {
            $contact_is_sender = $chat->sender_id == $user->id ? false : true;
            $chat->contact = !$contact_is_sender ? $chat->receiver : $chat->sender;
            $chat->last_message_datetime = optional($chat->messages()->orderByDesc('persian_datetime')->first())->persian_datetime;
        }
        $selectedUser = $user;
        $user->last_chat_visit_datetime = Carbon::now();
        $user->save();
        return view('admin.chats.index', compact('chats' , 'selectedUser'));
    }
    public function getMessagesForAdmin(Request $request, $chat_id , $user_id)
    {
        $user = User::find($user_id);
        $chat = Chat::find($chat_id);
        if (!$chat || !$user) {
            return response()->json(['error' => 'not found'], 404);
        }
        $messages = $chat->messages;

        if ($chat->sender_id != $user->id) {
            foreach ($messages as $message) {
                $message->is_sent = !$message->is_sent;
            }
        }
        $messages = $chat->messages;
        return view('admin.chats.messages', compact('messages' ,'chat'));
    }
    public function deleteMessageForAdmin(Request $request , $message_id){
        PrivateMessage::where('id' , $message_id)->update(['deleted_at' => Carbon::now()]);
        Swal::success('موفقیت آمیز', 'حذف پیام با موفقیت انجام شد.');
        return back();
    }
    public function deleteChatsForAdmin(Request $request){
        $request->validate([
            'chat_ids' => 'required|array',
            'chat_ids.*' => 'integer'
        ]);
        Chat::whereIn('id', $request->chat_ids)->update(['deleted_at'=> Carbon::now()]);
        Swal::success('موفقیت آمیز', 'حذف گفت و گو با موفقیت انجام شد.');
        return back();
    }
    //have to separate them for user and admin
    //but for admin is done in Message Controller
    public function getAdminMessages(Request $request){
        $user = auth()->guard('api')->user();
        $messages = Message::where('user_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at' , 'ASC')
            ->get();
        $messages->where('user_id', null)->each(function ($message) use($request) {
            $message->view = 1;
            $message->save();
        });
        foreach ($messages as $message) {
            try {
                $message->updated_at = strval(Verta::instance($message->updated_at));
            } catch (Throwable $e) {
                $message->updated_at = strval(Verta::parse($message->updated_at));
            }
            $message->attached_file_url = $message->attached_file ? URL::to('chats/images/app') . '/' . $message->id . '?access_token=' . $request->bearerToken() : null;
        }
        
        return response()->json(['messages' => $messages , 'user' => $user] ,200);
    }
    public function sendMessageToAdmin(Request $request){
        $request->validate([
            'message' => 'required|string',
            'file' => 'nullable|integer|exists:tmp_files,id',
        ], [
            'message.required' => 'متن پیام الزامی است.',
        ]);
        $user = auth()->guard('api')->user();
        $filename = null;
        if ($request->has('file') && $request->file != "") {
            //moving file from temp
            $tmp = TempFile::find($request->file);
            $filename = $tmp->filename;
            $path = storage_path('app' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $tmp->filename);
            $target = storage_path('app' . DIRECTORY_SEPARATOR . 'chat-images' . DIRECTORY_SEPARATOR . $tmp->filename);
            if (File::exists($path)) {
                File::move($path,$target);
            }
        }
        $msg = Message::create([
            'user_id' => $user->id,
            'receiver_id' => null,
            'message' => $request->message,
            'attached_file' => $filename,
            'view' => 0
        ]);
        SupportLastMessage::updateOrCreate([
            'user_id' => $user->id,
        ], [
            'last_message' => $request->message,
            'last_message_datetime' => $msg->created_at,
            'view' => 0
        ]);
        $msg->updated_at = strval(Verta::instance($msg->updated_at));
        return response()->json(['message_sent' => $msg]);
    }
    public function deleteAdminMessage(Message $message){
        $message->delete();
        return back();
    }
    public function getMessages(Request $request ,$chat_id){
        $chat = Chat::find($chat_id);
        if(!$chat){
            return response()->json(['error' => 'unauthorized'] , 401);
        }
        $user = auth()->guard('api')->user();
        if($chat->sender_id != $user->id && $chat->receiver_id != $user->id){
            return response()->json(['error' => 'unauthorized'] , 401);
        }
        $messages = $chat->messages;
        if ($chat->sender_id != $user->id) {
        $chat->messages()->where('is_sent' , true)->update(['read' => true]);
        foreach ($messages as $message) {
            $message->is_sent = !$message->is_sent;
        }
        }
        else{
            $chat->messages()->where('is_sent', false)->update(['read' => true]);
        }
        $chat->addBlockDetails($user);
        $chat->addLastVisitDatetime($user);
        $user = User::find($user->id);
        $user->last_chat_visit_datetime = Carbon::now();
        $user->save();
        $setting = Setting::first();
        $noMessages = $setting->no_messages;
        return response()->json([ 'chat' => $chat, 'chat_default_message' => $noMessages] ,200);
    }
    public function getMessagesPaginated(Request $request , $chat_id){
        $chat = Chat::find($chat_id);
        if (!$chat) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $user = auth()->guard('api')->user();
        if ($chat->sender_id != $user->id && $chat->receiver_id != $user->id) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $messages = PrivateMessage::where('chat_id' , $chat->id)->orderByDesc('id')
        ->with(['chatable' => function (MorphTo $morphTo) {
            $morphTo->morphWith([
                Store::class => ['photo'],
                ProductSeller::class
            ]);
        }])->paginate(20);
        $sortedResult = $messages->getCollection()->sortBy('id')->values();
        $messages->setCollection($sortedResult);
        if ($chat->sender_id != $user->id) {
            foreach ($messages as $message) {
                $message->is_sent = !$message->is_sent;
            }
            $chat->messages()->where('is_sent', true)->update(['read' => true]);
        } else {
            $chat->messages()->where('is_sent', false)->update(['read' => true]);
        }
        // $chat->addChatable();
        $chat->addBlockDetails($user);
        $chat->addLastVisitDatetime($user);
        $contact_is_sender = ($chat->sender_id == auth()->guard('api')->user()->id) ? false : true;
        $chat->contact = (!$contact_is_sender) ? $chat->receiver : $chat->sender;
        if($chat->contact && ($request->query('page') == 1 || !$request->query('page'))){
        $chat->contact->thumbnail_photo = $chat->contact->thumbnail_photo;
        $chat->contact->stores = Store::where('status' , 'approved')->where('user_id' , $chat->contact->id)->get();
        }
        unset($chat->messages);
        $user = User::find($user->id);
        $user->last_chat_visit_datetime = Carbon::now();
        $user->save();
        $setting = Setting::first();
        $noMessages = $setting->no_messages;
        foreach ($messages as $message) {
            $message->is_sent = $message->is_sent == false ? 0 : 1;
            if($message->chatable){
                $newChatable = new \StdClass();
                $newChatable->id = $message->chatable->id;
                $newChatable->name = $message->chatable->name;
                $newChatable->user_name = $message->chatable->user_name;
                if($message->chatable_name == Store::class){
                    $newChatable->photo_url = url()->to('image/store_photos') . '/' . optional($message->chatable->photo)->photo_name;
                    unset($message->chatable);
                    $message->chatable = $newChatable;
                    
                }
                else{
                    $newChatable->photo_url = url()->to('image/product_seller_photo/350') . '/' . optional($message->chatable->photos()->first())->file_name;
                    unset($message->chatable);
                    $message->chatable = $newChatable;
                }
            }
        }

        return response()->json(['messages' => $messages, 'chat' => $chat, 'chat_default_message' => $noMessages], 200);
    }
    public function sendMessage(Request $request , $chat_id){
        $validated = Validator::make($request->all() , [
            'content' => 'string|required_if:attached_file,null',
            'attached_file' => 'file|required_if:content,null|max:10000',
            'chatable_name' => 'in:service,product,store',
            'chatable_id' => 'integer'
        ]);
        if($validated->fails()){
            return response()->json(['error' => $validated->errors()->all()],400);
        }
        $chat = Chat::find($chat_id);
        if (!$chat) {
            return response()->json(['error' => 'unauthorized'], 404);
        }
        $chatable = null;
        if($request->has('chatable_name') && $request->has('chatable_id')) {
        if ($request->chatable_name == 'store') {
            $chatable = Store::where('id', $request->chatable_id)->whereIn('user_id', [$chat->sender_id , $chat->receiver_id])->first();
            if (!$chatable) {
                return 'not found';
            }
        } else {
            // means 'product' or 'service'
            $chatable = ProductSeller::where('id', $request->chatable_id)->first();
            $store = $chatable->store()->where('store_type' , $request->chatable_name)->whereIn('user_id' , [$chat->sender_id, $chat->receiver_id]);
            if (!$store) {
                return 'not found';
            }
        }
    }
        $user = auth()->guard('api')->user();
        if ($user->chats_blocked || $chat->blocked_by_sender || $chat->blocked_by_receiver) {
            return response()->json(['error' => 'blocked'], 404);
        }
        if ($chat->sender_id != $user->id && $chat->receiver_id != $user->id) {
            return response()->json(['error' => 'unauthorized'], 401);
        }
        $pm = new PrivateMessage();
        $pm->chat_id = $chat->id;
        $pm->content = $request->content;
        $pm->attached_file = $request->attached_file;
        $pm->is_sent = $user->id == $chat->sender_id ? 1 : 0;
        $pm->persian_datetime = strval(Verta::now());
        if($request->has('chatable_name') && $request->has('chatable_id')){
            $pm->chatable()->associate($chatable);
        }
        $pm->save();
        $message = $pm;
        $message->is_sent = $message->is_sent == false ? 0 : 1;
        if ($message->chatable) {
            if ($message->chatable_name == Store::class) {
                $message->chatable->photo_url = url()->to('image/store_photos') . '/' . optional($message->chatable->photo)->photo_name;
            } else {
                $message->chatable->photo_url = url()->to('image/product_seller_photo/350') . '/' . optional($message->chatable->photos()->first())->file_name;
            }
        }
        //broadcast it here
        return response()->json(['message_sent' => $message] , 201);
    }
    public function createChat(Request $request){
        $validated = Validator::make($request->all() , [
            'type' => 'required|in:service,product,store',
            'id' => 'required|integer'
        ]);
        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()->all()], 400);
        }
        $user = auth()->guard('web')->user();
        if ($user->chats_blocked) {
            return redirect()->back();
        }
        // creating chat or Redirecting Chat for this user
            $store = null;
            if($request->type == 'store'){
            $store = Store::where('id', $request->id)->first();
                if (!$store) {
                    return 'not found';
                }
            }
            else{
                // means 'product' or 'service'
                $store = ProductSeller::where('id', $request->id)->first()->store;
                    if (!$store || $store->store_type != $request->type) {
                        return 'not found';
                    }
            }
            $contact = $store->user;
            //finding chat
            $user_id = $user->id;
            $contact_id = $contact->id;
            if($user_id == $contact_id){
                Swal::error('اخطار', 'شما نمیتوانید با خودتان گفت و گو آعاز کنید');
                return redirect()->back();
            }
            $chat = Chat::where(function($query) use($user_id , $contact_id){
               $query->where('sender_id' , $user_id)->where('receiver_id' , $contact_id)
                    ->orwhere(function($query) use($user_id , $contact_id){
                      $query->where('sender_id' , $contact_id)->where('receiver_id' , $user_id);
                    });
            })->first();
            if($chat){
                // Swal::error('اخطار', 'گفت و گو های شما از سمت ادمین مسدود شده است');
                $chat->save();
                return redirect()->to(route('chats.get') . '?chat='.$chat->id);
                
            }
            else{
                $chat = new Chat();
                $chat->sender_id = $user->id;
                $chat->receiver_id = $contact->id;
                $chat->save();
                return redirect()->to(route('chats.get') . '?chat=' . $chat->id);
            }

    }
    public function deleteChat($chat_id){
        $user_id = auth()->guard('api')->user()->id;
        Chat::where('id' , $chat_id)->where('sender_id' , $user_id)->orWhere('receiver_id' , $user_id)->update(['deleted_at'=> Carbon::now()]);
        return response()->json([ "status" => 200] , 200);
    }
    public function uploadTempFile(Request $request){

        $validated = Validator::make($request->all(), [
            'file' => 'required|file|image|max:3000'
        ]);
        if ($validated->fails()) {
            return response()->json(['error' => $validated->errors()->all()], 400);
        }
        $filename = null;
        //saving file
        $file = $request->file('file');
        $image = Image::make($file->getRealPath());
        $image->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $filename = date('YmdHi') . $file->getClientOriginalName();
        $image->save(storage_path('app' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $filename));
        $tmp = new TempFile();
        $tmp->filename = $filename;
        $tmp->save();
        return response($tmp->id);
    }
    public function deleteTempFile(Request $request){
        $tmp = TempFile::find($request->getContent());
        if(!$tmp){
            return response()->json([ "status" => 200] , 200);
        }
        $path = storage_path('app' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $tmp->filename);
        if(File::exists($path)){
            File::delete($path);
        }
        $tmp->delete();
        return response()->json([ "status" => 200] , 200);
    }
}
