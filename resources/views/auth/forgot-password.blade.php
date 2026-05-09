@extends('layouts.app')

@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

  * { box-sizing: border-box; margin: 0; padding: 0; }
  body, html { height: 100%; }

  .forgot-page {
    min-height: 100vh;
    width: 100%;
    display: flex;
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #0e0a00;
    overflow: hidden;
  }

  /* ── Panel izquierdo ── */
  .forgot-left {
    flex: 1;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem;
    background: linear-gradient(145deg, #f97316 0%, #f59e0b 50%, #eab308 100%);
    overflow: hidden;
  }

  .forgot-left::before {
    content: '';
    position: absolute;
    width: 480px; height: 480px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
    top: -120px; right: -120px;
  }

  .forgot-left::after {
    content: '';
    position: absolute;
    width: 320px; height: 320px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -80px; left: -80px;
  }

  .left-circle { position: absolute; width: 200px; height: 200px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.15); bottom: 180px; right: 60px; z-index: 1; }

  .left-content { position: relative; z-index: 2; }

  .left-badge {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);
    border-radius: 999px; padding: 0.4rem 1rem;
    font-size: 0.78rem; font-weight: 600; color: #fff;
    letter-spacing: 0.05em; text-transform: uppercase;
    margin-bottom: 2rem; backdrop-filter: blur(6px);
  }

  .left-title {
    font-size: clamp(2rem, 3.5vw, 3rem);
    font-weight: 800; color: #fff; line-height: 1.15;
    margin-bottom: 1.2rem;
    text-shadow: 0 2px 20px rgba(0,0,0,0.15);
  }

  .left-title span { color: rgba(255,255,255,0.6); }

  .left-desc {
    font-size: 1rem; color: rgba(255,255,255,0.75);
    font-weight: 300; line-height: 1.7;
    max-width: 360px; margin-bottom: 2.5rem;
  }

  .left-steps { display: flex; flex-direction: column; gap: 1rem; }

  .step-item { display: flex; align-items: center; gap: 0.85rem; }

  .step-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: rgba(255,255,255,0.25);
    border: 1px solid rgba(255,255,255,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.72rem; font-weight: 800; color: #fff; flex-shrink: 0;
  }

  .step-text { font-size: 0.88rem; color: rgba(255,255,255,0.8); font-weight: 500; }

  /* ── Panel derecho ── */
  .forgot-right {
    width: 480px; flex-shrink: 0;
    background: #0e0a00;
    display: flex; align-items: center; justify-content: center;
    padding: 2.5rem 3rem; position: relative; overflow-y: auto;
  }

  .forgot-right::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(249,115,22,0.12) 0%, transparent 70%);
    pointer-events: none;
  }

  .forgot-form-wrapper {
    width: 100%; max-width: 360px;
    position: relative; z-index: 2;
    animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both;
    padding: 1rem 0;
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  .form-brand { display: flex; align-items: center; gap: 0.7rem; margin-bottom: 2rem; }

  .form-brand-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }

  .form-brand-icon img { width: 40px; height: 40px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(249,115,22,0.45)); }

  .form-brand-name { font-size: 1.05rem; font-weight: 700; color: rgba(255,255,255,0.9); }

  .form-title { font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: 0.35rem; line-height: 1.2; }

  .form-subtitle { font-size: 0.84rem; color: rgba(255,255,255,0.32); margin-bottom: 1.75rem; font-weight: 300; line-height: 1.6; }

  .field-label { display: block; font-size: 0.73rem; font-weight: 700; color: rgba(255,255,255,0.42); letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.45rem; }

  .field-wrap { position: relative; margin-bottom: 1.25rem; }

  .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: rgba(255,255,255,0.18); pointer-events: none; transition: color 0.2s; }

  .field-input {
    width: 100%; background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08); border-radius: 12px;
    color: #fff; font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem; padding: 0.8rem 0.9rem 0.8rem 2.7rem;
    outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }

  .field-input::placeholder { color: rgba(255,255,255,0.18); }

  .field-input:focus {
    background: rgba(249,115,22,0.07);
    border-color: rgba(249,115,22,0.55);
    box-shadow: 0 0 0 3px rgba(249,115,22,0.12);
    color: #fff;
  }

  .field-wrap:focus-within .field-icon { color: rgba(249,115,22,0.7); }

  .alert-success-custom {
    background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25);
    border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.3rem;
    font-size: 0.82rem; color: #86efac;
    display: flex; align-items: center; gap: 0.5rem;
  }

  .alert-error {
    background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25);
    border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.3rem;
    font-size: 0.82rem; color: #fca5a5;
  }

  .field-error { font-size: 0.78rem; color: #fca5a5; margin-top: 0.35rem; }

  .btn-submit {
    width: 100%; padding: 0.85rem;
    background: linear-gradient(135deg, #f97316 0%, #eab308 100%);
    border: none; border-radius: 12px; color: #0e0a00;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.92rem; font-weight: 800; letter-spacing: 0.02em;
    cursor: pointer; transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
    box-shadow: 0 6px 24px rgba(249,115,22,0.4); margin-bottom: 1.5rem;
  }

  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(249,115,22,0.5); opacity: 0.95; }
  .btn-submit:active { transform: translateY(0); }

  .divider { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1.4rem; }
  .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.06); }
  .divider-text { font-size: 0.75rem; color: rgba(255,255,255,0.2); font-weight: 300; }

  .login-row { text-align: center; font-size: 0.85rem; color: rgba(255,255,255,0.3); }

  .login-link { color: #f97316; text-decoration: none; font-weight: 700; margin-left: 0.3rem; transition: opacity 0.2s; }
  .login-link:hover { opacity: 0.7; color: #f97316; }

  @media (max-width: 768px) {
    .forgot-left { display: none; }
    .forgot-right { width: 100%; padding: 2rem 1.5rem; }
  }
</style>

<div class="forgot-page">

  {{-- Panel izquierdo --}}
  <div class="forgot-left">
    <div class="left-circle"></div>
    <div class="left-content">
      <div class="left-badge">🔑 Recuperar acceso</div>
      <h2 class="left-title">
        Sin acceso,<br>
        <span>no hay problema</span><br>
        te ayudamos.
      </h2>
      <p class="left-desc">
        Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña en segundos.
      </p>
      <div class="left-steps">
        <div class="step-item">
          <div class="step-num">1</div>
          <span class="step-text">Ingresa tu correo registrado</span>
        </div>
        <div class="step-item">
          <div class="step-num">2</div>
          <span class="step-text">Revisa tu bandeja de entrada</span>
        </div>
        <div class="step-item">
          <div class="step-num">3</div>
          <span class="step-text">Crea una nueva contraseña</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Panel derecho --}}
  <div class="forgot-right">
    <div class="forgot-form-wrapper">

      <div class="form-brand">
        <div class="form-brand-icon">
          <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <span class="form-brand-name">Mercadex</span>
      </div>

      <h1 class="form-title">¿Olvidaste tu contraseña?</h1>
      <p class="form-subtitle">Ingresa tu correo y te enviaremos un enlace para recuperar tu acceso.</p>

      @if(session('success'))
        <div class="alert-success-custom">✅ {{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert-error">
          <strong>⚠ </strong>
          @foreach($errors->all() as $error){{ $error }}@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
          <label class="field-label" for="email">Correo electrónico</label>
          <div class="field-wrap">
            <input type="email" id="email" name="email" class="field-input"
              placeholder="correo@ejemplo.com"
              value="{{ old('email') }}" required autocomplete="email">
            <span class="field-icon">✉</span>
          </div>
          @error('email') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-submit">📨 Enviar enlace de recuperación</button>

      </form>

      <div class="divider">
        <div class="divider-line"></div>
        <span class="divider-text">¿Recordaste tu contraseña?</span>
        <div class="divider-line"></div>
      </div>

      <div class="login-row">
        <span>Volver a</span>
        <a href="{{ route('login') }}" class="login-link">Iniciar sesión</a>
      </div>

    </div>
  </div>

</div>

@endsection