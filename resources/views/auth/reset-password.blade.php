@extends('layouts.app')

@section('content')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap');

  * { box-sizing: border-box; margin: 0; padding: 0; }
  body, html { height: 100%; }

  .reset-page {
    min-height: 100vh; width: 100%;
    display: flex; font-family: 'Plus Jakarta Sans', sans-serif;
    background: #0e0a00; overflow: hidden;
  }

  /* ── Panel izquierdo ── */
  .reset-left {
    flex: 1; position: relative;
    display: flex; flex-direction: column; justify-content: center;
    padding: 4rem;
    background: linear-gradient(145deg, #f97316 0%, #f59e0b 50%, #eab308 100%);
    overflow: hidden;
  }

  .reset-left::before { content: ''; position: absolute; width: 480px; height: 480px; border-radius: 50%; background: rgba(255,255,255,0.08); top: -120px; right: -120px; }
  .reset-left::after  { content: ''; position: absolute; width: 320px; height: 320px; border-radius: 50%; background: rgba(255,255,255,0.06); bottom: -80px; left: -80px; }

  .left-circle { position: absolute; width: 200px; height: 200px; border-radius: 50%; border: 2px solid rgba(255,255,255,0.15); bottom: 180px; right: 60px; z-index: 1; }

  .left-content { position: relative; z-index: 2; }

  .left-badge { display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3); border-radius: 999px; padding: 0.4rem 1rem; font-size: 0.78rem; font-weight: 600; color: #fff; letter-spacing: 0.05em; text-transform: uppercase; margin-bottom: 2rem; backdrop-filter: blur(6px); }

  .left-title { font-size: clamp(2rem, 3.5vw, 3rem); font-weight: 800; color: #fff; line-height: 1.15; margin-bottom: 1.2rem; text-shadow: 0 2px 20px rgba(0,0,0,0.15); }
  .left-title span { color: rgba(255,255,255,0.6); }

  .left-desc { font-size: 1rem; color: rgba(255,255,255,0.75); font-weight: 300; line-height: 1.7; max-width: 360px; margin-bottom: 2.5rem; }

  .left-tips { display: flex; flex-direction: column; gap: 0.85rem; }

  .tip-item { display: flex; align-items: flex-start; gap: 0.75rem; }

  .tip-icon { font-size: 1.1rem; flex-shrink: 0; margin-top: 0.05rem; }

  .tip-text { font-size: 0.85rem; color: rgba(255,255,255,0.75); line-height: 1.5; }

  /* ── Panel derecho ── */
  .reset-right {
    width: 480px; flex-shrink: 0;
    background: #0e0a00;
    display: flex; align-items: center; justify-content: center;
    padding: 2.5rem 3rem; position: relative; overflow-y: auto;
  }

  .reset-right::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(249,115,22,0.12) 0%, transparent 70%); pointer-events: none; }

  .reset-form-wrapper { width: 100%; max-width: 360px; position: relative; z-index: 2; animation: fadeUp 0.6s cubic-bezier(0.16,1,0.3,1) both; padding: 1rem 0; }

  @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

  .form-brand { display: flex; align-items: center; gap: 0.7rem; margin-bottom: 2rem; }
  .form-brand-icon { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
  .form-brand-icon img { width: 40px; height: 40px; object-fit: contain; filter: drop-shadow(0 4px 10px rgba(249,115,22,0.45)); }
  .form-brand-name { font-size: 1.05rem; font-weight: 700; color: rgba(255,255,255,0.9); }

  .form-title { font-size: 1.6rem; font-weight: 800; color: #fff; margin-bottom: 0.35rem; line-height: 1.2; }
  .form-subtitle { font-size: 0.84rem; color: rgba(255,255,255,0.32); margin-bottom: 1.75rem; font-weight: 300; line-height: 1.6; }

  .field-label { display: block; font-size: 0.73rem; font-weight: 700; color: rgba(255,255,255,0.42); letter-spacing: 0.07em; text-transform: uppercase; margin-bottom: 0.45rem; }

  .field-wrap { position: relative; margin-bottom: 1.25rem; }

  .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: rgba(255,255,255,0.18); pointer-events: none; transition: color 0.2s; }

  .field-input { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.9rem; padding: 0.8rem 2.7rem 0.8rem 2.7rem; outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; }
  .field-input::placeholder { color: rgba(255,255,255,0.18); }
  .field-input:focus { background: rgba(249,115,22,0.07); border-color: rgba(249,115,22,0.55); box-shadow: 0 0 0 3px rgba(249,115,22,0.12); color: #fff; }
  .field-wrap:focus-within .field-icon { color: rgba(249,115,22,0.7); }

  .toggle-pwd { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; color: rgba(255,255,255,0.22); cursor: pointer; font-size: 1rem; padding: 0; transition: color 0.2s; }
  .toggle-pwd:hover { color: rgba(255,255,255,0.6); }

  /* Strength bar */
  .strength-bar { display: flex; gap: 4px; margin-top: 0.45rem; }
  .strength-seg { flex: 1; height: 3px; border-radius: 999px; background: rgba(255,255,255,0.08); transition: background 0.3s; }

  .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 1.3rem; font-size: 0.82rem; color: #fca5a5; }
  .field-error { font-size: 0.78rem; color: #fca5a5; margin-top: 0.35rem; }

  .btn-submit { width: 100%; padding: 0.85rem; background: linear-gradient(135deg, #f97316 0%, #eab308 100%); border: none; border-radius: 12px; color: #0e0a00; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.92rem; font-weight: 800; letter-spacing: 0.02em; cursor: pointer; transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s; box-shadow: 0 6px 24px rgba(249,115,22,0.4); margin-bottom: 1.5rem; }
  .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 10px 32px rgba(249,115,22,0.5); opacity: 0.95; }
  .btn-submit:active { transform: translateY(0); }

  .divider { display: flex; align-items: center; gap: 0.8rem; margin-bottom: 1.4rem; }
  .divider-line { flex: 1; height: 1px; background: rgba(255,255,255,0.06); }
  .divider-text { font-size: 0.75rem; color: rgba(255,255,255,0.2); font-weight: 300; }

  .login-row { text-align: center; font-size: 0.85rem; color: rgba(255,255,255,0.3); }
  .login-link { color: #f97316; text-decoration: none; font-weight: 700; margin-left: 0.3rem; transition: opacity 0.2s; }
  .login-link:hover { opacity: 0.7; color: #f97316; }

  @media (max-width: 768px) {
    .reset-left { display: none; }
    .reset-right { width: 100%; padding: 2rem 1.5rem; }
  }
</style>

<div class="reset-page">

  {{-- Panel izquierdo --}}
  <div class="reset-left">
    <div class="left-circle"></div>
    <div class="left-content">
      <div class="left-badge">🔐 Nueva contraseña</div>
      <h2 class="left-title">
        Casi listo,<br>
        <span>crea tu nueva</span><br>
        contraseña.
      </h2>
      <p class="left-desc">
        Elige una contraseña segura para proteger tu cuenta en Mercadex.
      </p>
      <div class="left-tips">
        <div class="tip-item">
          <span class="tip-icon">✅</span>
          <span class="tip-text">Mínimo 6 caracteres</span>
        </div>
        <div class="tip-item">
          <span class="tip-icon">✅</span>
          <span class="tip-text">Combina letras mayúsculas y números</span>
        </div>
        <div class="tip-item">
          <span class="tip-icon">✅</span>
          <span class="tip-text">Usa caracteres especiales para más seguridad</span>
        </div>
        <div class="tip-item">
          <span class="tip-icon">⚠️</span>
          <span class="tip-text">No uses la misma contraseña de otra cuenta</span>
        </div>
      </div>
    </div>
  </div>

  {{-- Panel derecho --}}
  <div class="reset-right">
    <div class="reset-form-wrapper">

      <div class="form-brand">
        <div class="form-brand-icon">
          <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <span class="form-brand-name">Mercadex</span>
      </div>

      <h1 class="form-title">Nueva contraseña</h1>
      <p class="form-subtitle">Ingresa tu nueva contraseña para recuperar el acceso a tu cuenta.</p>

      @if($errors->any())
        <div class="alert-error">
          <strong>⚠ </strong>
          @foreach($errors->all() as $error){{ $error }}@endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
        @csrf

        {{-- Token oculto --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
          <label class="field-label" for="email">Correo electrónico</label>
          <div class="field-wrap">
            <input type="email" id="email" name="email" class="field-input"
              placeholder="correo@ejemplo.com"
              value="{{ old('email', $request->email) }}"
              required autocomplete="email">
            <span class="field-icon">✉</span>
          </div>
          @error('email') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Nueva contraseña --}}
        <div>
          <label class="field-label" for="password">Nueva contraseña</label>
          <div class="field-wrap">
            <input type="password" id="password" name="password" class="field-input"
              placeholder="Mínimo 6 caracteres" required
              autocomplete="new-password"
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
              class="field-input" placeholder="Repite tu contraseña" required
              autocomplete="new-password">
            <span class="field-icon">🔐</span>
            <button type="button" class="toggle-pwd" onclick="togglePwd('password_confirmation', this)">👁</button>
          </div>
        </div>

        <button type="submit" class="btn-submit">🔑 Restablecer contraseña</button>

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

<script>
  function togglePwd(id, btn) {
    const input = document.getElementById(id);
    input.type  = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  }

  function checkStrength(val) {
    const segs   = ['s1','s2','s3','s4'].map(id => document.getElementById(id));
    const colors = ['#ef4444','#f97316','#eab308','#22c55e'];
    let score = 0;
    if (val.length >= 6)  score++;
    if (val.length >= 10) score++;
    if (/[A-Z]/.test(val) && /[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    segs.forEach((s, i) => {
      s.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
    });
  }
</script>

@endsection