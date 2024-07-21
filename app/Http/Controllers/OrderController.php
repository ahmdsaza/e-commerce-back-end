<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $allorders = Order::with('OrderItems')->where('user_id', $user_id)->get();
        return $allorders;
    }
    public function show($id)
    {
        return  Order::where('id', $id)->with('OrderItems')->get();
    }
    public function showorders()
    {
        return Order::with('OrderItems')->get();
    }
    public function destroy($id)
    {
        return  Order::findOrFail($id)->delete();
    }
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required'
        ]);
        $order->update([
            'status' => $request->status,
        ]);
        $order->save();
    }
}
