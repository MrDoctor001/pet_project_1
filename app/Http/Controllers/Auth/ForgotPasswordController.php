<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Страница восстановления пароля
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Отправляет на почту ссылку для сброса пароля
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::ResetLinkSent) {
            return back()->with('status', 'Мы отправили ссылку для сброса пароля на вашу почту');
        }

        if ($status === Password::InvalidUser) {
            return back()
                ->withErrors(['email' => 'Такой email не зарегистрирован'])
                ->onlyInput('email');
        }

        return back()
            ->withErrors(['email' => 'Ссылка уже отправлена. Повторить можно через минуту'])
            ->onlyInput('email');
    }
}
