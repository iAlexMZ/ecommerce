<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function files(Product $product, Request $request)
    {
        $request->validate([
            'file' => 'required|image|max:2048'
        ]);

        $url = $request->file('file')->store('products', 'public');
        $product->images()->create([
            'url' => $url
        ]);
    }

}
