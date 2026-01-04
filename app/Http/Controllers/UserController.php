<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id','desc')->get();
        
        return view('user.index',compact('users'));
    }

    public function userCreate()
    {
        return view('user.create');
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'phone_number'  => 'required|unique:users,phone',
            'email'         => 'required|unique:users,email',
            'role'          => 'required',
            'password'      => 'required|min:8',
            'gender'        => 'required',
        ]);

        if ($request->hasFile('user_image')) {
            $image = $request->file('user_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/users/'), $imageName);
            $imagePath = 'upload/users/' . $imageName;
        } else {
            $imagePath = 'upload/users/dummy.jpg';
        }
        // dd($request->all());
        User::create([
            'name'     => $request->name,
            'slug'     => Str::slug($request->name). '_' . rand(100000, 999999),
            'phone'    => $request->phone_number,
            'role'     => $request->role,
            'email'    => $request->email,
            'address'  => $request->address,
            'gender'   => $request->gender,  
            'password' => Hash::make($request['password']),
            'image'    => $imagePath,
            'status'   => $request->status,
        ]);

        // Redirect with success message
        return redirect()->route('user.list')->with('success', 'User created successfully!');
    }


    public function userEdit($slug)
    {
        $user = User::where('slug',$slug)->first();
 
        return view('user.edit',compact('user'));
    }

    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);
       
        $request->validate([
            'name'          => 'required',
            'phone_number'  => 'required',
            'email'         => 'required',
            'role'          => 'required',
            'gender'        => 'required',
            'password'      => 'nullable|min:8', 
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
            'phone'    => $request->phone_number,
            'role'     => $request->role,
            'email'    => $request->email,
            'address'  => $request->address,
            'gender'   => $request->gender,
            'image'    => $imagePath,
            'status'   => $request->status,
        ];

        if ($request->filled('password')) { 
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('user.list')->with('success', 'User updated successfully!');
    }

    public function userDelete($id)
    {
        $user = User::findOrFail($id);

        if(file_exists(public_path($user->image)) ){
            unlink(public_path($user->image)); 
        }
        $user->delete();
        return redirect()->back()->with('warning', 'User delete successfully!');
    }
}
