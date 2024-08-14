<?php

namespace App\Http\Controllers;

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
    }

    public function show(Request $request)
    {
        $allrates = Rate::where('product_id', $request->id)->get();
        $ratecount = Rate::where('product_id', $request->id)->count();

        return $allrates;
    }
}