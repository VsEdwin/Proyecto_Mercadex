@extends('layouts.app')

@section('content')

<div class="register-page">

  {{-- Panel izquierdo --}}
  <div class="reg-left">
    <div class="left-circle"></div>
    <div class="reg-left-content">
      <div class="left-badge">✨ Únete gratis</div>
      <h2 class="left-title">Comienza hoy</h2>
      <p class="left-desc">
        Crea tu cuenta en segundos y accede a todas las herramientas de Mercadex desde cualquier lugar.
      </p>
      <div class="left-steps">
        <div class="step-item">
          <div class="step-num">1</div>
          <span class="step-text">Ingresa tus datos básicos</span>
        </div>
        <div class="step-item">
          <div class="step-num">2</div>
          <span class="step-text">Crea una contraseña segura</span>
        </div>
        <div class="step-item">
          <div class="step-num">3</div>
          <span class="step-text">Accede a tu panel al instante</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Panel derecho --}}
  <div class="reg-right">
    <div class="reg-form-wrapper">

      <div class="form-brand">
        <div class="form-brand-icon">
          <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <span class="form-brand-name">Mercadex</span>
      </div>

      <h1 class="form-title">Crear cuenta</h1>
      <p class="form-subtitle">Completa los datos para registrarte</p>

      {{-- Éxito --}}
      @if(session('success'))
        <div class="alert-success-custom">✅ {{ session('success') }}</div>
      @endif

      {{-- Errores --}}
      @if($errors->any())
        <div class="alert-error">
          <strong>⚠ Corrige los siguientes errores:</strong>
          <ul class="mb-0 mt-1 ps-3">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ url('/register') }}">
        @csrf

        {{-- Nombre --}}
        <div>
          <label class="field-label" for="name">Nombre completo</label>
          <div class="field-wrap">
            <input type="text" id="name" name="name" class="field-input"
              placeholder="Tu nombre" value="{{ old('name') }}" required autofocus autocomplete="name">
            <span class="field-icon">👤</span>
          </div>
          @error('name') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div>
          <label class="field-label" for="email">Correo electrónico</label>
          <div class="field-wrap">
            <input type="email" id="email" name="email" class="field-input"
              placeholder="correo@ejemplo.com" value="{{ old('email') }}" required autocomplete="email">
            <span class="field-icon">✉</span>
          </div>
          @error('email') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Contraseña --}}
        <div>
          <label class="field-label" for="password">Contraseña</label>
          <div class="field-wrap">
            <input type="password" id="password" name="password" class="field-input"
              placeholder="Mínimo 6 caracteres" required autocomplete="new-password"
              oninput="checkStrength(this.value)">
            <span class="field-icon">🔒</span>
            <button type="button" class="toggle-pwd" onclick="togglePwd('password', this)">👁</button>
          </div>
          <div class="strength-bar">
            <div class="strength-seg" id="s1"></div>
            <div class="strength-seg" id="s2"></div>
            <div class="strength-seg" id="s3"></div>
            <div class="strength-seg" id="s4"></div>
          </div>
          @error('password') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Confirmar contraseña --}}
        <div>
          <label class="field-label" for="password_confirmation">Confirmar contraseña</label>
          <div class="field-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation"
              class="field-input" placeholder="Repite tu contraseña" required autocomplete="new-password">
            <span class="field-icon">🔐</span>
            <button type="button" class="toggle-pwd" onclick="togglePwd('password_confirmation', this)">👁</button>
          </div>
        </div>

        <button type="submit" class="btn-submit">Crear cuenta →</button>

      </form>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">¿Ya tienes cuenta?</span>
        <div class="divider-line"></div>
      </div>

      <div class="login-row">
        <span>Inicia sesión</span>
        <a href="{{ route('login') }}" class="login-link">Entrar aquí</a>
      </div>

    </div>
  </div>

</div>

<script>
  function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  }

  function checkStrength(val) {
    const segs = [document.getElementById('s1'), document.getElementById('s2'),
                  document.getElementById('s3'), document.getElementById('s4')];
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#ef4444', '#f97316', '#eab308', '#22c55e'];
    segs.forEach((s, i) => {
      s.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
    });
  }
</script>

@endsection