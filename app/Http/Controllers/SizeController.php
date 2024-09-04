<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::all();
        return $sizes;
    }

    public function addSizes(Request $request)
    {
        $sizename = $request->name;
        $sizeproduct = $request->product_id;
        $sizequantity = $request->quantity;

        $size = new Size();
        $request->validate([
            'name' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        $sizecreated = $size->create([
            'name' => $sizename,
            'product_id' => $sizeproduct,
            'quantity' => $sizequantity,
        ]);
        return $sizecreated;
    }

    public function showSize($id)
    {
        return Size::where('product_id', $id)->get();
    }
}
