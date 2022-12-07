<?php

namespace App\Events;

use App\ProductSeller;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserWriteComment
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $productSeller;
    /**
     * Create a new event instance.
     * @param User $user
     * @param ProductSeller $product
     *
     * @return void
     */
    public function __construct(User $user , ProductSeller $product)
    {
        $this->user = $user;
        $this->productSeller = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
