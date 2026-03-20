@extends('layouts.app')
@section('content')

  <div class="login-page">

    {{-- ── Panel izquierdo ── --}}
    <div class="login-left">
      <div class="left-circle-mid"></div>
      <div class="left-content">
        <div class="left-badge">Sistema Mercadex</div>
        <h2 class="left-title">
          Tu trabajo,<br>
          <span>más inteligente</span><br>
          que nunca.
        </h2>
        <p class="left-desc">
          Accede a todos tu productos con un solo click
        </p>
      </div>
    </div>

    {{-- ── Panel derecho (formulario) ── --}}
    <div class="login-right">
      <div class="login-form-wrapper">

        <!-- <div class="form-brand">
          <div class="form-brand-icon">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
          </div>
          <span class="form-brand-name">Mi Aplicación</span>
        </div> -->

        <h1 class="form-title">Iniciar sesión</h1>
        <p class="form-subtitle">Ingresa tus datos para continuar</p>

        @if ($errors->any())
        <div class="alert-error">
          <strong>⚠ Verifica los siguientes campos:</strong>
          <ul class="mb-0 mt-1 ps-3">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
          @csrf

          {{-- Email --}}
          <div>
            <label class="field-label" for="email">Correo electrónico</label>
            <div class="field-wrap">
              <input type="email" id="email" name="email"
                class="field-input" placeholder="correo@ejemplo.com" value="{{ old('email') }}" autocomplete="email"
                required>
              <span class="field-icon">✉</span>
            </div>
            @error('email')
            <div class="field-error">⚠ {{ $message }}</div>
            @enderror
          </div>

          {{-- Contraseña --}}
          <div>
            <label class="field-label" for="password">Contraseña</label>
            <div class="field-wrap">
              <input type="password" id="password" name="password" class="field-input" placeholder="••••••••"
                autocomplete="current-password"
                required>
              <span class="field-icon">🔒</span>
              <button type="button" class="toggle-pwd" onclick="togglePassword()">👁</button>
            </div>
            @error('password')
            <div class="field-error">⚠ {{ $message }}</div>
            @enderror
          </div>

          <!-- {{-- Opciones --}}
          <div class="form-options">
            <label class="remember-label">
              <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
              Recordarme
            </label>
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
            @endif
          </div> -->

          <button type="submit" class="btn-submit">Iniciar sesión →</button>

        </form>

        <!-- <div class="divider">
          <div class="divider-line"></div>
          <span class="divider-text">¿No tienes cuenta?</span>
          <div class="divider-line"></div>
        </div>

        <div class="register-row">
          <span>Únete hoy</span>
          <a href="{{ route('register') }}" class="register-link">Crear cuenta gratis</a>
        </div> -->

      </div>
    </div>

  </div>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const btn = document.querySelector('.toggle-pwd');
      input.type = input.type === 'password' ? 'text' : 'password';
      btn.textContent = input.type === 'password' ? '👁' : '🙈';
    }
  </script>
@endsection