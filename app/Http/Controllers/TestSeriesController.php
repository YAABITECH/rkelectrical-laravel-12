<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TestSeries;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class TestSeriesController extends Controller
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
            'head_id' => 'nullable'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $head_id = $request->input('head_id', null);
        $viewType = $request->input('view_type', 'layered');
        $parent = null;

        $perPage = $request->input('perpage', 10);
        $page = (int) $request->input('page', 1);
        
        $filterQuery = TestSeries::query();

        if ($request->filled('name')) {
            $filterQuery->where('name', 'like', '%' . $request->input('name') . '%');
        }
        if ($request->filled('status')) {
            $filterQuery->where('status', $request->input('status'));
        }
        if ($request->filled('head_id')) {
            $filterQuery->where('head_id', $request->input('head_id'));
        }
        if ($request->filled('parent_id')) {
            $filterQuery->where('parent_id', $request->input('parent_id'));
            $parent = TestSeries::find($request->input('parent_id'));
        }
        if (!$request->filled('parent_id')) {
            if ($viewType === 'layered') {
                $filterQuery->whereNull('parent_id');
            } elseif ($viewType === 'parents') {
                $filterQuery->where('is_parent', true);
            } elseif ($viewType === 'individuals') {
                $filterQuery->whereNull('parent_id')->where('is_parent', false);
            } elseif ($viewType === 'all') {
            }
        }

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($dateFrom && $dateTo) {
            $filterQuery->whereBetween('launch_at', [
                Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay(),
                Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay(),
            ]);
        } elseif ($dateFrom) {
            $filterQuery->where('launch_at', '>=', Carbon::createFromFormat('d-m-Y', $dateFrom)->startOfDay());
        } elseif ($dateTo) {
            $filterQuery->where('launch_at', '<=', Carbon::createFromFormat('d-m-Y', $dateTo)->endOfDay());
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
        return view('admin.test.series.index', compact('items', 'parent'));
    }
    public function create(Request $request)
    {
        $parentSeries = null;
        if ($request->filled('parent_id')) {
            $parentSeries = TestSeries::find($request->parent_id);
        }
        return view('admin.test.series.create', compact('parentSeries'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'tagline' => 'nullable|max:255',
            'description' => 'nullable',
            'url_slug' => 'required|max:255|regex:/^[a-z0-9_\-]+$/',
            'mtit' => 'nullable',
            'mdes' => 'nullable',
            'status' => 'required|in:draft,active,upcoming,archive',
            'launch_at' => 'nullable',
            'expire_at' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'is_index' => 'nullable'
        ]);
        if ($request->status === 'upcoming') {
            $request->validate([
                'launch_at' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only([
            'name','url_slug','mtit','mdes','status','tagline','description'
        ]);
        // Parent and Head Test Series
        $parent = null;
        if ($request->filled('parent_id')) {
            $parent = TestSeries::find($request->parent_id);
            if (!$parent) {
                return back()->withInput()->with('error', 'Invalid Parent Test Series selected.');
            }
            $storeData['parent_id'] = $parent->id;
        }
        $slug = trim($request->input('url_slug'));
        if ($parent) {
            $ulink = trim(rtrim($parent->ulink, '/')) . '/' . $slug;
        } else {
            $ulink = $slug;
        }
        if (empty($ulink)) {
            return back()->withInput()->with('error', 'Ulink cannot be empty.');
        }
        if (TestSeries::where('ulink', $ulink)->exists()) {
            return back()->withInput()->with('error', 'This URL path is already taken.');
        }
        $storeData['ulink'] = $ulink;
        // fees validation
        $is_paid = filter_var($request->input('is_paid'), FILTER_VALIDATE_BOOLEAN);
        if($is_paid) {
            $fees = $request->input('fees');
            if (empty($fees) || !is_numeric($fees) || (int)$fees < 0) {
                return back()->withInput()->with('error', 'Fees must be entered for paid series.');
            }
            $storeData['is_paid'] = $is_paid;
            $storeData['fees'] = (int)$fees;
        }
        // indexing
        $is_index = filter_var($request->input('is_index'), FILTER_VALIDATE_BOOLEAN);
        if($is_index && $request->input('status') === 'active') {
            if (empty($request->mtit) || empty($request->mdes)) {
                return back()->withInput()->with('error', 'Meta title and description required when shown in index.');
            }
        }
        $featured = filter_var($request->input('featured'), FILTER_VALIDATE_BOOLEAN);
        if($featured) {
            $storeData['featured'] = $featured;
        }
        // Date formatting
        if ($request->input('status') == 'upcoming') {
            if ($request->launch_at) {
                try {
                    $storeData['launch_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->input('launch_at'))->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Invalid launch date format.');
                }
            }
        }
        if ($request->expire_at) {
            try {
                $storeData['expire_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->input('expire_at'))->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Invalid expire date format.');
            }
        }
        // auto priority
        $maxPriority = TestSeries::where('parent_id', $storeData['parent_id'] ?? null)->max('priority');
        $storeData['priority'] = ($maxPriority + 1);
        // Image uploads
        if ($request->hasFile('image')) {
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 300, 'path' => 'thumb/']
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/test-series/', 'image', $metadata);
            if ($fileName) {
                $storeData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }
        if ($request->hasFile('ogimage')) {
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/test-series/ogimage/', 'image', $metadata);
            if ($fileName) {
                $storeData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        $series = TestSeries::create($storeData);
        if ($series) {
            return redirect(route('admin.test.series.index'))
                ->with('success', 'Test Series has been created!');
        } else {
            return back()->with('error', 'Error creating Test Series, please try again')
                ->withInput();
        }
    }
    public function show(string $id)
    {
        //
    }
    public function edit(Request $request, $id)
    {
        $series=TestSeries::find($id);
        if ($series === null) {
            return redirect()->back()->withInput($request->all())->with('error', 'The Test Series does not exist');
        }
        return view('admin.test.series.edit',compact('series'));
    }
    public function update(Request $request, string $id)
    {
        $series = TestSeries::find($id);
        if (!$series) {
            return redirect(route('admin.test.series.index'))->with('error', 'The Test Series does not exist.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'tagline' => 'nullable|max:255',
            'description' => 'nullable',
            'url_slug' => 'required|max:255|regex:/^[a-z0-9_\-]+$/',
            'mtit' => 'nullable',
            'mdes' => 'nullable',
            'status' => 'required|in:draft,active,upcoming,archive',
            'launch_at' => 'nullable',
            'expire_at' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
            'ogimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:30720',
        ]);

        if ($request->status === 'upcoming') {
            $request->validate([
                'launch_at' => 'required',
            ]);
        }
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }

        $updateData = $request->only([
            'name','url_slug','mtit','mdes','status','tagline','description'
        ]);

        // Parent & Ulink
        $parent = $series->getParent;
        $slug = trim($request->url_slug);
        if ($parent) {
            $ulink = trim(rtrim($parent->ulink, '/')) . '/' . $slug;
        } else {
            $ulink = $slug;
        }
        if (empty($ulink)) {
            return back()->withInput()->with('error', 'Ulink cannot be empty.');
        }
        if (TestSeries::where('ulink', $ulink)->where('id', '!=', $id)->exists()) {
            return back()->withInput()->with('error', 'This URL path is already taken.');
        }
        $updateData['ulink'] = $ulink;

        // Fees logic
        $is_paid = filter_var($request->input('is_paid'), FILTER_VALIDATE_BOOLEAN);
        if ($is_paid) {
            $fees = $request->input('fees',0);
            if (empty($fees) || !is_numeric($fees) || (int)$fees < 0) {
                return back()->withInput()->with('error', 'Fees must be entered for paid series.');
            }
            $updateData['is_paid'] = $is_paid;
            $updateData['fees'] = (int)$fees;
        } else {
            $updateData['is_paid'] = false;
            $updateData['fees'] = 0;
        }

        // Indexing Check
        $is_index = filter_var($request->input('is_index'), FILTER_VALIDATE_BOOLEAN);
        if ($is_index && $request->input('status') === 'active') {
            if (empty($request->mtit) || empty($request->mdes)) {
                return back()->withInput()->with('error', 'Meta title and description required when shown in index.');
            }
        }
        $updateData['is_index'] = $is_index;

        // Featured
        $featured = filter_var($request->input('featured'), FILTER_VALIDATE_BOOLEAN);
        $updateData['featured'] = $featured;

        // Dates
        if ($request->input('status') == 'upcoming') {
            if ($request->launch_at) {
                try {
                    $updateData['launch_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->launch_at)->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return back()->withInput()->with('error', 'Invalid launch date format.');
                }
            }
        } else {
            $updateData['launch_at'] = null;
        }

        if ($request->expire_at) {
            try {
                $updateData['expire_at'] = Carbon::createFromFormat('d-m-Y g:i A', $request->expire_at)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Invalid expire date format.');
            }
        } else {
            $updateData['expire_at'] = null;
        }

        // Images
        if ($request->hasFile('image')) {
            $old = $series->image;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 900, 'path' => ''],
                    ['width' => 300, 'path' => 'thumb/']
                ],
                'deletefile' => $old,
            ];
            $fileName = $this->handleFileUpload($request, 'image', 'image/test-series/', 'image', $metadata);
            if ($fileName) {
                $updateData['image'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        if ($request->hasFile('ogimage')) {
            $old = $series->ogimage;
            $metadata = [
                'prefix' => '',
                'sizes' => [
                    ['width' => 1200, 'path' => ''],
                ],
                'deletefile' => $old,
            ];
            $fileName = $this->handleFileUpload($request, 'ogimage', 'image/test-series/ogimage/', 'image', $metadata);
            if ($fileName) {
                $updateData['ogimage'] = $fileName;
            } else {
                return back()->with('error', 'File upload error')->withInput();
            }
        }

        $updated = TestSeries::whereId($id)->update($updateData);

        if ($updated) {
            return redirect(route('admin.test.series.index'))
                ->with('success', 'Test Series has been updated!');
        } else {
            return back()->with('error', 'Error updating Test Series, please try again')
                ->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $series = TestSeries::find($id);
        if ($series) {
            $series->delete();
            $previousUrl = URL::previous();

            $delFiles = [];
            if (!empty($series->image)) {
                $delFiles[] = public_path('image/test-series/'.$series->image);
                $delFiles[] = public_path('image/test-series/thumb/'.$series->image);
            }
            if (!empty($series->ogimage)) {
                $delFiles[] = public_path('image/test-series/ogimage/'.$series->ogimage);
            }
            foreach ($delFiles as $delFile) {
                if (!empty($delFile) && file_exists($delFile)) {
                    unlink($delFile);
                }
            }
            if (Str::contains($previousUrl, route('admin.test.series.edit', $id))) {
                return redirect()->route('admin.test.series.index')->with('success', 'Test Series has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Test Series has been deleted');
            }
        } else {
            return redirect(route('admin.test.series.index'))->with('error', 'The Test Series does not exist');
        }
    }
    public function arrange(string $id)
    {
        //
    }
}
