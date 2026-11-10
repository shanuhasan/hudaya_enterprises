{{--
  Component: contact-lines — address, phone, email, hours.
  Unset values show bracketed placeholders as plain text (never a broken tel:/mailto: link).
  Props: class (optional, extra classes on <address>)
--}}
@props(['class' => null])
<address class="grid gap-4 not-italic {{ $class }}">
  <p class="contact-line"><i data-lucide="map-pin"></i><span>{{ $site->address ?: '[Street address, City, State, PIN code]' }}</span></p>
  @if ($site->phone_href)
  <a class="contact-line" href="tel:{{ $site->phone_href }}"><i data-lucide="phone"></i><span>{{ $site->phone ?: $site->phone_href }}</span></a>
  @else
  <p class="contact-line"><i data-lucide="phone"></i><span>{{ $site->phone ?: '[Phone number]' }}</span></p>
  @endif
  @if ($site->email)
  <a class="contact-line" href="mailto:{{ $site->email }}"><i data-lucide="mail"></i><span>{{ $site->email }}</span></a>
  @else
  <p class="contact-line"><i data-lucide="mail"></i><span>[Email address]</span></p>
  @endif
  <p class="contact-line"><i data-lucide="clock"></i><span>{{ $site->hours ?: '[Business hours]' }}</span></p>
</address>
