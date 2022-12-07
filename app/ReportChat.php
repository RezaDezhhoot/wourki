<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportChat extends Model
{
    protected $table = 'report_chat';
    protected $fillable = ['seen']; 
    public function user(){
        return $this->belongsTo(User::class , 'user_id');
    }
    public function chat(){
        return $this->belongsTo(Chat::class , 'chat_id');
    }
    public function getContact(){
        $chat = Chat::withTrashed()->find($this->chat_id);
        if($chat->sender_id == $this->user_id){
            return $chat->receiver;
        }
        if($chat->receiver_id == $this->user_id){
            return $chat->sender;
        }
        return null;
    }
}
