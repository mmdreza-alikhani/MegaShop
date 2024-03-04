<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required',Rule::unique('users')],
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'password' => 'required|min:8|max:12',
            'email' => ['required',Rule::unique('users')],
            'phone_number' => ['nullable',Rule::unique('users')]
        ]);

        $user = User::create([
            'username' => $request->username,
            'first_name' => $request->first_name ? $request->first_name : '',
            'last_name' => $request->last_name ? $request->last_name : '',
            'email' => $request->email,
            'phone_number' => $request->phone_number ? $request->phone_number : '',
            'password' => Hash::make($request->password),
            'provider_name' => 'manual'
        ]);

        toastr()->success('با موفقیت کاربر اضافه شد.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $comments = $user->comments;
        $favorites = $user->wishlist;
        $orders = $user->orders;
        return view('admin.users.show' , compact('user', 'comments', 'favorites', 'orders'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.users.edit' , compact('user', 'roles', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'username' => ['required',Rule::unique('users')->ignore($request->user->id)],
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'email' => ['required',Rule::unique('users')->ignore($request->user->id)],
            'phone_number' => ['nullable',Rule::unique('users')->ignore($request->user->id)],
            'password' => 'nullable|min:8|max:12',
            'status' => 'required'
        ]);

        try {
            DB::beginTransaction();

                if($request->email != $user->email){
                    $user->update([
                        'email_verified_at' => null
                    ]);
                }

                $permissions = $request->except('_token', '_method', 'username', 'user_id', 'role', 'first_name', 'last_name', 'phone_number', 'email', 'newPassword', 'status', 'submit');
                $user->syncPermissions($permissions);

                $user->syncRoles($request->role);

                $user->update([
                    'username' => $request->username,
                    'first_name' => $request->first_name ? $request->first_name : $user->first_name,
                    'last_name' => $request->last_name ? $request->last_name : $user->last_name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number ? $request->phone_number : $user->phone_number,
                    'password' => Hash::make($request->password),
                    'status' => $request->status
                ]);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
                    toastr()->error($ex->getMessage() . ' مشکل در ویرایش کردن کاربر');
                    return redirect()->back();
        }

        toastr()->success('با موفقیت کاربر ویرایش شد.');
        return redirect()->back();
    }

    public function search(Request $request)
    {
        $keyWord = request()->keyword;
        if (request()->has('keyword') && trim($keyWord) != ''){
            $users = User::where('username', 'LIKE', '%'.trim($keyWord).'%')->latest()->paginate(10);
            return view('admin.users.index' , compact('users'));
        }else{
            $users = User::latest()->paginate(10);
            return view('admin.users.index' , compact('users'));
        }
    }
}
