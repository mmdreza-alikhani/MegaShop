<div class="modal fade mk-adm-modal" id="createUserModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <form method="post" action="{{ route('admin.users.store') }}" class="modal-content">
            @csrf

            <header class="mk-adm-modal-head">
                <div>
                    <h5>کاربر جدید</h5>
                    <p>اطلاعات پایه حساب را وارد کنید؛ کاربر بلافاصله فعال می‌شود.</p>
                </div>
                <button type="button" class="mk-adm-modal-close" data-dismiss="modal" aria-label="بستن">&times;</button>
            </header>

            <div class="mk-adm-modal-body">
                @include('admin.layout.errors', ['errors' => $errors->store])

                <div class="mk-form-grid">
                    <div class="mk-field">
                        <label for="create-username">نام کاربری <em>*</em></label>
                        <input type="text" name="username" id="create-username" class="form-control"
                               value="{{ old('username') }}" required autocomplete="off">
                        <small>حروف کوچک انگلیسی، عدد و نقطه.</small>
                    </div>

                    <div class="mk-field">
                        <label for="create-email">ایمیل <em>*</em></label>
                        <input type="email" name="email" id="create-email" class="form-control" dir="ltr"
                               value="{{ old('email') }}" required autocomplete="off">
                        <small>برای ورود کاربر و ارسال اطلاعیه‌ها.</small>
                    </div>

                    <div class="mk-field">
                        <label for="create-first-name">نام</label>
                        <input type="text" name="first_name" id="create-first-name" class="form-control"
                               value="{{ old('first_name') }}">
                    </div>

                    <div class="mk-field">
                        <label for="create-last-name">نام خانوادگی</label>
                        <input type="text" name="last_name" id="create-last-name" class="form-control"
                               value="{{ old('last_name') }}">
                    </div>

                    <div class="mk-field">
                        <label for="create-phone">شماره موبایل</label>
                        <div class="mk-input-group">
                            <span class="mk-input-prefix" dir="ltr">+98</span>
                            <input type="tel" name="phone_number" id="create-phone" class="form-control" dir="ltr"
                                   minlength="10" maxlength="10" placeholder="9121234567"
                                   value="{{ old('phone_number') }}">
                        </div>
                        <small>بدون صفر ابتدایی؛ ۱۰ رقم.</small>
                    </div>

                    <div class="mk-field">
                        <label for="create-password">رمز عبور <em>*</em></label>
                        <input type="password" name="password" id="create-password" class="form-control"
                               autocomplete="new-password" required>
                        <small>حداقل ۶ کاراکتر.</small>
                    </div>

                    <div class="mk-field mk-field-full">
                        <label for="create-status">وضعیت حساب</label>
                        <select name="is_active" id="create-status" class="form-control">
                            <option value="1" @selected(old('is_active', '1') == '1')>فعال — امکان ورود دارد</option>
                            <option value="0" @selected(old('is_active') === '0')>غیرفعال — ورود مسدود است</option>
                        </select>
                    </div>
                </div>
            </div>

            <footer class="mk-adm-modal-foot">
                <button type="button" class="btn btn-light" data-dismiss="modal">انصراف</button>
                <button type="submit" class="btn btn-info">ایجاد کاربر</button>
            </footer>
        </form>
    </div>
</div>

<script>
    @if($errors->store->any())
    $(function () {
        $('#createUserModal').modal({ show: true });
    });
    @endif
</script>
