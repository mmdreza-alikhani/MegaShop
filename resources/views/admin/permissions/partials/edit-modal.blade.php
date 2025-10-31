<div class="modal w-lg fade light rtl" id="editPermissionModal-{{ $permission->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.permissions.update', ['permission' => $permission->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش دسترسی: {{ $permission->name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="name-{{ $permission->id }}">نام:*</label>
                            <input type="text" name="name" id="name-{{ $permission->id }}" class="form-control"
                                   value="{{ $permission->name }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="display_name-{{ $permission->id }}">نام نمایشی:*</label>
                            <input type="text" name="display_name" id="display_name-{{ $permission->id }}" class="form-control"
                                   value="{{ $permission->display_name }}" required>
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
@if($errors->update->any() && session('permission_id'))
    <script>
        $(function() {
            $('#editPermissionModal-{{ session('permission_id') }}').modal({
                show: true
            });
        });
    </script>
@endif
