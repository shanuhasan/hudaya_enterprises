{{-- Success / error message after an action (session "status" or "error"), plus a validation summary. --}}
@if (session('status'))
<div class="adm-flash" data-flash role="status">
  <i data-lucide="circle-check"></i>
  <p>{{ session('status') }}</p>
  <button type="button" class="adm-flash__close" data-dismiss><i data-lucide="x"></i><span class="sr-only">Dismiss</span></button>
</div>
@endif
@if (session('error'))
<div class="adm-flash adm-flash--error" data-flash role="alert">
  <i data-lucide="x"></i>
  <p>{{ session('error') }}</p>
  <button type="button" class="adm-flash__close" data-dismiss><i data-lucide="x"></i><span class="sr-only">Dismiss</span></button>
</div>
@endif
@if ($errors->any())
<div class="adm-flash adm-flash--error" data-flash role="alert">
  <i data-lucide="x"></i>
  <p>Please correct the {{ $errors->count() === 1 ? 'field' : $errors->count().' fields' }} marked below.</p>
</div>
@endif
