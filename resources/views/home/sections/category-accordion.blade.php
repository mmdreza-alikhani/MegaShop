@foreach ($categories as $category)
    <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="accordion-{{ $parentId }}-{{ $category->id }}-heading">
            <a class="collapsed"
               data-toggle="collapse"
               data-parent="#accordion-{{ $parentId }}"
               href="#accordion-{{ $parentId }}-{{ $category->id }}"
               aria-expanded="false"
               aria-controls="accordion-{{ $parentId }}-{{ $category->id }}">
                {{ $category->title }}
                <span class="panel-heading-arrow fa fa-angle-down"></span>
            </a>
        </div>

        <div id="accordion-{{ $parentId }}-{{ $category->id }}"
             class="panel-collapse collapse"
             role="tabpanel"
             aria-labelledby="accordion-{{ $parentId }}-{{ $category->id }}-heading">

            @if ($category->children->isNotEmpty())
                @php
                    $depth =+ 1
                @endphp
                <div class="nk-accordion"
                     id="accordion-{{ $category->id }}"
                     role="tablist"
                     aria-multiselectable="true"
                     style="margin:0 {{ $depth * 5 }}px;">
                    @include('home.sections.category-accordion', [
                        'categories' => $category->children,
                        'parentId' => $category->id,
                        'depth' => $depth + 1
                    ])
                </div>
            @endif
        </div>
    </div>
@endforeach
