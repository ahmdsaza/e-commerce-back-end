<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        return Banner::where('status', 1)->get();
    }

    public function showindashboard(Request $request)
    {
        return Banner::orderBy('id', 'DESC')->get();
    }

    public function showbanner(Request $request, $id)
    {
        return Banner::findOrFail($id);
    }

    public function create(Request $request)
    {
        $banner = new Banner();
        $request->validate([
            'image' => 'required|image',
            'url' => 'required',
            'status' => 'required',
            'description' => 'required'
        ]);
        $banner->url = $request->url;
        $banner->status = $request->status;
        $banner->description = $request->description;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $path = 'images';
            $file->move($path, $filename);
            $banner->image = url('/') . '/images/' . $filename;
        }
        $banner->save();
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $request->validate([
            'url' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);
        $banner->url = $request->url;
        $banner->status = $request->status;
        $banner->description = $request->description;
        if ($request->hasFile('image')) {
            $oldpath = public_path() . '/images/' . substr($banner['image'], strrpos($banner['image'], '/') + 1);

            if (File::exists($oldpath)) {
                File::delete($oldpath);
            }
            $file = $request->file('image');
            $filename = date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $banner->image = url('/') . '/images/' . $filename;
            $path = 'images';
            $file->move($path, $filename);
        }
        $banner->save();
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
    }
}
