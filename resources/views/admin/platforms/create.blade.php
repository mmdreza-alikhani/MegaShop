@extends('admin.layout.master')

@section('title', 'پلتفرم جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>پلتفرم جدید</h3>
                    <p>افزودن پلتفرم تازه</p>
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

                <form method="POST" action="{{ route('admin.platforms.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    

                    <div class="mk-form-grid">
                        <div class="mk-field">
        <label for="title">عنوان پلتفرم <em>*</em></label>
        <input type="text" name="title" id="title" class="form-control"
               value="{{ old('title', '') }}" required>
    </div><div class="mk-field">
        <label for="is_active">وضعیت</label>
        <select name="is_active" id="is_active" class="form-control">
            <option value="1" @selected((string) old('is_active', '1') === '1')>فعال</option>
            <option value="0" @selected((string) old('is_active', '1') === '0')>غیرفعال</option>
        </select>
    </div><div class="mk-field mk-field-full">
    <label>لوگوی پلتفرم</label>

    <div class="mk-upload" data-mk-upload>
        <input type="file" name="image" id="image" accept="image/*" hidden>

        <label for="image" class="mk-upload-box">
            <img data-mk-upload-preview src="{{ asset('assets/images/media-placeholder.svg') }}" alt="پیش‌نمایش لوگو">

            <span class="mk-upload-text">
                <strong data-mk-upload-title>انتخاب یا رها کردن تصویر</strong>
                <small>JPG، PNG یا SVG — حداکثر ۵۰۰ کیلوبایت</small>
            </span>
        </label>

        <button type="button" class="mk-upload-clear" data-mk-upload-clear>حذف انتخاب</button>
    </div>
</div>
                    </div>

                    <div class="mk-form-actions">
                        <button type="submit" class="btn btn-info">ایجاد پلتفرم</button>
                        <a href="{{ route('admin.platforms.index') }}" class="btn btn-light">انصراف</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
@endsection