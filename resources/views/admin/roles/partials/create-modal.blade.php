<div class="modal w-lg fade light rtl" id="createRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ایجاد نقش جدید
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="name">نام:*</label>
                            <input type="text" name="name" id="name" class="form-control"
                                   value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="display_name">نام نمایشی:*</label>
                            <input type="text" name="display_name" id="display_name" class="form-control"
                                   value="{{ old('display_name') }}" required>
                        </div>
                        <div class="form-group col-12 col-lg-12">
                            <button class="btn btn-block text-right border border-info" type="button"
                                    data-toggle="collapse" data-target="#permissionsCollapse">
                                مجوز ها
                            </button>
                            <div id="permissionsCollapse" class="collapse">
                                <div class="row">
                                    @foreach($permissions as $permission)
                                        <div class="col-lg-2 col-6 p-2 d-flex align-items-center">
                                            <input type="checkbox"
                                                   value="{{ $permission->name }}"
                                                   name="{{ $permission->name }}"
                                                   id="{{ $permission->name . '-check' }}"
                                                   class="mr-2">
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
                    <button type="submit" class="btn main f-main">ایجاد</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    @if($errors->store->any())
    $(function() {
        $('#createRoleModal').modal({
            show: true
        });
    });
    @endif
</script>
