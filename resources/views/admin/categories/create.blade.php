@extends('admin.layout.master')

@section('title', 'دسته‌بندی جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>دسته‌بندی جدید</h3>
                    <p>ساخت دسته اصلی یا زیردسته</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>اطلاعات دسته</h4>
                        <p>فیلدهای ستاره‌دار الزامی هستند</p>
                    </div>
                </header>

                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    @include('admin.layout.errors', ['errors' => $errors->store])

                    <div class="mk-form-grid">
                        <div class="mk-field">
                            <label for="title">عنوان <em>*</em></label>
                            <input type="text" name="title" id="title" class="form-control"
                                   value="{{ old('title') }}" required>
                        </div>

                        <div class="mk-field">
                            <label for="parent_id">دسته والد <em>*</em></label>
                            <select name="parent_id" id="parent_id" class="form-control" required>
                                <option value="0" @selected(old('parent_id', 0) == 0)>بدون والد (دسته اصلی)</option>
                                @foreach($parentCategories as $id => $title)
                                    <option value="{{ $id }}" @selected(old('parent_id') == $id)>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mk-field">
                            <label for="is_active">وضعیت <em>*</em></label>
                            <select name="is_active" id="is_active" class="form-control" required>
                                <option value="1" @selected(old('is_active', '1') == '1')>فعال</option>
                                <option value="0" @selected(old('is_active') === '0')>غیرفعال</option>
                            </select>
                        </div>

                        <div class="mk-field">
                            <label for="icon">آیکون</label>
                            <input type="text" name="icon" id="icon" class="form-control"
                                   value="{{ old('icon') }}" placeholder="fa fa-mobile">
                            <small>کلاس Font Awesome (اختیاری).</small>
                        </div>

                        <div class="mk-field">
                            <label for="variation_attribute_id">ویژگی تنوع <em>*</em></label>
                            <select name="variation_attribute_id" id="variation_attribute_id"
                                    class="form-control selectpicker" data-live-search="true"
                                    data-none-selected-text="انتخاب ویژگی" required>
                                <option data-hidden="true" value=""></option>
                                @foreach($attributes as $id => $title)
                                    <option value="{{ $id }}" @selected(old('variation_attribute_id') == $id)>{{ $title }}</option>
                                @endforeach
                            </select>
                            <small>مشخصه‌ای که تنوع محصول را می‌سازد (مثل حافظه).</small>
                        </div>

                        <div class="mk-field">
                            <label for="filter_attribute_ids">ویژگی‌های فیلتر</label>
                            <select name="filter_attribute_ids[]" id="filter_attribute_ids"
                                    class="form-control selectpicker" data-live-search="true" multiple
                                    data-actions-box="true" data-selected-text-format="count > 1"
                                    data-none-selected-text="انتخاب ویژگی‌ها">
                                @foreach($attributes as $id => $title)
                                    <option value="{{ $id }}"
                                            @selected(collect(old('filter_attribute_ids', []))->contains($id))>
                                        {{ $title }}
                                    </option>
                                @endforeach
                            </select>
                            <small>با Ctrl چند مورد را انتخاب کنید.</small>
                        </div>

                        <div class="mk-field mk-field-full">
                            <label for="description">توضیحات</label>
                            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="mk-form-actions">
                        <button type="submit" class="btn btn-info">ایجاد دسته</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">انصراف</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
@endsection
