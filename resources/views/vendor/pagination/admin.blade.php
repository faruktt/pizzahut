@if ($paginator->hasPages() || $paginator->total() > 0)
    <nav class="admin-pagination-nav" aria-label="Orders pagination navigation">
        <div class="admin-pagination-info">
            @if ($paginator->total() > 0)
                Showing <span class="fw-bold">{{ $paginator->firstItem() }}</span> to <span class="fw-bold">{{ $paginator->lastItem() }}</span> of <span class="fw-bold">{{ number_format($paginator->total()) }}</span> results
            @else
                Showing <span class="fw-bold">0</span> results
            @endif
        </div>

        @if ($paginator->hasPages())
            <div class="admin-pagination-links">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="page-link-btn disabled" aria-disabled="true" title="Previous Page">
                        <i class="fa-solid fa-chevron-left"></i> Prev
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="page-link-btn" rel="prev" title="Previous Page">
                        <i class="fa-solid fa-chevron-left"></i> Prev
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="page-link-btn dots" aria-disabled="true">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="page-link-btn active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-link-btn">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="page-link-btn" rel="next" title="Next Page">
                        Next <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @else
                    <span class="page-link-btn disabled" aria-disabled="true" title="Next Page">
                        Next <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @endif
            </div>
        @endif
    </nav>
@endif

<style>
.admin-pagination-nav {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    width: 100%;
}
.admin-pagination-info {
    font-size: 0.85rem;
    color: #64748b;
    font-weight: 500;
}
.admin-pagination-info .fw-bold {
    font-weight: 800;
    color: #0f172a;
}
.admin-pagination-links {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    flex-wrap: wrap;
}
.page-link-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-width: 34px;
    height: 34px;
    padding: 0 11px;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
    border-radius: 6px;
    text-decoration: none;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
    user-select: none;
    line-height: 1;
}
.page-link-btn:hover:not(.disabled):not(.active):not(.dots) {
    background: #f8fafc;
    border-color: var(--admin-primary, #0284c7);
    color: var(--admin-primary, #0284c7);
    transform: translateY(-1px);
}
.page-link-btn.active {
    background: var(--admin-primary, #0284c7);
    border-color: var(--admin-primary, #0284c7);
    color: #ffffff;
    font-weight: 800;
    cursor: default;
    box-shadow: 0 2px 4px rgba(2, 132, 199, 0.25);
}
.page-link-btn.disabled {
    color: #94a3b8;
    background: #f8fafc;
    border-color: #e2e8f0;
    cursor: not-allowed;
    opacity: 0.65;
}
.page-link-btn.dots {
    border: none;
    background: transparent;
    cursor: default;
    color: #64748b;
    padding: 0 4px;
    min-width: 20px;
    font-weight: 700;
}
@media (max-width: 640px) {
    .admin-pagination-nav {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
}
</style>

