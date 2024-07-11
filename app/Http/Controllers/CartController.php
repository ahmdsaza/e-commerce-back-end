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
            $upadteqty = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
            $upadteqtyedit = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
            $product_qty = $request->product_qty;
            $product_image = $request->product_image;

            if ($upadteqty) {
                $request->validate([
                    'user_id' => 'required',
                    'product_id' => 'required',
                    'product_qty' => 'required',
                    'product_image' => 'required'
                ]);
                $upadteqty->update([
                    'user_id' => $request->user_id,
                    'product_id' => $request->product_id,
                    'product_qty' =>  $request->product_qty + $upadteqtyedit->product_qty,
                    'product_image' => $request->product_image
                ]);
                $upadteqty->save();
            } else {
                $cartitem = new Cart;
                $cartitem->user_id = $user_id;
                $cartitem->product_id = $product_id;
                $cartitem->product_qty = $product_qty;
                $cartitem->product_image = $product_image;

                $cartitem->save();
            }
        } else {
            return response()->json(['status' => 401, 'meassge' => 'Login to Add to Cart']);
        }
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
        return  Cart::findOrFail($id)->delete();
    }
}
