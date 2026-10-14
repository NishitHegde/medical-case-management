@if ($paginator->hasPages())
    <nav aria-label="Medical Cases Page Navigation">
        <ul class="pagination pagination-sm mb-0 gap-1 align-items-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-2 px-3 py-1 text-muted border bg-light">
                        <i class="bi bi-chevron-left me-1"></i> Prev
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link rounded-2 px-3 py-1 border text-dark fw-semibold" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                        <i class="bi bi-chevron-left me-1"></i> Prev
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link rounded-2 px-2 py-1 border bg-light text-muted">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link rounded-2 px-3 py-1 fw-bold bg-primary text-white border-primary shadow-sm">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link rounded-2 px-3 py-1 border text-dark fw-medium" href="{{ $url }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link rounded-2 px-3 py-1 border text-dark fw-semibold" href="{{ $paginator->nextPageUrl() }}" rel="next">
                        Next <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link rounded-2 px-3 py-1 text-muted border bg-light">
                        Next <i class="bi bi-chevron-right ms-1"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
