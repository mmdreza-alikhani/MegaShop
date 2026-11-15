@php
    $isEdit = isset($product) && $product;
    $action = $isEdit
        ? route('admin.products.update', ['product' => $product])
        : route('admin.products.store');
    $errorBag = $isEdit ? 'update' : 'store';

    $variationAttributeTitle = $isEdit ? ($variations->first()['title'] ?? '') : '';

    $savedFilters = old('filters_value', []);
    if ($isEdit && empty($savedFilters)) {
        foreach ($filters as $filter) {
            $savedFilters[$filter['id']] = $filter['value'];
        }
    }

    $faqRows = [];
    if ($isEdit) {
        foreach ($product->faqs as $faq) {
            $faqRows[] = ['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer];
        }
    }
    $oldFaqs = old('faqs');
    if (is_array($oldFaqs)) {
        $faqRows = [];
        foreach ($oldFaqs as $key => $faq) {
            $faqRows[] = [
                'id' => is_numeric($key) ? $key : null,
                'question' => $faq['question'] ?? '',
                'answer' => $faq['answer'] ?? '',
            ];
        }
    }
@endphp

<form id="productForm" class="mk-product-form" method="POST" action="{{ $action }}"
      enctype="multipart/form-data" novalidate>
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @include('admin.layout.errors', ['errors' => $errors->{$errorBag}])

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>اطلاعات پایه</h4>
                <p>عنوان، وضعیت نمایش و ارتباط محصول با برند و پلتفرم</p>
            </div>
            @if($isEdit)
                <span class="mk-panel-chip">شناسه #{{ $product->id }}</span>
            @endif
        </header>

        <div class="mk-form-grid">
            <div class="mk-field mk-field-full">
                <label for="title">عنوان محصول <em>*</em></label>
                <input type="text" name="title" id="title" class="form-control"
                       value="{{ old('title', $isEdit ? $product->title : '') }}" maxlength="255" required>
            </div>

            <div class="mk-field">
                <label for="is_active">وضعیت <em>*</em></label>
                <select class="form-control" id="is_active" name="is_active" required>
                    <option value="1" @selected((string) old('is_active', $isEdit ? ($product->is_active ? '1' : '0') : '1') === '1')>فعال</option>
                    <option value="0" @selected((string) old('is_active', $isEdit ? ($product->is_active ? '1' : '0') : '1') === '0')>غیرفعال</option>
                </select>
            </div>

            <div class="mk-field">
                <label for="short_link">لینک کوتاه</label>
                <input type="text" name="short_link" id="short_link" class="form-control" dir="ltr"
                       value="{{ old('short_link', $isEdit ? $product->shortLink?->code : '') }}" maxlength="12"
                       placeholder="خودکار">
                <small>خالی بگذارید تا خودکار ساخته شود.</small>
            </div>

            <div class="mk-field">
                <label for="brandSelect">برند <em>*</em></label>
                <select id="brandSelect" class="form-control" name="brand_id" data-live-search="true" required>
                    <option value="" disabled @selected(! old('brand_id', $isEdit ? $product->brand_id : null))>انتخاب کنید…</option>
                    @foreach($brands as $key => $value)
                        <option value="{{ $key }}" @selected((string) old('brand_id', $isEdit ? $product->brand_id : '') === (string) $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-field">
                <label for="platformSelect">پلتفرم <em>*</em></label>
                <select id="platformSelect" class="form-control" name="platform_id" data-live-search="true" required>
                    <option value="" disabled @selected(! old('platform_id', $isEdit ? $product->platform_id : null))>انتخاب کنید…</option>
                    @foreach($platforms as $key => $value)
                        <option value="{{ $key }}" @selected((string) old('platform_id', $isEdit ? $product->platform_id : '') === (string) $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-field mk-field-full">
                <label for="tagSelect">برچسب‌ها</label>
                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple data-live-search="true"
                        title="انتخاب برچسب">
                    @foreach($tags as $key => $value)
                        <option value="{{ $key }}"
                            @selected(in_array($key, old('tag_ids', $isEdit ? $tagIds : [])))>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-field mk-field-full mk-field--editor">
                <label for="description">توضیحات <em>*</em></label>
                <textarea id="description" name="description" class="form-control" rows="9"
                          required>{{ old('description', $isEdit ? $product->description : '') }}</textarea>
            </div>
        </div>
    </section>

    <div class="mk-form-2col">
        <section class="mk-adm-panel">
            <header class="mk-adm-panel-head">
                <div>
                    <h4>تصاویر محصول</h4>
                    <p>{{ $isEdit ? 'مدیریت تصویر اصلی و گالری' : 'تصویر اصلی الزامی است' }}</p>
                </div>
            </header>

            @if($isEdit)
                @php($primary = $product->primary_image)
                <div class="mk-media-primary">
                    <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $primary) }}"
                         onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                         alt="{{ $product->title }}">
                    <div>
                        <strong>تصویر اصلی</strong>
                        <small>{{ $primary ?: 'ثبت نشده' }}</small>
                    </div>
                </div>

                <div class="mk-media-grid">
                    @forelse($product->images as $image)
                        <div class="mk-media-item">
                            <img src="{{ Storage::url(config('upload.product_others_path') . '/' . $image->image) }}"
                                 onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                                 alt="{{ $product->title }}">
                            <div class="mk-media-item-actions">
                                <button type="submit" form="imgPrimary{{ $image->id }}" class="mk-media-action"
                                        title="تنظیم به‌عنوان تصویر اصلی">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button type="submit" form="imgDelete{{ $image->id }}" class="mk-media-action is-danger"
                                        title="حذف تصویر"
                                        onclick="return confirm('این تصویر حذف شود؟')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="mk-muted">هنوز تصویر دیگری ثبت نشده است.</p>
                    @endforelse
                </div>

                <div class="mk-upload-slim">
                    <label class="mk-upload-btn" for="new_images">
                        <i class="fas fa-images"></i> انتخاب فایل‌ها
                    </label>
                    <input type="file" id="new_images" name="images[]" form="productImagesForm"
                           class="d-none" accept="image/*" multiple>
                    <button type="submit" form="productImagesForm" class="btn btn-info btn-sm">
                        <i class="fas fa-upload"></i> افزودن تصویر
                    </button>
                </div>
                <small class="mk-field-hint">حداکثر ۱۰ فایل، هر فایل تا ۵۰۰ کیلوبایت.</small>
            @else
                <div class="mk-field">
                    <label for="primary_image">تصویر اصلی <em>*</em></label>
                    <div class="mk-upload" data-mk-upload>
                        <img data-mk-upload-preview src="{{ asset('assets/images/upload-placeholder.svg') }}" alt="">
                        <div class="mk-upload-body">
                            <strong data-mk-upload-title>انتخاب یا رها کردن تصویر</strong>
                            <small>jpg، png یا webp — حداکثر ۵۰۰ کیلوبایت</small>
                        </div>
                        <label class="mk-upload-btn" for="primary_image">
                            <i class="fas fa-folder-open"></i> انتخاب فایل
                        </label>
                        <input type="file" id="primary_image" name="primary_image" class="d-none"
                               accept="image/jpeg,image/png,image/webp" required>
                    </div>
                </div>

                <div class="mk-field">
                    <label for="other_images">تصاویر دیگر</label>
                    <div class="mk-upload" data-mk-upload>
                        <img data-mk-upload-preview src="{{ asset('assets/images/upload-placeholder.svg') }}" alt="">
                        <div class="mk-upload-body">
                            <strong data-mk-upload-title>انتخاب چند تصویر</strong>
                            <small>می‌توانید چند فایل را هم‌زمان انتخاب کنید.</small>
                        </div>
                        <label class="mk-upload-btn" for="other_images">
                            <i class="fas fa-images"></i> انتخاب فایل‌ها
                        </label>
                        <input type="file" id="other_images" name="other_images[]" class="d-none"
                               accept="image/jpeg,image/png,image/webp" multiple>
                    </div>
                </div>
            @endif
        </section>

        <section class="mk-adm-panel">
            <header class="mk-adm-panel-head">
                <div>
                    <h4>هزینه ارسال</h4>
                    <p>مبلغ به تومان و برای همه تنوع‌ها یکسان است</p>
                </div>
            </header>

            <div class="mk-form-grid">
                <div class="mk-field mk-field-full">
                    <label for="delivery_amount">هزینه ارسال <em>*</em></label>
                    <div class="mk-input-suffix">
                        <input type="text" name="delivery_amount" id="delivery_amount" class="form-control"
                               inputmode="numeric"
                               value="{{ old('delivery_amount', $isEdit ? $product->delivery_amount : '') }}" required>
                        <span>تومان</span>
                    </div>
                </div>

                <div class="mk-field mk-field-full">
                    <label for="delivery_amount_per_product">هزینه ارسال هر محصول اضافه</label>
                    <div class="mk-input-suffix">
                        <input type="text" name="delivery_amount_per_product" id="delivery_amount_per_product"
                               class="form-control" inputmode="numeric"
                               value="{{ old('delivery_amount_per_product', $isEdit ? $product->delivery_amount_per_product : '') }}">
                        <span>تومان</span>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>دسته‌بندی و تنوع‌ها</h4>
                <p>با انتخاب دسته، فیلترها و ویژگی تنوع به‌صورت خودکار ساخته می‌شوند</p>
            </div>
            <span class="mk-panel-chip {{ old('category_id', $isEdit ? $product->category_id : '') ? '' : 'is-empty' }}"
                  id="categoryChip">
                {{ old('category_id', $isEdit ? $product->category_id : '') ? 'دسته انتخاب‌شده' : 'دسته‌ای انتخاب نشده' }}
            </span>
        </header>

        <div class="mk-category-picker">
            <div class="mk-field">
                <label for="categorySelect">دسته‌بندی <em>*</em></label>
                <select id="categorySelect" class="form-control" name="category_id" data-live-search="true" required>
                    <option value="" disabled @selected(! old('category_id', $isEdit ? $product->category_id : null))>انتخاب کنید…</option>
                    @foreach($categories as $key => $value)
                        <option value="{{ $key }}" @selected((string) old('category_id', $isEdit ? $product->category_id : '') === (string) $key)>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-category-note">
                <i class="fas fa-circle-info"></i>
                <span>ویژگی‌های فیلتر از دسته انتخاب‌شده خوانده می‌شوند.</span>
            </div>
        </div>

        <div id="attributeEmpty" class="mk-attr-empty {{ old('category_id', $isEdit ? $product->category_id : '') ? 'd-none' : '' }}">
            <i class="fas fa-layer-group"></i>
            <strong>هنوز دسته‌ای انتخاب نشده</strong>
            <span>با انتخاب دسته‌بندی، ویژگی‌های فیلتر و جدول تنوع‌ها اینجا ساخته می‌شود.</span>
        </div>

        <div id="attributeContainer" class="mk-attr-area {{ old('category_id', $isEdit ? $product->category_id : '') ? '' : 'd-none' }}">
            <div class="mk-attr-block">
                <div class="mk-attr-head">
                    <h6><i class="fas fa-filter"></i> ویژگی‌های فیلتر</h6>
                    <span class="mk-hint">در فیلترهای فروشگاه نمایش داده می‌شوند.</span>
                </div>
                <div id="filters" class="mk-form-grid">
                    @if($isEdit)
                        @foreach($filters as $filter)
                            <div class="mk-field">
                                <label for="filter-{{ $filter['id'] }}">{{ $filter['title'] }} <em>*</em></label>
                                <input id="filter-{{ $filter['id'] }}" type="text" class="form-control"
                                       value="{{ old('filters_value.' . $filter['id'], $filter['value']) }}"
                                       name="filters_value[{{ $filter['id'] }}]" required>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="mk-attr-block">
                <div class="mk-attr-head">
                    <h6>
                        <i class="fas fa-code-branch"></i>
                        قیمت و موجودی
                        <span id="variationTitle" class="mk-attr-name">{{ $variationAttributeTitle }}</span>
                    </h6>
                    <span class="mk-hint">برای هر تنوع یک ردیف بسازید.</span>
                </div>

                <div id="variationContainer" class="mk-var-list">
                    @if($isEdit)
                        @foreach($variations as $variation)
                            <div class="recordset mk-var-row">
                                <div class="mk-var-row-head">
                                    <span class="mk-var-index">تنوع</span>
                                    <span class="mk-var-id">#{{ $variation['id'] }}</span>
                                </div>
                                <div class="mk-form-grid mk-form-grid--4">
                                    <div class="mk-field">
                                        <label>عنوان <em>*</em></label>
                                        <input type="text" class="form-control" value="{{ $variation['value'] }}"
                                               name="variation_values[{{ $variation['id'] }}][value]" required>
                                    </div>
                                    <div class="mk-field">
                                        <label>قیمت <em>*</em></label>
                                        <input type="number" min="0" step="1" class="form-control"
                                               value="{{ $variation['price'] }}"
                                               name="variation_values[{{ $variation['id'] }}][price]" required>
                                    </div>
                                    <div class="mk-field">
                                        <label>تعداد <em>*</em></label>
                                        <input type="number" min="0" step="1" class="form-control"
                                               value="{{ $variation['quantity'] }}"
                                               name="variation_values[{{ $variation['id'] }}][quantity]" required>
                                    </div>
                                    <div class="mk-field">
                                        <label>شناسه (SKU) <em>*</em></label>
                                        <input type="text" class="form-control" value="{{ $variation['sku'] }}"
                                               name="variation_values[{{ $variation['id'] }}][sku]" required>
                                    </div>
                                    <div class="mk-field">
                                        <label>قیمت با تخفیف</label>
                                        <input type="number" min="0" step="1" class="form-control"
                                               value="{{ $variation['sale_price'] }}"
                                               name="variation_values[{{ $variation['id'] }}][sale_price]">
                                    </div>
                                    <div class="mk-field">
                                        <label>شروع تخفیف</label>
                                        <input type="text" class="form-control" data-jdp
                                               value="{{ $variation['date_on_sale_from'] ? str_replace('-', '/', verta($variation['date_on_sale_from'])) : '' }}"
                                               name="variation_values[{{ $variation['id'] }}][date_on_sale_from]">
                                    </div>
                                    <div class="mk-field">
                                        <label>پایان تخفیف</label>
                                        <input type="text" class="form-control" data-jdp
                                               value="{{ $variation['date_on_sale_to'] ? str_replace('-', '/', verta($variation['date_on_sale_to'])) : '' }}"
                                               name="variation_values[{{ $variation['id'] }}][date_on_sale_to]">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <div id="first">
                        <div class="recordset mk-var-row">
                            <div class="mk-var-row-head">
                                <span class="mk-var-index">تنوع جدید</span>
                            </div>
                            <div class="mk-form-grid mk-form-grid--4">
                                <div class="mk-field">
                                    <label>عنوان <em>*</em></label>
                                    <input type="text" class="form-control variation-value" required>
                                </div>
                                <div class="mk-field">
                                    <label>قیمت <em>*</em></label>
                                    <input type="number" min="0" step="1" class="form-control variation-price" required>
                                </div>
                                <div class="mk-field">
                                    <label>تعداد <em>*</em></label>
                                    <input type="number" min="0" step="1" class="form-control variation-quantity" required>
                                </div>
                                <div class="mk-field">
                                    <label>شناسه (SKU) <em>*</em></label>
                                    <input type="text" class="form-control variation-sku" required>
                                </div>
                                <div class="mk-field">
                                    <label>قیمت با تخفیف</label>
                                    <input type="number" min="0" step="1" class="form-control variation-sale-price">
                                </div>
                                <div class="mk-field">
                                    <label>شروع تخفیف</label>
                                    <input type="text" class="form-control variation-date-from" data-jdp>
                                </div>
                                <div class="mk-field">
                                    <label>پایان تخفیف</label>
                                    <input type="text" class="form-control variation-date-to" data-jdp>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>سوالات متداول</h4>
                <p>هر سوال با پاسخش نمایش داده می‌شود؛ ردیف‌های خالی ذخیره نمی‌شوند</p>
            </div>
            <span class="mk-panel-chip" id="faqCount">۰ سوال</span>
        </header>

        <div id="faqsContainer" class="mk-faq-list">
            @foreach($faqRows as $faq)
                <div class="recordset mk-faq-item" data-faq-id="{{ $faq['id'] }}">
                    <div class="mk-faq-head">
                        <span class="mk-faq-num">۱</span>
                        <span class="mk-faq-title">سوال متداول</span>
                    </div>
                    <div class="mk-field">
                        <label>عنوان سوال</label>
                        <input type="text" class="form-control question" maxlength="1250"
                               name="faqs[{{ $faq['id'] ?? $loop->index }}][question]" value="{{ $faq['question'] }}">
                    </div>
                    <div class="mk-field">
                        <label>پاسخ سوال</label>
                        <textarea class="form-control answer" rows="3" maxlength="1250"
                                  name="faqs[{{ $faq['id'] ?? $loop->index }}][answer]">{{ $faq['answer'] }}</textarea>
                    </div>
                </div>
            @endforeach

            <div id="first">
                <div class="recordset mk-faq-item">
                    <div class="mk-faq-head">
                        <span class="mk-faq-num">۱</span>
                        <span class="mk-faq-title">سوال جدید</span>
                    </div>
                    <div class="mk-field">
                        <label>عنوان سوال</label>
                        <input type="text" class="form-control question" maxlength="1250">
                    </div>
                    <div class="mk-field">
                        <label>پاسخ سوال</label>
                        <textarea class="form-control answer" rows="3" maxlength="1250"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="mk-form-actions mk-product-submit">
        <button type="submit" class="btn btn-info" id="submitButton">
            <i class="fas fa-check"></i>
            {{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد محصول' }}
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">انصراف</a>
    </div>
</form>

@if($isEdit)
    {{-- فرم‌های کمکی تصاویر؛ خارج از فرم اصلی تا تودرتو نشوند --}}
    <form id="productImagesForm" method="POST" enctype="multipart/form-data" class="d-none"
          action="{{ route('admin.products.images.add', $product) }}">
        @csrf
    </form>

    @foreach($product->images as $image)
        <form id="imgPrimary{{ $image->id }}" method="POST" class="d-none"
              action="{{ route('admin.products.images.primary', ['product' => $product, 'image' => $image->id]) }}">
            @csrf
            @method('PATCH')
        </form>
        <form id="imgDelete{{ $image->id }}" method="POST" class="d-none"
              action="{{ route('admin.products.images.destroy', ['product' => $product, 'image' => $image->id]) }}">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endif
