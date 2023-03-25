<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function signup()
    {
        $settings = (object) [
            'title' => 'Signup',
            'description' => 'Signup page',
            'keywords' => 'signup, page',
        ];

        return view('admin.signup', compact('settings'));
    }

    public function signupSubmit(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|min:8|max:99',
            'confirm_password' => 'required|same:password',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        if($user) return redirect()->route('signin')->with('success','Congrats! Signup successful!');

        return redirect()->back()->with('error', 'Sorry, Error while Signing up! Please try again later.')->withInput();
    }

    public function signin()
    {
        $settings = (object) [
            'title' => 'Signin',
            'description' => 'Signin page',
            'keywords' => 'signin, page',
        ];

        return view('admin.signin', compact('settings'));
    }

    public function signinSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required',
            'remember_me' => 'nullable',
        ]);

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember_me))
            return redirect()->intended('admin/dashboard');

        return redirect()->back()->with('error', 'Sorry, Incorrect email or password.')->withInput();
    }

    public function dashboard()
    {
        $settings = (object) [
            'title' => 'Dashboard',
            'description' => 'Dashboard page',
            'keywords' => 'dashboard, page',
        ];

        return view('admin.auth.dashboard', compact('settings'));
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('signin')->with('success','Congrats! You are successfully logged out!');
    }

    public function forgotPassword()
    {
        $settings = (object) [
            'title' => 'Forgot Password',
            'description' => 'Forgot Password page',
            'keywords' => 'forgot password, page',
        ];

        return view('admin.forgotPassword', compact('settings'));
    }

    public function forgotPasswordSubmit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if($user) {
            $token = Str::random(100);

            Token::where('user_id', $user->id)->delete();

            $save_token = new Token();
            $save_token->user_id = $user->id;
            $save_token->token = $token;
            $save_token->type = 'reset_password';
            $save_token->expire_at = now()->addHours(4);
            $save_token->is_expired = 0;
            if($save_token->save()){

                $data = ['name' => $user->name, 'link' => route('resetPassword').('?email='.$user->email.'&_token='.$token)];

                Mail::send('emails.forgot-password', $data, function($message) use ($user){
                    $message->to($user->email, $user->name)
                        ->subject('Reset Password | ' . env('APP_NAME'))
                        ->from(env('MAIL_FROM_ADDRESS', 'test@panel.com'), env('APP_NAME'));
                });

                $user->password = Hash::make($token);
                $user->save();

                return redirect()->route('signin')->with('success', 'Congrats! Request generated successfully, please check your email.');
            }

            return redirect()->back()->with('error', 'Sorry, Error while generating token.');
        }

        return redirect()->back()->with('error', 'Sorry, given email not registered with us.');
    }

    public function resetPassword(Request $req)
    {
        if(empty(trim($req->email)) || empty(trim($req->_token)))
            return redirect()->route('signin')->with(['error' => 'Oops! Invalid request']);

        $email = urldecode($req->email);
        $token = urldecode($req->_token);

        $user = User::select('users.id','users.email')
            ->join('tokens as t', 'users.id', '=', 't.user_id')
            ->where('users.email', $email)
            ->where('t.expire_at', '>', now())
            ->where('t.is_expired', 0)
            ->where('t.token', $token)
            ->where('t.type', 'reset_password')
            ->first();

        if(!$user) return redirect()->route('forgotPassword')->with(['error' => 'Sorry! Password reset link expired']);

        $settings = (object) [
            'title' => 'Reset Password',
            'description' => 'Reset Password page',
            'keywords' => 'Reset password, page',
        ];

        return view('admin.resetPassword', compact('settings', 'email', 'token'));
    }

    public function resetPasswordSubmit(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|string|confirmed',
            'token' => 'required|string',
        ]);

        $user = User::select('users.id','users.email')
            ->join('tokens as t', 'users.id', '=', 't.user_id')
            ->where('users.email', $req->email)
            ->where('t.expire_at', '>', now())
            ->where('t.is_expired', 0)
            ->where('t.token', $req->token)
            ->where('t.type', 'reset_password')
            ->first();

        if(!$user) return redirect()->route('signin')->with(['error' => 'Sorry! Password reset link expired.']);

        $user->password = Hash::make($req->password);
        if($user->save()){
            Token::where('user_id', $user->id)->delete();
            return redirect()->route('signin')->with(['success' => 'Congrats! Password changed successfully.']);
        }

        return redirect()->back()->with(['error' => 'Oops! Something went wrong, please try again.']);
    }

    public function profile(Request $req)
    {
        $user = User::find(Auth::user()->id);

        $settings = (object) [
            'title' => 'Profile',
            'description' => 'Profile page',
            'keywords' => 'profile, page',
        ];

        return view('auth.profile', compact('settings', 'user'));
    }

    public function profileSubmit(Request $req)
    {
        $req->validate([
            'name' => 'required|string|min:3|max:100',
            'mobile' => 'nullable|string|regex:/^[0-9]{10,12}$/',
            'address' => 'nullable|string|min:3|max:100',
            'city' => 'nullable|string|min:3|max:100',
            'country' => 'nullable|string|min:3|max:100',
            'zipcode' => 'nullable|string|regex:/^[0-9]{4,6}$/',
        ],[
            'mobile.regex' => 'Please enter a valid mobile number.',
            'zipcode.regex' => 'Please enter a valid zipcode.',
        ]);

        $user = User::find(Auth::user()->id);
        $user->name = $req->name;
        $user->mobile = $req->mobile;
        $user->address = $req->address;
        $user->city = $req->city;
        $user->country = $req->country;
        $user->zipcode = $req->zipcode;
        if($user->save()){
            return redirect()->back()->with(['success' => 'Congrats! Profile updated successfully.']);
        }
    }

    public function changePassword(Request $req)
    {
        $settings = (object) [
            'title' => 'Change Password',
            'description' => 'Change Password',
            'keywords' => 'change password, page',
        ];

        return view('admin.auth.changePassword', compact('settings'));
    }

    public function changePasswordSubmit(Request $req)
    {
        $req->validate([
            'old_password' => 'required|string|min:8|max:100',
            'new_password' => 'required|string|min:8|max:100|confirmed',
        ]);

        $user = User::find(Auth::user()->id);
        if(!$user)
            return redirect()->back()->with(['error' => 'Oops! Something went wrong, please try again.']);

        if(!Hash::check($req->old_password, $user->password))
            return redirect()->back()->with(['error' => 'Oops! Incorrect Old Password.']);

        $user->password = Hash::make($req->new_password);
        if($user->save())
            return redirect()->back()->with(['success' => 'Congrats! Password changed successfully.']);

        return redirect()->back()->with(['error' => 'Oops! Something went wrong, please try again.']);

    }
}
