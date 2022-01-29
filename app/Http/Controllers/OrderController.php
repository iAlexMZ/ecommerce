<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $items = json_decode($order->content);

        return view('orders.show', compact('order', 'items'));
    }

    public function index()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();

        return view('orders.index', compact('orders'));
    }
}
