<?php

namespace App\Listeners;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeTheCartLogout
{
    public function __construct()
    {
        //
    }

    public function handle(Logout $event)
    {
        Cart::erase(auth()->user()->id);
        Cart::store(auth()->user()->id);
    }
}
