@extends('layouts.admin')

@section('judul', 'Kelola Objek Wisata')

@section('konten')
<div class="space-y-8">
    
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-primary border border-emerald-200 mb-2">
                <span class="material-symbols-outlined text-sm">landscape</span>
                <span>Manajemen Objek Wisata</span>
            </span>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Kelola Destinasi Wisata</h1>
            <p class="text-xs text-slate-500 mt-1">Tambah foto wisata dari perangkat, perbarui harga tiket, kuota harian, atau tautan Google Maps.</p>
        </div>

        <div>
            <a href="{{ route('admin.wisata.tambah') }}"
                class="px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">add</span>
                <span>Tambah Wisata Baru</span>
            </a>
        </div>
    </div>

    @if(session('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            <span>{{ session('sukses') ?? session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Admin Card (Clean Pure White Enterprise Table 100% Konsisten) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Tabel & Search Bar GUI -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900">Daftar Objek Wisata</h2>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $daftarWisata->firstItem() ?? 0 }} - {{ $daftarWisata->lastItem() ?? 0 }} dari total {{ $daftarWisata->total() }} data</p>
            </div>

            <!-- Server Search Form GUI -->
            <form action="{{ route('admin.wisata.index') }}" method="GET" class="relative w-full sm:w-84">
                <div class="relative flex items-center">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama wisata / lokasi..."
                        class="w-full pl-10 pr-20 py-2.5 rounded-2xl bg-slate-50 border border-slate-300 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition shadow-2xs">
                    
                    <button type="submit" class="absolute left-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-xl text-slate-400 hover:text-primary hover:bg-slate-200/60 transition cursor-pointer flex items-center justify-center" title="Klik atau tekan Enter untuk mencari">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>

                    <div class="absolute right-1.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        @if(request('cari'))
                            <a href="{{ route('admin.wisata.index') }}" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center" title="Hapus pencarian">
                                <span class="material-symbols-outlined text-base">close</span>
                            </a>
                        @endif
                        <button type="submit" class="px-2.5 py-1 bg-primary text-white rounded-xl text-[11px] font-bold hover:bg-primary-container transition shadow-2xs flex items-center gap-1 cursor-pointer" title="Klik atau tekan Enter untuk mencari">
                            <span>Cari</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if($daftarWisata->isEmpty())
            <div class="p-12 text-center text-slate-400 text-xs">
                Belum ada data wisata terdaftar. Klik tombol <strong>Tambah Wisata Baru</strong> di atas.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">NO</th>
                            <th class="px-6 py-4">FOTO</th>
                            <th class="px-6 py-4">NAMA WISATA</th>
                            <th class="px-6 py-4">LOKASI</th>
                            <th class="px-6 py-4">HARGA TIKET</th>
                            <th class="px-6 py-4">KUOTA HARIAN</th>
                            <th class="px-6 py-4 text-center">AKSI OPERASIONAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($daftarWisata as $indeks => $w)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="px-6 py-5 font-bold text-slate-400 whitespace-nowrap">{{ $daftarWisata->firstItem() + $indeks }}</td>
                                
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="w-12 h-10 rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shrink-0">
                                        @if($w->gambar && str_contains($w->gambar, 'http'))
                                            <img src="{{ $w->gambar }}" alt="{{ $w->nama_wisata }}" class="w-full h-full object-cover">
                                        @elseif($w->gambar && file_exists(public_path('uploads/wisata/' . $w->gambar)))
                                            <img src="{{ asset('uploads/wisata/' . $w->gambar) }}" alt="{{ $w->nama_wisata }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-400">
                                                <span class="material-symbols-outlined text-base">landscape</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-5 font-bold text-slate-900 text-sm whitespace-normal break-words">
                                    {{ $w->nama_wisata }}
                                </td>
                                <td class="px-6 py-5 text-xs text-slate-600 whitespace-normal break-words">
                                    {{ $w->lokasi }}
                                </td>
                                <td class="px-6 py-5 font-bold text-primary text-xs whitespace-nowrap">
                                    Rp {{ number_format($w->harga_tiket, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-5 text-xs whitespace-nowrap">
                                    <span class="bg-emerald-50 text-emerald-800 border border-emerald-200 px-3 py-1 rounded-full font-bold">
                                        {{ number_format($w->kuota_harian) }} / hari
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center space-x-2">
                                        @if($w->link_gmaps)
                                            <a href="{{ $w->link_gmaps }}" target="_blank" rel="noopener noreferrer" 
                                                title="Buka lokasi di Google Maps"
                                                class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                                <span class="material-symbols-outlined text-sm text-primary">map</span>
                                                <span>Peta</span>
                                            </a>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Tanpa Map</span>
                                        @endif

                                        <a href="{{ route('admin.wisata.edit', $w->id_wisata) }}"
                                            class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                            <span>Ubah</span>
                                        </a>

                                        <button type="button"
                                            onclick="konfirmasiHapus('{{ route('admin.wisata.hapus', $w->id_wisata) }}', 'Wisata {{ addslashes($w->nama_wisata) }}')"
                                            class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="p-5 border-t border-slate-100 flex items-center justify-between">
                {{ $daftarWisata->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
