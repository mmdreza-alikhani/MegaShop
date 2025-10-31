<div class="modal w-lg fade light rtl" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.users.update', ['user' => $user->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش کاربر: {{ $user->username }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-4">
                            <label for="username-{{ $user->id }}">نام کاربری:*</label>
                            <input type="text" name="username" id="username-{{ $user->id }}" class="form-control"
                                   value="{{ $user->username }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="first_name-{{ $user->id }}">نام:</label>
                            <input type="text" name="first_name" id="first_name-{{ $user->id }}" class="form-control"
                                   value="{{ $user->first_name }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="last_name-{{ $user->id }}">نام خانوادگی:</label>
                            <input type="text" name="last_name" id="last_name-{{ $user->id }}" class="form-control"
                                   value="{{ $user->last_name }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="email-{{ $user->id }}">ایمیل:</label>
                            <input type="text" name="email" id="email-{{ $user->id }}" class="form-control"
                                   value="{{ $user->email }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="phone_number-{{ $user->id }}">شماره تلفن:</label>
                            <div class="input-group-prepend">
                                <input type="tel" name="phone_number" id="phone_number-{{ $user->id }}" size="10" class="form-control" value="{{ $user->phone_number }}">
                                <div class="input-group-text">98+</div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="role">نقش کاربری:</label>
                            <select class="form-control" id="role-{{ $user->id }}" name="role">
                                <option></option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ in_array($role->id, $user->roles->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <button class="btn btn-block text-right border border-info" type="button"
                                    data-toggle="collapse" data-target="#permissionsCollapse">
                                دسترسی به مجوز ها
                            </button>
                            <div id="permissionsCollapse" class="collapse">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-lg-2 col-6 p-2 d-flex align-items-center">
                                            <input type="checkbox"
                                                   value="{{ $permission->name }}"
                                                   name="{{ $permission->name }}"
                                                   id="{{ $permission->name . '-check' }}"
                                                   class="mr-2"
                                                {{ in_array($permission->id, $user->getAllPermissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
                                            <label for="{{ $permission->name . '-check' }}">
                                                {{ $permission->display_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                    <button type="submit" class="btn main f-main">ویرایش</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if($errors->update->any() && session('user_id'))
    <script>
        $(function() {
            $('#editUserModal-{{ session('user_id') }}').modal({
                show: true
            });
        });
    </script>
@endif
