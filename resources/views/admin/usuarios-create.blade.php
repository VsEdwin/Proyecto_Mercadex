@extends('layouts.app')
@section('content')

<style>
  .page-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.75rem;
    animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
  }

  .back-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.18s;
    flex-shrink: 0;
  }

  .back-btn:hover {
    background: rgba(249,115,22,0.1);
    border-color: rgba(249,115,22,0.3);
    color: #f97316;
  }

  .page-eyebrow {
    font-size: 0.72rem;
    font-weight: 700;
    color: #f97316;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    margin-bottom: 0.2rem;
  }

  .page-title {
    font-size: 1.6rem;
    font-weight: 800;
    color: #fff;
    margin: 0;
    line-height: 1.15;
  }

  .form-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 1.25rem;
    animation: fadeUp 0.4s 0.07s cubic-bezier(0.16,1,0.3,1) both;
    align-items: start;
    max-width: 900px;
  }

  .form-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 18px;
    overflow: hidden;
  }

  .card-section-title {
    font-size: 0.68rem;
    font-weight: 700;
    color: rgba(255,255,255,0.25);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 1.1rem 1.4rem 0.6rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .card-body-pad { padding: 1.4rem; }

  .field-group { margin-bottom: 1.25rem; }
  .field-group:last-child { margin-bottom: 0; }

  .field-label {
    display: block;
    font-size: 0.72rem;
    font-weight: 700;
    color: rgba(255,255,255,0.42);
    letter-spacing: 0.07em;
    text-transform: uppercase;
    margin-bottom: 0.45rem;
  }

  .field-wrap { position: relative; }

  .field-icon {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    font-size: 0.9rem;
    color: rgba(255,255,255,0.17);
    pointer-events: none;
    transition: color 0.2s;
  }

  .field-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    color: #fff;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.88rem;
    padding: 0.75rem 0.9rem 0.75rem 2.5rem;
    outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
  }

  .field-input::placeholder { color: rgba(255,255,255,0.15); }

  .field-input:focus {
    background: rgba(249,115,22,0.06);
    border-color: rgba(249,115,22,0.5);
    box-shadow: 0 0 0 3px rgba(249,115,22,0.1);
    color: #fff;
  }

  .field-wrap:focus-within .field-icon { color: rgba(249,115,22,0.6); }

  .toggle-pwd {
    position: absolute;
    right: 13px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: rgba(255,255,255,0.2);
    cursor: pointer; font-size: 0.95rem;
    padding: 0; transition: color 0.2s;
  }
  .toggle-pwd:hover { color: rgba(255,255,255,0.55); }

  /* Strength bar */
  .strength-bar {
    display: flex;
    gap: 4px;
    margin-top: 0.45rem;
  }

  .strength-seg {
    flex: 1; height: 3px;
    border-radius: 999px;
    background: rgba(255,255,255,0.08);
    transition: background 0.3s;
  }

  .field-hint {
    font-size: 0.72rem;
    color: rgba(255,255,255,0.2);
    margin-top: 0.35rem;
  }

  .field-error { font-size: 0.73rem; color: #fca5a5; margin-top: 0.3rem; }

  .alert-error {
    background: rgba(239,68,68,0.09);
    border: 1px solid rgba(239,68,68,0.22);
    border-radius: 12px;
    padding: 0.8rem 1.1rem;
    margin-bottom: 1.4rem;
    font-size: 0.82rem;
    color: #fca5a5;
    animation: fadeUp 0.4s cubic-bezier(0.16,1,0.3,1) both;
  }

  /* Panel lateral */
  .side-panel { display: flex; flex-direction: column; gap: 1.1rem; }

  /* Info card */
  .info-card {
    background: rgba(249,115,22,0.05);
    border: 1px solid rgba(249,115,22,0.15);
    border-radius: 18px;
    padding: 1.4rem;
  }

  .info-card-title {
    font-size: 0.75rem;
    font-weight: 700;
    color: #f97316;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 1rem;
  }

  .info-item {
    display: flex;
    align-items: flex-start;
    gap: 0.65rem;
    margin-bottom: 0.85rem;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.45);
    line-height: 1.5;
  }

  .info-item:last-child { margin-bottom: 0; }
  .info-item-icon { font-size: 1rem; flex-shrink: 0; margin-top: 0.05rem; }

  /* Preview avatar */
  .preview-avatar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.6rem;
    padding: 1.4rem;
    border-bottom: 1px solid rgba(255,255,255,0.05);
  }

  .preview-avatar {
    width: 60px; height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(249,115,22,0.3), rgba(234,179,8,0.3));
    border: 2px solid rgba(249,115,22,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    font-weight: 800;
    color: #f97316;
    text-transform: uppercase;
    transition: all 0.2s;
  }

  .preview-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: rgba(255,255,255,0.7);
  }

  .preview-role {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.65rem;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 700;
    background: rgba(249,115,22,0.15);
    color: #fdba74;
    border: 1px solid rgba(249,115,22,0.2);
  }

  /* Botones */
  .btn-submit {
    width: 100%;
    padding: 0.82rem;
    background: linear-gradient(135deg, #f97316, #eab308);
    border: none;
    border-radius: 12px;
    color: #0e0a00;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 800;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.2s, opacity 0.2s;
    box-shadow: 0 5px 18px rgba(249,115,22,0.35);
  }

  .btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 26px rgba(249,115,22,0.45);
    opacity: 0.93;
  }

  .btn-cancel {
    display: block;
    width: 100%;
    padding: 0.7rem;
    background: transparent;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 12px;
    color: rgba(255,255,255,0.35);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 600;
    text-decoration: none;
    text-align: center;
    transition: all 0.18s;
  }

  .btn-cancel:hover {
    border-color: rgba(255,255,255,0.18);
    color: rgba(255,255,255,0.6);
  }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 860px) {
    .form-layout { grid-template-columns: 1fr; }
  }
