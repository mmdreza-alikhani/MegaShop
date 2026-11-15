@extends('admin.layout.master')

@section('title', 'ویرایش پلتفرم')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>ویرایش پلتفرم</h3>
                    <p>{{ $platform->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.platforms.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            <section class="mk-adm-panel">
                <header class="mk-adm-panel-head">
                    <div>
                        <h4>اطلاعات</h4>
                        <p>فیلدهای ستاره‌دار الزامی هستند</p>
                    </div>
                </header>

                <form method="POST" action="{{ route('admin.platforms.update', ['platform' => $platform->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mk-form-grid">
                        <div class="mk-field">
        <label for="title">عنوان پلتفرم <em>*</em></label>
        <input type="text" name="title" id="title" class="form-control"
               value="{{ old('title', $platform->title) }}" required>
    </div><div class="mk-field">
        <label for="is_active">وضعیت</label>
        <select name="is_active" id="is_active" class="form-control">
            <option value="1" @selected((string) old('is_active', $platform->getRawOriginal('is_active')) === '1')>فعال</option>
            <option value="0" @selected((string) old('is_active', $platform->getRawOriginal('is_active')) === '0')>غیرفعال</option>
        </select>
    </div><div class="mk-field mk-field-full">
        <label for="image">لوگو</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
        <small>حداکثر ۲ مگابایت (JPG/PNG/SVG). خالی بگذارید تا لوگوی فعلی بماند.</small>
    </div>
                    </div>

                    <div class="mk-form-actions">
                        <button type="submit" class="btn btn-info">ذخیره تغییرات</button>
                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-light">انصراف</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
@endsection