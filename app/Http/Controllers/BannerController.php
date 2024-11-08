<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        return Banner::get();
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
            'url' => 'required'
        ]);
        $banner->url = $request->url;
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
        ]);
        $banner->url = $request->url;
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
        return Banner::orderBy('id', 'DESC')->get();
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
    }
}
