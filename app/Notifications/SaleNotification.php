<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SaleNotification extends Notification
{
    protected $sale;

    public function __construct($sale)
    {
        $this->sale = $sale;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'sale',
            'sale_id' => $this->sale->id,
            'total' => $this->sale->total,
            'time' => now()->format('d M Y, h:i A'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Sale',
            'message' => 'A sale was completed successfully',
        ];
    }
}