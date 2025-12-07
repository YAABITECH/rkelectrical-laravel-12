<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\Testimonial;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class TestimonialController extends Controller
{
    use FileUploadTrait;
    public function testimonial_index()
    {
        $testimonials = Testimonial::get();

        return view('testimonial.index',compact('testimonials'));
    }
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sort' => 'nullable',
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'name' => 'nullable|string|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $perPage = 10;
        $page = $request->input('page', 1);
        $page = (int) $page;
        $filterQuery = testimonial::query();
        if ($request->filled('name')) {
            $filterQuery->where('name', 'like', '%' . $request->input('name') . '%');
        }
        $sort = $request->input('sort', 'default');

        if ($sort == 'default') {
            $filterQuery->orderBy('id', 'asc');
        } elseif ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        } elseif ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        }

        if ($request->filled('perpage')) {
            $perPage = $request->input('perpage');
        }
        $testimonials = $filterQuery->paginate($perPage);
        $totalCount = $testimonials->total();
        $totalPages = $testimonials->lastPage();
        $count = $testimonials->count();
        if($totalCount>0)
        {
            if($page>$totalPages || $page<1)
            {
                $redirectUrl='/admin/testimonial';
                $requestParams = $request->all();
                if($page>$totalPages)
                {
                    $requestParams['page'] = $totalPages;
                } else
                {
                    $requestParams['page'] = 1;
                }
                $queryString = http_build_query($requestParams);

                if (!empty($queryString)) {
                    return redirect()->to($redirectUrl . '?' . $queryString);
                } else {
                    return redirect()->to($redirectUrl);
                }
            }
        }
        return view('admin.testimonial.index', compact('testimonials', 'totalCount', 'totalPages', 'count', 'page'));
    }
    public function create()
    {
        $testimonial = Testimonial::get();
        return view('admin.testimonial.create',compact('testimonial'));
    }
    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'content'=>'required|string',
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
                'star' => 'integer',
            ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only(['name','content','star']);
        if($request->hasFile('photo'))
        {
            $metadata = [
                'prefix' => 'student-',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'photo', 'image/testimonial/', 'image', $metadata);
            if ($fileName) {
                $storeData['photo'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $testimonial = Testimonial::create($storeData);
        if($testimonial)
        {
            return redirect(route('admin.testimonial.index'))->with('success', 'Testimonial has been created!');
        } else
        {
            return back()->with('error', 'Error creating Testimonial, please try again or contact our support team')->withInput();
        }
    }
    public function show()
    {

    }
    public function edit($id)
    {
        $testimonial = Testimonial::find($id);
        if (is_null($testimonial)) {
            return redirect()->back()->with('error', 'The Testimonial does not exist');
        }
        return view('admin.testimonial.edit', compact('testimonial','id'));
    }

    public function update(Request $request, $id)
    {
        $testimonial=Testimonial::find($id);
        if ($testimonial === null) {
            return redirect(route('admin.testimonial.index'))->with('error', 'The Testimonial does not exist');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'content'=>'required|string',
            'star'=>'integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData =$request->only(['name','content','star']);
        if($request->hasFile('photo'))
        {
            $oldname=$testimonial->photo;
            $metadata = [
                'prefix' => 'student-',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'photo', 'image/testimonial/', 'image', $metadata);
            if ($fileName) {
                $updateData['photo'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        $testimonial = Testimonial::whereId($id)->update($updateData);
        if($testimonial)
        {
            return redirect(route('admin.testimonial.index'))->with('success', 'Testimonial has been Updated!');
        } else
        {
            return back()->with('error', 'Error updating Testimonial, please try again or contact our support team')->withInput();
        }
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::find($id);

        if ($testimonial) {
            $oldname = $testimonial->photo;
            $oldimage = public_path('image/testimonial/' . $oldname);
            $oldthumb = public_path('image/testimonial/thumb/' . $oldname);

            if (file_exists($oldimage)) {
                unlink($oldimage);
            }
            if (file_exists($oldthumb)) {
                unlink($oldthumb);
            }

            $testimonial->delete();
            return redirect()->route('admin.testimonial.index')->with('success', 'Testimonial has been deleted');
        } else {
            return redirect()->route('admin.testimonial.index')->with('error', 'The Testimonial does not exist');
        }
    }

}
