<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::latest()->paginate(10);
        return view('admin.permissions.index' , compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Permission,name',
            'displayName' => 'required|min:3|max:20|unique:App\Models\Permission,display_name'
        ]);

        Permission::create([
            'name' => $request->name,
            'display_name' => $request->displayName,
            'guard_name' => 'web',
        ]);

        toastr()->success($request->name . '' . ' با موفقیت به مجوز اضافه شد');
        return redirect()->route('admin.permissions.index');
    }

    public function show(Permission $permission)
    {
        return view('admin.permissions.show' , compact('permission'));
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit' , compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('permissions')->ignore($id)],
            'displayName' => 'required|min:3|max:20'
        ]);

        $permission->update([
            'name' => $request->name,
            'display_name' => $request->displayName,
        ]);

        toastr()->success('با موفقیت مجوز ویرایش شد');
        return redirect()->route('admin.permissions.index');
    }

    public function destroy(Request $request)
    {
//        Permission::destroy($request->permission);
//
//        toastr()->success('مجوز مورد نظر با موفقیت حذف شد!');
//        return redirect()->back();
    }

}
