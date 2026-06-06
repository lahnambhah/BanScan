<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewItemNotification extends Notification
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
            'type' => 'new_item',
            'item_name' => $this->item->name,
            'time' => now()->format('d M Y, h:i A'),
        ];
    }


    public function toArray($notifiable)
    {
        return [
            'title' => 'New Item Added',
            'message' => 'A new product has been added',
        ];
    }
}