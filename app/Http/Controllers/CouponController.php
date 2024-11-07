<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $allcoupons = Coupon::orderBy('id', 'DESC')->get();
        $coupons = Coupon::orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $coupons : $allcoupons;
        return $finalResult;
    }

    public function show($id)
    {
        $coupon = Coupon::where('id', $id)->first();
        return $coupon;
    }

    public function checkcoupon($title)
    {
        $coupon = Coupon::where('title', $title)->first();
        return $coupon;
    }

    public function store(Request $request)
    {
        $coupon = new Coupon();
        $request->validate([
            'title' => 'required',
            'percent' => 'required',
            'lowest_price' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required'
        ]);
        $coupon->title = $request->title;
        $coupon->percent = $request->percent;
        $coupon->lowest_price = $request->lowest_price;
        $coupon->start_date = $request->start_date;
        $coupon->expire_date = $request->expire_date;
        $coupon->save();
    }
    public function editcoupon(Request $request)
    {
        $coupon = new Coupon();
        $request->validate([
            'title' => 'required',
            'percent' => 'required',
            'lowest_price' => 'required',
            'start_date' => 'required',
            'expire_date' => 'required'
        ]);
        $coupon->update([
            'title' => $request->title,
            'percent' => $request->percent,
            'lowest_price' => $request->lowest_price,
            'start_date' => $request->start_date,
            'expire_date' => $request->expire_date
        ]);
        $coupon->save();
    }
    public function destroy($id)
    {
        return Coupon::findOrFail($id)->delete();
    }
}
