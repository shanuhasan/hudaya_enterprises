{{--
  <x-admin.select name="category_id" label="Category" :options="[1 => 'Lowers']" :value="$product->category_id" required />
  placeholder: text of an empty first option (omit for none).
--}}
@props(['name', 'label', 'options' => [], 'value' => null, 'help' => null, 'required' => false, 'placeholder' => null, 'id' => null])
@php
    $id ??= 'f-'.$name;
    $selected = (string) old($name, $value instanceof \BackedEnum ? $value->value : $value);
@endphp
<div class="adm-field @error($name) has-error @enderror">
  <label class="adm-label" for="{{ $id }}">{{ $label }} @unless ($required)<small>(optional)</small>@endunless</label>
  <select {{ $attributes->merge(['class' => 'adm-select']) }} id="{{ $id }}" name="{{ $name }}" @required($required) @error($name) aria-invalid="true" @enderror>
    @if ($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
    @foreach ($options as $optionValue => $optionLabel)
      <option value="{{ $optionValue }}" @selected($selected === (string) $optionValue)>{{ $optionLabel }}</option>
    @endforeach
  </select>
  @if ($help)<p class="adm-help">{{ $help }}</p>@endif
  @error($name)<p class="adm-error">{{ $message }}</p>@enderror
</div>
