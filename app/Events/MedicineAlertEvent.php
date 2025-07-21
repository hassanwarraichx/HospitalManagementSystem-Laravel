<?php

namespace App\Events;

use App\Models\Medicine;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MedicineAlertEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;

    public function __construct($user)
    {
        $this->userId = $user->id;
        $this->message = $this->generateAlertMessage();
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel('medicine-alert');
    }

    public function broadcastAs(): string
    {
        return 'send-message';
    }

    private function generateAlertMessage()
    {
        $nearExpiry = Medicine::whereDate('expiry_date', '<=', now()->addDays(7))->count();
        $lowStock = Medicine::where('stock', '<', 10)->count();

        if ($nearExpiry === 0 && $lowStock === 0) return null;

        $messages = [];

        if ($nearExpiry) {
            $messages[] = "$nearExpiry medicine(s) are near expiry!";
        }

        if ($lowStock) {
            $messages[] = "$lowStock medicine(s) are low in stock!";
        }

        return implode("\n", $messages);
    }


}
