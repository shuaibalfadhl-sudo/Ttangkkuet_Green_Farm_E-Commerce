<?php

namespace App\Livewire;

use Livewire\Component;

class ProductStatus extends Component
{
    public $product;
    public $status;

    public function mount($product)
    {
        $this->product = $product;
        $this->status = $product->status;
    }
    public function updateStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->product->status = $newStatus;
        $this->product->save();
        
    }
    public function render()
    {
        return view('livewire.product-status');
    }
}
