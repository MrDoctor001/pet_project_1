<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Страница ввода нового пароля по ссылке из письма
     */
    public function create(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    /**
     * Сохраняет новый пароль и отправляет на страницу входа
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );

        if ($status === Password::PasswordReset) {
            return redirect()->route('login')->with('status', 'Пароль изменён, теперь можно войти');
        }

        return back()
            ->withErrors(['email' => 'Ссылка недействительна или устарела, запросите новую'])
            ->onlyInput('email');
    }
}
