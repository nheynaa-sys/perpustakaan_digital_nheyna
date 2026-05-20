<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Perpustakaan Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --navy:  #0C3D6E;
            --blue:  #185FA5;
            --mid:   #378ADD;
            --light: #B5D4F4;
            --pale:  #E6F1FB;
            --text:  #042C53;
            --muted: #3a6fa8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html, body { min-height: 100%; overflow-y: auto; }
        body {
            font-family: 'Source Sans 3', sans-serif;
            background: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed; inset: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(183,212,244,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(183,212,244,0.06) 1px, transparent 1px);
            background-size: 36px 36px;
            z-index: 0;
        }
        .ring { position: fixed; border-radius: 50%; border: 1px solid rgba(183,212,244,0.07); pointer-events: none; z-index: 0; }
        .ring-1 { width: 520px; height: 520px; top: -160px; left: -160px; }
        .ring-2 { width: 360px; height: 360px; bottom: -100px; right: -100px; }
        .login-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            width: 100%;
            max-width: 430px;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(183,212,244,0.2);
        }
        .card-head {
            background: var(--navy);
            padding: 2rem 2.25rem 1.75rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-head::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(183,212,244,0.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(183,212,244,0.07) 1px, transparent 1px);
            background-size: 22px 22px;
            pointer-events: none;
        }
        .logo-ring {
            width: 60px; height: 60px; border-radius: 50%;
            background: var(--blue);
            border: 2px solid rgba(183,212,244,0.3);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            position: relative; z-index: 1;
        }
        .card-head h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.25rem; font-weight: 700; color: #fff;
            margin-bottom: 4px; position: relative; z-index: 1;
        }
        .card-head span {
            font-size: 0.7rem; font-weight: 300; color: var(--light);
            letter-spacing: 0.1em; text-transform: uppercase;
            position: relative; z-index: 1;
        }
        .role-switcher { display: flex; border-bottom: 1px solid #e8f0f9; }
        .role-tab {
            flex: 1; padding: 0.85rem 1rem;
            font-size: 0.82rem; font-weight: 600;
            letter-spacing: 0.05em; text-transform: uppercase;
            color: var(--muted); background: #f7fbff;
            border: none; border-bottom: 3px solid transparent;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 7px;
            transition: all .2s;
        }
        .role-tab:first-child { border-right: 1px solid #e8f0f9; }
        .role-tab i { font-size: 1rem; }
        .role-tab.active          { background: #fff; color: var(--blue); border-bottom-color: var(--blue); }
        .role-tab.active.tab-admin{ color: #92400e; border-bottom-color: #d97706; }
        .role-tab:hover:not(.active) { background: var(--pale); color: var(--navy); }
        .card-form { padding: 1.75rem 2.25rem 2.25rem; }
        .role-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.73rem; font-weight: 700;
            letter-spacing: 0.07em; text-transform: uppercase;
            padding: 4px 12px; border-radius: 20px; margin-bottom: 1.25rem;
        }
        .badge-anggota { background: var(--pale); color: var(--blue); border: 1px solid var(--light); }
        .badge-admin   { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .form-label {
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase;
            color: var(--blue); margin-bottom: 6px; display: block;
        }
        .field-wrap { position: relative; }
        .field-wrap .ficon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--mid); font-size: 0.95rem;
            pointer-events: none; z-index: 2;
        }
        .form-control {
            padding: 0.6rem 1rem 0.6rem 2.4rem;
            border: 1.5px solid var(--light);
            border-radius: 9px;
            font-family: 'Source Sans 3', sans-serif;
            font-size: 0.92rem; color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            width: 100%;
        }
        .form-control::placeholder { color: #a8c6e0; }
        .form-control:focus {
            border-color: var(--blue);
            box-shadow: 0 0 0 3px rgba(24,95,165,0.1);
            outline: none;
        }
        .form-control.is-invalid { border-color: #dc3545; }
        .invalid-feedback { font-size: 0.78rem; color: #dc3545; margin-top: 4px; display: block; }
        .pw-wrap { position: relative; }
        .pw-toggle {
            position: absolute; right: 12px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--muted); font-size: 0.95rem;
            padding: 0; line-height: 1; z-index: 2;
            transition: color .15s;
        }
        .pw-toggle:hover { color: var(--navy); }
        .pw-wrap .form-control { padding-right: 2.4rem; }
        .form-check-input:checked { background-color: var(--blue); border-color: var(--blue); }
        .form-check-label { font-size: 0.83rem; color: var(--muted); cursor: pointer; }
        .forgot-link { font-size: 0.82rem; color: var(--blue); font-weight: 600; text-decoration: none; }
        .forgot-link:hover { color: var(--navy); text-decoration: underline; }
        .btn-masuk {
            width: 100%; border: none; border-radius: 9px;
            padding: 0.7rem 1.5rem;
            font-family: 'Source Sans 3', sans-serif;
            font-size: 0.88rem; font-weight: 700;
            letter-spacing: 0.07em; text-transform: uppercase;
            cursor: pointer; color: #fff;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .2s, transform .15s;
        }
        .btn-anggota { background: var(--blue); }
        .btn-anggota:hover { background: var(--navy); transform: translateY(-1px); }
        .btn-admin   { background: #b45309; }
        .btn-admin:hover   { background: #92400e; transform: translateY(-1px); }
        .btn-masuk:active  { transform: translateY(0) !important; }
        .register-link {
            color: var(--blue);
            font-weight: 700;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
            color: var(--navy);
        }
        .footer-note { font-size: 0.74rem; color: #a8c6e0; text-align: center; margin-top: 1rem; }
        .alert-status {
            background: #d1fae5; border: 1px solid #a7f3d0; color: #065f46;
            border-radius: 8px; padding: 8px 14px; font-size: 0.82rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: center; gap: 7px;
        }
        @media (max-width: 480px) {
            .card-form { padding: 1.5rem; }
            .card-head { padding: 1.75rem 1.5rem 1.5rem; }
            .role-tab  { font-size: 0.75rem; padding: 0.75rem 0.5rem; }
        }
    </style>
</head>
<body>

<div class="ring ring-1"></div>
<div class="ring ring-2"></div>

<div class="login-card">

    <div class="card-head">
        <div class="logo-ring">
            <img src="{{ asset('images/logo40.png') }}" alt="Logo" style="width:60px;height:60px;object-fit:contain;">
        </div>
        <h1>Perpustakaan Digital</h1>
        <span>SMKN 40 Jakarta &mdash; Sistem Informasi</span>
    </div>

    <div class="role-switcher">
        <button type="button" class="role-tab active" id="tabAnggota" onclick="switchRole('anggota')">
            <i class="bi bi-person"></i> Anggota
        </button>
        <button type="button" class="role-tab tab-admin" id="tabAdmin" onclick="switchRole('admin')">
            <i class="bi bi-shield-check"></i> Admin
        </button>
    </div>

    <div class="card-form">

        @if (session('status'))
            <div class="alert-status">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
            </div>
        @endif

        <div class="role-badge badge-anggota" id="roleBadge">
            <i class="bi bi-person-check" id="roleBadgeIcon"></i>
            <span id="roleBadgeText">Masuk sebagai Anggota</span>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="hidden" name="role" id="roleInput" value="anggota">

            {{-- ── FIELD ANGGOTA (NIS) ── --}}
            <div id="fieldNis">
                <div class="mb-3">
                    <label class="form-label" for="nis">NIS</label>
                    <div class="field-wrap">
                        <i class="bi bi-person-badge ficon"></i>
                        <input
                            id="nis"
                            type="text"
                            name="nis"
                            class="form-control @error('nis') is-invalid @enderror"
                            value="{{ old('nis') }}"
                            placeholder="Masukkan NIS Anda"
                            autocomplete="username"
                            inputmode="numeric"
                        >
                    </div>
                    @error('nis')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- ── FIELD ADMIN (Email) ── --}}
            <div id="fieldEmail" style="display:none;">
                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <div class="field-wrap">
                        <i class="bi bi-shield ficon"></i>
                        <input
                            id="email"
                            type="email"
                            name=""
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}"
                            placeholder="admin@sekolah.com"
                            autocomplete="username"
                        >
                    </div>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label class="form-label" for="password">Kata Sandi</label>
                <div class="field-wrap pw-wrap">
                    <i class="bi bi-lock ficon"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required autocomplete="current-password"
                    >
                    <button type="button" class="pw-toggle" id="pwToggle" aria-label="Tampilkan kata sandi">
                        <i class="bi bi-eye" id="pwIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label" for="remember_me">Ingat saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa kata sandi?</a>
                @endif
            </div>

            <button type="submit" class="btn-masuk btn-anggota" id="btnMasuk">
                <i class="bi bi-box-arrow-in-right"></i>
                <span id="btnText">Masuk sebagai Anggota</span>
            </button>

            <p class="text-center mt-3">
                Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar sekarang</a>
            </p>

            <p class="footer-note">
                <i class="bi bi-shield-check me-1"></i>Koneksi aman &amp; terenkripsi
            </p>
        </form>
    </div>
</div>

<script>
    const hasNisError   = {{ $errors->has('nis')   ? 'true' : 'false' }};
    const hasEmailError = {{ $errors->has('email') ? 'true' : 'false' }};
    const oldRole       = "{{ old('role', 'anggota') }}";

    if (hasEmailError || oldRole === 'admin') {
        switchRole('admin');
    }

    function switchRole(role) {
        const isAdmin = role === 'admin';

        // Tab
        document.getElementById('tabAnggota').classList.toggle('active', !isAdmin);
        document.getElementById('tabAdmin').classList.toggle('active', isAdmin);

        // Badge
        const badge = document.getElementById('roleBadge');
        badge.className = 'role-badge ' + (isAdmin ? 'badge-admin' : 'badge-anggota');
        document.getElementById('roleBadgeIcon').className = isAdmin ? 'bi bi-shield-check' : 'bi bi-person-check';
        document.getElementById('roleBadgeText').textContent = isAdmin ? 'Masuk sebagai Admin' : 'Masuk sebagai Anggota';

        // Field tampil/sembunyikan
        document.getElementById('fieldNis').style.display   = isAdmin ? 'none'  : 'block';
        document.getElementById('fieldEmail').style.display = isAdmin ? 'block' : 'none';

        // ✅ Kosongkan name field tersembunyi agar tidak ikut terkirim
        const nisInput   = document.getElementById('nis');
        const emailInput = document.getElementById('email');

        nisInput.required = !isAdmin;
        nisInput.name     = !isAdmin ? 'nis'   : ''; // anggota → kirim nis, admin → tidak
        emailInput.required = isAdmin;
        emailInput.name     = isAdmin  ? 'email' : ''; // admin → kirim email, anggota → tidak

        // Tombol
        const btn = document.getElementById('btnMasuk');
        btn.className = 'btn-masuk ' + (isAdmin ? 'btn-admin' : 'btn-anggota');
        document.getElementById('btnText').textContent = isAdmin ? 'Masuk sebagai Admin' : 'Masuk sebagai Anggota';

        // Hidden input role
        document.getElementById('roleInput').value = role;
    }

    // Password toggle
    document.getElementById('pwToggle').addEventListener('click', function () {
        const inp  = document.getElementById('password');
        const icon = document.getElementById('pwIcon');
        const show = inp.type === 'password';
        inp.type       = show ? 'text' : 'password';
        icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
        this.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
    });
</script>

</body>
</html>