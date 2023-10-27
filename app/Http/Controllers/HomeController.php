<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\NotifyMe;
use App\Models\ContactEmail;
use App\Models\PortalSetting;
use App\Models\User;
use App\Notifications\ContactAdminNotification;
use App\Notifications\OTPEmail;
use App\Repositories\HomeRepository;
use App\Repositories\SettingsRepository;
use App\Service\Facades\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use PHPUnit\Exception;
use Illuminate\Validation\Rules;
class HomeController extends Controller
{
    protected $home_repository;

    public function __constuct(HomeRepository $homeRepository){
        $this->home_repository = $homeRepository;
    }

    public function storeContact(Request $request){
        try {
            $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'message' => 'required|max:500',
                'email' => 'required|email',
            ]);

            $store = ContactEmail::create($request->all());
            if($store) {
//                return Inertia::location('/ ');
//                return Api::response('','Success');
//                return Redirect::route('/')->with('success','Witness Added Successfully');
                $contact_person = $store;
                $user = User::wherehas('roles',function ($q){
                    $q->where('name','admin');
                })->first();
                if($user){
                    $user->notify(new ContactAdminNotification($contact_person));
                }
                session()->flash('success', 'Your success message here');
                return Redirect::route("/")->with("success", "Ban doorgevoerd!");
            }
        }catch (Exception $exception){
            return Redirect::route('/')->with('error','Something went wrong!');
        }
    }

    public function getContactUsers(Request $request){
        try{
            return Inertia::render('admin/contacts/index', [
                'users' => function () use ($request) {
                    return ContactEmail::paginate(5);
                },
            ]);
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function privacyPolicy(){
        return Inertia::render('landing-page/privacy-policy', [
            'privacy_policy' => function () {
                return (new SettingsRepository())->privacyPolicy();
            },
        ]);
    }

    public function termsAndConditions(){
        return Inertia::render('landing-page/terms-and-conditions', [
            'terms_and_conditions' => function () {
                return (new SettingsRepository())->termsConditions();
            },
        ]);
    }

    public function notifyMe(Request $request){
        try {
            $mail = Mail::to($request['email'])->send(new NotifyMe($request['email']));
            if($mail){
                return Api::response('','Mail sent successfully');
            }else{
                return Api::error('Something Went wrong');
            }
        }catch (\Exception $exception){
            return Api::server_error($exception);
        }
    }

    public function profile(){
//        dd(auth()->user()->roles);
        return Inertia::render('Profile', [
            'user' => new UserResource(auth()->user()),
        ]);
    }
    public function updateProfile(Request $request){
        try {
            $data = $request->all();
            $user = Auth::user();
            $update = $user->update($data);
            return Redirect::route("profile")->with("success", 'Profile Updated');
        }catch (\Exception $exception){
            return Redirect::route("profile")->with("error", serverError($exception));
        }
    }

    public function updatePassword(Request $request){
        try {
            $request->validate([
                'old_password' => 'required',
                'password' => ['required','min:8', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => ['required','min:8'],
            ]);
//            dd($request->all());

            $user = auth()->user();
            if(!Hash::check($request->old_password, $user->password)){
                return Redirect::back()->with("error", 'Old Password is incorrect');
            }
            $user->password = Hash::make($request->password);
            $user->update();
            return Redirect::route("profile")->with("success", 'Password Updated');
        }catch (\Exception $exception){
            return Redirect::route("profile")->with("error", serverError($exception));
        }
    }

}
