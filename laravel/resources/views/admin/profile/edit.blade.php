<x-admin.layout title="Admin profile">
  <x-admin.page-head eyebrow="Account" title="Admin profile"
                     :sub="'Last sign-in: '.($user->last_login_at?->format('d M Y, H:i') ?? 'now')" />

  <div class="grid gap-8 xl:grid-cols-2">
    <section class="adm-card" aria-labelledby="profile-title">
      <div class="adm-card__head"><h2 id="profile-title" class="adm-card__title">Your details</h2></div>
      <form method="POST" action="{{ route('admin.profile.update') }}" class="adm-card__body adm-form-grid" novalidate>
        @csrf @method('PUT')
        <x-admin.input name="name" label="Name" :value="$user->name" required maxlength="120" autocomplete="name" />
        <x-admin.input name="email" label="Email (used to sign in)" type="email" :value="$user->email" required maxlength="190" autocomplete="email" />
        <div><button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>Save details</button></div>
      </form>
    </section>

    <section class="adm-card" aria-labelledby="password-title">
      <div class="adm-card__head"><h2 id="password-title" class="adm-card__title">Change password</h2></div>
      <form method="POST" action="{{ route('admin.profile.password') }}" class="adm-card__body adm-form-grid" novalidate>
        @csrf @method('PUT')
        <x-admin.input name="current_password" label="Current password" type="password" required autocomplete="current-password" />
        <x-admin.input name="password" label="New password" type="password" required autocomplete="new-password" help="At least 10 characters, with letters and numbers." />
        <x-admin.input name="password_confirmation" label="Repeat new password" type="password" required autocomplete="new-password" />
        <div><button type="submit" class="adm-btn adm-btn--primary"><i data-lucide="check"></i>Change password</button></div>
      </form>
    </section>
  </div>
</x-admin.layout>
