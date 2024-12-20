<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{

    public function placeorder(Request $request)
    {
        if (auth()->user()) {
            $request->validate([
                'address_id' => 'required|max:191',
                'payment_mode' => 'required|max:191',
                'productsprice' => 'required|max:191',
                'vat' => 'required|max:191',
                'fees' => 'required|max:191',
                'totalprice' => 'required|max:191',
                'coupon_id' => 'required|max:191',
            ]);

            $user_id = Auth::user()->id;

            $address = Address::where('id', $request->address_id)->first();

            $order = new Order;
            $order->user_id = $user_id;
            $order->firstname = $address->firstname;
            $order->lastname = $address->lastname;
            $order->phone = "+966" . $address->phone;
            $order->email = Auth::user()->email;
            $order->totalprice = $request->totalprice;
            $order->address = $address->address;
            $order->city = $address->city;
            $order->zipcode = $address->zipcode;
            $order->payment_mode = $request->payment_mode;
            $order->coupon_id = $request->coupon_id;
            $order->slug = Str::random(14);
            $order->tracking_no = rand(1111111111, 9999999999);
            $order->save();

            $cart = Cart::where('user_id', $user_id)->get();

            $orderitems = [];
            foreach ($cart as $item) {
                $orderitems[] = [
                    'product_id' => $item->product_id,
                    'product_slug' => $item->product_slug,
                    'product_title' => $item->product->title,
                    'product_image' => $item->product_image,
                    'qty' => $item->product_qty,
                    'price' => $item->product->discount > 0 ? $item->product->discount : $item->product->price,
                    'size' => $item->sizes[0]->title
                ];

                $item->sizes[0]->update([
                    'quantity' => $item->sizes[0]->quantity - $item->product_qty
                ]);
            }

            $order->orderitems()->createMany($orderitems);

            $payments = new Payment;
            $payments->order_id = $order->id;
            $payments->payment_mode = $request->payment_mode;
            $payments->productsprice = $request->productsprice;
            $payments->vat = $request->vat;
            $payments->fees = $request->fees;
            $payments->total_price = $request->totalprice + ($request->fees * 1);
            $payments->status = 0;
            $payments->save();

            $order->payment_id = $payments->id;
            $order->save();

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
        $orders = Order::with('OrderItems')->with('Payment')->with('Coupon')->where('user_id', $user_id)->limit('1')->latest('id')->get();
        return $orders;
    }
}
