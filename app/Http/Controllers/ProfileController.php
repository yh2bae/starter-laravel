<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProfileUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\ProfilePasswordUpdateRequest;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->routePath = 'profile';
        $this->prefix = 'profile';
        $this->pageName = 'Profile';

        $this->middleware('permission:Profile', ['only' => ['index', 'security']]);
        $this->middleware('permission:Profile Update', ['only' => ['update', 'updatePasswordProfile']]);

    }

    private function notDataInProfile()
    {
        $profile = ProfileUser::where('user_id', auth()->user()->id)->first();
        if (!$profile) {
            $profile = new ProfileUser();
            $profile->user_id = auth()->user()->id;
            $profile->save();
        }
        return $profile;
    }

    public function index()
    {
        $data['pageTitle'] = $this->pageName;
        $data['pageDescription'] = '';
        $data['breadcrumbItems'] = [
            ['name' => 'Profile', 'url' => route('profile.index')],
        ];

        $user = auth()->user();
        $profile = $this->notDataInProfile();

        $data['profile'] = $profile;
        $data['user'] = $user;

        return view($this->prefix . '.index', $data);
    }

    public function security()
    {
        $data['pageTitle'] = $this->pageName;
        $data['pageDescription'] = '';
        $data['breadcrumbItems'] = [
            ['name' => 'Profile', 'url' => route('profile.index')],
        ];

        $user = Auth()->user();
        $data['user'] = $user;

        return view($this->prefix . '.security', $data);
    }
    
    public function update(ProfileUpdateRequest $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        $profile = ProfileUser::where('user_id', $user->id)->first();

        if ($request->hasFile('avatar')) {

           Storage::disk('local')->delete('public/avatars/'.basename($profile->avatar));

            $avatar = $request->file('avatar');
            $username = str_replace(' ', '_', strtolower($user->name));
            $fileName = str_replace(' ', '_', strtolower($avatar->getClientOriginalName()));
            $avatarName = $username . '_' . $fileName;
            $avatar->storeAs('public/avatars', $avatarName);
            $profile->avatar = $avatarName;
        }


        $user->name = $request->name;
        $user->save();

        $profile->phone_number = $request->phone_number;
        $profile->address = $request->address;
        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updatePasswordProfile(ProfilePasswordUpdateRequest $request, $uuid)
    {
        $user = User::where('uuid', $uuid)->first();
        
       //check if old password match
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Old password does not match');
        }

        if (Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'New password cannot be the same as old password');
        }

        $user->password = Hash::make($request->password);
        $user->last_password_change = now();
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully');
    }

}
