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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|Application|Factory
    {
        $query = User::query();
        if ($request->input('q')) {
            $query->search('username', trim(request()->input('q')));
        }
        $users = $query->latest()->paginate(15)->withQueryString();;
        $roles = Cache::remember('roles', now()->addHour(), function () {
            return Role::all();
        });
        $permissions = Cache::remember('permissions', now()->addHour(), function () {
            return Permission::all();
        });
        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            User::create(Arr::only($request->validated(), [
                'username', 'first_name', 'last_name', 'email', 'phone_number',
            ]) + [
                'password' => Hash::make($request->input('password')),
                'provider_name' => 'manual',
            ]);
            toastr()->success(config('flasher.user.created'));
            return redirect()->back();
        } catch (Exception $e) {
            report($e);
            flash()->error(config('flasher.user.create_failed'));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();

            if ($request->input('email') !== $user->email) {
                $user->update(['email_verified_at' => null]);
            }

            $user->syncPermissions($request->except([
                '_token', '_method', 'username', 'role', 'first_name',
                'last_name', 'phone_number', 'email',
            ]));
            $user->syncRoles($request->role);

            $user->update(array_merge(
                $request->only(['username', 'email', 'status']),
                [
                    'first_name' => $request->input('first_name') ?: $user->first_name,
                    'last_name' => $request->input('last_name') ?: $user->last_name,
                    'phone_number' => $request->input('phone_number') ?: $user->phone_number,
                    'password' => Hash::make($request->input('password')),
                ]
            ));

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            toastr()->error(config('flasher.user.update_failed'));
            report($e);
            return redirect()->back();
        }

        toastr()->success(config('flasher.user.updated'));
        return redirect()->back();
    }
}
