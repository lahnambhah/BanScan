<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LowStockNotification extends Notification
{
    public $item; 

    public function __construct($item)
    {
        $this->item = $item; 
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'low_stock',
            'item_name' => $this->item->name,
            'stock' => $this->item->stock,
            'time' => now()->format('d M Y, h:i A'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Low Stock Alert',
            'message' => 'Item is running low on stock',
        ];
    }
}