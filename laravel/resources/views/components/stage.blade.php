{{--
  Component: stage — one production stage (image + text), alternating sides.
  Props: id, num, title, text, outcome (optional), variant ("default" | "reverse"), src, alt, brief
--}}
@props([
    'id',
    'num',
    'title',
    'text',
    'outcome' => null,
    'variant' => 'default',
    'src',
    'alt' => '',
    'brief' => '',
])
<article id="{{ $id }}" class="stage stage--{{ $variant }}">
  <div class="stage__media-wrap stage__media" data-reveal>
    <x-media :src="$src" :alt="$alt" :brief="$brief" class="h-full" />
  </div>
  <div class="stage__body" data-reveal>
    <span class="stage__num">{{ $num }}</span>
    <h3 class="stage__title">{{ $title }}</h3>
    <p class="stage__text">{{ $text }}</p>
    @if ($outcome)
    <p class="stage__outcome"><strong>Outcome</strong><span>{{ $outcome }}</span></p>
    @endif
  </div>
</article>
