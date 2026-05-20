<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Perpustakaan Digital</title>
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
        .card-form { padding: 1.75rem 2.25rem 2.25rem; }
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
        .btn-action {
            width: 100%; border: none; border-radius: 9px;
            padding: 0.7rem 1.5rem;
            font-family: 'Source Sans 3', sans-serif;
            font-size: 0.88rem; font-weight: 700;
            letter-spacing: 0.07em; text-transform: uppercase;
            cursor: pointer; color: #fff;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            background: var(--blue);
            transition: background .2s, transform .15s;
        }
        .btn-action:hover { background: var(--navy); transform: translateY(-1px); }
        .btn-action:active { transform: translateY(0) !important; }
        .forgot-link, .register-link {
            font-size: 0.82rem; color: var(--blue); font-weight: 600; text-decoration: none;
        }
        .forgot-link:hover, .register-link:hover { color: var(--navy); text-decoration: underline; }
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
        <span>SMKN 40 Jakarta — Sistem Informasi</span>
    </div>

    <div class="card-form">

        <div class="role-badge badge-anggota" style="margin-bottom:1.5rem; display:inline-flex; align-items:center; gap:8px; font-size:0.78rem; letter-spacing:0.08em; padding:8px 14px; border-radius:999px; background:#f7fbff; color:var(--blue); border:1px solid var(--light);">
            <i class="bi bi-key-fill"></i>
            Reset Password
        </div>

        @if (session('status'))
            <div class="alert-status">
                <i class="bi bi-check-circle-fill"></i> {{ session('status') }}
            </div>
        @endif

        <p style="color:var(--muted); margin-bottom:1.5rem; line-height:1.7; font-size:0.95rem;">
            Masukkan email Anda di bawah ini, lalu kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
        </p>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <div class="field-wrap">
                    <i class="bi bi-envelope ficon"></i>
                    <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="contoh@sekolah.com" required autofocus>
                </div>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-action">
                <i class="bi bi-envelope-open-fill"></i>
                Kirim Link Reset
            </button>

            <p class="text-center mt-3">
                Sudah ingat kata sandi? <a href="{{ route('login') }}" class="forgot-link">Kembali ke login</a>
            </p>

            <p class="footer-note">
                <i class="bi bi-shield-check me-1"></i>Koneksi aman & terenkripsi
            </p>
        </form>
    </div>
</div>

</body>
</html>
