<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->paginate(10);
        return view('admin.roles.index' , compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::latest()->get();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:20|unique:App\Models\Role,name',
            'displayName' => 'required|min:3|max:20|unique:App\Models\Role,display_name'
        ]);

        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'display_name' => $request->displayName,
                'guard_name' => 'web',
            ]);
            $permissions = $request->except('_token', 'name', 'displayName');
            $role->givePermissionTo($permissions);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage() . ' مشکل در اضافه کردن محصول');
            return redirect()->back();
        }

        toastr()->success($request->name . '' . ' با موفقیت به مجوز اضافه شد');
        return redirect()->route('admin.roles.index');
    }

    public function show(Role $role)
    {
        return view('admin.Roles.show' , compact('role'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::latest()->get();
        return view('admin.Roles.edit' , compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $id = $request->all()['id'];
        $request->validate([
            'name' => ['required','min:3','max:20',Rule::unique('roles')->ignore($id)],
            'displayName' => ['required','min:3','max:20']
        ]);
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'display_name' => $request->displayName,
            ]);

            $permissions = $request->except( 'id','_token', 'name', 'displayName', '_method');
            $role->syncPermissions($permissions);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage() . ' مشکل در ویرایش کردن نقش');
            return redirect()->back();
        }

        toastr()->success('با موفقیت نقش ویرایش شد');
        return redirect()->route('admin.roles.index');
    }

    public function destroy(Request $request)
    {
        Role::destroy($request->role);

        toastr()->success('نقش مورد نظر با موفقیت حذف شد!');
        return redirect()->back();
    }
}
