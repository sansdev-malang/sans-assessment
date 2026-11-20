<!DOCTYPE html>
<html lang="id" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Rapor Observasi — {{ $rapor['siswa']->nama }}</title>
        <script>
            (function () {
                const mode = localStorage.getItem('saans-mode') || 'dark';
                if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            })();
        </script>
        <link rel="preconnect" href="https://fonts.bunny.net">
        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            @media print {
                @page { size: A4; margin: 14mm; }
                body { background: #fff !important; color: #18181b !important; }
                .print-hidden { display: none !important; }
                .print-card { break-inside: avoid; box-shadow: none !important; background: #fff !important; }
                .print-page { max-width: none !important; padding: 0 !important; }
                .print-muted { color: #52525b !important; }
            }
        </style>
    </head>
    <body class="min-h-full bg-slate-50 text-foreground antialiased font-sans dark:bg-zinc-950">
        @php
            $siswa = $rapor['siswa'];
            $progress = $rapor['target_count'] > 0 ? min(100, round(($rapor['selesai_count'] / $rapor['target_count']) * 100)) : 0;
            $unit = match ($siswa->jenjang) {
                'sd' => 'SD Alam Anak Saleh',
                'smp' => 'SMP Alam Anak Saleh',
                'tk' => 'KB & TK Anak Saleh',
                default => strtoupper($siswa->jenjang),
            };
            $kelompokStyles = [
                'ubudiyah' => ['icon' => 'book-open', 'color' => 'emerald'],
                'persiapan' => ['icon' => 'pencil-ruler', 'color' => 'indigo'],
                'eksplorasi' => ['icon' => 'sparkles', 'color' => 'amber'],
                'keagamaan' => ['icon' => 'heart-handshake', 'color' => 'emerald'],
                'akademik' => ['icon' => 'graduation-cap', 'color' => 'blue'],
                'bakat_minat' => ['icon' => 'palette', 'color' => 'violet'],
                'pribadi_sosial' => ['icon' => 'users-round', 'color' => 'rose'],
            ];
        @endphp

        <main class="print-page mx-auto max-w-5xl px-4 py-8 sm:px-6 lg:py-12 space-y-6">
            <div class="print-hidden flex items-center justify-between gap-3">
                <a href="{{ route('admin.dashboard', ['jenjang' => $siswa->jenjang]) }}" class="inline-flex h-9 items-center gap-2 rounded-lg border border-border bg-card px-3 text-xs font-medium text-foreground hover:bg-muted">
                    <i data-lucide="arrow-left" class="h-4 w-4"></i> Kembali ke Dashboard
                </a>
                <button type="button" onclick="window.print()" class="inline-flex h-9 items-center gap-2 rounded-lg bg-emerald-600 px-4 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700">
                    <i data-lucide="printer" class="h-4 w-4"></i> Cetak / Simpan PDF
                </button>
            </div>

            <header class="print-card relative overflow-hidden rounded-3xl border border-emerald-900/10 bg-gradient-to-br from-emerald-950 via-emerald-900 to-teal-800 p-6 text-white shadow-xl sm:p-9">
                <div class="absolute -right-12 -top-16 h-64 w-64 rounded-full border-[32px] border-white/5"></div>
                <div class="absolute -bottom-24 right-32 h-56 w-56 rounded-full bg-emerald-400/10 blur-2xl"></div>
                <div class="relative flex flex-col justify-between gap-8 sm:flex-row sm:items-start">
                    <div class="space-y-7">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10 ring-1 ring-white/20"><i data-lucide="sprout" class="h-6 w-6 text-emerald-200"></i></div>
                            <div><p class="text-[10px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Sekolah Alam Anak Saleh</p><p class="text-xs text-white/70">{{ $unit }} · Tahun Ajaran 2026/2027</p></div>
                        </div>
                        <div>
                            <p class="text-xs font-medium uppercase tracking-[0.2em] text-emerald-200">Rapor Profil Potensi</p>
                            <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">{{ $siswa->nama }}</h1>
                            <p class="mt-2 text-sm text-white/75">Laporan hasil observasi kesiapan belajar dan perkembangan ananda</p>
                        </div>
                    </div>
                    <div class="relative rounded-2xl border border-white/15 bg-white/10 px-5 py-4 backdrop-blur-sm sm:min-w-44">
                        <p class="text-[10px] uppercase tracking-[0.18em] text-emerald-100/80">Nomor Pendaftaran</p>
                        <p class="mt-1 font-mono text-lg font-semibold">{{ $siswa->no_pendaftaran }}</p>
                        <p class="mt-4 text-[10px] uppercase tracking-[0.18em] text-emerald-100/80">Kelas Tujuan</p>
                        <p class="mt-1 text-sm font-semibold">{{ $siswa->kelas_target ?: 'Belum ditentukan' }}</p>
                    </div>
                </div>
                <div class="relative mt-8 flex flex-wrap gap-x-8 gap-y-3 border-t border-white/15 pt-4 text-xs text-white/75">
                    <span class="inline-flex items-center gap-2"><i data-lucide="calendar-days" class="h-3.5 w-3.5 text-emerald-200"></i> Diperbarui {{ $siswa->updated_at?->format('d M Y') ?? now()->format('d M Y') }}</span>
                    <span class="inline-flex items-center gap-2"><i data-lucide="map-pin" class="h-3.5 w-3.5 text-emerald-200"></i> Asal sekolah: {{ $siswa->asal_sekolah ?: 'Belum diisi' }}</span>
                </div>
            </header>

            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <article class="print-card rounded-2xl border border-border bg-card p-5 shadow-sm">
                    <div class="flex items-center justify-between"><p class="text-xs font-medium text-muted-foreground">Skor Kesiapan Terpadu</p><span class="rounded-lg bg-emerald-100 p-2 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300"><i data-lucide="gauge" class="h-4 w-4"></i></span></div>
                    <p class="mt-4 text-3xl font-bold tracking-tight">{{ $siswa->nilai_akhir_terpadu !== null ? number_format((float) $siswa->nilai_akhir_terpadu, 1).'%' : '—' }}</p>
                    <p class="mt-1 text-xs text-muted-foreground">Rata-rata kelompok yang telah selesai</p>
                </article>
                <article class="print-card rounded-2xl border border-border bg-card p-5 shadow-sm">
                    <div class="flex items-center justify-between"><p class="text-xs font-medium text-muted-foreground">Progres Observasi</p><span class="rounded-lg bg-blue-100 p-2 text-blue-700 dark:bg-blue-950 dark:text-blue-300"><i data-lucide="list-checks" class="h-4 w-4"></i></span></div>
                    <p class="mt-4 text-3xl font-bold tracking-tight">{{ $rapor['selesai_count'] }}<span class="text-base font-medium text-muted-foreground"> / {{ $rapor['target_count'] }}</span></p>
                    <div class="mt-3 h-2 overflow-hidden rounded-full bg-muted"><div class="h-full rounded-full bg-blue-600" style="width: {{ $progress }}%"></div></div>
                    <p class="mt-2 text-xs text-muted-foreground">{{ $progress }}% kelompok selesai</p>
                </article>
                <article class="print-card rounded-2xl border border-border bg-card p-5 shadow-sm">
                    <div class="flex items-center justify-between"><p class="text-xs font-medium text-muted-foreground">Rekomendasi Saat Ini</p><span class="rounded-lg bg-violet-100 p-2 text-violet-700 dark:bg-violet-950 dark:text-violet-300"><i data-lucide="badge-check" class="h-4 w-4"></i></span></div>
                    <p class="mt-4 text-xl font-bold tracking-tight">{{ $siswa->status_rekomendasi ?: 'Dalam Proses' }}</p>
                    <p class="mt-2 text-xs text-muted-foreground">{{ $rapor['status'] }} berdasarkan data asesmen</p>
                </article>
            </section>

            @if($rapor['selesai_count'] < $rapor['target_count'])
                <div class="print-card flex gap-3 rounded-2xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-950 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-100">
                    <i data-lucide="info" class="mt-0.5 h-5 w-5 shrink-0"></i><div><p class="font-semibold">Rapor sementara</p><p class="mt-1 text-xs leading-relaxed opacity-80">Sebagian observasi masih berjalan. Rincian di bawah hanya memuat penilaian yang sudah tersimpan dan rekomendasi dapat berubah setelah seluruh kelompok menyelesaikan asesmen.</p></div>
                </div>
            @endif

            @if($siswa->catatan_umum || $siswa->wali || $siswa->usia)
                <section class="print-card rounded-2xl border border-border bg-card p-5 shadow-sm sm:p-6">
                    <div class="mb-4 flex items-center gap-2"><span class="rounded-lg bg-amber-100 p-2 text-amber-700 dark:bg-amber-950 dark:text-amber-300"><i data-lucide="user-round" class="h-4 w-4"></i></span><h2 class="font-semibold">Profil dan Catatan Ananda</h2></div>
                    <div class="grid grid-cols-2 gap-4 border-b border-border pb-4 text-sm sm:grid-cols-4">
                        <div><p class="text-[11px] text-muted-foreground">Usia</p><p class="mt-1 font-medium">{{ $siswa->usia ?: 'Belum diisi' }}</p></div>
                        <div><p class="text-[11px] text-muted-foreground">Wali</p><p class="mt-1 font-medium">{{ $siswa->wali ?: 'Belum diisi' }}</p></div>
                        <div><p class="text-[11px] text-muted-foreground">Kontak</p><p class="mt-1 font-medium">{{ $siswa->kontak ?: 'Belum diisi' }}</p></div>
                        <div><p class="text-[11px] text-muted-foreground">Mulai observasi</p><p class="mt-1 font-medium">{{ $siswa->mulai_dari ?: '—' }}</p></div>
                    </div>
                    @if($siswa->catatan_umum)<div class="pt-4"><p class="text-[11px] font-semibold uppercase tracking-wide text-muted-foreground">Catatan umum</p><p class="print-muted mt-2 whitespace-pre-line text-sm leading-relaxed">{{ $siswa->catatan_umum }}</p></div>@endif
                </section>
            @endif

            <section class="print-card rounded-2xl border border-border bg-card p-5 shadow-sm sm:p-6">
                <div class="mb-5 flex flex-wrap items-end justify-between gap-2">
                    <div><p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Ringkasan Asesmen</p><h2 class="mt-1 text-lg font-semibold">Profil Hasil per Kelompok</h2></div>
                    <p class="text-xs text-muted-foreground">Skala nilai 0–100</p>
                </div>
                @if($rapor['kelompok']->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($rapor['kelompok'] as $kelompok)
                            @php
                                $asesmen = $kelompok['asesmen'];
                                $style = $kelompokStyles[$asesmen->kelompok_key] ?? ['icon' => 'clipboard-check', 'color' => 'slate'];
                                $score = $asesmen->nilai !== null ? (float) $asesmen->nilai : 0;
                            @endphp
                            <div class="grid grid-cols-[auto_1fr_auto] items-center gap-3 rounded-xl border border-border/70 bg-background/70 p-3 sm:gap-4 sm:p-4">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $style['color'] }}-100 text-{{ $style['color'] }}-700 dark:bg-{{ $style['color'] }}-950 dark:text-{{ $style['color'] }}-300"><i data-lucide="{{ $style['icon'] }}" class="h-5 w-5"></i></span>
                                <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><h3 class="text-sm font-semibold">{{ $kelompok['label'] }}</h3><span class="rounded-full px-2 py-0.5 text-[10px] font-medium {{ $asesmen->status === 'Selesai' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300' }}">{{ $asesmen->status }}</span></div><div class="mt-2 h-1.5 overflow-hidden rounded-full bg-muted"><div class="h-full rounded-full bg-emerald-600" style="width: {{ min(100, $score) }}%"></div></div><p class="mt-1 text-[10px] text-muted-foreground">Pengamat: {{ $asesmen->guruPengamat?->nama ?? 'Belum tercatat' }}</p></div>
                                <div class="text-right"><p class="text-lg font-bold">{{ $asesmen->nilai !== null ? number_format($score, 1) : '—' }}<span class="text-xs font-medium text-muted-foreground">{{ $asesmen->nilai !== null ? '%' : '' }}</span></p><p class="text-[10px] text-muted-foreground">{{ $asesmen->kategori ?: 'Belum ada kategori' }}</p></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-border px-5 py-10 text-center"><i data-lucide="clipboard-list" class="mx-auto h-8 w-8 text-muted-foreground/50"></i><p class="mt-3 text-sm font-medium">Belum ada hasil observasi</p><p class="mt-1 text-xs text-muted-foreground">Rincian penilaian akan muncul setelah guru menyimpan asesmen.</p></div>
                @endif
            </section>

            <section class="space-y-4">
                <div><p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Rincian Penilaian</p><h2 class="mt-1 text-lg font-semibold">Catatan Perkembangan</h2><p class="mt-1 text-xs text-muted-foreground">Skor indikator menggunakan skala 1–4 sesuai rubrik observasi.</p></div>
                @forelse($rapor['kelompok'] as $kelompok)
                    @php
                        $asesmen = $kelompok['asesmen'];
                        $style = $kelompokStyles[$asesmen->kelompok_key] ?? ['icon' => 'clipboard-check', 'color' => 'slate'];
                        $isCbt = isset($kelompok['detail']['cbt']) && is_array($kelompok['detail']['cbt']);
                    @endphp
                    <article class="print-card overflow-hidden rounded-2xl border border-border bg-card shadow-sm">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-border bg-muted/40 px-5 py-4">
                            <div class="flex items-center gap-3"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-{{ $style['color'] }}-100 text-{{ $style['color'] }}-700 dark:bg-{{ $style['color'] }}-950 dark:text-{{ $style['color'] }}-300"><i data-lucide="{{ $style['icon'] }}" class="h-4 w-4"></i></span><div><h3 class="text-sm font-semibold">{{ $kelompok['label'] }}</h3><p class="text-[11px] text-muted-foreground">{{ $asesmen->updated_at?->format('d M Y, H:i') ?? 'Waktu tidak tercatat' }}</p></div></div>
                            <div class="text-right"><span class="text-[10px] uppercase tracking-wide text-muted-foreground">Total skor</span><p class="font-mono text-sm font-semibold">{{ $asesmen->total_skor ?? '—' }} <span class="font-normal text-muted-foreground">/ {{ $asesmen->skor_maksimal ?? '—' }}</span></p></div>
                        </div>
                        @if($kelompok['indikator']->isNotEmpty())
                            <div class="divide-y divide-border/70">
                                @foreach($kelompok['indikator'] as $indikator)
                                    <div class="grid grid-cols-[1fr_auto] gap-3 px-5 py-3 sm:grid-cols-[1fr_5rem_1.5fr] sm:items-center">
                                        <div><p class="text-xs font-medium leading-relaxed">{{ $indikator['name'] }}</p>@if($indikator['aspect'])<p class="mt-0.5 text-[10px] text-muted-foreground">{{ $indikator['aspect'] }}</p>@endif</div>
                                        <span class="inline-flex items-center justify-center rounded-lg bg-emerald-50 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">{{ $indikator['score'] !== null ? rtrim(rtrim(number_format($indikator['score'], 1), '0'), '.') : $indikator['raw'] }}</span>
                                        <p class="col-span-2 text-[11px] leading-relaxed text-muted-foreground sm:col-span-1">{{ $indikator['rubrik'] ?: ($indikator['raw'] !== null ? 'Nilai tersimpan: '.$indikator['raw'] : 'Belum ada deskripsi rubrik untuk data ini.') }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($isCbt)
                            <div class="grid grid-cols-2 gap-3 p-5 sm:grid-cols-4">
                                @foreach(['indo' => 'Bahasa Indonesia', 'mtk' => 'Matematika', 'ipas' => 'IPAS', 'inggris' => 'Bahasa Inggris'] as $key => $label)
                                    <div class="rounded-xl border border-border bg-background p-3"><p class="text-[10px] text-muted-foreground">{{ $label }}</p><p class="mt-1 text-xl font-bold">{{ $kelompok['detail']['cbt'][$key] ?? '—' }}<span class="text-xs font-medium text-muted-foreground">/100</span></p></div>
                                @endforeach
                            </div>
                        @else
                            <p class="px-5 py-4 text-xs text-muted-foreground">Belum ada rincian indikator untuk asesmen ini.</p>
                        @endif
                        @if($asesmen->catatan_anecdotal)
                            <div class="border-t border-border bg-amber-50/70 px-5 py-4 dark:bg-amber-950/20"><p class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-wide text-amber-800 dark:text-amber-300"><i data-lucide="message-square-text" class="h-3.5 w-3.5"></i> Catatan Pengamat</p><p class="print-muted mt-2 whitespace-pre-line text-xs leading-relaxed">{{ $asesmen->catatan_anecdotal }}</p></div>
                        @endif
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-border p-8 text-center text-sm text-muted-foreground">Data rincian belum tersedia.</div>
                @endforelse
            </section>

            <footer class="print-card rounded-2xl border border-border bg-card p-5 text-center text-xs text-muted-foreground">
                <p class="font-semibold text-foreground">Sekolah Alam Anak Saleh</p>
                <p class="mt-1">Laporan ini merangkum data asesmen yang tersimpan pada sistem SPMB.</p>
                <p class="mt-3 text-[10px]">Dicetak pada {{ now()->format('d F Y, H:i') }}</p>
            </footer>
        </main>
    </body>
</html>
