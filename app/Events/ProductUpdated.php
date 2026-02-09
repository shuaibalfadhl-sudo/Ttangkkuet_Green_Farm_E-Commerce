<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductUpdated implements ShouldBroadcast
{
    public $product;

    public function __construct(Product $product)
    {
        $this->product = $product; 
    }

    public function broadcastOn()
    {
        return [
            new Channel('products'),
    ];
    }
    public function broadcastAs()   
    {
        return 'ProductUpdated';
    }
    public function broadcastWith()
    {
        return [
            'product' => $this->product
        ];
    }

}

