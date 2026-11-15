@php
    $isEdit = isset($banner) && $banner;
    $action = $isEdit ? route('admin.banners.update', ['banner' => $banner]) : route('admin.banners.store');
    $errorBag = $isEdit ? 'update' : 'store';

    $typeOptions = [
        'main/top-slider' => 'اسلایدر بالای صفحه اصلی',
        'main/middle-banner' => 'بنر میانی صفحه اصلی',
        'main/bottom-banner' => 'بنر پایین صفحه اصلی',
        'category/top-banner' => 'بنر بالای دسته‌بندی',
    ];

    $currentType = old('type', $isEdit ? $banner->type : 'main/top-slider');
    if (! array_key_exists($currentType, $typeOptions)) {
        $typeOptions[$currentType] = $currentType;
    }
@endphp

<form method="POST" action="{{ $action }}" class="mk-product-form" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @include('admin.layout.errors', ['errors' => $errors->{$errorBag}])

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>اطلاعات بنر</h4>
                <p>عنوان، جایگاه نمایش و متن روی بنر</p>
            </div>
        </header>

        <div class="mk-form-grid">
            <div class="mk-field mk-field-full">
                <label for="title">عنوان <em>*</em></label>
                <input type="text" name="title" id="title" class="form-control" maxlength="255"
                       value="{{ old('title', $isEdit ? $banner->title : '') }}" required>
            </div>

            <div class="mk-field">
                <label for="type">جایگاه نمایش <em>*</em></label>
                <select name="type" id="type" class="form-control" required>
                    @foreach($typeOptions as $value => $label)
                        <option value="{{ $value }}" @selected($currentType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-field">
                <label for="priority">اولویت <em>*</em></label>
                <input type="number" min="1" max="100" name="priority" id="priority" class="form-control"
                       value="{{ old('priority', $isEdit ? $banner->priority : 10) }}" required>
                <small>عدد کمتر، نمایش زودتر.</small>
            </div>

            <div class="mk-field">
                <label for="is_active">وضعیت</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" @selected((string) old('is_active', $isEdit ? $banner->getRawOriginal('is_active') : '1') === '1')>فعال</option>
                    <option value="0" @selected((string) old('is_active', $isEdit ? $banner->getRawOriginal('is_active') : '1') === '0')>غیرفعال</option>
                </select>
            </div>

            <div class="mk-field">
                <label for="text">متن بنر</label>
                <input type="text" name="text" id="text" class="form-control" maxlength="255"
                       value="{{ old('text', $isEdit ? $banner->text : '') }}">
            </div>

            <div class="mk-field">
                <label for="button_text">متن دکمه</label>
                <input type="text" name="button_text" id="button_text" class="form-control" maxlength="255"
                       value="{{ old('button_text', $isEdit ? $banner->button_text : '') }}"
                       placeholder="مثلاً: مشاهده محصولات">
            </div>

            <div class="mk-field">
                <label for="button_link">لینک دکمه</label>
                <input type="text" name="button_link" id="button_link" class="form-control" dir="ltr" maxlength="255"
                       value="{{ old('button_link', $isEdit ? $banner->button_link : '') }}"
                       placeholder="/products">
            </div>
        </div>
    </section>

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>تصویر بنر</h4>
                <p>{{ $isEdit ? 'در صورت نیاز تصویر را جایگزین کنید' : 'تصویر بنر الزامی است' }}</p>
            </div>
        </header>

        @if($isEdit)
            <div class="mk-media-primary">
                <img src="{{ Storage::url(config('upload.banner_path') . '/' . $banner->image) }}"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                     alt="{{ $banner->title }}">
                <div>
                    <strong>تصویر فعلی</strong>
                    <small>{{ $banner->image }}</small>
                </div>
            </div>
        @endif

        <div class="mk-field {{ $isEdit ? '' : 'mk-field-full' }}">
            <label for="image">{{ $isEdit ? 'تصویر جدید (اختیاری)' : 'تصویر بنر' }} @if(! $isEdit)<em>*</em>@endif</label>
            <div class="mk-upload" data-mk-upload>
                <img data-mk-upload-preview
                     src="{{ $isEdit ? Storage::url(config('upload.banner_path') . '/' . $banner->image) : asset('assets/images/upload-placeholder.svg') }}"
                     alt="">
                <div class="mk-upload-body">
                    <strong data-mk-upload-title>{{ $isEdit ? 'انتخاب تصویر جدید' : 'انتخاب یا رها کردن تصویر' }}</strong>
                    <small>jpg، png، webp یا svg — حداکثر ۵۰۰ کیلوبایت</small>
                </div>
                <label class="mk-upload-btn" for="image">
                    <i class="fas fa-folder-open"></i> انتخاب فایل
                </label>
                <input type="file" id="image" name="image" class="d-none"
                       accept="image/jpeg,image/png,image/webp,image/svg+xml" @required(! $isEdit)>
            </div>
        </div>
    </section>

    <div class="mk-form-actions mk-product-submit">
        <button type="submit" class="btn btn-info">
            <i class="fas fa-check"></i>
            {{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد بنر' }}
        </button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-light">انصراف</a>
    </div>
</form>
