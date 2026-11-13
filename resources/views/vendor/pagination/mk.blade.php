@if ($paginator->hasPages())
    <ul class="mk-pagination">
        {{-- قبلی --}}
        @if ($paginator->onFirstPage())
            <li class="is-disabled"><span>قبلی</span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev">قبلی</a></li>
        @endif

        {{-- شماره صفحه‌ها --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="is-disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="is-active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- بعدی --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next">بعدی</a></li>
        @else
            <li class="is-disabled"><span>بعدی</span></li>
        @endif
    </ul>
@endif
