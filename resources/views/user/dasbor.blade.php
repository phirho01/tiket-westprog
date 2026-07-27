@extends('layouts.app')

@section('judul', 'Dasbor Wisatawan - Westprog Ticket')

@section('konten')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
    <!-- Header Greeting Card (User Control & Freedom) -->
    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center space-x-2 bg-brand-50 text-brand-800 px-3 py-1 rounded-full text-xs font-semibold mb-3 border border-brand-100">
                <svg class="w-4 h-4 text-brand-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Akun Wisatawan Terverifikasi</span>
            </div>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang, {{ Auth::user()->nama }}</h1>
            <p class="text-slate-500 text-xs sm:text-sm mt-1">Kelola tiket pemesanan Anda dan pilih destinasi wisata Kulon Progo.</p>
        </div>
        <div class="bg-slate-50 text-slate-700 border border-slate-200 px-4 py-2 rounded-2xl text-xs font-bold uppercase tracking-wider">
            Role: {{ Auth::user()->role }}
        </div>
    </div>

    <!-- Section Title -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h2 class="font-heading text-2xl font-bold tracking-tight text-slate-900">Destinasi Wisata Siap Dipesan</h2>
            <p class="text-xs text-slate-500 mt-0.5">Pilih wisata di bawah ini untuk memulai pemesanan tiket digital</p>
        </div>
    </div>

    <!-- Grid Card Wisata -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($daftarWisata as $wisata)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-xl transition duration-300 flex flex-col overflow-hidden group">
                
                <div class="h-48 bg-slate-900 relative overflow-hidden flex items-center justify-center bg-gradient-to-br from-slate-900 to-brand-950">
                    @if($wisata->gambar && str_contains($wisata->gambar, 'http'))
                        <img src="{{ $wisata->gambar }}" alt="{{ $wisata->nama_wisata }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500 opacity-90">
                    @else
                        <svg class="w-16 h-16 text-white/20 group-hover:scale-110 transition duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    @endif
                    <div class="absolute top-4 right-4 bg-white/95 backdrop-blur-md px-3.5 py-1 rounded-full text-xs font-bold text-brand-800 shadow-sm border border-white/50">
                        Kuota: {{ number_format($wisata->kuota_harian) }} / hari
                    </div>
                </div>

                <div class="p-6 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-heading text-xl font-bold text-slate-900 mb-2 group-hover:text-brand-700 transition">
                            {{ $wisata->nama_wisata }}
                        </h3>
                        
                        @if($wisata->deskripsi)
                            <p class="text-xs text-slate-600 mb-4 line-clamp-2 leading-relaxed">
                                {{ $wisata->deskripsi }}
                            </p>
                        @endif
                        
                        <div class="flex items-center text-xs font-medium text-slate-500 mb-6">
                            <svg class="w-4 h-4 mr-1.5 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="truncate">{{ $wisata->lokasi }}</span>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="block text-[11px] font-semibold uppercase tracking-wider text-slate-400">Harga Tiket</span>
                            <span class="font-heading text-lg font-extrabold text-brand-700">Rp {{ number_format($wisata->harga_tiket, 0, ',', '.') }}</span>
                        </div>

                        <div class="flex items-center space-x-2">
                            @if($wisata->link_gmaps)
                            <a href="{{ $wisata->link_gmaps }}" target="_blank" rel="noopener noreferrer" 
                               title="Lihat Peta Lokasi di Google Maps" 
                               class="p-2.5 text-slate-600 bg-slate-100 hover:bg-brand-50 hover:text-brand-700 rounded-xl transition border border-slate-200/80">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                </svg>
                            </a>
                            @endif

                            <button onclick="alert('Pemesanan tiket {{ addslashes($wisata->nama_wisata) }} siap dilanjutkan.')"
                                class="px-4 py-2.5 text-xs font-semibold text-white bg-brand-700 hover:bg-brand-800 rounded-xl transition shadow-sm shadow-brand-700/20">
                                Pesan Tiket
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
