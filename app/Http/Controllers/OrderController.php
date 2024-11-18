<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // $user_id = Auth::user()->id;
        $user_id = Auth::user()->id;
        $orders = Order::with('OrderItems')->with('users')->where('user_id', $user_id)->with('Payment')->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        // $query = $request->input('status');
        $orderssort = Order::with('OrderItems')->where('user_id', $user_id)->with('users')->with('Payment')->where('status', '=', $request->input('status'))->orderBy('id', 'desc')->paginate($request->input('limit', 10));
        return $request->input('status') ? $orderssort : $orders;

        // return $allorders;
    }
    public function show($id)
    {
        return Order::where('slug', $id)->with('OrderItems')->with('Payment')->with('Coupon')->get();
    }
    public function showorders(Request $request)
    {
        $orders = Order::with('OrderItems')->with('users')->with('Payment')->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        // $query = $request->input('status');
        $orderssort = Order::with('OrderItems')->with('users')->with('Payment')->where('status', '=', $request->input('status'))->orderBy('id', 'desc')->paginate($request->input('limit', 10));
        return $request->input('status') ? $orderssort : $orders;
    }

    public function lastorders()
    {
        $orders = Order::with('users')->with('Payment')->orderBy('id', 'DESC')->limit('6')->get();
        return $orders;
    }

    public function destroy($id)
    {
        return  Order::findOrFail($id)->delete();
    }
    public function update(Request $request, $id)
    {
        $order = Order::where('slug', $id)->first();
        $request->validate([
            'status' => 'required'
        ]);
        $order->update([
            'status' => $request->status,
        ]);
        $order->save();
    }

    public function cancelorder(Request $request, $id)
    {
        $order = Order::where('slug', $id)->first();
        $order->status = 5;
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

        // Products & Users

        $users = User::count();
        $product = Product::count();

        $orderscall = [
            'orderscount' => $ordersCount,
            'orderspending' => $ordersPending,
            'ordercompleted' => $ordersCompleted,
            'orderscancelled' => $ordersCancelled,
            'ordersamount' => number_format($ordersAmount, 2),
            'orderspendingamount' => number_format($ordersPendingAmount, 2),
            'ordercompletedamount' => number_format($ordersCompletedAmount, 2),
            'orderscancelledamount' => number_format($ordersCancelledAmount, 2),
            'UsersCount' => $users,
            2,
            'ProductsCount' => $product,
            2,
        ];

        return $orderscall;
    }
}
