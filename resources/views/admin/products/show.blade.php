@extends('admin.layout.master')

@section('title', 'نمایش محصول')

@php
    $variationRows = collect($variations);
    $prices = $variationRows->pluck('price')->filter()->sort()->values();
    $minPrice = $prices->first();
    $maxPrice = $prices->last();
    $stock = (int) $variationRows->sum('quantity');
    $discountedCount = $variationRows->filter(fn ($item) => ! empty($item['sale_price']) && $item['sale_price'] < $item['price'])->count();
    $discountPercent = 0;
    if ($discountedCount && $minPrice) {
        $bestSale = $variationRows->filter(fn ($item) => ! empty($item['sale_price']) && $item['sale_price'] < $item['price'])->min('sale_price');
        $discountPercent = $bestSale ? (int) round((($minPrice - $bestSale) / max(1, $minPrice)) * 100) : 0;
    }
@endphp

@section('content')
    <main class="bmd-layout-content">
        <div class="container-fluid">
            <div class="mk-dash-head">
                <div>
                    <h3>نمایش محصول</h3>
                    <p>{{ $product->title }}</p>
                </div>
                <div class="mk-dash-actions">
                    <a href="{{ route('home.products.show', ['product' => $product->slug]) }}" target="_blank"
                       rel="noopener" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> در فروشگاه
                    </a>
                    <a href="{{ route('admin.products.edit', ['product' => $product->slug]) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-pen mr-1"></i> ویرایش
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-arrow-right mr-1"></i> بازگشت
                    </a>
                </div>
            </div>

            {{-- هدر محصول --}}
            <section class="mk-product-hero">
                <div class="mk-product-media">
                    <div class="mk-product-cover">
                        <img src="{{ Storage::url(config('upload.product_primary_path') . '/' . $product->primary_image) }}"
                             onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                             alt="{{ $product->title }}">
                        <span class="mk-chip {{ $product->is_active ? 'is-ok' : 'is-bad' }} mk-product-cover-badge">
                            {{ $product->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </div>

                    @if($product->images->isNotEmpty())
                        <div class="mk-product-thumbs">
                            @foreach($product->images->take(6) as $image)
                                <img src="{{ Storage::url(config('upload.product_others_path') . '/' . $image->image) }}"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/images/media-placeholder.svg') }}'"
                                     alt="{{ $product->title }}">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="mk-product-info">
                    <div class="mk-product-badges">
                        <span class="mk-chip">{{ $product->brand?->title ?? 'بدون برند' }}</span>
                        <span class="mk-chip">{{ $product->platform?->title ?? 'بدون پلتفرم' }}</span>
                        <span class="mk-chip">{{ $product->category?->title ?? 'بدون دسته' }}</span>
                        @if($discountPercent > 0)
                            <span class="mk-chip is-accent">{{ $discountPercent }}٪ تخفیف</span>
                        @endif
                    </div>

                    <h2 class="mk-product-title">{{ $product->title }}</h2>

                    <div class="mk-product-price">
                        @if($minPrice)
                            <span class="mk-price-label">از</span>
                            <strong>{{ number_format($minPrice) }}</strong>
                            <span class="mk-price-unit">تومان</span>
                            @if($maxPrice && $maxPrice > $minPrice)
                                <small>تا {{ number_format($maxPrice) }} تومان</small>
                            @endif
                        @else
                            <span class="mk-muted">قیمتی ثبت نشده است</span>
                        @endif
                    </div>

                    <div class="mk-product-stats">
                        <div class="mk-stat">
                            <span>تنوع‌ها</span>
                            <strong>{{ number_format($variationRows->count()) }}</strong>
                        </div>
                        <div class="mk-stat">
                            <span>موجودی کل</span>
                            <strong class="{{ $stock > 0 ? '' : 'is-danger' }}">{{ number_format($stock) }}</strong>
                        </div>
                        <div class="mk-stat">
                            <span>تنوع تخفیف‌دار</span>
                            <strong>{{ number_format($discountedCount) }}</strong>
                        </div>
                        <div class="mk-stat">
                            <span>هزینه ارسال</span>
                            <strong>{{ number_format((int) $product->delivery_amount) }}</strong>
                        </div>
                    </div>

                    <ul class="mk-product-meta">
                        <li>
                            <span>لینک کوتاه</span>
                            <strong dir="ltr">{{ $product->shortLink?->code ? url('/q/' . $product->shortLink->code) : '—' }}</strong>
                        </li>
                        <li>
                            <span>تاریخ ایجاد</span>
                            <strong>{{ verta($product->created_at)->format('%d %B %Y') }}</strong>
                        </li>
                        <li>
                            <span>آخرین ویرایش</span>
                            <strong>{{ verta($product->updated_at)->format('%d %B %Y') }}</strong>
                        </li>
                    </ul>
                </div>
            </section>

            {{-- جزئیات --}}
            <div class="mk-product-grid">
                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>تنوع‌ها و قیمت‌ها</h4>
                            <p>{{ number_format($variationRows->count()) }} تنوع برای این محصول ثبت شده است</p>
                        </div>
                    </header>

                    @if($variationRows->isEmpty())
                        <p class="mk-adm-empty">تنوعی برای این محصول ثبت نشده است.</p>
                    @else
                        <div class="mk-var-table">
                            <table class="table mb-0">
                                <thead>
                                <tr>
                                    <th>تنوع</th>
                                    <th>قیمت</th>
                                    <th>قیمت با تخفیف</th>
                                    <th>موجودی</th>
                                    <th>شناسه (SKU)</th>
                                    <th>بازه تخفیف</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($variationRows as $variation)
                                    <tr>
                                        <td>
                                            <strong>{{ $variation['value'] }}</strong>
                                            @if($variation['title'])
                                                <small class="mk-muted">{{ $variation['title'] }}</small>
                                            @endif
                                        </td>
                                        <td>{{ number_format((int) $variation['price']) }}</td>
                                        <td>
                                            @if($variation['sale_price'])
                                                <span class="mk-price-sale">{{ number_format((int) $variation['sale_price']) }}</span>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            <span class="mk-chip {{ $variation['quantity'] > 0 ? 'is-ok' : 'is-bad' }}">
                                                {{ number_format((int) $variation['quantity']) }}
                                            </span>
                                        </td>
                                        <td><code class="mk-code" dir="ltr">{{ $variation['sku'] }}</code></td>
                                        <td class="mk-muted">
                                            @if($variation['date_on_sale_from'] || $variation['date_on_sale_to'])
                                                {{ $variation['date_on_sale_from'] ? verta($variation['date_on_sale_from'])->format('%d %B') : '—' }}
                                                تا
                                                {{ $variation['date_on_sale_to'] ? verta($variation['date_on_sale_to'])->format('%d %B') : '—' }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>ویژگی‌های فیلتر</h4>
                            <p>مقادیر ثبت‌شده برای این محصول</p>
                        </div>
                    </header>

                    @forelse($filters as $filter)
                        <div class="mk-filter-row">
                            <span>{{ $filter['title'] }}</span>
                            <strong>{{ $filter['value'] }}</strong>
                        </div>
                    @empty
                        <p class="mk-adm-empty">ویژگی فیلتری ثبت نشده است.</p>
                    @endforelse
                </section>

                <section class="mk-adm-panel">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>برچسب‌ها</h4>
                            <p>برچسب‌های این محصول</p>
                        </div>
                    </header>

                    <div class="mk-tags">
                        @forelse($product->tags as $tag)
                            <span class="mk-chip">{{ $tag->title }}</span>
                        @empty
                            <p class="mk-adm-empty">برچسبی ثبت نشده است.</p>
                        @endforelse
                    </div>
                </section>

                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>سوالات متداول</h4>
                            <p>{{ number_format($product->faqs->count()) }} سوال ثبت شده است</p>
                        </div>
                    </header>

                    @forelse($product->faqs as $faq)
                        <details class="mk-faq-acc" @if($loop->first) open @endif>
                            <summary>
                                <span class="mk-faq-num">{{ $loop->iteration }}</span>
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </summary>
                            <p>{{ $faq->answer }}</p>
                        </details>
                    @empty
                        <p class="mk-adm-empty">سوال متداولی ثبت نشده است.</p>
                    @endforelse
                </section>

                <section class="mk-adm-panel mk-product-span-2">
                    <header class="mk-adm-panel-head">
                        <div>
                            <h4>توضیحات محصول</h4>
                            <p>متنی که در صفحه محصول نمایش داده می‌شود</p>
                        </div>
                    </header>

                    <div class="mk-rich-text">
                        {!! $product->description !!}
                    </div>
                </section>
            </div>
        </div>
    </main>
@endsection
