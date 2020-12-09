@if ($paginator->hasPages())
    <nav aria-label="Page navigation">
        <ul class="pagination">

            @if (!$paginator->onFirstPage())
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                        <span aria-hidden="true"><i class="fa fa-long-arrow-left" aria-hidden="true"></i><span>Предыдущая</span></span>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="disabled"><span>{{ $element }}</span></li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                                <li><a class="curent">{{ $page }}</a></li>
                        @else
                            <li><a href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
                        <span aria-hidden="true">
                            <span>Следующая</span>
                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                        </span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif