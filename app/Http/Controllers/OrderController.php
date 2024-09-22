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
        // Count
        $ordersCount = Order::count();
        $ordersPending = Order::where('status', '=', '0')->count();
        $ordersCompleted = Order::where('status', '=', '3')->count();
        $ordersCancelled = Order::where('status', '=', '5')->count();

        // Total Amount
        $ordersAmount = Order::where('status', '!=', '5')->sum('totalprice');
        $ordersPendingAmount = Order::where('status', '=', '0')->sum('totalprice');
        $ordersCompletedAmount = Order::where('status', '=', '3')->sum('totalprice');
        $ordersCancelledAmount = Order::where('status', '=', '5')->sum('totalprice');

        $orderscall = [
            'orderscount' => $ordersCount,
            'orderspending' => $ordersPending,
            'ordercompleted' => $ordersCompleted,
            'orderscancelled' => $ordersCancelled,
            'ordersamount' => $ordersAmount,
            'orderspendingamount' => $ordersPendingAmount,
            'ordercompletedamount' => $ordersCompletedAmount,
            'orderscancelledamount' => $ordersCancelledAmount,
        ];

        return $orderscall;
    }
}
