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
        return  Order::where('id', $id)->with('OrderItems')->get();
    }
    public function showorders(Request $request)
    {
        // $allorders = Order::with('OrderItems')->get();
        $orders = Order::with('OrderItems')->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $query = $request->input('status');
        $orderssort = Order::with('OrderItems')->where('status', '=', $query)->orderBy('id', 'desc')->paginate($request->input('limit', 10));
        $finalResult = $orderssort;
        return $query ? $finalResult : $orders;
    }
    public function showorderscount()
    {
        $orders = Order::count();
        return $orders;
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
