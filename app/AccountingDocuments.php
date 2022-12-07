<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AccountingDocuments
 *
 * @method static create(array $array)
 * @property int $id
 * @property string $balance
 * @property string $description
 * @property string $type
 * @property int|null $bill_id
 * @property int|null $wallet_id
 * @property int|null $marketer_id
 * @property int|null $plan_id
 * @property int|null $checkout_id
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereBillId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereCheckoutId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereMarketerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments wherePlanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccountingDocuments whereWalletId($value)
 * @method static createMany(array[] $array)
 * @mixin \Eloquent
 */
class AccountingDocuments extends Model
{
    protected $fillable = ['balance', 'description', 'type', 'bill_id', 'wallet_id', 'plan_id', 'checkout_id','market_id'];

    protected $table = 'accounting_documents';

    public function bill()
    {
        return $this->belongsTo(Bill::class, 'bill_id');
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
    public function upgrade(){
        return $this->belongsTo(Upgrade::class , 'upgrade_id');
    }

}
