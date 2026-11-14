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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users-index', ['only' => ['index']]);
        $this->middleware('permission:users-create', ['only' => ['store']]);
        $this->middleware('permission:users-edit', ['only' => ['update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $permissions = Permission::all();

        $query = Role::query()->with('permissions')->withCount('users');

        if ($request->filled('q')) {
            $like = '%'.trim((string) $request->input('q')).'%';

            $query->where(function ($sub) use ($like) {
                $sub->where('name', 'like', $like)
                    ->orWhere('display_name', 'like', $like);
            });
        }

        $roles = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'staff' => \App\Models\User::whereHas('roles')->count(),
            'empty' => Role::has('permissions', '=', 0)->count(),
        ];

        return view('admin.roles.index', compact('roles', 'permissions', 'stats'));
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $role = Permission::create([
                ...$request->validated(),
                'guard_name' => 'web',
            ]);

            $permissions = $request->except('_token', 'name', 'display_name');
            $role->givePermissionTo($permissions);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.role.create_failed'));
            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.role.created'));
        return redirect()->back();
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $role->update($request->validated());

            $permissions = $request->except('id', '_token', 'name', 'display_name', '_method');
            $role->syncPermissions($permissions);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.role.update_failed'));
            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.role.updated'));
        return redirect()->back();
    }
}
