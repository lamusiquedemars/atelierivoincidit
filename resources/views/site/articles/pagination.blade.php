@if ($paginator->hasPages())
    <nav class="article-pagination" aria-label="Pagination des articles">
        @if ($paginator->count() > 0)
            <p class="article-pagination__summary">
                Articles {{ $paginator->firstItem() }} à {{ $paginator->lastItem() }} sur {{ $paginator->total() }}
            </p>
        @endif

        <ul class="article-pagination__links">
            @if ($paginator->onFirstPage())
                <li><span class="article-pagination__control" aria-disabled="true">‹ Précédent</span></li>
            @else
                <li><a class="article-pagination__control" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Précédent</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="article-pagination__control">{{ $element }}</span></li>
                @else
                    @foreach ($element as $page => $url)
                        @if ($page === $paginator->currentPage())
                            <li><span class="article-pagination__control" aria-current="page" aria-label="Page {{ $page }}">{{ $page }}</span></li>
                        @else
                            <li><a class="article-pagination__control" href="{{ $url }}" aria-label="Aller à la page {{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="article-pagination__control" href="{{ $paginator->nextPageUrl() }}" rel="next">Suivant ›</a></li>
            @else
                <li><span class="article-pagination__control" aria-disabled="true">Suivant ›</span></li>
            @endif
        </ul>
    </nav>
@endif
