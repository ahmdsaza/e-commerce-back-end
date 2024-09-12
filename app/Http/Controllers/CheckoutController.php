<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function placeorder(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'firstname' => 'required|max:191',
                'lastname' => 'required|max:191',
                'phone' => 'required|max:191',
                'email' => 'required|max:191',
                'address' => 'required|max:191',
                'city' => 'required|max:191',
                'zipcode' => 'required|max:191',
                'payment_mode' => 'required|max:191',
            ]);

            $user_id = Auth::user()->id;

            $order = new Order;
            $order->user_id = $user_id;
            $order->firstname = $request->firstname;
            $order->lastname = $request->lastname;
            $order->phone = $request->phone;
            $order->email = $request->email;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->zipcode = $request->zipcode;
            $order->payment_mode = $request->payment_mode;
            $order->tracking_no = rand(1111111111, 9999999999);
            $order->save();

            $cart = Cart::where('user_id', $user_id)->get();

            $orderitems = [];
            foreach ($cart as $item) {
                $orderitems[] = [
                    'product_id' => $item->product_id,
                    'product_title' => $item->product->title,
                    'product_image' => $item->product_image,
                    'qty' => $item->product_qty,
                    'price' => $item->product->discount,
                    'size' => $item->sizes[0]->name
                ];

                $item->sizes[0]->update([
                    'quantity' => $item->sizes[0]->quantity - $item->product_qty
                ]);
            }

            $order->orderitems()->createMany($orderitems);
            Cart::destroy($cart);
        } else {
            return response()->json()([
                'status' => 401,
                'message' => 'Login First'
            ]);
        }
    }
    public function getLastOrder()
    {
        $user_id = Auth::user()->id;
        $orders = Order::with('OrderItems')->where('user_id', $user_id)->limit('1')->latest('id')->get();
        return $orders;
    }
}