</style>

{{-- Header --}}
<div class="page-header">
  <a href="{{ route('admin.usuarios') }}" class="back-btn">←</a>
  <div>
    <p class="page-eyebrow">Usuarios</p>
    <h1 class="page-title">Nuevo Vendedor</h1>
  </div>
</div>

@if($errors->any())
  <div class="alert-error">
    <strong>⚠ Corrige los siguientes errores:</strong>
    <ul class="mb-0 mt-1 ps-3">
      @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
    </ul>
  </div>
@endif

<form method="POST" action="{{ route('admin.usuarios.store') }}" id="userForm">
  @csrf
  <div class="form-layout">

    {{-- Formulario --}}
    <div class="form-card">
      <div class="card-section-title">Datos del vendedor</div>
      <div class="card-body-pad">

        {{-- Nombre --}}
        <div class="field-group">
          <label class="field-label" for="name">Nombre completo</label>
          <div class="field-wrap">
            <input type="text" id="name" name="name" class="field-input"
              placeholder="Nombre del vendedor"
              value="{{ old('name') }}" required
              oninput="updatePreview()">
            <span class="field-icon">👤</span>
          </div>
          @error('name') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Email --}}
        <div class="field-group">
          <label class="field-label" for="email">Correo electrónico</label>
          <div class="field-wrap">
            <input type="email" id="email" name="email" class="field-input"
              placeholder="correo@ejemplo.com"
              value="{{ old('email') }}" required>
            <span class="field-icon">✉</span>
          </div>
          @error('email') <div class="field-error">⚠ {{ $message }}</div> @enderror
        </div>

        {{-- Contraseña --}}
        <div class="field-group">
          <label class="field-label" for="password">Contraseña</label>
          <div class="field-wrap">
            <input type="password" id="password" name="password" class="field-input"
              placeholder="Mínimo 6 caracteres" required
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

        {{-- Confirmar --}}
        <div class="field-group">
          <label class="field-label" for="password_confirmation">Confirmar contraseña</label>
          <div class="field-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation"
              class="field-input" placeholder="Repite la contraseña" required>
            <span class="field-icon">🔐</span>
            <button type="button" class="toggle-pwd" onclick="togglePwd('password_confirmation', this)">👁</button>
          </div>
        </div>

      </div>
    </div>

    {{-- Panel lateral --}}
    <div class="side-panel">

      {{-- Preview --}}
      <div class="form-card">
        <div class="preview-avatar-wrap">
          <div class="preview-avatar" id="prevAvatar">?</div>
          <div class="preview-name" id="prevName">Nombre del vendedor</div>
          <span class="preview-role">🏷 Vendedor</span>
        </div>
        <div class="card-body-pad" style="padding-top:1rem;">
          <p class="field-hint" style="text-align:center;">
            El rol se asigna automáticamente como <strong style="color:#fdba74;">Vendedor</strong>
          </p>
        </div>
      </div>

      {{-- Info --}}
      <div class="info-card">
        <div class="info-card-title">ℹ️ Información</div>
        <div class="info-item">
          <span class="info-item-icon">🔑</span>
          <span>El vendedor podrá iniciar sesión inmediatamente con estas credenciales.</span>
        </div>
        <div class="info-item">
          <span class="info-item-icon">🏷</span>
          <span>El rol <strong style="color:#fdba74;">Vendedor</strong> se asigna automáticamente.</span>
        </div>
        <div class="info-item">
          <span class="info-item-icon">🧾</span>
          <span>Solo podrá registrar y ver sus propias ventas.</span>
        </div>
      </div>

      {{-- Botones --}}
      <div class="form-card">
        <div class="card-section-title">Guardar</div>
        <div class="card-body-pad" style="display:flex; flex-direction:column; gap:0.75rem;">
          <button type="submit" class="btn-submit">👤 Crear Vendedor</button>
          <a href="{{ route('admin.usuarios') }}" class="btn-cancel">Cancelar</a>
        </div>
      </div>

    </div>
  </div>
</form>

<script>
  function updatePreview() {
    const name = document.getElementById('name').value.trim();
    document.getElementById('prevAvatar').textContent = name ? name.charAt(0).toUpperCase() : '?';
    document.getElementById('prevName').textContent   = name || 'Nombre del vendedor';
  }

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