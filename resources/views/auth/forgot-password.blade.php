<x-layouts.app title="Восстановление пароля">
    <x-auth-card title="Забыли пароль?" subtitle="Укажите email — мы пришлём ссылку для сброса пароля">
        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <x-form-field name="email" label="Email" type="email" autocomplete="email" required autofocus />

            <button
                type="submit"
                class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                Отправить ссылку
            </button>
        </form>

        <x-slot:footer>
            Вспомнили пароль?
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 transition hover:text-brand-500">Войти</a>
        </x-slot:footer>
    </x-auth-card>
</x-layouts.app>
