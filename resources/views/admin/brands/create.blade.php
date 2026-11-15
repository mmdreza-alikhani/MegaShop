@extends('admin.layout.master')

@section('title', 'برند جدید')

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>برند جدید</h3>
                    <p>افزودن برند تازه به فروشگاه</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-outline-info btn-sm">
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

                <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data">
                    @csrf
                    @include('admin.layout.errors', ['errors' => $errors->store])
                    

                    <div class="mk-form-grid">
                        <div class="mk-field">
        <label for="title">عنوان برند <em>*</em></label>
        <input type="text" name="title" id="title" class="form-control"
               value="{{ old('title', '') }}" required>
        <small>نام برند همان‌طور که در فروشگاه دیده می‌شود.</small>
    </div><div class="mk-field">
        <label for="is_active">وضعیت</label>
        <select name="is_active" id="is_active" class="form-control">
            <option value="1" @selected((string) old('is_active', '1') === '1')>فعال</option>
            <option value="0" @selected((string) old('is_active', '1') === '0')>غیرفعال</option>
        </select>
        <small>برند غیرفعال در فیلترهای فروشگاه نمایش داده نمی‌شود.</small>
    </div>
                    </div>

                    <div class="mk-form-actions">
                        <button type="submit" class="btn btn-info">ایجاد برند</button>
                        <a href="{{ route('admin.brands.index') }}" class="btn btn-light">انصراف</a>
                    </div>
                </form>
            </section>
        </div>
    </main>
@endsection