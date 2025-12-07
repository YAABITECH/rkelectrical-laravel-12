<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Models\CourseVideo;
use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CourseVideoController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'topic' => 'nullable',
            'demo' => 'nullable',
            'course_id' => 'nullable',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $perPage = $request->input('perpage', 10);
        $page = (int) $request->input('page', 1);

        $filterQuery = CourseVideo::query();
        if ($request->filled('topic')) {
            $filterQuery->where('topic', 'like', '%' . $request->input('topic') . '%');
        }
        if ($request->filled('demo')) {
            $filterQuery->where('demo', 'like', '%' . $request->input('demo') . '%');
        }
        if ($request->filled('course_id')) {
            $filterQuery->where('course_id', $request->input('course_id'));
        }

        $sort = $request->input('sort','default');
        if ($sort == 'newest') {
            $filterQuery->orderBy('post_date', 'desc');
        } else if ($sort == 'oldest') {
            $filterQuery->orderBy('post_date', 'asc');
        }
        $filterQuery->orderBy('id', 'desc');

        if ($request->filled('perpage')) {
            $perPage = $request->input('perpage');
        }

        $totcount = $filterQuery->count();

        if($totcount>0)
        {
            $totpage = ceil($totcount / $perPage);
            if ($page > $totpage) {
                return redirect()->to(url()->current() . '?' . http_build_query(array_merge($request->all(), ['page' => $totpage])));
            } else if ($page < 1) {
                return redirect()->to(url()->current() . '?' . http_build_query(array_merge($request->all(), ['page' => 1])));
            }
        }

        $videos = $filterQuery->paginate($perPage);
        $totalCount = $videos->total();
        $totalPages = $videos->lastPage();
        $count = $videos->count();
        $courses = Course::all();
        return view('admin.video.index', compact('courses','videos','totalCount','totalPages','count','page'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('admin.video.create',compact('courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'description'=>'required|string',
            'demo'=>'required|integer',
            'video'=>'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData =$request->only(['topic','course_id','description','demo','video']);
        $maxPriority = CourseVideo::max('priority');
        $priority = $maxPriority + 1;
        $storeData['priority'] = $priority;
        $video = CourseVideo::create($storeData);
        if($video)
        {
            return redirect(route('admin.video.index'))->with('success', 'Course Chapter has been Updated!');
        } else
        {
            return back()->with('error', 'Error updating Course Chapter, please try again or contact our support team')->withInput();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $video = CourseVideo::find($id);
        $courses = Course::all();
        if ($video === null) {
            return redirect(route('admin.video.index'))->with('error', 'The course chapter does not exist');
        }
        return view('admin.video.edit', compact('video','courses'));
    }

    public function update(Request $request, $id)
    {
        $video = CourseVideo::find($id);
        if ($video === null) {
            return redirect(route('admin.video.index'))->with('error', 'The Course Chapter does not exist');
        }
        $validator = Validator::make($request->all(), [
            'topic' => 'required|string',
            'course_id' => 'required|exists:courses,id',
            'description'=>'required|string',
            'demo'=>'required|integer',
            'video'=>'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData =$request->only(['topic','course_id','description','demo','video']);
        $video = CourseVideo::whereId($id)->update($updateData);
        if($video)
        {
            return redirect(route('admin.video.index'))->with('success', 'Course Chapter has been Updated!');
        } else
        {
            return back()->with('error', 'Error updating Course Chapter, please try again or contact our support team')->withInput();
        }
    }

    public function destroy(Request $request, $id)
    {
        $video = CourseVideo::find($id);
        if($video) {
            $videoid=$video->id;
            $video->delete();
            $previousUrl = url()->previous();

            if (Str::contains($previousUrl, route('admin.video.edit', $videoid))) {
                return redirect()->route('admin.video.index')->with('success', 'Course Chapter has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Course Chapter has been deleted');
            }
        } else {
            return redirect(route('admin.video.index'))->with('error', 'The course chapter does not exist');
        }
    }
}
