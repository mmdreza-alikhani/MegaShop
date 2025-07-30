<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): View|Application|Factory
    {
        $permissions = Permission::latest()->paginate(10);

        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        Permission::create([
            'name' => $request->input('name'),
            'display_name' => $request->input('display_name'),
            'guard_name' => 'web',
        ]);

        toastr()->success('با موفقیت اضافه شد!');

        return redirect()->back();
    }

    public function update(UpdateRoleRequest $request, Permission $permission): RedirectResponse
    {
        $permission->update([
            'name' => $request->input('name'),
            'display_name' => $request->input('display_name'),
        ]);

        toastr()->success('با موفقیت ویرایش شد!');

        return redirect()->back();
    }
}
