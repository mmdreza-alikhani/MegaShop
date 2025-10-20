<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\StorePermissionRequest;
use App\Http\Requests\Admin\Permission\UpdatePermissionRequest;
use App\Http\Requests\Admin\Role\StoreRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:permission-index', ['only' => ['index']]);
        $this->middleware('permission:permission-create', ['only' => ['store']]);
        $this->middleware('permission:permission-edit', ['only' => ['update']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = Permission::query();
        if ($request->input('q')) {
            $query->search('display_name', trim(request()->input('q')));
        }
        $permissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        try {
            Permission::create([
                ...$request->validated(),
                'guard_name' => 'web',
            ]);

            flash()->success(config('flasher.permission.created'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.permission.create_failed'));
            return redirect()->back()->withInput();
        }
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        try {
            $permission->update($request->validated());

            flash()->success(config('flasher.permission.updated'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.permission.update_failed'));
            return redirect()->back()
                ->withInput()
                ->with('attribute_id', $permission->id);
        }
    }
}
