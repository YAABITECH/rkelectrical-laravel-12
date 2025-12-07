<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\CoursePack;
use App\Models\CoursePaid;
use App\Models\CourseVideo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class CourseController extends Controller
{
    public function course_Index()
    {
        $courses = Course::get();
        $course_packs=CoursePack::get();
        $course_paids=CoursePaid::get();
        return view('course.index',compact('courses','course_packs','course_paids'));
    }
    public function detail($url)
    {
        // $course_packs = CoursePack::first();
        $course = Course::where('url',$url)->first();
        $courses = Course::orderBy('id','asc')->get();
        return view('course.detail', compact( 'course','courses'));
    }
    public function coursedetail(Request $request)
    {
        $coursevideo=CourseVideo::get();
        $url = $request->query('uname');
        $course = Course::where('url', $url)->first();
        $courses = Course::get();
        $video=CourseVideo::get();
        return view('course.coursedetail', compact('course','courses','video'));
    }
    public function video(Request $request, $ulink, $chapter = null)
    {
        $user = Auth::user();
        $course = Course::where('url', $ulink)->first();
        $courseChapters = CourseVideo::where('course_id',$course->id)->get();
        if($user == null){
            return redirect()->route('user.login');
        }
        $purchased = 0;
        $coursePayment = CoursePaid::where('user_id',$user->id)->where('course_id',$course->id)->first();
        if($coursePayment){
            $purchased = 1;
            if($chapter !== null){
                $currentChapter = CourseVideo::where('id',$chapter)->where('course_id',$coursePayment->course_id)->first();
            }
            else{
                $currentChapter = CourseVideo::where('course_id',$coursePayment->course_id)->first();
            }
        }else{
            if($chapter !== null){
                $currentChapter = CourseVideo::where('id',$chapter)->where('course_id',$course->id)->first();
            }
            else{
                $currentChapter = CourseVideo::where('course_id',$course->id)->first();
            }
        }
        $previousChapter = CourseVideo::where('course_id', $course->id)
            ->where('id', '<', $currentChapter->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextChapter = CourseVideo::where('course_id', $course->id)
            ->where('id', '>', $currentChapter->id)
            ->orderBy('id')
            ->first();
        return view('course.video.index',compact('courseChapters','course','currentChapter', 'previousChapter', 'nextChapter','purchased'));
    }
    public function purchase(Request $request,$coursepack = 1, $ulink = null)
    {
        $user = Auth::user();
        if($user == null){
            return redirect()->route('user.login');
        }
        $previousUrl = url()->previous();
        $course = null;
        $coursepack = CoursePack::where('id',$coursepack)->first();
        $coursepacks = CoursePack::all();
        $purchasedCourseIds = $user->getPurchasedCourses->pluck('course_id')->toArray(); // Assuming you have a relationship like this
        $purchasedCoursePackIds = $user->getPurchasedCourses->pluck('coursepack_id')->toArray(); // Assuming you have a relationship like this
        if($ulink !== null){
            $course = Course::where('url',$ulink)->first();
        }
        $courses = Course::where('course_pack',$coursepack)->get();
        return view('page.purchase',compact('courses','course','coursepack','coursepacks','user','previousUrl','purchasedCourseIds','purchasedCoursePackIds'));
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
        $filterQuery = course::query();
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
        $courses = $filterQuery->paginate($perPage);
        $totalCount = $courses->total();
        $totalPages = $courses->lastPage();
        $count = $courses->count();
        if($totalCount>0)
        {
            if($page>$totalPages || $page<1)
            {
                $redirectUrl='/admin/course';
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
        return view('admin.course.index', compact('courses', 'totalCount', 'totalPages', 'count', 'page'));

    }

    public function create()
    {
        return view('admin.course.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'url' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new Course)->getTable(),
            'description'=>'required|string',
            'fees'=>'required',
            'coursedur'=>'required',
            'classdur'=>'required',
            'metadesc'=>'required',
            'metatitle'=>'required',
            'course_pack'=>'required|integer',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:25000',
            'preview' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:25000',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only(['title','url','description','fees','coursedur','classdur','metadesc','metatitle','course_pack']);
        if($request->hasFile('photo'))
        {
            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $fileName ='-img'.uniqid().'.'.$extension;
            $img = Image::make($image->getRealPath());
            $img->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/'.$fileName));
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/thumb/'.$fileName));
            $storeData['photo'] = $fileName;
        }
        if($request->hasFile('preview'))
        {
            $image = $request->file('preview');
            $extension = $image->getClientOriginalExtension();
            $fileName ='-img'.uniqid().'.'.$extension;
            $img = Image::make($image->getRealPath());
            $img->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/preview/'.$fileName));
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $storeData['preview'] = $fileName;
        }
        $course = Course::create($storeData);
        if($course)
        {
            return redirect(route('admin.course.index'))->with('success', 'Course has been created!');
        } else
        {
            return back()->with('error', 'Error creating Course, please try again or contact our support team')->withInput();
        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $course = Course::find($id);
        if ($course === null) {
            return redirect()->back()->with('error', 'The Course does not exist');
        }
        return view('admin.course.edit', compact('course'));
    }

    public function update(Request $request, $id)
    {
        $course=Course::find($id);
        if ($course === null) {
            return redirect(route('admin.course.index'))->with('error', 'The Course does not exist');
        }
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'url' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new Course)->getTable().',url,'.$id,
            'description'=>'required|string',
            'fees'=>'required',
            'coursedur'=>'required',
            'classdur'=>'required',
            'metadesc'=>'required',
            'metatitle'=>'required',
            'course_pack'=>'required|integer',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:25000',
            'preview' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:25000',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData =$request->only(['title','url','description','fees','coursedur','classdur','metadesc','metatitle','course_pack']);

        if($request->hasFile('photo'))
        {
            $image = $request->file('photo');
            $extension = $image->getClientOriginalExtension();
            $fileName ='img'.uniqid().'.'.$extension;
            $img = Image::make($image->getRealPath());
            $img->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/'.$fileName));
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/thumbs/'.$fileName));
            $updateData['photo'] = $fileName;
            $oldname=$course->image;
            $oldimage=public_path('image/course/'.$oldname);
            $oldthumb=public_path('image/course/thumbs/'.$oldname);
            if(!empty($oldname) && file_exists($oldimage))
            {
                unlink($oldimage);
            }
            if(!empty($oldname) && file_exists($oldthumb))
            {
                unlink($oldthumb);
            }
        }

        if($request->hasFile('preview'))
        {
            $image = $request->file('preview');
            $extension = $image->getClientOriginalExtension();
            $fileName ='img'.uniqid().'.'.$extension;
            $img = Image::make($image->getRealPath());
            $img->resize(900, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $img->save(public_path('image/course/'.$fileName));
            $img->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $updateData['preview'] = $fileName;
            $oldname=$course->preview;
            $oldimage=public_path('image/course/'.$oldname);
            $oldthumb=public_path('image/course/thumbs/'.$oldname);
            if(!empty($oldname) && file_exists($oldimage))
            {
                unlink($oldimage);
            }

        }
        $course = Course::whereId($id)->update($updateData);
        if($course)
        {
            return redirect(route('admin.course.index'))->with('success', 'Course has been Updated!');
        } else
        {
            return back()->with('error', 'Error updating Course, please try again or contact our support team')->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $course = Course::find($id);
        if($course) {
            $courseid=$course->id;
            $course->delete();
            $previousUrl = url()->previous();

            $oldname=$course->image;
            $oldogname=$course->ogimage;
            $oldimage=public_path('image/course/'.$oldname);
            $oldthumb=public_path('image/course/thumb/'.$oldname);
            $oldog=public_path('image/course/preview/'.$oldogname);
            if(!empty($oldname) && file_exists($oldimage))
            {
                unlink($oldimage);
            }
            if(!empty($oldname) && file_exists($oldthumb))
            {
                unlink($oldthumb);
            }
            if(!empty($oldogname) && file_exists($oldog))
            {
                unlink($oldog);
            }
            if (Str::contains($previousUrl, route('admin.course.edit', $courseid))) {
                return redirect()->route('admin.course.index')->with('success', 'Course has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Course has been deleted');
            }
        } else {
            return redirect(route('admin.course.index'))->with('error', 'The course does not exist');
        }
    }
}
