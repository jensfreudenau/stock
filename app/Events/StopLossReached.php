<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StopLossReached
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $value;
    public string $share;

    /**
     * Create a new event instance.
     */
    public function __construct($share, $value)
    {
        $this->share = $share;
        $this->value = $value;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return ['stop-loss-channel'];
    }

    public function broadcastAs()
    {
        return 'stop-loss-event';
    }
}
