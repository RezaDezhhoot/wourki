<?php

namespace App\Http\Controllers\ApiV2;

use App\Chat;
use App\Http\Controllers\Controller;
use App\Message;
use App\PrivateMessage;
use App\ProductSeller;
use App\Setting;
use App\Store;
use DB;
use Illuminate\Http\Request;
use Validator;

class ChatsApi extends Controller
{
    public function createChat(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:service,product,store',
            'id' => 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json(['status' => 400 , 'errors' => $validator->errors()->all()] , 200);
        }
        $user = auth()->guard('api')->user();
        if ($user->chats_blocked) {
            return response()->json(['status' => 400 , 'errors' => ['گفت و گو های شما از طرف ادمین مسدود شده است']], 200);
        }
        // creating chat or Redirecting Chat for this user
        $store = null;
        if ($request->type == 'store') {
            $store = Store::where('id', $request->id)->first();
            if (!$store) {
                return response()->json(['status' => 404 , 'errors' => ['آیتم مورد گفت و گو یافت نشد']], 200);
            }
        } else {
            // means 'product' or 'service'
            $productSeller = ProductSeller::where('id', $request->id)->first();
            if(!$productSeller){
                return response()->json(['status' => 404, 'errors' => ['آیتم مورد گفت و گو یافت نشد']], 200);
            }
            $store = $productSeller->store;
            if (!$store || $store->store_type != $request->type) {
                return response()->json(['status' => 404 , 'errors' => ['آیتم مورد گفت و گو یافت نشد']], 200);
            }
        }
        $contact = $store->user;
        //finding chat
        $user_id = $user->id;
        $contact_id = $contact->id;
        if ($user_id == $contact_id) {
            return response()->json(['status' => 404, 'errors' => ['شما نمیتوانید با خودتان گفت و گو آغاز کنید']], 200);
        }
        $chat = Chat::where(function ($query) use ($user_id, $contact_id) {
            $query->where('sender_id', $user_id)->where('receiver_id', $contact_id)
                ->orwhere(function ($query) use ($user_id, $contact_id) {
                    $query->where('sender_id', $contact_id)->where('receiver_id', $user_id);
                });
        })->first();
        if ($chat) {
            // Swal::error('اخطار', 'گفت و گو های شما از سمت ادمین مسدود شده است');
            // $chat->chatable_name = $request->type;
            // $chat->chatable_id = $request->id;
            $chat->save();
            return response()->json(['status' => 200 , 'chat' => $chat] , 201);
        } else {
            $chat = new Chat();
            $chat->sender_id = $user->id;
            $chat->receiver_id = $contact->id;
            // $chat->chatable_name = $request->type;
            // $chat->chatable_id = $request->id;
            $chat->save();
            return response()->json(['status' => 200 , 'chat' => $chat] , 201);
        }

    }
    public function getChats(Request $request)
    {
        $user = auth()->guard('api')->user();
        if ($user->chats_blocked)
            $blocked = true;
        else $blocked = false;
        $chats = Chat::whereNull('chats.deleted_at')->where(function ($query) use ($user) {
            return $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })
        ->leftJoin('private_messages' , 'chats.id' , '=' , 'private_messages.chat_id')
        ->groupBy('chats.id')
        ->select('chats.*')
        ->orderByDesc(DB::raw('MAX(private_messages.persian_datetime)'))
            ->with('sender')->with('receiver')->get();
        foreach ($chats as $chat) {
            $contact_is_sender = $chat->sender_id == auth()->guard('api')->user()->id ? false : true;
            $chat->contact = !$contact_is_sender ? $chat->receiver : $chat->sender;
            if($chat->contact)
            $chat->contact->thumbnail_photo = url()->to('/image/store_photos/') . '/' . $chat->contact->thumbnail_photo;
            $chat->newMessages = PrivateMessage::query()->whereNull('deleted_at')->where('read', false)->where('chat_id', $chat->id)->where('is_sent', $contact_is_sender ? true : false)->count();
            $chat->last_message = PrivateMessage::query()->whereNull('deleted_at')->where('chat_id', $chat->id)->orderByDesc('persian_datetime')->first();
            $chat->contact->stores = Store::where('user_id' , $chat->contact->id)->where('status' , 'approved')->get();
            $chat->addLastVisitDatetime($user);
        }
        $adminNewMessages = Message::whereNull('user_id')
        ->where('receiver_id', auth()->guard('api')->user()->id)
            ->where('view', 0)->count();
        $adminLastMessage = Message::where('user_id', auth()->guard('api')->user()->id)
                                ->orWhere('receiver_id', auth()->guard('api')->user()->id)->orderByDesc('created_at')->first();
        return response()->json(['status' => 200 , 'chats' => $chats , 'admin-messages' => $adminNewMessages ,  'blocked' => $blocked , 'admin_last_message' => $adminLastMessage] , 200);
    }
    public function getRules()
    {
        $rules = Setting::first()->chat_rules;
        return response()->json(['status' => 200 ,'rules' => $rules] , 200);
    }
    public function groupDeleteMessages(Request $request){
        $validator = Validator::make($request->all() , [
            'ids' => ['required' , 'array'],
            'ids.*' => ['integer']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = auth()->guard('api')->user();
        PrivateMessage::join('chats' , 'private_messages.chat_id' , '=' , 'chats.id')->where(function($query) use($user) {
            return $query->where('sender_id' , $user->id)->orWhere('receiver_id' , $user->id);
        })->whereIn('private_messages.id' , $request->ids)->delete();
        return response()->json("" , 200);
    }
    public function groupDeleteChats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => ['required', 'array'],
            'ids.*' => ['integer']
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = auth()->guard('api')->user();
        Chat::where(function ($query) use ($user) {
            return $query->where('sender_id', $user->id)->orWhere('receiver_id', $user->id);
        })->whereIn('id', $request->ids)->delete();
        return response()->json([ "status" => 200] , 200);
    }
    public function getChatStores(Request $request , $chat_id){
        $user = auth()->guard('api')->user();
        $chat = Chat::find($chat_id);
        if(!$chat || ($chat->sender_id != $user->id && $chat->receiver_id != $user->id)){
            return response()->json([ "status" => 200] , 200);
        }
        if($user->id == $chat->sender_id){
            $stores = Store::where('user_id', $chat->receiver_id)->where('status', 'approved')->get();
            foreach($stores as $store){
                $store->photo_url = url()->to('image/store_photos') . '/' . optional($store->photo)->photo_name;
            }
            return response()->json( $stores, 200);
        }
        else{
            $stores = Store::where('user_id', $chat->sender_id)->where('status', 'approved')->get();
            foreach ($stores as $store) {
                $store->photo_url = url()->to('image/store_photos') . '/' . optional($store->photo)->photo_name;
            }
            return response()->json( $stores , 200);
        }

    }
    public function getChatProducts(Request $request , $chat_id){
        $validator = Validator::make($request->all() , [
            'type' => 'in:product,service'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'errors' => $validator->errors()->all()], 200);
        }
        $user = auth()->guard('api')->user();
        $chat = Chat::find($chat_id);
        if (!$chat || ($chat->sender_id != $user->id && $chat->receiver_id != $user->id)) {
            return response()->json([ "status" => 200], 200);
        }
        $products = ProductSeller::join('store' , 'product_seller.store_id' , '=' , 'store.id')
            ->where('product_seller.status' , 'approved')
            ->where('user_id' , $chat->sender_id == $user->id ? $chat->receiver_id : $chat->sender_id)
            ->select('product_seller.*' , 'store.store_type');
        if($request->has('type')){
            $products->where('store_type' , $request->type);
        }
        $products = $products->get();
        foreach($products as $product){
            $product->photo_url = url()->to('image/product_seller_photo') . '/' . optional($product->photos()->first())->file_name;
        }
        return response()->json($products , 200);
    }
    public function getSingleProduct(Request $request){
        $validator = Validator::make($request->all() , [
            'chat_id' => 'exists:chats,id',
            'product_id' => 'required|exists:product_seller,id'
        ]);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->all()] , 400);
        }
        $product = ProductSeller::find($request->product_id);
        if($request->has('chat_id')){
        $chat = Chat::find($request->chat_id);
        $contact_id = $chat->sender_id == auth()->guard('api')->user()->id ? $chat->receiver_id : $chat->sender_id;
        if($product->store->user_id == $contact_id){
            $product->photo_url = url()->to('image/product_seller_photo') . '/' . optional($product->photos()->first())->file_name;
            return response()->json($product , 200);
        }
        else{
            return response()->json(['errors' => ['access denied']] , 400);
        }
    }
    else return response()->json($product, 200);
    }
}
