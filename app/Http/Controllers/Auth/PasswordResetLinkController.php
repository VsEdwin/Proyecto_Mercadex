<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    // Mostrar formulario de "olvidé mi contraseña"
    public function create()
    {
        return view('auth.forgot-password');
    }

    // Enviar el correo con el link
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', '¡Correo enviado! Revisa tu bandeja de entrada.')
            : back()->withErrors(['email' => 'No encontramos un usuario con ese correo.']);
    }
}