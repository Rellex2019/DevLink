<?php

namespace App\Events;

use App\Models\InviteTeam;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserInvited implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $invite;
    
    public function __construct(InviteTeam $invite)
    {
        $this->invite = $invite->load(['user', 'sender', 'team.ownerInfo']);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->invite->user->id),
        ];
    }
}
