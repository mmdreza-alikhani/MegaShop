<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): View|Application|Factory
    {
        $roles = Role::latest()->paginate(10);
        $permissions = Permission::latest()->get();

        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->input('name'),
                'display_name' => $request->input('display_name'),
                'guard_name' => 'web',
            ]);

            $permissions = $request->except('_token', 'name', 'display_name');
            $role->givePermissionTo($permissions);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage().' مشکل به وجود آمد!');

            return redirect()->back();
        }

        toastr()->success('با موفقیت اضافه شد!');

        return redirect()->back();
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->input('name'),
                'display_name' => $request->input('display_name'),
            ]);

            $permissions = $request->except('id', '_token', 'name', 'display_name', '_method');
            $role->syncPermissions($permissions);

            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage().' مشکل به وجود آمد!');

            return redirect()->back();
        }

        toastr()->success('با موفقیت ویرایش شد!');

        return redirect()->back();
    }

    public function destroy(Role $role): RedirectResponse
    {
        $role->delete();

        toastr()->success('با موفقیت حذف شد!');

        return redirect()->back();
    }
}
