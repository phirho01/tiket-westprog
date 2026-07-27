@extends('layouts.admin')

@section('judul', 'Kelola Pengguna')

@section('konten')
<div class="space-y-8">
    
    <!-- Admin Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-6">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-primary border border-emerald-200 mb-2">
                <span class="material-symbols-outlined text-sm">group</span>
                <span>Manajemen Pengguna Terdaftar</span>
            </span>
            <h1 class="font-heading text-2xl sm:text-3xl font-extrabold text-slate-900">Kelola Akun Pengguna</h1>
            <p class="text-xs text-slate-500 mt-1">Tambah, perbarui data diri, atau hapus akun pengguna ber-role Wisatawan (User).</p>
        </div>

        <div>
            <a href="{{ route('admin.users.tambah') }}"
                class="px-5 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Tambah Wisatawan Baru</span>
            </a>
        </div>
    </div>

    @if(session('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            <span>{{ session('sukses') ?? session('success') }}</span>
        </div>
    @endif

    @if(session('gagal'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs font-semibold flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">warning</span>
            <span>{{ session('gagal') }}</span>
        </div>
    @endif

    <!-- Tabel Kelola Users (Clean Pure White Enterprise Theme) -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        
        <!-- Header Tabel & Search Bar GUI -->
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="font-heading text-base font-bold text-slate-900">Daftar Akun Terdaftar</h2>
                <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $daftarUser->firstItem() ?? 0 }} - {{ $daftarUser->lastItem() ?? 0 }} dari total {{ $daftarUser->total() }} data</p>
            </div>

            <!-- Server Search Form GUI -->
            <form action="{{ route('admin.users.index') }}" method="GET" class="relative w-full sm:w-84">
                <div class="relative flex items-center">
                    <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama, email, no. HP, role..."
                        class="w-full pl-10 pr-20 py-2.5 rounded-2xl bg-slate-50 border border-slate-300 text-xs font-medium text-slate-900 placeholder:text-slate-400 focus:bg-white focus:ring-2 focus:ring-primary focus:border-primary outline-none transition shadow-2xs">
                    
                    <button type="submit" class="absolute left-1.5 top-1/2 -translate-y-1/2 p-1.5 rounded-xl text-slate-400 hover:text-primary hover:bg-slate-200/60 transition cursor-pointer flex items-center justify-center" title="Klik atau tekan Enter untuk mencari">
                        <span class="material-symbols-outlined text-lg">search</span>
                    </button>

                    <div class="absolute right-1.5 top-1/2 -translate-y-1/2 flex items-center gap-1">
                        @if(request('cari'))
                            <a href="{{ route('admin.users.index') }}" class="p-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition flex items-center justify-center" title="Hapus pencarian">
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
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-700 uppercase tracking-wider">
                        <th class="py-4 px-6">ID AKUN</th>
                        <th class="py-4 px-6">NAMA LENGKAP</th>
                        <th class="py-4 px-6">ALAMAT SUREL (EMAIL)</th>
                        <th class="py-4 px-6">NO. TELEPON / WHATSAPP</th>
                        <th class="py-4 px-6 text-center">PERAN (ROLE)</th>
                        <th class="py-4 px-6 text-right whitespace-nowrap">AKSI MODERASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($daftarUser as $u)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-5 px-6 font-bold text-primary whitespace-nowrap">#USR-{{ $u->id_user }}</td>
                            <td class="py-5 px-6 font-bold text-slate-900 text-sm whitespace-normal break-words">{{ $u->nama }}</td>
                            <td class="py-5 px-6 text-slate-700 font-medium whitespace-normal break-words">{{ $u->email }}</td>
                            <td class="py-5 px-6 text-slate-500 whitespace-nowrap">{{ $u->no_hp ?? '-' }}</td>
                            <td class="py-5 px-6 text-center whitespace-nowrap">
                                @if($u->role === 'admin')
                                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 font-bold rounded-full text-[11px] border border-indigo-200 uppercase">Pengelola (Admin)</span>
                                @else
                                    <span class="px-3 py-1 bg-emerald-50 text-emerald-800 font-bold rounded-full text-[11px] border border-emerald-200 uppercase">Wisatawan</span>
                                @endif
                            </td>
                            <td class="py-5 px-6 text-right whitespace-nowrap">
                                @if($u->role === 'user')
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('admin.users.edit', $u->id_user) }}"
                                            class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">edit</span>
                                            <span>Ubah</span>
                                        </a>

                                        <button type="button"
                                            onclick="konfirmasiHapus('{{ route('admin.users.hapus', $u->id_user) }}', 'Akun {{ addslashes($u->nama) }}')"
                                            class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm">delete</span>
                                            <span>Hapus</span>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-[11px] text-slate-400 font-medium italic">Proteksi Pengelola Utama</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 text-xs italic">Belum ada pengguna terdaftar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="p-5 border-t border-slate-100 flex items-center justify-between">
            {{ $daftarUser->links() }}
        </div>
    </div>
</div>
@endsection
