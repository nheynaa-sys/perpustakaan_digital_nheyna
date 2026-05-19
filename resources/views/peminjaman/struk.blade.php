<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $peminjaman->jenis_struk }} #{{ $peminjaman->id }}</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #eef1f5;
            color: #172033;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding: 24px 12px;
        }
        .wrap { width: 100%; max-width: 430px; }
        .actions {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 16px;
        }
        .btn {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #172033;
            border-radius: 6px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        .receipt {
            background: #fffef8;
            border: 1px solid #e2e8f0;
            box-shadow: 0 18px 45px rgba(15, 23, 42, .16);
            padding: 28px 24px;
        }
        .center { text-align: center; }
        .title {
            margin: 8px 0 2px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }
        .muted { color: #64748b; font-size: 12px; }
        .code {
            display: inline-block;
            margin-top: 10px;
            background: #172033;
            color: #fff;
            padding: 4px 10px;
            border-radius: 4px;
            font-family: Consolas, monospace;
            font-size: 12px;
        }
        .divider {
            border-top: 1px dashed #94a3b8;
            margin: 18px 0;
        }
        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .badge-pinjam { background: #dbeafe; color: #1d4ed8; }
        .badge-kembali { background: #dcfce7; color: #15803d; }
        .badge-denda { background: #fee2e2; color: #b91c1c; }
        .section-title {
            margin-bottom: 8px;
            color: #64748b;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
            padding: 4px 0;
            font-size: 13px;
        }
        .key { color: #64748b; white-space: nowrap; }
        .value { font-weight: 700; text-align: right; }
        .book {
            border-left: 4px solid #2563eb;
            background: #eff6ff;
            padding: 10px 12px;
            margin-top: 6px;
        }
        .book-title { font-weight: 800; font-size: 14px; }
        .book-meta { color: #475569; font-size: 12px; margin-top: 3px; }
        .fine {
            margin-top: 10px;
            border: 1px solid #fecaca;
            background: #fff1f2;
            color: #b91c1c;
            border-radius: 6px;
            padding: 10px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 800;
        }
        .ok {
            margin-top: 10px;
            border: 1px solid #bbf7d0;
            background: #f0fdf4;
            color: #15803d;
            border-radius: 6px;
            padding: 9px 12px;
            text-align: center;
            font-weight: 800;
            font-size: 13px;
        }
        .note {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 12px;
        }
        .barcode {
            width: 170px;
            height: 38px;
            margin: 12px auto 6px;
            background: repeating-linear-gradient(90deg, #172033 0 2px, transparent 2px 5px, #172033 5px 7px, transparent 7px 11px);
            opacity: .75;
        }
        .footer { text-align: center; color: #64748b; font-size: 11px; line-height: 1.5; }
        @media print {
            body { background: #fff; padding: 0; }
            .actions { display: none; }
            .receipt { box-shadow: none; border: 0; }
            @page { size: 80mm auto; margin: 4mm; }
        }
    </style>
</head>
<body>
@php
    $statusClass = $peminjaman->denda > 0 || $peminjaman->status === 'terlambat'
        ? 'badge-denda'
        : ($peminjaman->status === 'dikembalikan' ? 'badge-kembali' : 'badge-pinjam');

    $terlambatHari = 0;
    if ($peminjaman->tgl_kembali_aktual) {
        $terlambatHari = max(0, $peminjaman->tgl_kembali_rencana->diffInDays($peminjaman->tgl_kembali_aktual, false));
    }
@endphp

<div class="wrap">
    <div class="actions">
        <a href="{{ auth()->user()->role === 'admin' ? route('peminjaman.show', $peminjaman->id) : route('user.riwayat') }}" class="btn">Kembali</a>
        <button class="btn btn-primary" onclick="window.print()">Cetak Struk</button>
    </div>

    <main class="receipt">
        <header class="center">
            <div class="title">Perpustakaan Digital</div>
            <div class="muted">SMKN 40 Jakarta</div>
            <div class="code">STRUK-{{ str_pad($peminjaman->id, 6, '0', STR_PAD_LEFT) }}</div>
        </header>

        <div class="divider"></div>

        <div class="center">
            <span class="badge {{ $statusClass }}">{{ $peminjaman->jenis_struk }}</span>
        </div>

        <div class="divider"></div>

        <section>
            <div class="section-title">Data Anggota</div>
            <div class="row"><span class="key">Nama</span><span class="value">{{ $peminjaman->anggota->nama }}</span></div>
            <div class="row"><span class="key">NIS</span><span class="value">{{ $peminjaman->anggota->nis }}</span></div>
            <div class="row"><span class="key">Kelas</span><span class="value">{{ $peminjaman->anggota->kelas }}</span></div>
        </section>

        <div class="divider"></div>

        <section>
            <div class="section-title">Data Buku</div>
            <div class="book">
                <div class="book-title">{{ $peminjaman->buku->judul }}</div>
                <div class="book-meta">{{ $peminjaman->buku->pengarang }}</div>
                @if($peminjaman->buku->isbn)
                    <div class="book-meta">ISBN {{ $peminjaman->buku->isbn }}</div>
                @endif
            </div>
        </section>

        <div class="divider"></div>

        <section>
            <div class="section-title">Waktu Transaksi</div>
            <div class="row"><span class="key">Tanggal pinjam</span><span class="value">{{ $peminjaman->tgl_pinjam->format('d M Y') }}</span></div>
            <div class="row"><span class="key">Jatuh tempo</span><span class="value">{{ $peminjaman->tgl_kembali_rencana->format('d M Y') }}</span></div>
            <div class="row"><span class="key">Tanggal kembali</span><span class="value">{{ $peminjaman->tgl_kembali_aktual?->format('d M Y') ?? '-' }}</span></div>
            @if($terlambatHari > 0)
                <div class="row"><span class="key">Terlambat</span><span class="value">{{ $terlambatHari }} hari</span></div>
            @endif
            <div class="row"><span class="key">Dicetak</span><span class="value">{{ now()->format('d M Y H:i') }}</span></div>
            @if($peminjaman->struk_disetujui_at)
                <div class="row"><span class="key">Dikirim admin</span><span class="value">{{ $peminjaman->struk_disetujui_at->format('d M Y H:i') }}</span></div>
            @endif
        </section>

        <div class="divider"></div>

        <section>
            <div class="section-title">Denda</div>
            @if($peminjaman->denda > 0)
                <div class="row"><span class="key">Tarif</span><span class="value">Rp 1.000 / hari</span></div>
                <div class="fine">
                    <span>Total</span>
                    <span>{{ $peminjaman->denda_format }}</span>
                </div>
            @else
                <div class="ok">Tidak ada denda</div>
            @endif
        </section>

        @if($peminjaman->catatan_admin)
            <div class="divider"></div>
            <section>
                <div class="section-title">Catatan Petugas</div>
                <div class="note">{{ $peminjaman->catatan_admin }}</div>
            </section>
        @endif

        <div class="divider"></div>

        <footer class="footer">
            <div class="barcode"></div>
            <div>{{ str_pad($peminjaman->id, 12, '0', STR_PAD_LEFT) }}</div>
            <div>Struk ini adalah bukti resmi transaksi perpustakaan.</div>
        </footer>
    </main>
</div>
</body>
</html>
