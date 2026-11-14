<div class="modal fade mk-adm-modal" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form method="post" action="{{ route('admin.users.update', ['user' => $user->id]) }}" class="modal-content">
            @csrf
            @method('PATCH')

            <header class="mk-adm-modal-head">
                <div>
                    <h5>ویرایش کاربر</h5>
                    <p>{{ '@'.$user->username }} — تغییر اطلاعات حساب</p>
                </div>
                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
            </header>

            <div class="mk-adm-modal-body">
                @include('admin.layout.errors', ['errors' => $errors->update])

                <div class="mk-form-grid">
                    <div class="mk-field">
                        <label for="edit-username-{{ $user->id }}">نام کاربری <em>*</em></label>
                        <input type="text" name="username" id="edit-username-{{ $user->id }}" class="form-control"
                               value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="mk-field">
                        <label for="edit-email-{{ $user->id }}">ایمیل <em>*</em></label>
                        <input type="email" name="email" id="edit-email-{{ $user->id }}" class="form-control" dir="ltr"
                               value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="mk-field">
                        <label for="edit-first-name-{{ $user->id }}">نام</label>
                        <input type="text" name="first_name" id="edit-first-name-{{ $user->id }}" class="form-control"
                               value="{{ old('first_name', $user->first_name) }}">
                    </div>

                    <div class="mk-field">
                        <label for="edit-last-name-{{ $user->id }}">نام خانوادگی</label>
                        <input type="text" name="last_name" id="edit-last-name-{{ $user->id }}" class="form-control"
                               value="{{ old('last_name', $user->last_name) }}">
                    </div>

                    <div class="mk-field">
                        <label for="edit-phone-{{ $user->id }}">شماره موبایل</label>
                        <div class="mk-input-group">
                            <span class="mk-input-prefix" dir="ltr">+98</span>
                            <input type="tel" name="phone_number" id="edit-phone-{{ $user->id }}" class="form-control"
                                   dir="ltr" minlength="10" maxlength="10" placeholder="9121234567"
                                   value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                    </div>

                    <div class="mk-field">
                        <label for="edit-password-{{ $user->id }}">رمز عبور جدید</label>
                        <input type="password" name="password" id="edit-password-{{ $user->id }}" class="form-control"
                               autocomplete="new-password" placeholder="خالی بگذارید تا تغییر نکند">
                        <small>در صورت خالی بودن، رمز فعلی حفظ می‌شود.</small>
                    </div>

                    <div class="mk-field mk-field-full">
                        <label for="edit-status-{{ $user->id }}">وضعیت حساب</label>
                        <select name="is_active" id="edit-status-{{ $user->id }}" class="form-control">
                            <option value="1" @selected((string) old('is_active', $user->getRawOriginal('is_active')) === '1')>فعال</option>
                            <option value="0" @selected((string) old('is_active', $user->getRawOriginal('is_active')) === '0')>غیرفعال</option>
                        </select>
                    </div>
                </div>
            </div>

            <footer class="mk-adm-modal-foot">
                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                <button type="submit" class="btn btn-info">ذخیره تغییرات</button>
            </footer>
        </form>
    </div>
</div>
