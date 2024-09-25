<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->id;
        $allCarts = Cart::with('Images')->with('Sizes')->where('user_id', $user)->get();
        return  $allCarts;
    }

    public function addToCart(Request $request)
    {
        if (auth()->user()) {

            // call request
            $user_id = $request->user_id;
            $product_id = $request->product_id;
            $product_slug = $request->product_slug;
            $product_size = $request->product_size;
            $product_qty = $request->product_qty;

            // call database
            $product_qty_check = Size::where('id', $product_size)->where('product_id', $product_id)->first();
            $upadteqty = Cart::where('user_id', $user_id)->where('product_id', $product_id)->where('product_size', $product_size)->first();

            $call_product_qty_check = $product_qty_check->quantity;

            if ($upadteqty) {
                $upadteqtycount = $upadteqty->product_qty + $product_qty;

                if ($call_product_qty_check >= $upadteqtycount) {

                    $request->validate([
                        'product_qty' => 'required',
                    ]);
                    $upadteqty->update([
                        'product_qty' =>  $request->product_qty + $upadteqty->product_qty,
                    ]);

                    $upadteqty->save();
                } else {
                    return response()->json(['error' => 'No Quantity enough there is only: ' . $call_product_qty_check . ' pices'], 420);
                }
            } else {
                $cartitem = new Cart;
                $cartitem->user_id = $user_id;
                $cartitem->product_id = $product_id;
                $cartitem->product_slug = $product_slug;
                $cartitem->product_qty = $product_qty;
                $cartitem->product_image = $product_id;
                $cartitem->product_size = $product_size;

                $cartitem->save();
            }
        } else {
            return response()->json(['status' => 401, 'meassge' => 'Login to Add to Cart']);
        }
    }

    public function cartlength()
    {
        $user_id = Auth::user()->id;
        $cart = Cart::where('user_id', $user_id)->count();
        return $cart;
    }

    public function updatequantity($qty_id, $scope)
    {
        if (auth()->user()) {
            $user_id = Auth::user()->id;
            $cartitem = Cart::where('id', $qty_id)->where('user_id', $user_id)->first();
            if ($scope == 'inc') {
                $cartitem->product_qty += 1;
            } else if ($scope == 'dec') {
                $cartitem->product_qty -= 1;
            }
            $cartitem->update();
        }
    }

    public function destroy($id)
    {
        return Cart::findOrFail($id)->delete();
    }
}
