<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PrivateMessage extends Model
{
    use SoftDeletes;
    protected $table = 'private_messages';
    public $timestamps = false;

    public function chat(){
        return $this->belongsTo(Chat::class , 'chat_id');
    }
    public function chatable(){
        return $this->morphTo('chatable' , 'chatable_name' ,'chatable_id');
    }
    public function addChatable()
    {
        if ($this->chatable_name == 'store') {
            $this->store = Store::find($this->chatable_id, ['id', 'slogan', 'slug', 'name', 'user_name']);
        }
        if ($this->chatable_name == 'product') {
            $this->product = ProductSeller::find($this->chatable_id, ['id', 'name']);
        }
        if ($this->chatable_name == 'service') {
            $this->service = ProductSeller::find($this->chatable_id, ['id', 'name']);
        }
    }
}
