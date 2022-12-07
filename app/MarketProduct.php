<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketProduct extends Model
{
    use HasFactory;
    //created for direct access to intermediate table
    protected $table = 'market_product';
    public function market(){
        return $this->belongsTo(Store::class , 'market_id' , 'id');
    }
    public function product(){
        return $this->belongsTo(ProductSeller::class , 'product_id' , 'id');
    }

}
