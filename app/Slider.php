<?php

namespace App;

use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Slider
 *
 * @property int $id
 * @property string $pic
 * @property int|null $product_id
 * @property int|null $store_id
 * @property string|null $alt
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\ProductSeller|null $product
 * @property-read \App\Store|null $store
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider query()
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereAlt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider wherePic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Slider whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Slider extends Model
{
    protected $table = 'slider';
    protected $guarded = [];
    // types of slider
    const HOME = 'home';
    const STORE = 'store';
    const SERVICE = 'service';
    const PRODUCT = 'product';

    const FIELDS = 'slider.* , product.name as product_name , category.name as category_name , product.slug as product_slug';
    private $dbQuery;
    public function __construct()
    {
        $this->dbQuery = DB::table($this->table)
            ->join('product' , 'slider.product_id' , '=' , 'product.id')
            ->join('sub_category', 'sub_category.id' , '=' , 'product.subcatid')
            ->join('category', 'category.id' , '=' , 'sub_category.category_id');
    }

    public function dbSelect($fields){
        $this->dbQuery = $this->dbQuery->selectRaw($fields);
        return $this->dbQuery;
    }

    public function product(){
        return $this->belongsTo(ProductSeller::class , 'product_id');
    }

    public function store(){
        return $this->belongsTo(Store::class , 'store_id');
    }
    
    protected static function boot(){
        parent::boot();

        static::deleting(function ($slider){
            //deleting photo
            $path = public_path('image'.DIRECTORY_SEPARATOR.'slider'.$slider->pic);
            if(File::exists($path)){
                File::delete($path);
            }
        });
    }
    public function getPersianType(){
        if($this->type == self::HOME) return 'صفحه اصلی';
        if($this->type == self::STORE) return 'صفحه فروشگاه ها';
        if($this->type == self::PRODUCT) return 'صفحه محصولات';
        if($this->type == self::SERVICE) return 'صفحه خدمات';

    }


}
