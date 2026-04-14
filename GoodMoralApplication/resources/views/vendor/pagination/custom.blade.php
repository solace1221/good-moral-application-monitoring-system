@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: center; align-items: center; margin-top: 16px;">
        <ul style="display: flex; list-style: none; padding: 0; margin: 0; gap: 4px; align-items: center;">

            {{-- Previous Page --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; color: #9ca3af; background: #f3f4f6; border-radius: 6px; cursor: not-allowed; user-select: none;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; color: #374151; background: #f3f4f6; border-radius: 6px; text-decoration: none;"
                       onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </a>
                </li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li>
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; color: #9ca3af; background: #f3f4f6; border-radius: 6px;">…</span>
                    </li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li>
                                <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; font-weight: 600; color: #ffffff; background: #16a34a; border-radius: 6px;">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; font-weight: 500; color: #374151; background: #f3f4f6; border-radius: 6px; text-decoration: none;"
                                   onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; color: #374151; background: #f3f4f6; border-radius: 6px; text-decoration: none;"
                       onmouseover="this.style.background='#e5e7eb'" onmouseout="this.style.background='#f3f4f6'">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    </a>
                </li>
            @else
                <li>
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; font-size: 13px; color: #9ca3af; background: #f3f4f6; border-radius: 6px; cursor: not-allowed; user-select: none;">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                    </span>
                </li>
            @endif

        </ul>
    </nav>
@endif
