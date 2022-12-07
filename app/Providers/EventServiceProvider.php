<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\UserVerifyMobile' => [
            'App\Listeners\SendWelcomeSupportMessageToUser',
            'App\Listeners\AssignRegisterGiftToUser',
            'App\Listeners\AssignGiftToReferrer',
            'App\Listeners\AssignGiftToReferred'
        ],
        'App\Events\BillConfirmed' => [
            'App\Listeners\AssignFirstBuyGift',
            'App\Listeners\AssignFirstSellGift'

        ],
        'App\Events\ApproveStore' => [
            'App\Listeners\SendApproveStoreMessageToUser',
            'App\Listeners\AssignStoreCreateGiftToReferrer'
        ],
        'App\Events\RejectStore' => [
            'App\Listeners\SendRejectStoreMessageToUser'
        ],
        'App\Events\UserWriteComment' => [
            'App\Listeners\SendCommentMessageToStoreOwner'
        ],
        'App\Events\CheckoutStore' => [
            'App\Listeners\SendCheckoutMessageToStoreOwner'
        ],
        'App\Events\SubmitBill' => [
            'App\Listeners\SaveBillInAccountingDocument'
        ],
        'App\Events\SMSHandler' => [
            'App\Listeners\SMSHandlerListener'
        ],
        'App\Events\DiscountSaved' => [
            'App\Listeners\SendDiscountMessageToAllUsers'
        ],
        'App\Events\UpgradeCreated' => [
            'App\Listeners\AddUpgradeToAccountingDocuments'
        ],
        'App\Events\SubscriptionCreated' => [
            'App\Listeners\AddPlanToAccountingDocuments'
        ],
        'App\Events\AdCreated' => [
            'App\Listeners\AddAdToAccountingDocuments'
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
