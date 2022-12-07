<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $product_id
 * @property string $comment
 * @property string $status
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|Comment[] $childComments
 * @property-read int|null $child_comments_count
 * @property-read \App\Products $product
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    protected $table = 'comment';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Products::class , 'product_id');
    }

    public function parent_comment()
    {
        $this->belongsTo(Comment::class , 'parent_comment_id');
    }

    private $dbQuery;

    const FIELDS = 'comment.* , users.id as user_id , users.first_name as user_first_name , users.last_name as user_last_name ,  product.id as product_id , product.name as product_name';

    public function __construct()
    {
        $this->dbQuery = DB::table($this->table)
            ->join('product', 'product.id' , '=' , 'comment.product_id')
            ->join('users', 'users.id', '=', 'comment.user_id')
            ->leftJoin('comment as response', 'response.id' , '=' , 'comment.parent_comment_id');
    }

    public function dbSelect($fields)
    {
        $this->dbQuery = $this->dbQuery->selectRaw($fields);
        return $this->dbQuery;
    }

    public function childComments(){
        return $this->hasMany(Comment::class , 'parent_comment_id');
    }
}
