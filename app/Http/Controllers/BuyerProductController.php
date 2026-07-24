<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class BuyerProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load('producer.user');

        return view('buyer.products.show', compact('product'));
    }
}
