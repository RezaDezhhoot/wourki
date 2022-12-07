<?php

namespace App\Console;

use App\Helpers\LaravelCafebazaar\LaravelCafebazaarConsole;
use App\Message;
use App\ProductSeller;
use App\Setting;
use App\Store;
use App\SupportLastMessage;
use App\Test;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        LaravelCafebazaarConsole::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //schedule for notify user which submitted product without any photo.
        $schedule->call(function () {
            $usersToNotify = ProductSeller::where('product_seller.product_without_photo_notified', '=', 0)
                ->join('store', 'store.id', '=', 'product_seller.store_id')
                ->join('users', 'users.id', '=', 'store.user_id')
                ->select('users.id', 'users.first_name', 'users.last_name', 'product_seller.id as product_id', 'product_seller.name as product_name')
                ->addSelect(\DB::raw('(
                    select count(*)
                    from product_seller_photo 
                    where product_seller_photo.seller_product_id = product_seller.id
                ) as photos_count'))
                ->whereIn('product_seller.status', ['pending', 'approved'])
                ->where('photos_count', '=', 0)
                ->where('store.status', '!=', 'deleted')
                ->get();
            $messagesArr = [];
            $lastSupportMsgArr = [];
            $products = [];
            $productWithoutPhotoMessageText = Setting::first()->product_without_photo_msg;
            foreach ($usersToNotify as $user) {
                // fill full name and product name placeholder with real user full name and product name
                $msgText = str_replace(['%full_name%' , '%product_name%'], [$user->first_name . ' ' . $user->last_name , $user->product_name] , $productWithoutPhotoMessageText);

                // insert message in message table
                $arr = [];
                $arr['user_id'] = null;
                $arr['receiver_id'] = $user->id;
                $arr['message'] = $msgText;
                $arr['view'] = 0;
                $arr['created_at'] = Carbon::now()->toDateTimeString();
                $arr['updated_at'] = Carbon::now()->toDateTimeString();
                $messagesArr[] = $arr;

                // insert message in support_last_message table
                $arr2 = [];
                $arr2['user_id'] = $user->id;
                $arr2['last_message'] = $msgText;
                $arr2['last_message_datetime'] = Carbon::now()->toDateTimeString();
                $arr2['view'] = 0;
                $arr2['created_at'] = Carbon::now()->toDateTimeString();
                $arr2['updated_at'] = Carbon::now()->toDateTimeString();
                $lastSupportMsgArr[] = $arr2;

                //set the field in product table to 1 to don't notify later if product doesn't have any photo
                $products[] = $user->product_id;
            }

            Message::insert($messagesArr);
            SupportLastMessage::insert($lastSupportMsgArr);
            ProductSeller::whereIn('id' ,$products)
                ->update(['product_without_photo_notified' => 1]);
        })->everyFifteenMinutes();

        // schedule for notify user when his subscription plan finished.
        $schedule->call(function(){
            $usersToNotify = Store::join('users' , 'users.id' , '=' , 'store.user_id')
                ->where('store.notified_finishing_subscription_plan' , '=' , 0)
                ->where('store.status' , '!=' , 'deleted')
                ->whereRaw(' (
                    select count(*)
                    from seller_plan_subscription_details
                    where seller_plan_subscription_details.store_id = store.id and
                    seller_plan_subscription_details.from_date <= "'. Carbon::now()->toDateString() .'" and
                    seller_plan_subscription_details.to_date >= "'. Carbon::now()->toDateString() .'"
                ) = 0')
                ->select('users.id' , 'store.id as store_id' , 'users.first_name' , 'users.last_name')
                ->get();
            $finishingPlanMessage = Setting::first()->finishing_subscription_plan_message;
            $messagesArr = [];
            $lastSupportMsgArr = [];
            $stores = [];
            foreach($usersToNotify as $user){

                // fill full name placeholder with real user full name.
                $msgText = str_replace('%full_name%', $user->first_name . ' ' . $user->last_name , $finishingPlanMessage);

                // insert message in message table
                $arr = [];
                $arr['user_id'] = null;
                $arr['receiver_id'] = $user->id;
                $arr['message'] = $msgText;
                $arr['view'] = 0;
                $arr['created_at'] = Carbon::now()->toDateTimeString();
                $arr['updated_at'] = Carbon::now()->toDateTimeString();
                $messagesArr[] = $arr;

                // insert message in support_last_message table
                $arr2 = [];
                $arr2['user_id'] = $user->id;
                $arr2['last_message'] = $msgText;
                $arr2['last_message_datetime'] = Carbon::now()->toDateTimeString();
                $arr2['view'] = 0;
                $arr2['created_at'] = Carbon::now()->toDateTimeString();
                $arr2['updated_at'] = Carbon::now()->toDateTimeString();
                $lastSupportMsgArr[] = $arr2;

                $stores[] = $user->store_id;
            }

            Message::insert($messagesArr);
            SupportLastMessage::insert($lastSupportMsgArr);
            Store::whereIn('id' , $stores)
                ->update(['notified_finishing_subscription_plan' => 1]);
        })->everyFifteenMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
