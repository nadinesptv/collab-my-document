<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentUpdatedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $documentId;
    public $content;
    public $userId;

    public function __construct($documentId, $content, $userId)
    {
        $this->documentId = $documentId;
        $this->content = $content;
        $this->userId = $userId;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('document.' . $this->documentId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'document.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'documentId' => $this->documentId,
            'content' => $this->content,
            'userId' => $this->userId,
        ];
    }
}