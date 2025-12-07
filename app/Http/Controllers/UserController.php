<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $previousUrl = $request->query('redirect');
        // $previousUrl = url()->previous();
        return view('user.login',compact('previousUrl'));
    }
    public function login_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $email = $request->input('email');
        $password = $request->input('password');
        // $previousUrl = $request->input('previousUrl');

        // $parsedUrl = parse_url($previousUrl);
        // $cleanUrl = $parsedUrl['path'] . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');

        $user = User::where('email', $email)->first();
        $redirect = urldecode($request->input('previousUrl',null));

        if ($user) {
            $info = password_get_info($user->password);
            if ($info['algoName'] === 'bcrypt') {
                if (Hash::check($password, $user->password)) {
                    Auth::login($user);
                    return redirect()->intended($redirect ?: route('user.profile'));
                }
            } else {
                if (password_verify($password, $user->password)) {
                    $user->password = Hash::make($password);
                    $user->save();
        
                    Auth::login($user);
                    return redirect()->intended($redirect ?: route('user.profile'));
                } else {
                    $error="Invalid password. Please check and try again";
                }
            }

            // if (Hash::check($password, $user->password)) {
            //     Auth::login($user);
            //     return redirect()->intended($redirect ?: route('user.profile'));
            // } else
            // {
            //     $error="Invalid password. Please check and try again";
            // }
        } else
        {
            $error="Invalid Email ID. Please check and try again";
        }
        return back()->withError($error);
    }
    private function argon2iPasswordMatches($plain, $hashed)
    {
        return password_verify($plain, $hashed);
    }
    public function register()
    {
       return view('user.register');
    }
    public function register_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255|confirmed',
            'name' => 'required',
            'phone' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $checkexist = User::where('email', $email)->first();
        if($checkexist)
        {
            return redirect(route('user.register'))->with('error', 'User already exist!');
        }
        $storeData = $request->only(['email','name','phone']);
        $storeData['password'] = Hash::make($request->input('password'));
        $user = User::create($storeData);
        if($user)
        {
            return redirect(route('user.profile'))->with('success', 'User has been created!');
        } else
        {
            return back()->with('error', 'Error creating User, please try again or contact our support team')->withInput();
        }
    }
    public function profile()
    {
        $user = Auth::user();

        if ($user) {
            return view('user.profile', compact('user'));
        } else {
            return redirect()->route('user.login');
        }
    }

    public function edit_profile()
    {
        $user = Auth::user();
        return view('user.edit_profile', compact('user'));
    }

    public function update_profile(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'user_email' => 'required|email|unique:'.(new User)->getTable().',email,' . $user->id,
            'user_phone' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->name = $request->input('user_name');
        $user->email = $request->input('user_email');
        $user->phone = $request->input('user_phone');
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Profile updated successfully');
    }

    public function student_register()
    {
        $user = Auth::user();
        if(!$user){
            return redirect()->route('user.login',);
        }
       return view('user.student_register',compact('user'));
    }
    public function update_studentregister(Request $request)
    {
        $user = Auth::user();
        if(!$user){
            return redirect()->route('user.login');
        }
        $validator = Validator::make($request->all(), [
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:Male,Female,Others',
            'clgname' => 'nullable',
            'year' => 'nullable',
            'degree' => 'nullable',
            'department' => 'nullable',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user->dob = $request->input('dob');
        $user->gender = $request->input('gender');
        $user->clgname = $request->input('clgname');
        $user->year = $request->input('year');
        $user->degree = $request->input('degree');
        $user->department = $request->input('department');
        $user->save();
        if($user){
            return redirect()->route('user.profile')->with('success', 'Student details updated successfully');
        }else{
            return back()->with('error', 'Error creating course, please try again or contact our support team')->withInput();
        }
    }

    public function edit_password()
    {
        $user = Auth::user();
       return view('user.edit_password',compact('user'));
    }
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required|min:6|max:100',
            'password' => 'required|min:6|max:100',
            're_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $current_user = auth()->user();

        if (!Hash::check($request->oldpassword, $current_user->password)) {
            return redirect()->back()->with('error', 'Old password does not match');
        }

        $current_user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('user.profile')->with('success', 'Password changed successfully');
    }


    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('user.login');
    }

    public function forget_password()
    {
        return view('user.forget_password');
    }
    public function checkUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:' . (new User)->getTable() . ',email',
        ]);
        if ($validate->fails()) {
            $errorMessage = $validate->errors()->first();
            return response()->json(['status' => 'error', 'message' => $errorMessage]);
        }
        $email = $request->input('email');
        $user = User::where('email',$email)->first();
        if($user){
            return response()->json(['status'=> 'success','message' => 'User Available']);
        }else{
            return response()->json(['status'=> 'error','message' => 'User not available']);
        }
        return view('user.forget_password');
    }

    public function update_forget_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:8|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $checkexist = User::where('email', $email)->first();
        if($checkexist)
        {
            // return redirect(route('user.register'))->with('error', 'User already exist!');
            $hashedPassword = Hash::make($password);
            $checkexist->password = $hashedPassword;
            $checkexist->save;
            Auth::login($checkexist);
            return redirect(route('user.profile'))->with('success', 'Password has been updated!');
        }else{
            return back()->with('error', 'Error updating password, please try again or contact our support team')->withInput();
        }
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
        $filterQuery = User::query();
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
        $users = $filterQuery->paginate($perPage);
        $totalCount = $users->total();
        $totalPages = $users->lastPage();
        $count = $users->count();
        if($totalCount>0)
        {
            if($page>$totalPages || $page<1)
            {
                $redirectUrl='/admin/user';
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
        return view('admin.user.index', compact('users', 'totalCount', 'totalPages', 'count', 'page'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return redirect()->route('admin.user.index')->with('success', 'User has been deleted');
        } else {
            return redirect()->route('admin.user.index')->with('error', 'The User does not exist');
        }
    }
}
