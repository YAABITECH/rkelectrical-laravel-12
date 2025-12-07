<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Traits\FileUploadTrait;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    use FileUploadTrait;
    public function index()
    {
        $banner=Banner::get();
        $count=$banner->count();
      return view('admin.banner.index',compact('banner','count'));
    }
    public function create()
    {
        return view('admin.banner.create');
    }
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);
        
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        if($request->hasFile('photo'))
        {
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 300, 'path' => 'thumb/']
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'photo', 'image/banner/', 'image', $metadata);
            if ($fileName) {
                $storeData['photo'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $banner = Banner::create($storeData);
        if($banner)
        {
            return redirect(route('admin.banner.index'))->with('success', 'Banner has been created!');
        } else
        {
            return back()->with('error', 'Error creating banner, please try again or contact our support team')->withInput();
        }
    }
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $banner = Banner::find($id);
        if (is_null($banner)) {
            return redirect()->back()->with('error', 'The Banner does not exist');
        }
        return view('admin.banner.edit', compact('banner','id'));
    }

    public function update(Request $request, $id)
    {
        $banner=Banner::find($id);
        if ($banner === null) {
            return redirect(route('admin.banner.index'))->with('error', 'The Banner does not exist');
        }
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
           
        ]); 
    
    if ($validator->fails()) {
        return back()->withInput()->withErrors($validator->errors());
    }
    if($request->hasFile('photo'))
    {
        $photo = $request->file('photo');
        $extension = $photo->getClientOriginalExtension();
        $fileName = '-img'.uniqid().'.'.$extension;
        $img = Image::make($photo->getRealPath());
        $img->resize(900, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path('image/banner/'.$fileName));
        $img->resize(300, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->save(public_path('image/banner/thumbs/'.$fileName));
        $updateData['photo'] = $fileName;
        $oldname=$banner->photo;
        $oldimage=public_path('image/banner/'.$oldname);
        $oldthumb=public_path('image/banner/thumbs/'.$oldname);
        if(!empty($oldname) && file_exists($oldimage))
        {
            unlink($oldimage);
        }
        if(!empty($oldname) && file_exists($oldthumb))
        {
            unlink($oldthumb);
        }
    }
    $banner = Banner::whereId($id)->update($updateData);
    if($banner)
    {
        return redirect(route('admin.banner.index'))->with('success', 'The Banner has been created!');
    } else
    {
        return back()->with('error', 'Error creating Banner, please try again or contact our support team')->withInput();
    }
    }

    public function destroy($id)
    {
        $banner = Banner::find($id);
    
        if ($banner) {
            $oldname = $banner->photo;
            $oldimage = public_path('image/banner/' . $oldname);
            $oldthumb = public_path('image/banner/thumb/' . $oldname);
    
            if (file_exists($oldimage)) {
                unlink($oldimage);
            }
            if (file_exists($oldthumb)) {
                unlink($oldthumb);
            }
    
            $banner->delete();
            return redirect()->route('admin.banner.index')->with('success', 'Banner has been deleted');
        } else {
            return redirect()->route('admin.banner.index')->with('error', 'The Banner does not exist');
        }
    }
}
