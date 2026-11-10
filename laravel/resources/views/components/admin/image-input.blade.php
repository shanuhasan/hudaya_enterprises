{{--
  Single image upload with live preview of the current / chosen file.
  <x-admin.image-input name="image" label="Featured image" :current="$product->image" />
  variant="logo" shows the preview on black (for the light logo).
--}}
@props(['name', 'label', 'current' => null, 'help' => 'JPG, PNG or WebP, up to 5 MB.', 'required' => false, 'variant' => 'photo'])
@php($id = 'f-'.$name)
<div class="adm-field @error($name) has-error @enderror">
  <label class="adm-label" for="{{ $id }}">{{ $label }} @unless ($required)<small>(optional)</small>@endunless</label>
  <div class="adm-upload">
    <img id="{{ $id }}-preview" src="{{ $current ? asset($current) : '' }}" alt="" class="adm-upload__preview {{ $variant === 'logo' ? 'adm-upload__preview--logo' : '' }}" @unless ($current) hidden @endunless>
    <input class="adm-file" id="{{ $id }}" name="{{ $name }}" type="file" accept="image/jpeg,image/png,image/webp" data-preview="#{{ $id }}-preview" @required($required)>
  </div>
  @if ($help)<p class="adm-help">{{ $help }}</p>@endif
  @error($name)<p class="adm-error">{{ $message }}</p>@enderror
</div>
