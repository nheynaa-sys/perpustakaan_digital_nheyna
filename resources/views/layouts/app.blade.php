<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Perpustakaan') — SMKN</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #1a2332;
            --sidebar-hover: #2d3f5c;
            --accent: #4f8ef7;
        }

        body { background: #f4f6fb; font-family: 'Segoe UI', sans-serif; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            transition: width .25s;
            overflow-x: hidden;
        }

        #sidebar .brand {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .brand h5 { color: #fff; font-weight: 700; margin: 0; font-size: 1rem; }
        #sidebar .brand small { color: rgba(255,255,255,.5); font-size: .75rem; }

        #sidebar .nav-link {
            color: rgba(255,255,255,.7);
            padding: .6rem 1.5rem;
            border-radius: 0;
            display: flex; align-items: center; gap: .75rem;
            transition: background .2s, color .2s;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: var(--sidebar-hover);
            color: #fff;
            border-left: 3px solid var(--accent);
        }
        #sidebar .nav-link i { font-size: 1.1rem; width: 1.3rem; text-align: center; }

        #sidebar .nav-section {
            font-size: .7rem; font-weight: 700;
            letter-spacing: .1em;
            color: rgba(255,255,255,.3);
            padding: .75rem 1.5rem .25rem;
            text-transform: uppercase;
        }

        /* ── MAIN CONTENT ── */
        #main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── TOPBAR ── */
        #topbar {
            background: #fff;
            padding: .75rem 1.5rem;
            border-bottom: 1px solid #e4e9f2;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }

        /* ── CARDS ── */
        .stat-card {
            border: none; border-radius: 12px; padding: 1.4rem 1.5rem;
            display: flex; align-items: center; gap: 1rem;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
        .stat-card .icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
        .stat-card .value { font-size: 1.75rem; font-weight: 700; line-height: 1; }
        .stat-card .label { font-size: .8rem; color: #6b7280; }

        /* ── TABLES ── */
        .table-card { background: #fff; border-radius: 12px; padding: 1.25rem; border: none; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .table thead th { background: #f8fafc; border-bottom: 2px solid #e4e9f2; font-size: .8rem; font-weight: 600; color: #374151; text-transform: uppercase; letter-spacing: .04em; }
        .table-hover tbody tr:hover { background: #f0f4ff; }

        /* ── FORMS ── */
        .form-card { background: #fff; border-radius: 12px; padding: 1.75rem; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
        .form-label { font-size: .85rem; font-weight: 600; color: #374151; }
        .form-control:focus, .form-select:focus { border-color: var(--accent); box-shadow: 0 0 0 .2rem rgba(79,142,247,.15); }

        .badge { font-weight: 500; font-size: .75rem; }

        .page-header { margin-bottom: 1.5rem; }
        .page-header h4 { font-weight: 700; color: #1a2332; margin: 0; }
        .page-header .breadcrumb { margin: 0; font-size: .8rem; }

        @media (max-width: 768px) {
            #sidebar { width: 0; }
            #main-content { margin-left: 0; }
        }
    </style>

    @stack('styles')
</head>

<body>

{{-- ═══════════════ SIDEBAR ═══════════════ --}}
<nav id="sidebar">
    <div class="brand">
        <h5>
            <img src="{{ asset('images/logo40.png') }}" alt="Logo" width="40">
            Perpustakaan
        </h5>
        <small>SMKN Sistem Manajemen</small>
    </div>

    <div class="pt-2">
        @auth

            {{-- ══════════════════════════════ --}}
            {{-- MENU ADMIN                     --}}
            {{-- ══════════════════════════════ --}}
            @if(auth()->user()->role === 'admin')

                <div class="nav-section">Utama</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>

                <div class="nav-section">Koleksi</div>
                <a href="{{ route('buku.index') }}"
                   class="nav-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                    <i class="bi bi-book"></i> Buku
                </a>
                <a href="{{ route('kategori.index') }}"
                   class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i> Kategori
                </a>

                <div class="nav-section">Sirkulasi</div>
                <a href="{{ route('peminjaman.index') }}"
                   class="nav-link {{ request()->routeIs('peminjaman.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right"></i> Peminjaman
                </a>

                <div class="nav-section">Anggota</div>
                <a href="{{ route('anggota.index') }}"
                   class="nav-link {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Data Anggota
                </a>

            {{-- ══════════════════════════════ --}}
            {{-- MENU ANGGOTA                   --}}
            {{-- ══════════════════════════════ --}}
            @else

                <div class="nav-section">Perpustakaan</div>
                <a href="{{ route('user.katalog') }}"
                   class="nav-link {{ request()->routeIs('user.katalog') ? 'active' : '' }}">
                    <i class="bi bi-grid"></i> Katalog Buku
                </a>
                <a href="{{ route('user.riwayat') }}"
                   class="nav-link {{ request()->routeIs('user.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Pinjam
                </a>

            @endif

            {{-- ══════════════════════════════ --}}
            {{-- MENU AKUN (semua role)         --}}
            {{-- ══════════════════════════════ --}}
            <div class="nav-section">Akun</div>
            <a href="{{ route('profile.edit') }}"
               class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profil
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent w-100 text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>

        @endauth
    </div>
</nav>

{{-- ═══════════════ MAIN CONTENT ═══════════════ --}}
<div id="main-content">

    {{-- TOPBAR --}}
    <div id="topbar">
        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-sm btn-light d-md-none" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <span class="text-muted small">@yield('breadcrumb', 'Beranda')</span>
        </div>

        @auth
            <div class="d-flex align-items-center gap-3">
                <div class="text-end">
                    <div class="fw-semibold" style="font-size:.85rem">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size:.7rem">
                        <span class="badge {{ auth()->user()->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                            {{ ucfirst(auth()->user()->role) }}
                        </span>
                    </div>
                </div>
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold"
                     style="width:36px;height:36px;font-size:.9rem">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>
        @endauth
    </div>

    {{-- PAGE CONTENT --}}
    <div class="flex-grow-1 p-4">

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="text-center text-muted py-3"
            style="font-size:.75rem; border-top:1px solid #e4e9f2; background:#fff">
        &copy; {{ date('Y') }} Created by Nheyna Az-Zahra
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.alert').forEach(el => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
            if (bsAlert) bsAlert.close();
        }, 5000);
    });
</script>
@stack('scripts')
</body>
</html>