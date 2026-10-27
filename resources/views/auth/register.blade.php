<x-layouts.app title="Регистрация">
    <x-auth-card title="Регистрация" subtitle="Создайте аккаунт, чтобы следить за своими заказами">
        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <x-form-field name="name" label="Имя" autocomplete="name" required autofocus />
            <x-form-field name="email" label="Email" type="email" autocomplete="email" required />
            <x-form-field name="password" label="Пароль" type="password" autocomplete="new-password" required />
            <x-form-field name="password_confirmation" label="Повторите пароль" type="password" autocomplete="new-password" required />

            <button
                type="submit"
                class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                Зарегистрироваться
            </button>
        </form>

        <x-slot:footer>
            Уже есть аккаунт?
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 transition hover:text-brand-500">Войти</a>
        </x-slot:footer>
    </x-auth-card>
</x-layouts.app>
