<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    use FileUploadTrait;
    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:64',
            'date_from' => 'nullable|date_format:d-m-Y',
            'date_to' => 'nullable|date_format:d-m-Y|after_or_equal:date_from',
            'sort' => 'nullable|string',
            'perpage' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $perPage = $request->input('perpage', 10);
        $page = (int) $request->input('page', 1);

        $filterQuery = Blog::query();
        if ($request->filled('title')) {
            $filterQuery->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->filled('author')) {
            $filterQuery->where('author', 'like', '%' . $request->input('author') . '%');
        }

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        if ($dateFrom && $dateTo) {
            $filterQuery->whereBetween('created_at', [
                Carbon::createFromFormat('d-m-Y', $dateFrom)->format('Y-m-d'),
                Carbon::createFromFormat('d-m-Y', $dateTo)->format('Y-m-d'),
            ]);
        } elseif ($dateFrom) {
            $filterQuery->where('created_at', '>=', Carbon::createFromFormat('d-m-Y', $dateFrom)->format('Y-m-d'));
        } elseif ($dateTo) {
            $filterQuery->where('created_at', '<=', Carbon::createFromFormat('d-m-Y', $dateTo)->format('Y-m-d'));
        }

        $sort = $request->input('sort','default');
        if ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc');
        } else if ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc');
        }
        $filterQuery->orderBy('id', 'desc');

        $totcount = $filterQuery->count();

        if($totcount>0)
        {
            $totpage = ceil($totcount / $perPage);
            if ($page > $totpage) {
                return redirect()->to(URL::current() . '?' . http_build_query(array_merge($request->all(), ['page' => $totpage])));
            } else if ($page < 1) {
                return redirect()->to(URL::current() . '?' . http_build_query(array_merge($request->all(), ['page' => 1])));
            }
        }

        $items = $filterQuery->paginate($perPage);
        return view('blog.list', compact('items'));
    }
    public function detail(Request $request, $ulink)
    {
        $blog = Blog::where('ulink', $ulink)->first();
        return view('blog.detail', compact('blog'));
    }
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'date_from' => 'nullable|date_format:d-m-Y',
            'date_to' => 'nullable|date_format:d-m-Y|after_or_equal:date_from',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $perPage = $request->input('perpage', 15);
        $page = (int) $request->input('page', 1);

        $filterQuery = Blog::query();
        if ($request->filled('title')) {
            $filterQuery->where('title', 'like', '%' . $request->input('title') . '%');
        }
        if ($request->filled('author')) {
            $filterQuery->where('author', 'like', '%' . $request->input('author') . '%');
        }
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        if ($dateFrom && $dateTo) {
            $filterQuery->whereBetween('created_at', [
                Carbon::createFromFormat('d-m-Y g:i A', $dateFrom)->format('Y-m-d H:i:s'),
                Carbon::createFromFormat('d-m-Y g:i A', $dateTo)->format('Y-m-d H:i:s'),
            ]);
        } elseif ($dateFrom) {
            $filterQuery->where('created_at', '>=', Carbon::createFromFormat('d-m-Y g:i A', $dateFrom)->format('Y-m-d H:i:s'));
        } elseif ($dateTo) {
            $filterQuery->where('created_at', '<=', Carbon::createFromFormat('d-m-Y g:i A', $dateTo)->format('Y-m-d H:i:s'));
        }

        $sort = $request->input('sort','default');
        if ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc');
        } else if ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc');
        }
        $filterQuery->orderBy('id', 'desc');

        $totcount = $filterQuery->count();

        if($totcount>0)
        {
            $totpage = ceil($totcount / $perPage);
            if ($page > $totpage) {
                return redirect()->to(URL::current() . '?' . http_build_query(array_merge($request->all(), ['page' => $totpage])));
            } else if ($page < 1) {
                return redirect()->to(URL::current() . '?' . http_build_query(array_merge($request->all(), ['page' => 1])));
            }
        }

        $items = $filterQuery->paginate($perPage);
        return view('admin.blog.index', compact('items'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string|max:255',
            'ulink'     => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new Blog)->getTable(),
            'content'   => 'required',
            'mtit'      => 'required|string',
            'mdes'      => 'required|string',
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'post_date' => 'required',
            'author'    => 'required|string',
            'status'    => 'required|in:active,archive,draft'
            
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only(['title','ulink','content','mtit','mdes','author','status']);
        if($request->input('post_date')){
            $storeData['post_date'] = Carbon::createFromFormat('d-m-Y', $request->input('post_date'))->format('Y-m-d');
        }
        
        if($request->hasFile('image'))
        {
            $metadata = [
                'prefix' => 'blog-',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/blog/', 'image', $metadata);
            if ($fileName) {
                $storeData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if($request->hasFile('ogimage'))
        {
            $metadata = [
                'prefix' => 'career',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/blog/ogimage/', 'image', $metadata);
            if ($fileName) {
                $storeData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        $blog = Blog::create($storeData);
        if($blog)
        {
            return redirect(route('admin.blog.index'))->with('success', 'Blog has been created!');
        } else
        {
            return back()->with('error', 'Error creating blog, please try again or contact our support team')->withInput();
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        $blog=Blog::find($id);
        if ($blog === null) {
            return redirect(route('admin.blog.index'))->withInput($request->all())->with('error', 'The blog does not exist');
        }
        return view('admin.blog.edit',compact('blog'));
    }

    public function update(Request $request, string $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return redirect(route('admin.blog.index'))->with('error', 'The blog does not exist.');
        }
        $validator = Validator::make($request->all(), [
            'title'     => 'required|string|max:255',
            'ulink' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new Blog)->getTable().',ulink,'.$id,
            'content'   => 'required',
            'mtit'      => 'required|string',
            'mdes'      => 'required|string',
            'image'     => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'post_date' => 'required',
            'author'    => 'required|string',
            'status'    => 'required|in:active,archive,draft'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData = $request->only(['title','ulink','content','mtit','mdes','author','status']);
        if($request->input('post_date')){
            $updateData['post_date'] = Carbon::createFromFormat('d-m-Y', $request->input('post_date'))->format('Y-m-d');
        }
        
        if($request->hasFile('image'))
        {
            $oldname=$blog->image;
            $metadata = [
                'prefix' => 'career',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 100, 'path' => 'thumb/']
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/blog/', 'image', $metadata);
            if ($fileName) {
                $updateData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        if($request->hasFile('ogimage'))
        {
            $oldname=$blog->ogimage;
            $metadata = [
                'prefix' => 'career',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
                'deletefile' => $oldname,
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/blog/ogimage/', 'image', $metadata);
            if ($fileName) {
                $updateData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        $blog = blog::whereId($id)->update($updateData);
        if($blog)
        {
            return redirect(route('admin.blog.index'))->with('success', 'Blog has been updated!');
        } else
        {
            return back()->with('error', 'Error updating blog, please try again or contact our support team')->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $blog = Blog::find($id);
        if($blog) {
            $blog->delete();
            $previousUrl = URL::previous();
            
            $delFiles = [];
            if (!empty($blog->image)) {
                $delFiles[] = public_path('image/blog/'.$blog->image);
                $delFiles[] = public_path('image/blog/thumb/'.$blog->image);
            }
            if (!empty($blog->ogimage)) {
                $delFiles[] = public_path('image/blog/ogimage/'.$blog->ogimage);
            }
            foreach ($delFiles as $delFile) {
                if (!empty($delFile) && file_exists($delFile)) {
                    unlink($delFile);
                }
            }

            if (Str::contains($previousUrl, route('admin.blog.edit', $id))) {
                return redirect()->route('admin.blog.index')->with('success', 'Blog has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Blog has been deleted');
            }
        } else {
            return redirect(route('admin.blog.index'))->with('error', 'The blog does not exist');
        }
    }
}
