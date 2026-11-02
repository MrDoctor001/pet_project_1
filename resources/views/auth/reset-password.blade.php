<x-layouts.app title="Новый пароль">
    <x-auth-card title="Новый пароль" subtitle="Придумайте новый пароль для своего аккаунта">
        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <x-form-field name="email" label="Email" type="email" :value="$email" autocomplete="email" required />
            <x-form-field name="password" label="Новый пароль" type="password" autocomplete="new-password" required autofocus />
            <x-form-field name="password_confirmation" label="Повторите пароль" type="password" autocomplete="new-password" required />

            <button
                type="submit"
                class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                Сохранить пароль
            </button>
        </form>
    </x-auth-card>
</x-layouts.app>
