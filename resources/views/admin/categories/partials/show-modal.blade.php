<div class="modal w-lg fade light rtl" id="showCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content card shade">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    نمایش دسته بندی: {{ $category->title }}
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label for="title-{{ $category->id }}">عنوان:</label>
                        <input type="text" id="title-{{ $category->id }}" class="form-control" value="{{ $category->title }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="parent_id-{{ $category->id }}">والد:</label>
                        <input type="text" id="parent_id-{{ $category->id }}" class="form-control" value="{{ $category->isParent() ? 'والد' : $category->parent->title }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="is_active-{{ $category->id }}">وضعیت:</label>
                        <input type="text" id="is_active-{{ $category->id }}" class="form-control" value="{{ $category->is_active }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="icon-{{ $category->id }}">آیکون:</label>
                        <input type="text" id="icon-{{ $category->id }}" class="form-control" value="{{ $category->icon }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="created_at-{{ $category->id }}">تاریخ ایجاد:</label>
                        <input type="text" id="created_at-{{ $category->id }}" class="form-control" value="{{ verta($category->created_at) }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <label for="updated_at-{{ $category->id }}">تاریخ آخرین ویرایش:</label>
                        <input type="text" id="updated_at-{{ $category->id }}" class="form-control" value="{{ verta($category->updated_at) }}" disabled>
                    </div>
                    <div class="form-group col-12 col-lg-12">
                        <label for="description-{{ $category->id }}">توضیحات:</label>
                        <textarea id="description-{{ $category->id }}" class="form-control" disabled>>{{ $category->description }}</textarea>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <button class="btn btn-block text-right border border-info" type="button"
                                data-toggle="collapse" data-target="#filtersCollapse">
                            فیلتر
                        </button>
                        <div id="filtersCollapse" class="collapse">
                            <div class="row align-items-center">
                                @foreach($category->filters as $filter)
                                    <p><a>{{ $filter->title }}</a>,</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-12 col-lg-6">
                        <button class="btn btn-block text-right border border-info" type="button"
                                data-toggle="collapse" data-target="#variationCollapse">
                            متغیر
                        </button>
                        <div id="variationCollapse" class="collapse">
                            <div class="row">
                                @foreach($category->variation as $variation)
                                    <p><a>{{ $variation->title }}</a>,</p>
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
