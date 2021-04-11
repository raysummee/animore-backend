<?php

namespace App\Events\VetBook;

use App\Models\VetBook;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class onStatusChangeVetBook implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $vetBook;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(VetBook $vetBook)
    {
        $this->vetBook = $vetBook;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('book-vet-'.$this->vetBook->pet->user->id);
    }
}
