<?php

namespace App\Http\Controllers\API;

use App\Marketer;
use App\Message;
use App\SupportLastMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PrivateMessage;

class MessageApi extends Controller
{
    public function userMessages(Request $request)
    {
        $user = auth()->guard('api')->user();
        $offset = $request->filled('offset') ? $request->offset : 0;
        $limit = $request->filled('limit') ? $request->limit : 1;
        $messages = Message::where('user_id' , $user->id)
            ->orWhere('receiver_id' , $user->id)
            ->latest()
            ->offset($offset)
            ->limit($limit)
            ->get();
        $messages->where('user_id' , null)->each(function ($message) {
            $message->view = 1;
            $message->save();
        });
        return response()->json($messages , 200);
    }

    public function userStore(Request $request)
    {
        $user = auth()->guard('api')->user();
        if($user->banned == 1){
            return response()->json([
                'status' => 400,
                'message' => 'user_is_banned',
                'entire' => []
            ]);
        }
        $message = Message::create([
            'user_id' => $user->id,
            'receiver_id' => null,
            'message' => $request->message,
            'view' => 0
        ]);
        SupportLastMessage::updateOrCreate([
            'user_id' => $user->id
        ] , [
            'last_message' => $request->message,
            'last_message_datetime' => $message->created_at
        ]);

        return response()->json($message , 200);
    }

    public function delete(Request $request)
    {
        $message = Message::find($request->id)->delete();
        if ($message)
            return response()->json(['status' => 200] , 200);
        else
            return response()->json(['status' => 400] , 400);
    }

    public function userMessageCount()
    {
        $user = auth()->guard('api')->user();
        $plansCount = $user->plans()->count();
        $messageCount = Message::where('user_id' , null)
            ->where('receiver_id' , $user->id)
            ->where('view' , 0)
            ->count();
        $chatMessagesCount = PrivateMessage::join('chats', 'private_messages.chat_id', '=', 'chats.id')->where('read', false)
            ->where(function ($query) use($user) {
                $query->where(function ($query) use($user) {
                    $query->where('receiver_id', $user->id)->where('is_sent', true);
                })->orWhere(function ($query) use($user) {
                    $query->where('sender_id', $user->id)->where('is_sent', false);
                });
            })
            ->count();
        $isMerketer = Marketer::where('user_id' , $user->id)->exists();
        $stores = $user->stores;
        $hasServiceStore = false;
        $hasProductStore = false;
        $hasMarketStore = false;
        $marketStoreId = null;
        $productStoreId = null;
        $serviceStoreId = null;
        foreach ($stores as $store) {
            if($store->store_type == 'service'){
                $hasServiceStore = true;
                $serviceStoreId = $store->id;
            } 
            if($store->store_type == 'product'){
                $hasProductStore = true;
                $productStoreId = $store->id;
            } 
            if($store->store_type == 'market'){
                $hasMarketStore = true;
                $marketStoreId = $store->id;
            } 
        }
        return response()->json([
            'messageCount' => $messageCount ,
            'chatMessagesCount' => $chatMessagesCount,
            'isMarketer' => $isMerketer ,
            'userAddressCount' => $user->addresses()->count(),
            'hasProductStore' => $hasProductStore,
            'hasServiceStore' => $hasServiceStore,
            'hasMarketStore' => $hasMarketStore,
            'marketStoreId' => $marketStoreId,
            'productStoreId' => $productStoreId,
            'serviceStoreId' => $serviceStoreId,
            'plansCount' => $plansCount
        ] , 200);
    }
}
