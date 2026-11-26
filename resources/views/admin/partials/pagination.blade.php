{{-- Pagination for admin tables: $items->links('admin.partials.pagination') --}}
@if ($paginator->hasPages())
<nav class="adm-pagination" aria-label="Pagination">
  <p>Showing {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }}</p>
  <ul>
    <li>
      @if ($paginator->onFirstPage())
        <span class="is-disabled" aria-hidden="true"><i data-lucide="chevron-left"></i></span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i data-lucide="chevron-left"></i><span class="sr-only">Previous page</span></a>
      @endif
    </li>
    @foreach ($elements as $element)
      @if (is_string($element))
        <li><span class="is-disabled">{{ $element }}</span></li>
      @endif
      @if (is_array($element))
        @foreach ($element as $page => $url)
          <li>
            @if ($page == $paginator->currentPage())
              <span aria-current="page">{{ $page }}</span>
            @else
              <a href="{{ $url }}">{{ $page }}<span class="sr-only"> (page)</span></a>
            @endif
          </li>
        @endforeach
      @endif
    @endforeach
    <li>
      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next"><i data-lucide="chevron-right"></i><span class="sr-only">Next page</span></a>
      @else
        <span class="is-disabled" aria-hidden="true"><i data-lucide="chevron-right"></i></span>
      @endif
    </li>
  </ul>
</nav>
@endif
