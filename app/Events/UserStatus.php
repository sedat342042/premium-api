<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private string $message;
    private User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new Channel('presence-users');
        //return new PrivateChannel('users.'. $this->user->id);
        //return new PrivateChannel('users.'. $this->user->id);
        return new PresenceChannel('updates');
        //return new PrivateChannel('updates');
        //return new Channel('users');
    }

    public function broadcastAs()
    {
        return 'UpdateCreated';
    }

    public function broadcastWith()
    {
        return [
            'userId' => $this->user->id,
            'user' => $this->user
        ];
    }

}
