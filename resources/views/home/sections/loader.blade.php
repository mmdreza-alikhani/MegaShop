{{--
    لودر گیمینگ مینیمال
    پارامترها:
      $size  : sm | md | lg
      $label : متن زیر/کنار لودر (پیش‌فرض: در حال بارگذاری…)
      $inline: true برای چیدمان افقی
      $overlay: true برای پوشش تمام صفحه
--}}
@php
    $size = $size ?? 'md';
    $inline = $inline ?? false;
    $overlay = $overlay ?? false;
    $labelText = $label ?? 'در حال بارگذاری…';
@endphp

<div @class([
        'mk-loader',
        'mk-loader--' . $size,
        'mk-loader--inline' => $inline,
        'mk-loader--overlay' => $overlay,
        $class ?? '',
    ])
    role="status"
    aria-live="polite">
    <span class="mk-loader-grid" aria-hidden="true">
        <i></i><i></i><i></i>
        <i></i><i></i><i></i>
        <i></i><i></i><i></i>
    </span>

    @if($labelText !== false && $labelText !== null)
        <span class="mk-loader-label">{{ $labelText }}</span>
    @endif
</div>
