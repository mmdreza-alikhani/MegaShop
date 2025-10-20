<div class="modal w-lg fade light rtl" id="showRoleModal-{{ $role->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content card shade">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    نمایش نقش: {{ $role->name }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label for="name-{{ $role->id }}">نام:</label>
                        <input type="text" id="name-{{ $role->id }}" class="form-control"
                               value="{{ $role->name }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="display_name-{{ $role->id }}">نام نمایشی:</label>
                        <input type="text" id="display_name-{{ $role->id }}" class="form-control"
                               value="{{ $role->display_name }}" disabled>
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
            </div>
        </div>
    </div>
</div>
