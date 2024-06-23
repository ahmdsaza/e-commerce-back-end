<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->id;
        $allCarts = Cart::where('user_id', $user)->get();
        return  $allCarts;
    }

    public function addToCart(Request $request)
    {
        if (auth()->user()) {

            $user_id = $request->user_id;
            $product_id = $request->product_id;
            $product_qty = $request->product_qty;
            $product_image = $request->product_image;

            $cartitem = new Cart;
            $cartitem->user_id = $user_id;
            $cartitem->product_id = $product_id;
            $cartitem->product_qty = $product_qty;
            $cartitem->product_image = $product_image;

            $cartitem->save();
        } else {
            return response()->json(['status' => 401, 'meassge' => 'Login to Add to Cart control']);
        }
    }
}
