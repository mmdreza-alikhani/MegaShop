@php
    $isEdit = isset($product) && $product;

    $savedFilters = old('filters_value', []);
    if ($isEdit && empty($savedFilters)) {
        foreach ($filters as $filter) {
            $savedFilters[$filter['id']] = $filter['value'];
        }
    }
@endphp

@include('admin.sections.editor')

<script>
    (function () {
        'use strict';

        var form = document.getElementById('productForm');
        var isEdit = @json($isEdit);
        var attributesUrl = @json(url('/management/categories/__ID__/attributes'));
        var oldFilters = @json($savedFilters);

        var toast = function (message) {
            if (window.toastr) {
                toastr.error(message);

                return;
            }

            window.alert(message);
        };

        var toFa = function (value) {
            return String(value).replace(/\d/g, function (digit) {
                return '۰۱۲۳۴۵۶۷۸۹'[digit];
            });
        };

        /* ---------- انتخابگرها ---------- */
        $('#brandSelect, #platformSelect, #tagSelect, #categorySelect')
            .attr('data-live-search-placeholder', 'جستجو…')
            .selectpicker();

        /* ---------- ردیف‌های تکرارشونده ---------- */
        var repeaterOptions = {
            onAdd: function () {
                MkRepeater.decorate(document);
                renumber();
            },
            onDelete: function () {
                MkRepeater.decorate(document);
                renumber();
            }
        };

        $('#variationContainer').czMore(repeaterOptions);
        $('#faqsContainer').czMore(repeaterOptions);
        MkRepeater.decorate(document);

        $('#variationContainer').siblings('.btnPlus').find('span').text('افزودن تنوع');
        $('#faqsContainer').siblings('.btnPlus').find('span').text('افزودن سوال');

        AdminEditor.create('#description', { height: 320, min_height: 220 });

        if (window.jalaliDatepicker) {
            jalaliDatepicker.startWatch({ minDate: 'today', time: false });
        }

        function variationRows() {
            return Array.prototype.slice.call(document.querySelectorAll('#variationContainer .recordset'));
        }

        function newVariationRows() {
            return variationRows().filter(function (row) {
                return row.querySelector('.variation-value') !== null;
            });
        }

        function variationFieldValues(row) {
            return ['value', 'price', 'quantity', 'sku'].map(function (field) {
                var input = row.querySelector('.variation-' + field);

                return input ? input.value.trim() : '';
            });
        }

        function filledVariationRows() {
            return newVariationRows().filter(function (row) {
                return variationFieldValues(row).some(function (value) {
                    return value !== '';
                });
            });
        }

        function existingVariationRows() {
            return variationRows().filter(function (row) {
                return row.querySelector('input[name^="variation_values["]') !== null;
            });
        }

        function renumber() {
            variationRows().forEach(function (row, index) {
                var badge = row.querySelector('.mk-var-index');

                if (badge) {
                    badge.textContent = 'تنوع ' + toFa(index + 1);
                }
            });

            var faqRows = document.querySelectorAll('#faqsContainer .recordset');

            faqRows.forEach(function (row, index) {
                var badge = row.querySelector('.mk-faq-num');

                if (badge) {
                    badge.textContent = toFa(index + 1);
                }
            });

            var counter = document.getElementById('faqCount');

            if (counter) {
                counter.textContent = toFa(faqRows.length) + ' سوال';
            }
        }

        function syncCategoryChip() {
            var chip = document.getElementById('categoryChip');
            var select = document.getElementById('categorySelect');

            if (! chip || ! select) {
                return;
            }

            var option = select.options[select.selectedIndex];
            var label = option && option.value ? option.text : '';

            chip.textContent = label || 'دسته‌ای انتخاب نشده';
            chip.classList.toggle('is-empty', ! label);
        }

        function addVariationRow() {
            if (newVariationRows().length === 0) {
                $('#variationContainer').siblings('.btnPlus').first().trigger('click');
            }
        }

        function addFaqRow() {
            var container = $('#faqsContainer');

            if (container.children('.recordset').length === 0) {
                container.siblings('.btnPlus').first().trigger('click');
            }
        }

        function loadAttributes(categoryId, selectedFilters) {
            var container = $('#attributeContainer');

            if (! categoryId) {
                container.addClass('d-none');
                $('#filters').empty();
                $('#attributeEmpty').removeClass('d-none');
                syncCategoryChip();

                return;
            }

            $('#attributeEmpty').addClass('d-none');

            $.get(attributesUrl.replace('__ID__', categoryId))
                .done(function (response) {
                    container.removeClass('d-none');
                    $('#attributeEmpty').addClass('d-none');
                    $('#filters').empty();

                    var filters = response.filters || {};
                    var hasFilters = Object.keys(filters).length > 0;

                    if (! hasFilters) {
                        $('#filters').append(
                            $('<p/>', { class: 'mk-muted', text: 'برای این دسته ویژگی فیلتری تعریف نشده است.' })
                        );
                    }

                    $.each(filters, function (attributeId, title) {
                        var field = $('<div/>', { class: 'mk-field' });
                        var label = $('<label/>', { text: title + ' *' });
                        var input = $('<input/>', {
                            type: 'text',
                            class: 'form-control',
                            name: 'filters_value[' + attributeId + ']',
                            value: selectedFilters && selectedFilters[attributeId] ? selectedFilters[attributeId] : '',
                            required: true
                        });

                        field.append(label).append(input);
                        $('#filters').append(field);
                    });

                    var variationTitle = Object.values(response.variation || {})[0] || '';
                    $('#variationTitle').text(variationTitle ? '— ' + variationTitle : '');

                    addVariationRow();
                    syncCategoryChip();
                    renumber();
                })
                .fail(function () {
                    toast('دریافت ویژگی‌های دسته‌بندی انجام نشد.');
                });
        }

        $('#categorySelect').on('changed.bs.select', function () {
            loadAttributes($(this).val());
        });

        loadAttributes($('#categorySelect').val(), oldFilters);
        addFaqRow();
        syncCategoryChip();
        renumber();

        /* ---------- نام‌گذاری فیلدها پیش از ارسال ---------- */
        function resetVariationNames() {
            var fields = {
                value: 'value',
                price: 'price',
                quantity: 'quantity',
                sku: 'sku',
                'sale-price': 'sale_price',
                'date-from': 'date_on_sale_from',
                'date-to': 'date_on_sale_to'
            };

            filledVariationRows().forEach(function (row, index) {
                var prefix = (isEdit ? 'new_variation_values[' : 'variation_values[') + index + ']';

                Object.keys(fields).forEach(function (suffix) {
                    var input = row.querySelector('.variation-' + suffix);

                    if (input) {
                        input.name = prefix + '[' + fields[suffix] + ']';
                    }
                });
            });
        }

        function resetFaqNames() {
            var newIndex = 0;

            document.querySelectorAll('#faqsContainer .recordset').forEach(function (row) {
                var question = row.querySelector('.question');
                var answer = row.querySelector('.answer');

                if (! question || ! answer) {
                    return;
                }

                var id = row.getAttribute('data-faq-id');

                if (! question.value.trim() || ! answer.value.trim()) {
                    question.removeAttribute('name');
                    answer.removeAttribute('name');

                    return;
                }

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

        /* ---------- اعتبارسنجی سمت کلاینت ---------- */
        function firstError() {
            if (! document.getElementById('title').value.trim()) {
                return { message: 'عنوان محصول را وارد کنید.', element: 'title' };
            }

            if (! $('#brandSelect').val()) {
                return { message: 'برند محصول را انتخاب کنید.', element: 'brandSelect' };
            }

            if (! $('#platformSelect').val()) {
                return { message: 'پلتفرم محصول را انتخاب کنید.', element: 'platformSelect' };
            }

            if (! $('#categorySelect').val()) {
                return { message: 'دسته‌بندی محصول را انتخاب کنید.', element: 'categorySelect' };
            }

            if (AdminEditor.isEmpty('#description')) {
                return { message: 'توضیحات محصول را وارد کنید.', element: 'description' };
            }

            if (! isEdit) {
                var primary = document.getElementById('primary_image');

                if (! primary || ! primary.files.length) {
                    return { message: 'تصویر اصلی محصول را انتخاب کنید.', element: 'primary_image' };
                }
            }

            var rows = filledVariationRows();

            if (! rows.length && ! existingVariationRows().length) {
                return { message: 'حداقل یک تنوع (عنوان، قیمت، تعداد و شناسه) اضافه کنید.', element: 'variationContainer' };
            }

            for (var i = 0; i < rows.length; i++) {
                if (variationFieldValues(rows[i]).some(function (value) { return value === ''; })) {
                    return {
                        message: 'ردیف ' + (i + 1) + ' تنوع کامل نیست (عنوان، قیمت، تعداد و شناسه الزامی است).',
                        element: 'variationContainer'
                    };
                }
            }

            var faqError = null;

            document.querySelectorAll('#faqsContainer .recordset').forEach(function (row) {
                var question = row.querySelector('.question');
                var answer = row.querySelector('.answer');

                if (! question || ! answer || faqError) {
                    return;
                }

                if (Boolean(question.value.trim()) !== Boolean(answer.value.trim())) {
                    faqError = 'برای هر سوال متداول، هم عنوان و هم پاسخ را وارد کنید.';
                }
            });

            if (faqError) {
                return { message: faqError, element: 'faqsContainer' };
            }

            return null;
        }

        form.addEventListener('submit', function (event) {
            AdminEditor.save();
            resetVariationNames();
            resetFaqNames();

            var error = firstError();

            if (! error) {
                return;
            }

            event.preventDefault();

            if (error.element === 'description') {
                AdminEditor.focus('#description');
            } else {
                var field = document.getElementById(error.element);

                if (field && field.scrollIntoView) {
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }

            toast(error.message);
        });
    })();
</script>

@if($isEdit)
    <script>
        /* آپلود تصاویر از فرم کمکی، بدون تداخل با فرم اصلی */
        (function () {
            var input = document.getElementById('new_images');

            if (! input) {
                return;
            }

            input.addEventListener('change', function () {
                if (! input.files.length) {
                    return;
                }

                document.getElementById('productImagesForm').submit();
            });
        })();
    </script>
@endif
