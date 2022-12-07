<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Setting
 *
 * @property int $id
 * @property int $reagent_user_fee
 * @property int $reagented_user_fee
 * @property int $marketer_fee
 * @property int $reagent_user_create_store
 * @property int $marketer_user_create_store
 * @property string $register_gift
 * @property string $welcome_msg
 * @property string $approve_store_msg
 * @property string $new_comment_msg
 * @property string $checkout_msg
 * @property string $product_without_photo_msg
 * @property string $finishing_subscription_plan_message
 * @property float $app_version
 * @property int $ads_expire_days
 * @property string|null $wallet_page_help_text
 * @property string|null $support_page_help_text
 * @property string|null $ads_page_help_text
 * @property int $excel_export_rows_num
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereAdsExpireDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereAdsPageHelpText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereAppVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereApproveStoreMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCheckoutMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereExcelExportRowsNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereFinishingSubscriptionPlanMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereMarketerFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereMarketerUserCreateStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereNewCommentMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereProductWithoutPhotoMsg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereReagentUserCreateStore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereReagentUserFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereReagentedUserFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereRegisterGift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereSupportPageHelpText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWalletPageHelpText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWelcomeMsg($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    protected $table = 'setting';
    public $timestamps = false;
    protected $guarded = [];
}
