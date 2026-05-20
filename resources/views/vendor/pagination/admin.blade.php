@php
    $hasPages = $paginator->hasPages();
@endphp

<div class="d-flex justify-content-center mt-3">
    <nav aria-label="Page navigation">
        <ul class="pagination pagination-sm rounded-pill bg-white shadow-sm p-1 mb-0">
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}" aria-label="First">««</a>
            </li>
            <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                <a class="page-link" href="{{ $paginator->previousPageUrl() ?: '#' }}" rel="prev" aria-label="Previous">«</a>
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}" aria-current="page">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->nextPageUrl() ?: '#' }}" rel="next" aria-label="Next">»</a>
            </li>
            <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}" aria-label="Last">»»</a>
            </li>
        </ul>
    </nav>
</div>
