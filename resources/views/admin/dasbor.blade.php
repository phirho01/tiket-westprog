@extends('layouts.admin')

@section('judul', 'Dasbor Analisis Performa')

@section('konten')
<!-- Chart.js Script CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-8">
    
    <!-- Admin Header Banner -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="inline-flex items-center space-x-2 bg-emerald-50 text-primary px-3.5 py-1 rounded-full text-xs font-bold mb-3 border border-emerald-200">
                <span class="material-symbols-outlined text-sm">trending_up</span>
                <span>Analisis Macro & Tren Pariwisata</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Dasbor Analisis Performa</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Ringkasan performa finansial, tren kunjungan wisatawan, dan metrik sistem secara real-time.</p>
        </div>

        <div class="flex items-center space-x-3">
            <span class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-2xl text-xs border border-slate-200 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Status Sistem: AKTIF</span>
            </span>
        </div>
    </div>

    <!-- 5 Summary KPI Cards (Ikon Seragam Uniform Theme Emerald Clean, Tanpa Warna-Warni) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5">
        
        <!-- Total Pendapatan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">TOTAL PENDAPATAN</span>
                <div class="w-10 h-10 bg-emerald-50 text-primary border border-emerald-200 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">payments</span>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-heading text-xl font-extrabold text-primary block">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Pendapatan lunas</span>
            </div>
        </div>

        <!-- Total Pemesanan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">TOTAL PESANAN</span>
                <div class="w-10 h-10 bg-emerald-50 text-primary border border-emerald-200 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">confirmation_number</span>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-heading text-3xl font-extrabold text-slate-900 block">{{ number_format($totalPemesanan) }}</span>
                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Tiket dipesan</span>
            </div>
        </div>

        <!-- Wisata Aktif -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">DESTINASI AKTIF</span>
                <div class="w-10 h-10 bg-emerald-50 text-primary border border-emerald-200 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">landscape</span>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-heading text-3xl font-extrabold text-slate-900 block">{{ number_format($totalWisata) }}</span>
                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Objek wisata</span>
            </div>
        </div>

        <!-- Total User -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">WISATAWAN</span>
                <div class="w-10 h-10 bg-emerald-50 text-primary border border-emerald-200 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">group</span>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-heading text-3xl font-extrabold text-slate-900 block">{{ number_format($totalUser) }}</span>
                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Akun terdaftar</span>
            </div>
        </div>

        <!-- Total Ulasan -->
        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">TOTAL ULASAN</span>
                <div class="w-10 h-10 bg-emerald-50 text-primary border border-emerald-200 rounded-2xl flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-xl">rate_review</span>
                </div>
            </div>
            <div class="mt-4">
                <span class="font-heading text-3xl font-extrabold text-slate-900 block">{{ number_format($totalUlasan) }}</span>
                <span class="text-[10px] text-slate-400 font-medium block mt-0.5">Penilaian masuk</span>
            </div>
        </div>
    </div>

    <!-- Chart Analytics Per Bulan (Januari - Desember Tahun Berjalan) -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-4 gap-2">
            <div>
                <h2 class="font-heading text-lg font-bold text-slate-900">Analisis Tren Pendapatan Per Bulan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Grafik visualisasi pendapatan hasil pemesanan tiket wisata per bulan (Januari - Desember {{ date('Y') }})</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="w-3 h-3 bg-emerald-600 rounded-full inline-block"></span>
                <span class="text-xs font-bold text-slate-700">Pendapatan Tiket (Rp / Bulan)</span>
            </div>
        </div>

        <div class="relative w-full h-80">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>

</div>

<!-- Chart.js Initialization Script (Monthly 12 Months Jan - Dec Data) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('visitorChart').getContext('2d');
        
        const labels = {!! json_encode(array_keys($statistikBulan)) !!};
        const dataValues = {!! json_encode(array_values($statistikBulan)) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan Tiket (Rp)',
                    data: dataValues,
                    borderColor: '#0b6c47',
                    backgroundColor: 'rgba(11, 108, 71, 0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0b6c47',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return ' Pendapatan Bulan ' + context.label + ': Rp ' + context.raw.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + (value / 1000).toLocaleString('id-ID') + 'k';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
