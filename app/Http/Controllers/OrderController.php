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
        $allorders = Order::with('OrderItems')->orderBy('id', 'DESC')->where('user_id', $user_id)->get();
        $orders = Order::with('OrderItems')->where('user_id', $user_id)->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $orders : $allorders;
        return $finalResult;

        // return $allorders;
    }
    public function show($id)
    {
        return Order::where('id', $id)->with('OrderItems')->with('Payment')->get();
    }
    public function showorders(Request $request)
    {
        // $allorders = Order::with('OrderItems')->get();
        $orders = Order::with('OrderItems')->with('users')->with('Payment')->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $query = $request->input('status');
        $orderssort = Order::with('OrderItems')->with('users')->with('Payment')->where('status', '=', $query)->orderBy('id', 'desc')->paginate($request->input('limit', 10));
        return $query ? $orderssort : $orders;
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

    // Showing Orders

    public function showorderscount()
    {
        $orders = Order::count();
        return $orders;
    }
    public function showpendingorderscount()
    {
        $orders = Order::where('status', '=', '0')->count();
        return $orders;
    }
    public function showcompletedorderscount()
    {
        $orders = Order::where('status', '=', '3')->count();
        return $orders;
    }
    public function showcancelledorderscount()
    {
        $orders = Order::where('status', '=', '5')->count();
        return $orders;
    }

    // Showing Orders Amount

    public function showorderssum()
    {
        $orders = Order::where('status', '!=', '5')->sum('totalprice');
        return $orders;
    }
    public function showpendingorderssum()
    {
        $orders = Order::where('status', '=', '0')->sum('totalprice');
        return $orders;
    }
    public function showcompletedorderssum()
    {
        $orders = Order::where('status', '=', '3')->sum('totalprice');
        return $orders;
    }
    public function showcancelledorderssum()
    {
        $orders = Order::where('status', '=', '5')->sum('totalprice');
        return $orders;
    }
}
