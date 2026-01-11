@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Pagination Navigation">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="pagination-btn disabled">
                <i class="fas fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev">
                <i class="fas fa-chevron-left"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="pagination-numbers">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="pagination-dots">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="pagination-number active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next">
                <i class="fas fa-chevron-right"></i>
            </a>
        @else
            <span class="pagination-btn disabled">
                <i class="fas fa-chevron-right"></i>
            </span>
        @endif
    </nav>

    <style>
        .pagination {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pagination-btn {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition-base);
        }

        .pagination-btn:hover:not(.disabled) {
            background: var(--primary-50);
            border-color: var(--primary-400);
            color: var(--primary-600);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-numbers {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .pagination-number {
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px solid var(--gray-200);
            border-radius: var(--radius-lg);
            color: var(--gray-700);
            font-weight: 500;
            text-decoration: none;
            transition: var(--transition-base);
        }

        .pagination-number:hover:not(.active) {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }

        .pagination-number.active {
            background: var(--gradient-primary);
            border-color: var(--primary-500);
            color: white;
        }

        .pagination-dots {
            color: var(--gray-400);
            padding: 0 0.5rem;
        }
    </style>
@endif
