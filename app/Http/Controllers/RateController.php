<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $allrates = Rate::where('user_id', $user_id)->get();
        return $allrates;
    }

    public function store(Request $request)
    {
        $rate = new Rate();
        $request->validate([
            'user_id' => 'required',
            'product_id' => 'required',
            'product_rate' => 'required',
            'description' => 'nullable'
        ]);
        $ratecreate = $rate->create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'product_rate' => $request->product_rate,
            'description' => $request->description,
        ]);
        $ratecreate->save();

        $ratecount = Rate::where('product_id', $request->product_id)->count();
        $ratestarcount = Rate::where('product_id', $request->product_id)->sum('product_rate');
        $calcualterates = $ratestarcount / $ratecount;

        $product = Product::findOrFail($request->product_id);
        $product->rating = $calcualterates;
        $product->save();
    }

    public function show(Request $request)
    {
        $allrates = Rate::with('users')->where('product_id', $request->id)->limit('8')->get();
        return $allrates;
    }
}
