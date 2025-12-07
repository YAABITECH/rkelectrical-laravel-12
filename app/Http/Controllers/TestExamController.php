<?php

namespace App\Http\Controllers;

use App\Models\TestExam;
use App\Models\TestSeries;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TestExamController extends Controller
{
    use FileUploadTrait;
    public function index(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'sort' => 'nullable',
            'page' => 'nullable|integer',
            'perpage' => 'nullable|integer|max:500',
            'name' => 'nullable|string|max:255',
            'status' => 'nullable|in:draft,upcoming,active,archive',
            'series_id' => 'nullable'
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $series = null;

        $perPage = $request->input('perpage', 10);
        $page = (int) $request->input('page', 1);

        $filterQuery = TestExam::query();

        if ($request->filled('name')) {
            $filterQuery->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('status')) {
            $filterQuery->where('status', $request->input('status'));
        }
        if ($request->filled('series_id')) {
            $filterQuery->where('series_id', $request->input('series_id'));
            $series = TestSeries::find($request->input('series_id'));
        }
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($dateFrom && $dateTo) {
            $filterQuery->whereBetween('start_at', [
                Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay(),
            ]);
        } elseif ($dateFrom) {
            $filterQuery->where('start_at', '>=', Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay());
        } elseif ($dateTo) {
            $filterQuery->where('start_at', '<=', Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay());
        }

        $sort = $request->input('sort', 'default');
        if ($sort == 'newest') {
            $filterQuery->orderBy('created_at', 'desc');
        } elseif ($sort == 'oldest') {
            $filterQuery->orderBy('created_at', 'asc');
        }
        $filterQuery->orderBy('priority', 'asc');

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

        $items = $filterQuery->paginate($perPage);
        return view('admin.test.exam.index', compact('items','series'));
    }
    public function create(Request $request)
    {
        $seriesId = $request->query('series_id');
        $series = null;

        if ($seriesId) {
            $series = TestSeries::find($seriesId);
            if (!$series) {
                return redirect()->route('admin.test.exam.index')->with('error', 'Invalid Test Series selected.');
            }
        } else {
            return redirect()->route('admin.test.series.index')->with('error', 'Create Test Exam inside Test Series');
        }

        return view('admin.test.exam.create', compact('series'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'series_id'        => 'required|integer|exists:' . (new TestSeries())->getTable() . ',id',
            'name'             => 'required|string|max:255',
            'tagline'          => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'instruction'      => 'nullable|string',
            'total_questions'  => 'nullable|integer|min:0',
            'marks'            => 'nullable|integer|min:0',
            'duration'         => 'nullable|integer|min:0',
            'attempt_limit'    => 'nullable|integer|min:1',
            'difficulty'       => 'required|in:easy,medium,hard,mixed',
            'status'           => 'required|in:draft,upcoming,active,archive',
            'has_negative_marks' => 'nullable|boolean',
            'start_at'         => 'nullable|string',
            'end_at'           => 'nullable|string',
            'is_demo'          => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $storeData = $request->only([
            'series_id','name','tagline','description','instruction','difficulty','status'
        ]);
        $fields = ['total_questions','marks','duration','attempt_limit'];
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $storeData[$field] = $request->input($field);
            }
        }

        $has_negative_marks = filter_var($request->input('has_negative_marks'), FILTER_VALIDATE_BOOLEAN);
        if($has_negative_marks) {
            $storeData['has_negative_marks'] = $has_negative_marks;
        }
        $is_demo = filter_var($request->input('is_demo'), FILTER_VALIDATE_BOOLEAN);
        if($is_demo) {
            $storeData['is_demo'] = $is_demo;
        }
        // Date formatting
        if ($request->input('status') == 'upcoming') {
            if ($request->start_at) {
                try {
                    $storeData['start_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->input('start_at'))->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Invalid start date format.');
                }
            }
        }
        if ($request->end_at) {
            try {
                $storeData['end_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->input('end_at'))->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Invalid end date format.');
            }
        }
        // auto priority
        $maxPriority = TestExam::where('series_id', $storeData['series_id'] ?? null)->max('priority');
        $storeData['priority'] = ($maxPriority + 1);

        $exam = TestExam::create($storeData);
        if ($exam) {
            return redirect(route('admin.test.exam.index'))
                ->with('success', 'Test exam has been created!');
        } else {
            return back()->with('error', 'Error creating Test exam, please try again')
                ->withInput();
        }
    }
    public function show(string $id)
    {
        //
    }
    public function edit(Request $request, $id)
    {
        $exam=TestExam::find($id);
        if ($exam === null) {
            return redirect()->back()->withInput($request->all())->with('error', 'The Test Exam does not exist');
        }
        return view('admin.test.exam.edit',compact('exam'));
    }
    public function update(Request $request, string $id)
    {
        $exam = TestExam::find($id);
        if (!$exam) {
            return redirect()->back()->withInput()->with('error', 'The Test Exam does not exist');
        }

        $validator = Validator::make($request->all(), [
            'name'             => 'required|string|max:255',
            'tagline'          => 'nullable|string|max:255',
            'description'      => 'nullable|string',
            'instruction'      => 'nullable|string',
            'total_questions'  => 'nullable|integer|min:0',
            'marks'            => 'nullable|integer|min:0',
            'duration'         => 'nullable|integer|min:0',
            'attempt_limit'    => 'nullable|integer|min:1',
            'difficulty'       => 'required|in:easy,medium,hard,mixed',
            'status'           => 'required|in:draft,upcoming,active,archive',
            'has_negative_marks' => 'nullable|boolean',
            'start_at'         => 'nullable|string',
            'end_at'           => 'nullable|string',
            'is_demo'          => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $updateData = $request->only([
            'name','tagline','description','instruction','difficulty','status'
        ]);

        $fields = ['total_questions','marks','duration','attempt_limit'];
        foreach ($fields as $field) {
            if ($request->filled($field)) {
                $updateData[$field] = $request->input($field);
            }
        }

        // Checkboxes
        $updateData['has_negative_marks'] = filter_var($request->input('has_negative_marks'), FILTER_VALIDATE_BOOLEAN);
        $updateData['is_demo']            = filter_var($request->input('is_demo'), FILTER_VALIDATE_BOOLEAN);

        // Dates
        if ($request->input('status') === 'upcoming') {
            if ($request->start_at) {
                try {
                    $updateData['start_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->start_at)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Invalid start date format.');
                }
            }
        } else {
            $updateData['start_at'] = null;
        }

        if ($request->end_at) {
            try {
                $updateData['end_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->end_at)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Invalid end date format.');
            }
        } else {
            $updateData['end_at'] = null;
        }

        $updated = TestExam::whereId($id)->update($updateData);

        if ($updated) {
            return redirect(route('admin.test.exam.index'))
                ->with('success', 'Test exam has been updated!');
        } else {
            return back()->with('error', 'Error updating Test exam, please try again')
                ->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $exam = TestExam::find($id);
        if ($exam) {
            $exam->delete();
            $previousUrl = URL::previous();

            if (Str::contains($previousUrl, route('admin.test.exam.edit', $id))) {
                return redirect()->route('admin.test.exam.index')->with('success', 'Test Exam has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Test Exam has been deleted');
            }
        } else {
            return redirect(route('admin.test.exam.index'))->with('error', 'The Test Exam does not exist');
        }
    }
    public function arrange(string $id)
    {
    }
}
