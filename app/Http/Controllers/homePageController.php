<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Http\Request;

class homePageController extends Controller
{
    public function index()
    {
        return User::all();
        return Product::with('Images')->where('status', '=', 'published')->get();
    }
    public function getUser($id)
    {
        return User::findOrFail($id);
    }
}
