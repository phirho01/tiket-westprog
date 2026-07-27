@extends('layouts.admin')

@section('judul', 'Ubah Data Wisata - ' . $wisata->nama_wisata)

@section('konten')
<div class="max-w-3xl mx-auto space-y-8">
    
    <!-- Header Back Button -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-6">
        <div>
            <a href="{{ route('admin.wisata.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary inline-flex items-center gap-1.5 transition mb-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Daftar Wisata</span>
            </a>
            <h1 class="font-heading text-2xl font-extrabold text-slate-900">Ubah Data Destinasi Wisata</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui rincian {{ $wisata->nama_wisata }}</p>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.wisata.perbarui', $wisata->id_wisata) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-6" onsubmit="konfirmasiSimpanForm(event, 'Apakah Anda yakin ingin menyimpan perubahan data wisata {{ addslashes($wisata->nama_wisata) }} ini?')">
        @csrf
        @method('PUT')

        <!-- Nama Wisata -->
        <div>
            <label for="nama_wisata" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Nama Destinasi Wisata <span class="text-rose-600">*</span>
            </label>
            <input type="text" name="nama_wisata" id="nama_wisata" value="{{ old('nama_wisata', $wisata->nama_wisata) }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-semibold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
        </div>

        <!-- Lokasi -->
        <div>
            <label for="lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Lokasi Alamat Wisata <span class="text-rose-600">*</span>
            </label>
            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $wisata->lokasi) }}" required
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm bg-slate-50 focus:bg-white outline-none transition text-slate-900">
        </div>

        <!-- Harga Tiket & Kuota Harian -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="harga_tiket_display" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Harga Tiket Masuk (RP) <span class="text-rose-600">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-xs font-bold text-slate-400">Rp</span>
                    <input type="text" id="harga_tiket_display" 
                        value="{{ number_format((float)old('harga_tiket', $wisata->harga_tiket), 0, ',', '.') }}" required
                        oninput="formatRupiahInput(this)"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
                    <input type="hidden" name="harga_tiket" id="harga_tiket" value="{{ old('harga_tiket', $wisata->harga_tiket) }}">
                </div>
            </div>

            <div>
                <label for="kuota_harian" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kuota Tiket Harian <span class="text-rose-600">*</span>
                </label>
                <input type="number" name="kuota_harian" id="kuota_harian" value="{{ old('kuota_harian', $wisata->kuota_harian) }}" min="0" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>
        </div>

        <!-- Jam Operasional (Jam Buka & Jam Tutup Format WIB) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="jam_buka" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Jam Buka Operasional (WIB) <span class="text-rose-600">*</span>
                </label>
                <input type="time" name="jam_buka" id="jam_buka" value="{{ old('jam_buka', date('H:i', strtotime($wisata->jam_buka ?? '07:00'))) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <div>
                <label for="jam_tutup" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Jam Tutup Operasional (WIB) <span class="text-rose-600">*</span>
                </label>
                <input type="time" name="jam_tutup" id="jam_tutup" value="{{ old('jam_tutup', date('H:i', strtotime($wisata->jam_tutup ?? '17:00'))) }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Deskripsi Lengkap Wisata
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="4"
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-xs bg-slate-50 focus:bg-white outline-none transition text-slate-900">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
        </div>

        <!-- Tautan Google Maps -->
        <div>
            <label for="link_gmaps" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Tautan Embed / Lokasi Google Maps
            </label>
            <input type="url" name="link_gmaps" id="link_gmaps" value="{{ old('link_gmaps', $wisata->link_gmaps) }}"
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-xs bg-slate-50 focus:bg-white outline-none transition text-slate-900">
        </div>

        <!-- Custom Upload Gambar Bahasa Indonesia -->
        <div x-data="{ fileName: 'Belum ada berkas foto baru dipilih' }">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Foto Sampul Wisata Saat Ini & Upload Baru
            </label>
            
            <div class="mb-3 w-40 h-24 rounded-xl overflow-hidden bg-slate-100 border border-slate-200">
                @if($wisata->gambar && str_contains($wisata->gambar, 'http'))
                    <img src="{{ $wisata->gambar }}" alt="Foto Sampul" class="w-full h-full object-cover">
                @elseif($wisata->gambar && file_exists(public_path('uploads/wisata/' . $wisata->gambar)))
                    <img src="{{ asset('uploads/wisata/' . $wisata->gambar) }}" alt="Foto Sampul" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs italic">Tanpa Foto</div>
                @endif
            </div>

            <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden"
                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Belum ada berkas foto baru dipilih'">
            
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-300">
                <label for="gambar" class="px-5 py-2.5 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl cursor-pointer transition shadow-xs inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-sm">cloud_upload</span>
                    <span>Pilih Berkas Foto Baru</span>
                </label>
                <span class="text-xs text-slate-600 font-semibold truncate" x-text="fileName"></span>
            </div>

            <p class="text-[11px] text-slate-500 mt-1.5">Biarkan kosong jika tidak ingin mengganti foto sampul saat ini.</p>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.wisata.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Simpan Perubahan Wisata</span>
            </button>
        </div>
    </form>
</div>

<script>
    function formatRupiahInput(input) {
        let val = input.value.replace(/[^0-9]/g, '');
        if (val) {
            let number = parseInt(val, 10);
            input.value = number.toLocaleString('id-ID');
            document.getElementById('harga_tiket').value = number;
        } else {
            input.value = '';
            document.getElementById('harga_tiket').value = 0;
        }
    }
</script>
@endsection
