{{--
  Component: breadcrumb
  Props:
    items    links between Home and the current page, e.g.
             [['label' => 'Products', 'href' => route('products.index')]]   (Home is added automatically)
    current  label of the current page
  Extra attributes (e.g. data-reveal) are passed to the <nav>.
--}}
@props(['items' => [], 'current'])
<nav {{ $attributes->merge(['class' => 'crumbs']) }} aria-label="Breadcrumb">
  <ol>
    <li><a href="{{ route('home') }}">Home</a></li>
    @foreach ($items as $item)
    <li><a href="{{ $item['href'] }}">{{ $item['label'] }}</a></li>
    @endforeach
    <li><span aria-current="page">{{ $current }}</span></li>
  </ol>
</nav>
