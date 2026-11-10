{{--
  On/off switch. A hidden 0 is sent first, so unticking really saves "off".
  <x-admin.switch name="is_active" label="Active" :checked="$product->is_active" help="Shown on the website" />
--}}
@props(['name', 'label', 'checked' => false, 'help' => null])
<div class="adm-field">
  <input type="hidden" name="{{ $name }}" value="0">
  <label class="adm-switch">
    <span>
      <span class="adm-label block">{{ $label }}</span>
      @if ($help)<span class="adm-help block">{{ $help }}</span>@endif
    </span>
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked))>
  </label>
  @error($name)<p class="adm-error">{{ $message }}</p>@enderror
</div>
