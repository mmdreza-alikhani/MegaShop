@extends('admin.layout.master')

@php
    $title = 'ایجاد دسته بندی';
@endphp

@section('title', $title)

@section('styles')
    <style>
        .disabled-option{
            color: #999;
            background-color: #f9f9f9;
        }
    </style>
@endsection

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="row m-1 pb-4 mb-3">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 p-2">
                    <div class="page-header breadcrumb-header">
                        <div class="row align-items-end">
                            <div class="col-lg-8">
                                <div class="page-header-title text-left-rtl">
                                    <div class="d-inline">
                                        <h3 class="lite-text">پنل مدیریت</h3>
                                        <span class="lite-text">{{ $title }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <ol class="breadcrumb float-sm-right">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.panel') }}"><i class="fas fa-home"></i></a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $title }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mx-1">
                <div class="card shade c-grey w-100">
                    <h5 class="card-header c-primary">{{ $title }}</h5>
                    <div class="card-body">
                        @include('admin.layout.errors', ['errors' => $errors->store])
                        <form class="row p-3" action="{{ route('admin.categories.store') }}" method="POST">
                            @csrf
                            <div class="form-group col-12 col-lg-4">
                                <label for="title">عنوان:*</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="parent_id">والد: *</label>
                                <select class="form-control" id="parent_id" name="parent_id" required>
                                    <option value="0">بدون والد</option>
                                    @foreach($parentCategories as $key => $value)
                                        <option value="{{ $key }}" {{ old('parent_id') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="is_active">وضعیت: *</label>
                                <select class="form-control" id="is_active" name="is_active" required>
                                    <option value="1" {{ old('is_active') && old('is_active') == 1 ? 'selected' : '' }}>فعال</option>
                                    <option value="0" {{ old('is_active') && old('is_active') == 0 ? 'selected' : '' }}>غیرفعال</option>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="attributeIsFilter">ویژگی های قابل فیلتر: *</label>
                                <select id="attributeIsFilter" class="form-control" name="filter_attribute_ids[]" data-live-search="true" required multiple>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="attributeIsVariation">ویژگی متغیر: *</label>
                                <select id="attributeIsVariation" class="form-control" name="variation_attribute_id" data-live-search="true" required>
                                </select>
                            </div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="icon">آیکون:</label>
                                <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon') }}">
                            </div>
                            <div class="form-group col-12 col-lg-12">
                                <label for="description">توضیحات:</label>
                                <textarea type="text" name="description" id="description"
                                          class="form-control">{{ old('description') }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn f-primary text-left">ادامه</button>
                                <a href="{{ url()->previous() }}" class="btn f-secondary">بازگشت</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script>
        const attributes = @json($attributes);

        const attributeSelects = {
            '#attributeIsFilter': 'ویژگی‌های قابل فیلتر',
            '#attributeIsVariation': 'ویژگی متغیر'
        };

        Object.entries(attributeSelects).forEach(([selector, title]) => {
            $(selector).selectpicker({ title });
        });

        $(document).ready(function () {
            const attributes = @json($attributes); // Laravel-passed data

            const $filterSelect = $('#attributeIsFilter');
            const $variationSelect = $('#attributeIsVariation');

            // Populate both selects
            function populateSelects(data) {
                $.each(data, function (key, value) {
                    $filterSelect.append(new Option(value, key));
                    $variationSelect.append(new Option(value, key));
                });
                $filterSelect.selectpicker('refresh');
                $variationSelect.selectpicker('refresh');
            }

            function syncOptions() {
                const selectedFilters = $filterSelect.val() || [];
                const selectedVariation = $variationSelect.val();

                // Filter → Variation
                $variationSelect.find('option').each(function () {
                    const val = $(this).val();
                    if (selectedFilters.includes(val)) {
                        $(this).prop('disabled', true).prop('selected', false).addClass('disabled-option');
                    } else {
                        $(this).prop('disabled', false).removeClass('disabled-option');
                    }
                });

                // Variation → Filter
                $filterSelect.find('option').each(function () {
                    const val = $(this).val();
                    if (val === selectedVariation) {
                        $(this).prop('disabled', true).prop('selected', false).addClass('disabled-option');
                    } else {
                        $(this).prop('disabled', false).removeClass('disabled-option');
                    }
                });

                // One refresh per field
                $filterSelect.selectpicker('refresh');
                $variationSelect.selectpicker('refresh');
            }


            // Initial populate
            populateSelects(attributes);
            syncOptions();

            // Re-sync on change
            $filterSelect.on('change', syncOptions);
            $variationSelect.on('change', syncOptions);
        });
    </script>
@endsection
