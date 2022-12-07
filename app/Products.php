<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Products
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property int|null $discount takhfif
 * @property int $quantity tedad
 * @property int|null $visible
 * @property int $deleted
 * @property int $subcatid
 * @property int $hits
 * @property string $price
 * @property int|null $is_vip
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BillItem[] $billsItem
 * @property-read int|null $bills_item_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $favoriteByUser
 * @property-read int|null $favorite_by_user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ProductPhoto[] $photo
 * @property-read int|null $photo_count
 * @property-read \App\Slider|null $slider
 * @property-read \App\SubCategory $subCategory
 * @method static \Illuminate\Database\Eloquent\Builder|Products findSimilarSlugs(string $attribute, array $config,
 *     string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Products newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Products newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Products query()
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereHits($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereIsVip($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereSubcatid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Products whereVisible($value)
 * @mixin \Eloquent
 */
class Products extends Model
{
    use Sluggable;

    const FIELDS = 'product.* , category.id as category_id ,  category.name as category_name , category.icon as cat_icon , sub_category.name as sub_cat_name , sub_category.icon as sub_cat_icon ,
    (
    
    SELECT SUM(((bill_item.price - ((bill_item.price * bill_item.discount) / 100))) * bill_item.quantity)  
    FROM bill_item 
    JOIN bill ON bill.id = bill_item.bill_id 
    WHERE bill_item.product_id = product.id AND (bill.status="delivered" OR bill.status="shipping")
    
    ) as sale_price ,
    
    round(product.price-(product.price*product.discount)/100) as total_price , 
    (
        select product_pic.name
        from product_pic
        where product_pic.product_id = product.id
        limit 1
    ) as first_photo ';
    protected $table = 'product';
    protected $guarded = ['id'];
    private $dbQuery;

    public function __construct()
    {
        $this->dbQuery = DB::table($this->table)
            ->join('sub_category', 'sub_category.id', '=', 'product.subcatid')
            ->join('category', 'category.id', '=', 'sub_category.category_id');
    }

    public function dbSelect($fields)
    {
        $this->dbQuery = $this->dbQuery->selectRaw($fields);
        return $this->dbQuery;
    }

    public function sluggable() : array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function photo()
    {
        return $this->hasMany(ProductPhoto::class, 'product_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'product_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcatid');
    }

    public function billsItem()
    {
        return $this->hasMany(BillItem::class, 'product_id');
    }

    public function favoriteByUser()
    {
        return $this->belongsToMany(User::class, 'fav_product', 'product_id', 'user_id');
    }

    public function slider()
    {
        return $this->hasOne(Slider::class, 'product_id');
    }
}
