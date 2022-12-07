<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketCommission extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'market_commissions';
    public function category(){
        return $this->belongsTo(Category::class , 'category_id');
    }
    public function applyOn($price){
        return ($this->amount * $price) / 100;
    }
}
