<div class="modal w-lg fade light rtl" id="editRoleModal-{{ $role->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('admin.roles.update', ['role' => $role->id]) }}">
            @method('put')
            @csrf
            <div class="modal-content card shade">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        ویرایش نقش: {{ $role->name }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.layout.errors', ['errors' => $errors->update])
                    <div class="row">
                        <div class="form-group col-12 col-lg-6">
                            <label for="name-{{ $role->id }}">نام:</label>
                            <input type="text" name="name" id="name-{{ $role->id }}" class="form-control"
                                   value="{{ $role->name }}">
                        </div>
                        <div class="form-group col-12 col-lg-6">
                            <label for="display_name-{{ $role->id }}">نام نمایشی:</label>
                            <input type="text" name="display_name" id="display_name-{{ $role->id }}" class="form-control"
                                   value="{{ $role->display_name }}">
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
                                                {{ in_array($permission->id, $role->permissions()->pluck('id')->toArray()) ? 'checked' : '' }}>
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
                    <button type="button" class="btn f-danger main" data-dismiss="modal">بستن</button>
                    <button type="submit" class="btn main f-main">ویرایش</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if($errors->update->any() && session('role_id'))
    <script>
        $(function() {
            $('#editRoleModal-{{ session('role_id') }}').modal({
                show: true
            });
        });
    </script>
@endif
