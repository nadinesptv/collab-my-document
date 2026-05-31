<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CursorMovedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int    $documentId,
        public int    $userId,
        public string $userName,
        public int    $position,
    ) {}

    public function broadcastOn(): array
    {
        return [new PresenceChannel("document.{$this->documentId}")];
    }

    public function broadcastAs(): string
    {
        return 'cursor.moved';
    }
}