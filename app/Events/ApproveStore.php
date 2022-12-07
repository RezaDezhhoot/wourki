<?php

namespace App\Events;

use App\Store;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApproveStore
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $store;
    public $user;
    /**
     * Create a new event instance.
     *
     * @param Store $store
     * @param User $user
     * @return void
     */
    public function __construct(Store $store,User $user)
    {
        $this->store = $store;
        $this->user = $user;
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
