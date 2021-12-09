<?php

namespace App\Events\Server;

use Illuminate\Broadcasting\{
    Channel,
    InteractsWithSockets,
    PresenceChannel,
    PrivateChannel
};
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Server;

class ServerWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The created user brough into the event
     * 
     * @var \App\Models\Server
     */
    private $server;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Server  $server
     * @return void
     */
    public function __construct(Server $server)
    {
        $this->server = $server;
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
