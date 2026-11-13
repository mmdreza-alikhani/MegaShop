<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = User::query()->with('roles:id,name,display_name');

        if ($request->filled('q')) {
            $query->search('username', trim((string) $request->input('q')));
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        $roles = Cache::remember('admin_roles', now()->addHour(), fn () => Role::all());
        $permissions = Cache::remember('admin_permissions', now()->addHour(), fn () => Permission::all());

        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            $user = User::create([
                'username' => $request->input('username'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'phone_number' => $request->input('phone_number'),
                // کاربر ساخته‌شده توسط مدیر نیازی به تایید ایمیل ندارد.
                'password' => $request->input('password'),
                'email_verified_at' => now(),
                'provider_name' => 'manual',
                'status' => 1,
                'is_active' => $request->boolean('is_active', true),
            ]);

            flash()->success(config('flasher.user.created'));
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.user.create_failed'));

            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = [
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'first_name' => $request->input('first_name') ?: $user->first_name,
                'last_name' => $request->input('last_name') ?: $user->last_name,
                'phone_number' => $request->input('phone_number') ?: $user->phone_number,
            ];

            // فقط در صورت ارسال رمز جدید، رمز عبور عوض می‌شود.
            if ($request->filled('password')) {
                $data['password'] = $request->input('password');
            }

            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }

            if ($request->input('email') !== $user->email) {
                $data['email_verified_at'] = null;
            }

            $user->update($data);

            if ($request->filled('role')) {
                $user->syncRoles([$request->input('role')]);
            }

            // فقط چک‌باکس‌های مجوز (نام‌های معتبر Permission) ذخیره می‌شوند.
            $permissionNames = Permission::pluck('name')->all();
            $selectedPermissions = array_values(array_intersect(
                array_keys($request->except(['_token', '_method', 'username', 'email', 'first_name', 'last_name', 'phone_number', 'role', 'password', 'is_active'])),
                $permissionNames
            ));

            $user->syncPermissions($selectedPermissions);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            flash()->error(config('flasher.user.update_failed'));

            return redirect()->back()->withInput();
        }

        flash()->success(config('flasher.user.updated'));

        return redirect()->back();
    }
}
