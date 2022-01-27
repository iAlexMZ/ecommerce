<?php

namespace App\Listeners;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeTheCart
{
    public function __construct()
    {
        //
    }

    public function handle(Login $event)
    {
        Cart::merge(auth()->user()->id);
    }
}
