{{-- Page header: eyebrow, title, optional subtitle, back link and actions (slot). --}}
@props(['title', 'eyebrow' => null, 'sub' => null, 'back' => null, 'backLabel' => 'Back'])
<div class="adm-head">
  <div>
    @if ($back)
      <a href="{{ $back }}" class="adm-crumb"><i data-lucide="arrow-left"></i>{{ $backLabel }}</a>
    @endif
    @if ($eyebrow)<p class="adm-eyebrow">{{ $eyebrow }}</p>@endif
    <h1 class="adm-title">{{ $title }}</h1>
    @if ($sub)<p class="adm-sub">{{ $sub }}</p>@endif
  </div>
  @if (trim($slot) !== '')
    <div class="flex flex-wrap gap-3">{{ $slot }}</div>
  @endif
</div>
