<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Rate;
use App\Models\Size;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allProducts = Product::with('Images')->orderBy('id', 'DESC')->with('Rate')->get();
        $products = Product::with('Images')->with('Rate')->where('status', '=', 'published')->orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $products : $allProducts;
        return $finalResult;
    }


    public function getLastSaleProducts(Request $request)
    {
        $products = Product::with('Images')->with('Rate')->where('status', '=', 'published')->where('discount', '>', '0')->latest()->take(8)->get();
        return $products;
    }


    public function getLatest(Request $request)
    {
        $products = Product::with('Images')->with('Rate')->orderBy('id', 'DESC')->where('status', '=', 'published')->latest()->take(8)->get();
        return $products;
    }

    public function getTopRated(Request $request)
    {
        $products = Product::with('Images')->with('Rate')->where('status', '=', 'published')->where('rating', '>=', '4.5')->latest()->take(8)->get();
        return $products;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required | numeric',
            'discount' => 'required | numeric',
            'About' => 'required'

        ]);
        $productCreated = $product->create([
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'About' => $request->About,
            'discount' => $request->discount,
            'slug' => Str::random(14)
        ]);
        return $productCreated;
    }

    public function addSizes(Request $request)
    {
        $sizetitle = $request->title;
        $sizeproduct = $request->product_id;
        $sizequantity = $request->quantity;

        $size = new Size();
        $request->validate([
            'title' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
        ]);
        $sizecreated = $size->create([
            'title' => $sizetitle,
            'product_id' => $sizeproduct,
            'quantity' => $sizequantity,
        ]);
        return $sizecreated;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Product::where('slug', $id)->with('Images')->with('Rate')->with('Sizes')->where('status', '=', 'published')->get();
    }

    public function showRelated($id)
    {
        $product = Product::where('slug', $id)->first();
        $productcategory = $product->category;
        $getProduct = Product::where('slug', '!=', $id)->where('category', '=', $productcategory)->with('Images')->where('status', '=', 'published')->get();
        return $getProduct;
    }

    public function showCategory($id)
    {
        return Product::where('id', $id)->with('Images')->with('Rate')->with('Sizes')->where('status', '=', 'published')->get();
    }

    public function showSize($id)
    {
        return Size::where('product_id', $id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required | numeric',
            'discount' => 'required | numeric',
            'About' => 'required',
        ]);
        $product->update([
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'About' => $request->About,
            'discount' => $request->discount,
        ]);
        $product->status = 'published';
        $product->save();
        $productId = $product->id;
        if ($request->hasFile('images')) {
            $files = $request->file("images");
            $i = 0;
            foreach ($files as $file) {
                $i = $i + 1;
                $image = new ProductImage();
                $image->product_id = $productId;
                $filename = date('YmdHis') . $i . '.' . $file->getClientOriginalExtension();
                $path = 'images';
                $file->move($path, $filename);
                $image->image = url('/') . '/images/' . $filename;
                $image->save();
            }
        }
    }

    // Search On Users
    public function search(Request $request)
    {
        $query = $request->input('title');
        $results = Product::with('Images')->where('title', 'like', "%$query%")->limit(10)->get();
        return response()->json($results);
    }

    public function updateSize(Request $request, $id)
    {
        $size = Size::findOrFail($id);
        $request->validate([
            'title' => 'required',
            'quantity' => 'required',
        ]);
        $size->update([
            'title' => $request->title,
            'quantity' => $request->quantity,
        ]);
        $size->save();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productImages = ProductImage::where('product_id', '=', $id)->get();
        foreach ($productImages as $productImage) {
            $path = public_path() . '/images/' . substr($productImage['image'], strrpos($productImage['image'], '/') + 1);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        DB::table('products')->where('id', '=', $id)->delete();
        DB::table('rates')->where('product_id', '=', $id)->delete();
    }

    public function destroysize($id)
    {
        return  Size::findOrFail($id)->delete();
    }
}
