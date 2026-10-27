<x-layouts.app title="Вход">
    <x-auth-card title="Вход" subtitle="Рады видеть вас снова">
        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <x-form-field name="email" label="Email" type="email" autocomplete="email" required autofocus />
            <x-form-field name="password" label="Пароль" type="password" autocomplete="current-password" required />

            <label class="flex items-center gap-2.5 text-sm text-zinc-600">
                <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="size-4 rounded border-zinc-300 accent-brand-600">
                Запомнить меня
            </label>

            <button
                type="submit"
                class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                Войти
            </button>
        </form>

        <x-slot:footer>
            Нет аккаунта?
            <a href="{{ route('register') }}" class="font-semibold text-brand-600 transition hover:text-brand-500">Зарегистрироваться</a>
        </x-slot:footer>
    </x-auth-card>
</x-layouts.app>
