{{--
  Component: page-hero — image hero for inner pages.
  Props:
    crumb                          current page label in the breadcrumb              (required)
    crumbParent, crumbParentHref   optional middle breadcrumb link                   (optional)
    eyebrow, title, titleEm, lead                                                    (required)
    src, alt, brief                hero image slot (see media)                       (required)
    heroSize                       "page" (default) | "compact"
--}}
@props([
    'crumb',
    'crumbParent' => null,
    'crumbParentHref' => null,
    'eyebrow',
    'title',
    'titleEm' => null,
    'lead',
    'src',
    'alt' => '',
    'brief' => '',
    'heroSize' => 'page',
])
<section class="hero hero--{{ $heroSize }} surface-ink" aria-labelledby="page-title">
  <div class="hero__media" aria-hidden="true">
    <x-media :src="$src" :alt="$alt" :brief="$brief" img-class="hero-settle" loading="eager" priority="high" />
  </div>
  <div class="hero__overlay" aria-hidden="true"></div>

  <div class="container-site hero__inner">
    <div class="hero__content grid gap-10 lg:grid-cols-12 lg:items-end">
      <div class="lg:col-span-8">
        <x-breadcrumb :current="$crumb" :items="$crumbParent ? [['label' => $crumbParent, 'href' => $crumbParentHref]] : []" data-reveal />
        <p class="eyebrow mt-10" data-reveal>{{ $eyebrow }}</p>
        <h1 id="page-title" class="t-h1 mt-6" data-reveal>{{ $title }} <span class="t-em">{{ $titleEm }}</span></h1>
      </div>
      <p class="hero__lead lg:col-span-4" data-reveal>{{ $lead }}</p>
    </div>
  </div>
</section>
