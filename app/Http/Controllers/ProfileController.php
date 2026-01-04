<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    public function profileEdit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user_id = $request->user_id;
        $user = User::find($user_id);
       
        $request->validate([
            'name'          => 'required',
            'phone'         => 'required',
            'email'         => 'required',
            'gender'        => 'required',
        ]);
        
        if ($request->hasFile('user_image')) {
            if (!empty($user->image) && file_exists(public_path($user->image))) {
                unlink(public_path($user->image)); 
            }
            $image = $request->file('user_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/users/'), $imageName);
            $imagePath = 'upload/users/' . $imageName;
        } else {
            $imagePath = $user->image;
        }

        $updateData = [
            'name'     => $request->name,
            'slug'     => Str::slug($request->name). '_' . rand(100000, 999999),
            'phone'    => $request->phone,
            'role'     => $user->role,
            'email'    => $request->email,
            'address'  => $request->address,
            'gender'   => $request->gender,
            'image'    => $imagePath,
            'status'   => $user->status,
        ];

        if ($request->filled('password')) { 
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }

}
