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
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|Application|Factory
    {
        $users = User::latest()->paginate(10);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            User::create(Arr::only($request->validated(), [
                'username', 'first_name', 'last_name', 'email', 'phone_number',
            ]) + [
                'password' => Hash::make($request->input('password')),
                'provider_name' => 'manual'
            ]);

            DB::commit();
        }catch (Exception $ex) {
            DB::rollBack();
            toastr()->error($ex->getMessage() . 'مشکلی پیش آمد!');
            return redirect()->back();
        }

        toastr()->success('با موفقیت اضافه شد!');
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request, $user) {
                if ($request->input('email') !== $user->email) {
                    $user->update(['email_verified_at' => null]);
                }

                $user->syncPermissions($request->except([
                    '_token', '_method', 'username', 'role', 'first_name',
                    'last_name', 'phone_number', 'email'
                ]));
                $user->syncRoles($request->role);

                $user->update(array_merge(
                    $request->only(['username', 'email', 'status']),
                    [
                        'first_name' => $request->first_name ?: $user->first_name,
                        'last_name' => $request->last_name ?: $user->last_name,
                        'phone_number' => $request->phone_number ?: $user->phone_number,
                        'password' => Hash::make($request->password),
                    ]
                ));
            });

            toastr()->success('با موفقیت ویرایش شد!');
        } catch (Exception $ex) {
            toastr()->error($ex->getMessage() . ' مشکلی پیش آمد!');
        }

        return redirect()->back();
    }

    public function search(): View|Application|Factory
    {
        $users = User::search('username', trim(request()->keyword))->latest()->paginate(10);
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.index', compact('users', 'roles', 'permissions'));
    }
}
