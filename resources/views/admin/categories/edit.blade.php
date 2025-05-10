@extends('admin.layout.master')
@section('title')
    ویرایش دسته بندی: {{$category->name}}
@endsection
@php
    $active_parent = 'categories';
    $active_child = ''
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.categories.update' , ['category' => $category->id]) }}" method="POST" class="row">
            @csrf
            @method('put')
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        ویرایش
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-3">
                                <label for="name">عنوان*</label>
                                <input type="text" name="name" id="name" class="form-control"
                                       value="{{ $category->name }}">
                                <input type="hidden" name="id" id="id" class="form-control" value="{{ $category->id }}">
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="slug" data-bs-toggle="tooltip" data-bs-placement="top" title="اختیاری">نام
                                    انگلیسی*</label>
                                <input type="text" name="slug" id="slug" class="form-control"
                                       value="{{ $category->slug }}">
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="parent_id">والد*</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="0" {{ $category->parent_id == 0 ? 'selected' : '' }} >بدون والد
                                    </option>
                                    @foreach($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}" {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }} >
                                            {{ $parentCategory->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="is_active">وضعیت*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" {{ $category->getRawOriginal('is_active') ? 'selected' : '' }} >
                                        فعال
                                    </option>
                                    <option value="0" {{ $category->getRawOriginal('is_active') ? '' : 'selected' }} >
                                        غیرفعال
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeSelect">ویژگی ها*</label>
                                <select id="attributeSelect" class="form-control" name="attribute_ids[]" multiple
                                        data-live-search="true">
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                                {{ in_array($attribute->id , $category->attributes()->pluck('id')->toArray() ) ? 'selected' : '' }}
                                        >{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeIsFilter">ویژگی های قابل فیلتر*</label>
                                <select id="attributeIsFilter" class="form-control" name="attribute_is_filter_ids[]"
                                        multiple data-live-search="true">
                                    @foreach($category->attributes()->wherePivot('is_filter' , 1)->get() as $attribute)
                                        <option value="{{ $attribute->id }}" selected>{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeIsVariation">ویژگی متغیر*</label>
                                <select id="attributeIsVariation" class="form-control" name="attribute_is_variation_id"
                                        data-live-search="true">
                                    <option value="{{ $category->attributes()->wherePivot('is_variation' , 1)->first()->id }}"
                                            selected>{{ $category->attributes()->wherePivot('is_variation' , 1)->first()->name }}</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="icon">آیکون</label>
                                <input type="text" name="icon" id="icon" class="form-control"
                                       value="{{ $category->icon }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="description">توضیحات</label>
                                <textarea type="text" name="description" id="description"
                                          class="form-control">{{ $category->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-danger w-100"
                                   type="cancel" name="cancel">بازگشت</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        $('#attributeSelect').selectpicker({
            'title': 'انتخاب ویژگی'
        });
        $('#attributeIsFilter').selectpicker({
            'title': 'انتخاب ویژگی قابل فیلتر'
        });
        $('#attributeIsVariation').selectpicker({
            'title': 'انتخاب ویژگی قابل متغیر'
        });
        $('#attributeSelect').on('changed.bs.select', function () {
            const selectedAttributes = $(this).val();
            const attributes = @json($attributes);
            const attributeForFilter = [];

            attributes.map((attribute) => {
                $.each(selectedAttributes, function (i, element) {
                    if (attribute.id == element) {
                        attributeForFilter.push(attribute);
                    }
                });
            })

            $("#attributeIsFilter").find("option").remove();
            $("#attributeIsVariation").find("option").remove();
            attributeForFilter.forEach((element) => {
                let attributeForFilterOption = $("<option/>", {
                    value: element.id,
                    text: element.name,
                });

                let attributeForVariationOption = $("<option/>", {
                    value: element.id,
                    text: element.name,
                });
                $("#attributeIsFilter").append(attributeForFilterOption);
                $('#attributeIsFilter').selectpicker('refresh');

                $("#attributeIsVariation").append(attributeForVariationOption);
                $('#attributeIsVariation').selectpicker('refresh');
            })
        })
    </script>
@endsection
