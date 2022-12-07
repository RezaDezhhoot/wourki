<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;
    protected $table = 'chats';
    public $timestamps = false;

    public function messages(){
        return $this->hasMany(PrivateMessage::class , 'chat_id')->orderBy('private_messages.id');
    }
    public function sender(){
        return $this->belongsTo(User::class , 'sender_id')->select(['id' , 'first_name' , 'last_name' , 'mobile' , 'chats_blocked' , 'thumbnail_photo' , 'last_chat_visit_datetime']);
    }
    public function receiver(){
        return $this->belongsTo(User::class , 'receiver_id')->select(['id', 'first_name', 'last_name', 'mobile', 'chats_blocked', 'thumbnail_photo' , 'last_chat_visit_datetime']);
    }
    public function getRouteKeyName()
    {
        return 'id';
    }
    // public function addChatable(){
    //     if($this->chatable_name == 'store'){
    //         $this->store = Store::find($this->chatable_id , ['id', 'slogan', 'slug', 'name', 'user_name']);
    //     }
    //     if($this->chatable_name == 'product'){
    //         $this->product = ProductSeller::find($this->chatable_id , ['id', 'name']);
    //     }
    //     if($this->chatable_name == 'service'){
    //         $this->service = ProductSeller::find($this->chatable_id , ['id', 'name']);
    //     }
    // }
    public function addBlockDetails($user){
        if(($user->id == $this->sender_id && $this->blocked_by_sender )|| ($user->id == $this->receiver_id && $this->blocked_by_receiver)){
            $this->you_blocked = true;
        }
        else{
            $this->you_blocked = false;
        }
        if (($user->id == $this->sender_id && $this->blocked_by_receiver) || ($user->id == $this->receiver_id && $this->blocked_by_sender)) {
            $this->contact_blocked = true;
        }
        else{
            $this->contact_blocked = false;
        }
    }
    public function addLastVisitDatetime($user){
        if($this->sender_id == $user->id){
            $this->last_visit_datetime = $this->receiver->last_chat_visit_datetime;
            $this->persian_last_visit_datetime = \Morilog\Jalali\Jalalian::forge($this->receiver->last_chat_visit_datetime)->format('%d %B %Y H:m');
        }
        else{
            $this->last_visit_datetime = $this->sender->last_chat_visit_datetime;
            $this->persian_last_visit_datetime = \Morilog\Jalali\Jalalian::forge($this->sender->last_chat_visit_datetime)->format('%d %B %Y H:m');
        }
    }
}
