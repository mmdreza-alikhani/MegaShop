<div class="modal w-lg fade light rtl" id="showUserModal-{{ $user->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content card shade">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    نمایش کاربر: {{ $user->username }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12 col-lg-4">
                        <label for="username-{{ $user->id }}-show">نام کاربری:</label>
                        <input id="username-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->username }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="first_name-{{ $user->id }}-show">نام:</label>
                        <input id="first_name-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->first_name }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="last_name-{{ $user->id }}-show">نام خانوادگی:</label>
                        <input id="last_name-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->last_name }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="email-{{ $user->id }}-show">ایمیل:
                            @if($user->email_verified_at == null)
                                <i class="fa fa-times-circle text-danger"></i>
                            @else
                                <i class="fa fa-check-circle text-success"></i>
                            @endif
                        </label>
                        <input id="email-{{ $user->id }}-show" type="email" class="form-control" value="{{ $user->email }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="phone_number-{{ $user->id }}-show">شماره تلفن:</label>
                        <input id="phone_number-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->formatted_phone }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="provider_name-{{ $user->id }}-show">ارائه دهنده:</label>
                        <input id="provider_name-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->provider }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="created_at-{{ $user->id }}-show">تاریخ ایجاد:</label>
                        <input id="created_at-{{ $user->id }}-show" type="text" class="form-control" value="{{ verta($user->created_at) }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="updated_at-{{ $user->id }}-show">تاریخ آخرین ویرایش:</label>
                        <input id="updated_at-{{ $user->id }}-show" type="text" class="form-control" value="{{ verta($user->updated_at) }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label for="role-{{ $user->id }}-show">نقش کاربری:</label>
                        <input id="role-{{ $user->id }}-show" type="text" class="form-control" value="{{ $user->roles()->first()?->display_name }}" disabled>
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
                                               id="{{ $permission->name . '-check' }}"
                                               class="mr-2"
                                               disabled
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
            </div>
        </div>
    </div>
</div>
