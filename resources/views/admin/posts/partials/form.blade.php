@php
    $isEdit = isset($post) && $post;
    $action = $isEdit ? route('admin.posts.update', ['post' => $post]) : route('admin.posts.store');
    $errorBag = $isEdit ? 'update' : 'store';

    $faqRows = [];
    if ($isEdit) {
        foreach ($post->faqs as $faq) {
            $faqRows[] = ['id' => $faq->id, 'question' => $faq->question, 'answer' => $faq->answer];
        }
    }
@endphp

<form id="postForm" method="POST" action="{{ $action }}" class="mk-product-form" enctype="multipart/form-data">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    @include('admin.layout.errors', ['errors' => $errors->{$errorBag}])

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>اطلاعات پست</h4>
                <p>عنوان، نوع محتوا و وضعیت انتشار</p>
            </div>
            @if($isEdit)
                <span class="mk-panel-chip">شناسه #{{ $post->id }}</span>
            @endif
        </header>

        <div class="mk-form-grid">
            <div class="mk-field mk-field-full">
                <label for="title">عنوان <em>*</em></label>
                <input type="text" name="title" id="title" class="form-control" maxlength="255"
                       value="{{ old('title', $isEdit ? $post->title : '') }}" required>
            </div>

            <div class="mk-field">
                <label for="type">نوع محتوا <em>*</em></label>
                <select name="type" id="type" class="form-control" required>
                    <option value="article" @selected(old('type', $isEdit ? $post->type : 'article') === 'article')>مقاله</option>
                    <option value="news" @selected(old('type', $isEdit ? $post->type : 'article') === 'news')>خبر</option>
                </select>
            </div>

            <div class="mk-field">
                <label for="is_active">وضعیت</label>
                <select name="is_active" id="is_active" class="form-control">
                    <option value="1" @selected((string) old('is_active', $isEdit ? $post->getRawOriginal('is_active') : '1') === '1')>فعال</option>
                    <option value="0" @selected((string) old('is_active', $isEdit ? $post->getRawOriginal('is_active') : '1') === '0')>غیرفعال</option>
                </select>
            </div>

            <div class="mk-field">
                <label for="short_link">لینک کوتاه</label>
                <input type="text" name="short_link" id="short_link" class="form-control" dir="ltr" maxlength="12"
                       value="{{ old('short_link', $isEdit ? $post->shortLink?->code : '') }}"
                       placeholder="خودکار">
                <small>خالی بگذارید تا خودکار ساخته شود.</small>
            </div>

            <div class="mk-field mk-field-full">
                <label for="tagSelect">برچسب‌ها</label>
                <select id="tagSelect" class="form-control" name="tag_ids[]" multiple data-live-search="true"
                        title="انتخاب برچسب">
                    @foreach($tags as $key => $value)
                        <option value="{{ $key }}"
                            @selected(in_array($key, old('tag_ids', $isEdit ? $post->tags->pluck('id')->all() : [])))>{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mk-field mk-field-full mk-field--editor">
                <label for="text">متن پست <em>*</em></label>
                <textarea id="text" name="text" class="form-control" rows="10"
                          required>{{ old('text', $isEdit ? $post->text : '') }}</textarea>
            </div>
        </div>
    </section>

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>تصویر شاخص</h4>
                <p>{{ $isEdit ? 'در صورت نیاز تصویر را جایگزین کنید' : 'تصویر پست الزامی است' }}</p>
            </div>
        </header>

        @if($isEdit)
            <div class="mk-media-primary">
                <img src="{{ Storage::url(config('upload.post_path') . '/' . $post->image) }}"
                     onerror="this.onerror=null;this.src='{{ asset('assets/images/upload-placeholder.svg') }}'"
                     alt="{{ $post->title }}">
                <div>
                    <strong>تصویر فعلی</strong>
                    <small>{{ $post->image }}</small>
                </div>
            </div>
        @endif

        <div class="mk-field">
            <label for="image">{{ $isEdit ? 'تصویر جدید (اختیاری)' : 'تصویر پست' }} @if(! $isEdit)<em>*</em>@endif</label>
            <div class="mk-upload" data-mk-upload>
                <img data-mk-upload-preview
                     src="{{ $isEdit ? Storage::url(config('upload.post_path') . '/' . $post->image) : asset('assets/images/upload-placeholder.svg') }}"
                     alt="">
                <div class="mk-upload-body">
                    <strong data-mk-upload-title>{{ $isEdit ? 'انتخاب تصویر جدید' : 'انتخاب یا رها کردن تصویر' }}</strong>
                    <small>jpg، png، webp یا svg — حداکثر ۲ مگابایت</small>
                </div>
                <label class="mk-upload-btn" for="image">
                    <i class="fas fa-folder-open"></i> انتخاب فایل
                </label>
                <input type="file" id="image" name="image" class="d-none"
                       accept="image/jpeg,image/png,image/webp,image/svg+xml" @required(! $isEdit)>
            </div>
        </div>
    </section>

    <section class="mk-adm-panel">
        <header class="mk-adm-panel-head">
            <div>
                <h4>سوالات متداول</h4>
                <p>سوال‌های پرتکرار درباره این مطلب</p>
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
                               name="faqs[{{ $faq['id'] }}][question]" value="{{ $faq['question'] }}">
                    </div>
                    <div class="mk-field">
                        <label>پاسخ سوال</label>
                        <textarea class="form-control answer" rows="3" maxlength="1250"
                                  name="faqs[{{ $faq['id'] }}][answer]">{{ $faq['answer'] }}</textarea>
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
            {{ $isEdit ? 'ذخیره تغییرات' : 'ایجاد پست' }}
        </button>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-light">انصراف</a>
    </div>
</form>

@include('admin.sections.editor')

@section('scripts')
<script>
    (function () {
        'use strict';

        var isEdit = @json($isEdit);
        var form = document.getElementById('postForm');

        $('#tagSelect').attr('data-live-search-placeholder', 'جستجو…').selectpicker();

        var repeaterOptions = {
            onAdd: function () { MkRepeater.decorate(document); renumber(); },
            onDelete: function () { MkRepeater.decorate(document); renumber(); }
        };

        $('#faqsContainer').czMore(repeaterOptions);
        MkRepeater.decorate(document);
        $('#faqsContainer').siblings('.btnPlus').find('span').text('افزودن سوال');

        AdminEditor.create('#text', { height: 360, min_height: 240 });

        function toFa(value) {
            return String(value).replace(/\d/g, function (digit) {
                return '۰۱۲۳۴۵۶۷۸۹'[digit];
            });
        }

        function renumber() {
            var rows = document.querySelectorAll('#faqsContainer .recordset');

            rows.forEach(function (row, index) {
                var badge = row.querySelector('.mk-faq-num');

                if (badge) {
                    badge.textContent = toFa(index + 1);
                }
            });

            var counter = document.getElementById('faqCount');

            if (counter) {
                counter.textContent = toFa(rows.length) + ' سوال';
            }
        }

        function addFaqRow() {
            if (document.querySelectorAll('#faqsContainer .recordset').length === 0) {
                $('#faqsContainer').siblings('.btnPlus').first().trigger('click');
            }
        }

        function resetFaqNames() {
            var newIndex = 0;

            document.querySelectorAll('#faqsContainer .recordset').forEach(function (row) {
                var question = row.querySelector('.question');
                var answer = row.querySelector('.answer');

                if (! question || ! answer) {
                    return;
                }

                if (! question.value.trim() || ! answer.value.trim()) {
                    question.removeAttribute('name');
                    answer.removeAttribute('name');

                    return;
                }

                var id = row.getAttribute('data-faq-id');

                if (id) {
                    question.name = 'faqs[' + id + '][question]';
                    answer.name = 'faqs[' + id + '][answer]';

                    return;
                }

                var group = isEdit ? 'newFaqs' : 'faqs';
                question.name = group + '[' + newIndex + '][question]';
                answer.name = group + '[' + newIndex + '][answer]';
                newIndex++;
            });
        }

        form.addEventListener('submit', function (event) {
            AdminEditor.save();
            resetFaqNames();

            if (! document.getElementById('title').value.trim()) {
                event.preventDefault();
                toastr.error('عنوان پست را وارد کنید.');

                return;
            }

            if (AdminEditor.isEmpty('#text')) {
                event.preventDefault();
                AdminEditor.focus('#text');
                toastr.error('متن پست را وارد کنید.');
            }
        });

        addFaqRow();
        renumber();
    })();
</script>
@endsection
