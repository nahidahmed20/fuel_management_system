<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('permission.index', [
            'permissions' => Permission::latest()->get()
        ]);
    }

    public function create()
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','unique:permissions,name']
        ]);

        Permission::create($data);

        return redirect()->route('permissions.index')->with('success','Permission created');
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update([
            'name' => $request->name,
        ]);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('success','Permission deleted');
    }
}
