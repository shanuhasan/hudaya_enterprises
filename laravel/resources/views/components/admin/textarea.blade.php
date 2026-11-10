{{-- <x-admin.textarea name="description" label="Description" :value="$product->description" rows="6" /> --}}
@props(['name', 'label', 'value' => null, 'help' => null, 'required' => false, 'id' => null])
@php($id ??= 'f-'.$name)
<div class="adm-field @error($name) has-error @enderror">
  <label class="adm-label" for="{{ $id }}">{{ $label }} @unless ($required)<small>(optional)</small>@endunless</label>
  <textarea {{ $attributes->merge(['class' => 'adm-textarea']) }} id="{{ $id }}" name="{{ $name }}" @required($required)
            @if ($help) aria-describedby="{{ $id }}-help" @endif @error($name) aria-invalid="true" @enderror>{{ old($name, $value) }}</textarea>
  @if ($help)<p class="adm-help" id="{{ $id }}-help">{{ $help }}</p>@endif
  @error($name)<p class="adm-error">{{ $message }}</p>@enderror
</div>
