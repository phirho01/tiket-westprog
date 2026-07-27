@extends('layouts.admin')

@section('judul', 'Moderasi Ulasan Pengunjung')

@section('konten')
<div class="space-y-8">
    
    <!-- Admin Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span class="material-symbols-outlined text-sm">rate_review</span>
                <span>Manajemen & Moderasi Ulasan</span>
            </span>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Moderasi Ulasan Pengunjung</h1>
            <p class="text-xs text-slate-500 mt-1">Pantau dan hapus ulasan tidak layak atau spam yang diberikan oleh wisatawan.</p>
        </div>

        <div>
            <span class="px-4 py-2 bg-white border border-slate-200 text-slate-700 rounded-xl text-xs font-bold shadow-2xs">
                Total: {{ $ulasanList->total() }} Ulasan
            </span>
        </div>
    </div>

    @if(session('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            <span>{{ session('sukses') ?? session('success') }}</span>
        </div>
    @endif

    <!-- Tabel Kelola Ulasan (Clean Pure White Enterprise Theme) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Tabel & Search Bar GUI -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900">Daftar Ulasan & Penilaian Masuk</h2>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $ulasanList->firstItem() ?? 0 }} - {{ $ulasanList->lastItem() ?? 0 }} dari total {{ $ulasanList->total() }} data</p>
            </div>

            <!-- Server Search Form GUI -->
            <form action="{{ route('admin.ulasan.index') }}" method="GET" class="relative w-full sm:w-84">
                <div class="relative flex items-center">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama wisatawan, wisata, ulasan..."
                        class="w-full pl-10 pr-20 py-2.5 rounded-2xl bg-slate-50 border border-slate-300 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition shadow-2xs">
                    
                    <button type="submit" class="absolute left-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-xl text-slate-400 hover:text-primary hover:bg-slate-200/60 transition cursor-pointer flex items-center justify-center" title="Klik atau tekan Enter untuk mencari">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>

                    <div class="absolute right-1.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        @if(request('cari'))
                            <a href="{{ route('admin.ulasan.index') }}" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center" title="Hapus pencarian">
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

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                    <tr>
                        <th class="py-4 px-6">ID ULASAN</th>
                        <th class="py-4 px-6">NAMA WISATAWAN</th>
                        <th class="py-4 px-6">DESTINASI WISATA</th>
                        <th class="py-4 px-6">BINTANG RATING</th>
                        <th class="py-4 px-6">KOMENTAR / ULASAN</th>
                        <th class="py-4 px-6">TANGGAL</th>
                        <th class="py-4 px-6 text-center">MODERASI AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($ulasanList as $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-5 px-6 font-bold text-primary whitespace-nowrap">#REV-{{ $u->id_ulasan }}</td>
                            <td class="py-5 px-6 font-bold text-slate-900 text-sm whitespace-normal break-words">{{ $u->user->nama ?? 'Wisatawan' }}</td>
                            <td class="py-5 px-6 font-semibold text-slate-800 whitespace-normal break-words">{{ $u->wisata->nama_wisata ?? 'Wisata' }}</td>
                            <td class="py-5 px-6 whitespace-nowrap">
                                <div class="inline-flex items-center gap-1 bg-amber-50 text-amber-800 px-2.5 py-1 rounded-xl border border-amber-200 font-bold text-xs">
                                    <span class="material-symbols-outlined text-sm text-amber-500" style="font-variation-settings: 'FILL' 1;">star</span>
                                    <span>{{ $u->rating }} / 5</span>
                                </div>
                            </td>
                            <td class="py-5 px-6 text-slate-700 whitespace-normal break-words max-w-sm leading-relaxed">
                                "{{ $u->komentar ?? 'Tanpa ulasan teks' }}"
                            </td>
                            <td class="py-5 px-6 text-slate-500 whitespace-nowrap font-medium">{{ $u->tanggal_ulasan }}</td>
                            <td class="py-5 px-6 text-center whitespace-nowrap">
                                <button type="button"
                                    onclick="konfirmasiHapus('{{ route('admin.ulasan.destroy', $u->id_ulasan) }}', 'Ulasan dari {{ addslashes($u->user->nama ?? 'Wisatawan') }}')"
                                    class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold transition inline-flex items-center gap-1.5 shadow-2xs cursor-pointer">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                    <span>Hapus Ulasan</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-xs italic">
                                Belum ada ulasan pengunjung terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-5 border-t border-slate-100 flex items-center justify-between">
            {{ $ulasanList->links() }}
        </div>
    </div>
</div>
@endsection
