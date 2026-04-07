@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" style="display: flex; justify-content: space-between; align-items: center;">
        <div style="flex: 1;">
            <p style="font-size: 14px; color: #6b7280; margin: 0;">
                Showing
                <span style="font-weight: 600;">{{ $paginator->firstItem() }}</span>
                to
                <span style="font-weight: 600;">{{ $paginator->lastItem() }}</span>
                of
                <span style="font-weight: 600;">{{ $paginator->total() }}</span>
                results
            </p>
        </div>

        <div style="flex: 1; display: flex; justify-content: flex-end;">
            <ul style="display: flex; list-style: none; padding: 0; margin: 0; gap: 4px;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li>
                        <span style="display: inline-flex; align-items: center; padding: 8px 12px; font-size: 14px; font-weight: 500; color: #9ca3af; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; cursor: not-allowed;">
                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" style="display: inline-flex; align-items: center; padding: 8px 12px; font-size: 14px; font-weight: 500; color: #374151; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; transition: all 0.15s;">
                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li>
                            <span style="display: inline-flex; align-items: center; padding: 8px 12px; font-size: 14px; font-weight: 500; color: #6b7280; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px;">{{ $element }}</span>
                        </li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li>
                                    <span style="display: inline-flex; align-items: center; padding: 8px 14px; font-size: 14px; font-weight: 600; color: #ffffff; background-color: #10b981; border: 1px solid #10b981; border-radius: 6px;">{{ $page }}</span>
                                </li>
                            @else
                                <li>
                                    <a href="{{ $url }}" style="display: inline-flex; align-items: center; padding: 8px 14px; font-size: 14px; font-weight: 500; color: #374151; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; transition: all 0.15s;">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" style="display: inline-flex; align-items: center; padding: 8px 12px; font-size: 14px; font-weight: 500; color: #374151; background-color: #ffffff; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; transition: all 0.15s;">
                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    </li>
                @else
                    <li>
                        <span style="display: inline-flex; align-items: center; padding: 8px 12px; font-size: 14px; font-weight: 500; color: #9ca3af; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; cursor: not-allowed;">
                            <svg style="width: 16px; height: 16px;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </span>
                    </li>
                @endif
            </ul>
        </div>
    </nav>
@endif
