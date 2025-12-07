<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\ThrottleAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;

class AdminController extends Controller
{
    public function home()
    {
        return view('admin.home');
    }
    public function login()
    {
        return view('admin.login');
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'=>'required',
            'password'=>'required',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $LoginData = $request->only(['username','password']);
        $username=$request->input('username');
        $throttle = ThrottleAdmin::where('username', $username)->first();

        if ($throttle && $throttle->attempts >= 5) {
            $minutes = $throttle->updated_at->addMinutes(10)->diffInMinutes(now());
            if ($minutes > 0) {
                return back()->withInput(['username' => $request->input('username')])->withErrors(['Too many login attempts. Please try again after '.$minutes.' minutes.']);
            }
        }
        if(Auth::guard('admin')->attempt($LoginData)){
            $user=Admin::where('username',$username)->first();
            $admin_hash = $user->admin_hash;
            cookie()->queue('admin_hash', $admin_hash, 43200);
            Auth::guard('admin')->login($user);
            if ($throttle) {
                $throttle->update([
                    'attempts' => 0,
                    'last_attempt_at' => null,
                ]);
            }
            return redirect()->route('admin');
        } else{
            if ($throttle) {
                $throttle->update([
                    'attempts' => $throttle->attempts + 1,
                    'last_attempt_at' => now(),
                ]);
            } else {
                ThrottleAdmin::create([
                    'username' => $username,
                    'attempts' => 1,
                    'last_attempt_at' => now(),
                ]);
            }
            return back()->withInput(['username' => $request->input('username')])->withErrors(['Invalid Credentials']);
        }
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        if (Cookie::get('admin_hash')) {
            Cookie::queue(Cookie::forget('admin_hash'));
        }
        return redirect()->route('admin.login');
    }
    public function index()
    {
        $admin = Admin::all();
        return view('admin.admin.index', compact('admin'));
    }
    public function create()
    {
        return view('admin.admin.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|regex:/^[a-z0-9_\-.]+$/|unique:'.(new admin)->getTable(),
            'password' => 'required|min:8|max:255',
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $storeData = $request->only(['name','username']);
        $storeData['password'] = Hash::make($request->input('password'));
        $storeData['admin_hash'] = Str::random(128);
        $admin = Admin::create($storeData);
        if($admin)
        {
            $admin_credential = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];
            return redirect(route('admin.admin.index'))->with('success', 'Admin has been created!')->with('admin_credential', $admin_credential);
        } else
        {
            return back()->with('error', 'Error creating admin, please try again or contact our support team')->withInput();
        }
    }
    public function show($id)
    {
    }
    public function edit(Request $request, $id)
    {
        $admin=Admin::find($id);
        if ($admin === null) {
            return redirect()->back()->withInput($request->all())->with('error', 'The admin does not exist');
        }
        return view('admin.admin.edit', compact('admin'));
    }
    public function update(Request $request, $id)
    {
        $admin=Admin::find($id);
        if ($admin === null) {
            return redirect(route('admin.admin.index'))->with('error', 'The admin does not exist');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'username' => 'required|max:255|regex:/^[a-z0-9_\-]+$/|unique:'.(new admin)->getTable().',username,'.$id,
        ]);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        }
        $updateData = $request->only(['name','username']);
        if ($request->has('checkchpass')) {
            $validator = Validator::make($request->only('password'), [
                'password' => 'required|min:8|max:255',
            ]);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator->errors());
            }
            $updateData['password']=Hash::make($request->input('password'));
        }
        if($request->input('logout_all'))
        {
            $updateData['admin_hash'] = Str::random(128);
        }
        $admin = Admin::whereId($id)->update($updateData);
        if($admin)
        {
            $admin_credential=null;
            if($request->input('password'))
            {
                $admin_credential = [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ];
            }
            return redirect(route('admin.admin.index'))->with('success', 'Admin has been updated!')->with('admin_credential', $admin_credential);
        } else
        {
            return back()->with('error', 'Error updating admin, please try again or contact our support team')->withInput();
        }
    }
    public function destroy(Request $request, $id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $adminid=$admin->id;
            $admin->delete();
            $previousUrl = url()->previous();
            if (Str::contains($previousUrl, route('admin.admin.edit', $adminid))) {
                return redirect()->route('admin.admin.index')->with('success', 'Admin has been deleted');
            } else {
                return redirect()->back()->withInput($request->all())->with('success', 'Admin has been deleted');
            }
        } else {
            return redirect(route('admin.admin.index'))->with('error', 'The admin does not exist');
        }
    }
    public function generate_password()
    {
        $length = 24;
        $num_passwords = 6;
        $passwords = [];

        for ($i = 0; $i < $num_passwords; $i++) {
            $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $password = substr(str_shuffle($characters), 0, $length);
            $passwords[] = $password;
        }
        $password = '';
        while (strlen($password) < $length || !preg_match('/[a-z]/', $password) || !preg_match('/[^a-zA-Z]/', $password)) {
            $special_chars = '!@#$%&*()_+-=<>{}[]<>/\:;.,?';
            $special_char_count = random_int(2,5);
            $random_chars = Str::random($length - $special_char_count);
            $random_special_chars = '';

            for ($i = 0; $i < 5; $i++) {
                $index = random_int(0, strlen($special_chars) - 1);
                $random_special_chars .= $special_chars[$index];
            }
            $all_chars = str_split($random_chars . $random_special_chars);
            shuffle($all_chars);
            $password = implode('', $all_chars);
        }
        $passwords[] = $password;

        return response()->json(['passwords' => $passwords]);
    }
}
