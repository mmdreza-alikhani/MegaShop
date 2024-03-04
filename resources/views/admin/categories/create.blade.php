@extends('admin.layouts.master')
@section('title' , 'ایجاد دسته بندی')
@php
    $active_parent = 'categories';
    $active_child = 'makecategory'
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.categories.store') }}" method="POST" class="row">
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                         افزودن دسته بندی
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4">
                                <label for="name">نام*</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="parent_id">والد*</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="0">بدون والد</option>
                                    @foreach($parentCategories as $parentCategory)
                                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت*</label>
                                <select class="form-control" id="is_active" name="is_active">
                                    <option value="1" selected>فعال</option>
                                    <option value="0">غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeSelect">ویژگی ها*</label>
                                <select id="attributeSelect" class="form-control" name="attribute_ids[]" multiple data-live-search="true">
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeIsFilter">ویژگی های قابل فیلتر*</label>
                                <select id="attributeIsFilter" class="form-control" name="attribute_is_filter_ids[]" multiple data-live-search="true">
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="attributeIsVariation">ویژگی متغیر*</label>
                                <select id="attributeIsVariation" class="form-control" name="attribute_is_variation_id" data-live-search="true">
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-3">
                                <label for="icon">آیکون</label>
                                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon') }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="description">توضیحات</label>
                                <textarea type="text" name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">افزودن</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
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
            'title' : 'انتخاب ویژگی'
        });
        $('#attributeIsFilter').selectpicker({
            'title' : 'انتخاب ویژگی قابل فیلتر'
        });
        $('#attributeIsVariation').selectpicker({
            'title' : 'انتخاب ویژگی قابل متغیر'
        });
        $('#attributeSelect').on('changed.bs.select' , function () {
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
            attributeForFilter.forEach((element)=>{
                let attributeForFilterOption = $("<option/>" , {
                    value :element.id,
                    text : element.name,
                });

                let attributeForVariationOption = $("<option/>" , {
                    value :element.id,
                    text : element.name,
                });
                $("#attributeIsFilter").append(attributeForFilterOption);
                $('#attributeIsFilter').selectpicker('refresh');

                $("#attributeIsVariation").append(attributeForVariationOption);
                $('#attributeIsVariation').selectpicker('refresh');
            })
        })
    </script>
@endsection
