<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ProductSellerComment
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_seller_id
 * @property string $comment
 * @property string $status
 * @property int|null $parent_comment_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\ProductSeller $product
 * @property-read \Illuminate\Database\Eloquent\Collection|ProductSellerComment[] $responses
 * @property-read int|null $responses_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereParentCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereProductSellerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductSellerComment whereUserId($value)
 * @mixin \Eloquent
 */
class ProductSellerComment extends Model
{
    protected $table = 'product_seller_comment';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductSeller::class , 'product_seller_id');
    }

    public function responses(){
        return $this->hasMany(ProductSellerComment::class , 'parent_comment_id')
            ->whereNotNull('parent_comment_id');
    }
}
