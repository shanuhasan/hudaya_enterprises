{{--
  Text-like field with label, help and validation error. Keeps old input after a failed submit.
  <x-admin.input name="name" label="Product name" :value="$product->name" required />
  Extra attributes (maxlength, data-*, placeholder…) go on the <input>.
--}}
@props(['name', 'label', 'value' => null, 'type' => 'text', 'help' => null, 'required' => false, 'id' => null])
@php
    $id ??= 'f-'.str_replace(['[', ']', '.'], '-', $name);
    $key = str_replace(['[', ']'], ['.', ''], $name);
@endphp
<div class="adm-field @error($key) has-error @enderror">
  <label class="adm-label" for="{{ $id }}">{{ $label }} @unless ($required)<small>(optional)</small>@endunless</label>
  <input {{ $attributes->merge(['class' => 'adm-input']) }} id="{{ $id }}" name="{{ $name }}" type="{{ $type }}"
         @if ($type !== 'password') value="{{ old($key, $value) }}" @endif @required($required)
         @if ($help) aria-describedby="{{ $id }}-help" @endif @error($key) aria-invalid="true" @enderror>
  @if ($help)<p class="adm-help" id="{{ $id }}-help">{{ $help }}</p>@endif
  @error($key)<p class="adm-error">{{ $message }}</p>@enderror
</div>
