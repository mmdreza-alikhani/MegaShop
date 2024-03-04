@extends('admin.layouts.master')
@section('title')
    ویرایش دسته بندی محصول:  {{$product->name}}
@endsection
@php
    $active_parent = 'products';
    $active_child = ''
@endphp
@section('content')
    <div class="mx-4">
        @include('admin.sections.errors')
        <form action="{{ route('admin.products.category.update', ['product' => $product]) }}" method="POST" class="row" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="col-lg-12 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        تنظیم دسته بندی
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12 col-lg-4"></div>
                            <div class="form-group col-12 col-lg-4">
                                <label for="categorySelect">دسته بندی*</label>
                                <select id="categorySelect" class="form-control" name="category_id" data-live-search="true">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $product->category->id ? 'selected' : ''}}>{{ $category->name }}-{{ $category->parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="attributeContainer">
                            {{--Show Attributes--}}
                            <div id="attributes" class="row"></div>
                            {{--End Show Attributes--}}
                            {{--Show Variation--}}
                            <hr>
                            افزودن قیمت و موجودی برای متغیر
                            <span id="variationName" class="font-weight-bold">

                            </span>:
                            <div id="czContainer">
                                <div id="first">
                                    <div class="recordset">
                                        <div class="row">
                                            <span class="col-12 col-lg-3 my-2">
                                                <label>نام</label>
                                                <input type="text" name="variation_values[value][]" class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label>قیمت</label>
                                                <input type="text" name="variation_values[price][]" class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label>تعداد</label>
                                                <input type="text" name="variation_values[quantity][]" class="form-control">
                                            </span>
                                            <span class="col-12 col-lg-3 my-2">
                                                <label>شناسه عددی</label>
                                                <input type="text" name="variation_values[sku][]" class="form-control">
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- The elements you want repeated must be wrapped in an element with id="recordset" -->
                            {{--End Show Variation--}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card">
                    <div class="card-header bg-primary">
                        انتشار
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <button class="btn btn-primary w-100" type="submit" name="submit">ویرایش</button>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-danger w-100" type="cancel" name="cancel">بازگشت</a>
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
        $('#categorySelect').selectpicker({
            'title' : 'انتخاب دسته بندی'
        });


        $('#attributeContainer').hide()
        $('#categorySelect').on('changed.bs.select' , function () {
            const categoryId = $(this).val();

            $.get(`{{ url('/admin-panel/management/get-category-attribute/${categoryId}') }}`, function(response , status){
                if(status === 'success'){
                    $('#attributeContainer').fadeIn()

                    $('#attributes').find('div').remove();
                    // console.log(response.attributes);

                    response.attributes.forEach(attribute => {
                        const attributeFormgroup = $('<div/>', {
                            class : 'form-group col-12 col-lg-4'
                        });
                        const attributeLable = $('<lable/>', {
                            for : attribute.name,
                            text : attribute.name
                        });
                        const attributeInput = $('<input/>', {
                            id : attribute.name,
                            type : 'text',
                            class : 'form-control',
                            name : `attribute_ids[${attribute.id}]`
                        });

                        attributeFormgroup.append(attributeLable);
                        attributeFormgroup.append(attributeInput);

                        $('#attributes').append(attributeFormgroup)
                    })

                    response.variation.forEach(variation => {
                        $('#variationName').text(variation.name);
                    })

                }else{
                    alert('مشکلی پیش آمد!')
                }
            }).fail(function(){
                alert('مشکلی پیش آمد!')
            })

        })

        $("#czContainer").czMore();
    </script>
@endsection
