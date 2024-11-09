<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCategories = Category::orderBy('id', 'DESC')->get();
        $categories = Category::orderBy('id', 'DESC')->paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $categories : $allCategories;
        return $finalResult;
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
        $category = new Category();
        $request->validate([
            'title' => 'required',
            'image' => 'required|image'
        ]);
        $category->title = $request->title;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'images';
            $file->move($path, $filename);
            $category->image = url('/') . '/images/' . $filename;
        }
        $category->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {

        $allproducts = Product::where('category', $id)->with('Images')->orderBy('id', 'DESC')->where('status', '=', 'published')->get();
        // $products = Product::where('category', $id)->with('Images')->where('status', '=', 'published')->paginate($request->input('limit', 10));
        $productsSort = Product::where('category', $id)->with('Images')->where('status', '=', 'published')->orderBy($request->input('sort'), $request->input('type'))->paginate($request->input('limit', 10));
        $finalResult = $request->input('sort') ? $productsSort : $allproducts;
        return $finalResult;
    }

    public function showcategory(Request $request, $id)
    {
        return  Category::findOrFail($id);
    }

    public function productsCategory(Category $category, $id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, $id, Request $request)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'title' => 'required',
        ]);
        $category->title = $request->title;
        if ($request->hasFile('image')) {
            $oldpath = public_path() . '/images/' . substr($category['image'], strrpos($category['image'], '/') + 1);

            if (File::exists($oldpath)) {
                File::delete($oldpath);
            }
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $category->image = url('/') . '/images/' . $filename;
            $path = 'images';
            $file->move($path, $filename);
        }
        $category->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    // Search On Users
    public function search(Request $request)
    {
        $query = $request->input('title');
        $results = Category::where('title', 'like', "%$query%")->get();
        return response()->json($results);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        $path = public_path() . '/images/' . substr($category['image'], strrpos($category['image'], '/') + 1);

        if (File::exists($path)) {
            File::delete($path);
        }
        $category->delete();
    }
}
