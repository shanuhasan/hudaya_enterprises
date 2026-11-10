{{--
  Component: collection-search — live search for a filterable grid.
  Props: searchId (unique per page), searchLabel (optional, default "Search styles")
  Behaviour: resources/js/modules/collection-filter.js
--}}
@props(['searchId', 'searchLabel' => 'Search styles'])
<div class="search-field" role="search">
  <i data-lucide="search"></i>
  <label class="sr-only" for="search-{{ $searchId }}">{{ $searchLabel }}</label>
  <input class="search-field__input" id="search-{{ $searchId }}" type="search" placeholder="{{ $searchLabel }}" autocomplete="off" spellcheck="false" enterkeyhint="search" data-collection-search>
  <button type="button" class="search-field__clear" data-search-clear hidden>
    <i data-lucide="x"></i><span class="sr-only">Clear search</span>
  </button>
</div>
