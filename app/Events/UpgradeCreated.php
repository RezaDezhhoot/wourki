<?php

namespace App\Events;

use App\Upgrade;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpgradeCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $upgrade;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Upgrade $upgrade)
    {
        $this->upgrade = $upgrade;
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
