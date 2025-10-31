<div class="modal w-lg fade light rtl" id="createUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ایجاد کاربر جدید
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    <div class="row">
                        <div class="form-group col-12 col-lg-4">
                            <label for="username">نام کاربری:*</label>
                            <input type="text" name="username" id="username" class="form-control"
                                   value="{{ old('username') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="first_name">نام:</label>
                            <input type="text" name="first_name" id="first_name" class="form-control"
                                   value="{{ old('first_name') }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="last_name">نام خانوادگی:</label>
                            <input type="text" name="last_name" id="last_name" class="form-control"
                                   value="{{ old('last_name') }}">
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="email">ایمیل:*</label>
                            <input type="text" name="email" id="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="password">رمز عبور:*</label>
                            <input type="password" name="password" id="password" class="form-control"
                                   value="{{ old('password') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-4">
                            <label for="phone_number">شماره تلفن:</label>
                            <div class="input-group-prepend">
                                <input type="tel" name="phone_number" id="phone_number" minlength="10"
                                       maxlength="10" class="form-control" value="{{ old('phone_number') }}">
                                <div class="input-group-text">98+</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بازگشت</button>
                    <button type="submit" class="btn main f-main">ایجاد</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    @if($errors->store->any())
    $(function() {
        $('#createUserModal').modal({
            show: true
        });
    });
    @endif
</script>
