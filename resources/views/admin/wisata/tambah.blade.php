@extends('layouts.admin')

@section('judul', 'Tambah Objek Wisata Baru')

@section('konten')
<div class="max-w-3xl mx-auto space-y-8">
    
    <!-- Header Back Button -->
    <div class="flex items-center justify-between border-b border-slate-200 pb-6">
        <div>
            <a href="{{ route('admin.wisata.index') }}" class="text-xs font-semibold text-slate-500 hover:text-primary inline-flex items-center gap-1.5 transition mb-2">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Kembali ke Daftar Wisata</span>
            </a>
            <h1 class="font-heading text-2xl font-extrabold text-slate-900">Tambah Destinasi Wisata Baru</h1>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.wisata.simpan') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200 p-8 shadow-xs space-y-6" onsubmit="konfirmasiSimpanForm(event, 'Apakah Anda yakin ingin menyimpan destinasi wisata baru ini?')">
        @csrf

        <!-- Nama Wisata -->
        <div>
            <label for="nama_wisata" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Nama Destinasi Wisata <span class="text-rose-600">*</span>
            </label>
            <input type="text" name="nama_wisata" id="nama_wisata" value="{{ old('nama_wisata') }}" placeholder="mis. Pantai Glagah Indah" required
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-semibold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
        </div>

        <!-- Lokasi -->
        <div>
            <label for="lokasi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Lokasi Alamat Wisata <span class="text-rose-600">*</span>
            </label>
            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="mis. Temon, Kulon Progo, DIY" required
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
                    <input type="text" id="harga_tiket_display" placeholder="15.000" required oninput="formatRupiahInput(this)"
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
                    <input type="hidden" name="harga_tiket" id="harga_tiket" value="{{ old('harga_tiket', 15000) }}">
                </div>
            </div>

            <div>
                <label for="kuota_harian" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Kuota Tiket Harian <span class="text-rose-600">*</span>
                </label>
                <input type="number" name="kuota_harian" id="kuota_harian" value="{{ old('kuota_harian', 300) }}" min="0" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>
        </div>

        <!-- Jam Operasional (Jam Buka & Jam Tutup Format WIB) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="jam_buka" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Jam Buka Operasional (WIB) <span class="text-rose-600">*</span>
                </label>
                <input type="time" name="jam_buka" id="jam_buka" value="{{ old('jam_buka', '07:00') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>

            <div>
                <label for="jam_tutup" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Jam Tutup Operasional (WIB) <span class="text-rose-600">*</span>
                </label>
                <input type="time" name="jam_tutup" id="jam_tutup" value="{{ old('jam_tutup', '17:00') }}" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-sm font-bold bg-slate-50 focus:bg-white outline-none transition text-slate-900">
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label for="deskripsi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Deskripsi Lengkap Wisata
            </label>
            <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Jelaskan keindahan daya tarik wisata, fasilitas, dan aksesibilitas..."
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-xs bg-slate-50 focus:bg-white outline-none transition text-slate-900">{{ old('deskripsi') }}</textarea>
        </div>

        <!-- Tautan Google Maps -->
        <div>
            <label for="link_gmaps" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Tautan Embed / Lokasi Google Maps
            </label>
            <input type="url" name="link_gmaps" id="link_gmaps" value="{{ old('link_gmaps') }}" placeholder="https://maps.google.com/?q=..."
                class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:ring-2 focus:ring-primary focus:border-primary text-xs bg-slate-50 focus:bg-white outline-none transition text-slate-900">
        </div>

        <!-- Custom Upload Gambar Bahasa Indonesia -->
        <div x-data="{ fileName: 'Belum ada berkas foto dipilih' }">
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                Upload Foto Sampul Wisata (Dari Perangkat)
            </label>
            
            <input type="file" name="gambar" id="gambar" accept="image/*" class="hidden"
                   @change="fileName = $event.target.files[0] ? $event.target.files[0].name : 'Belum ada berkas foto dipilih'">
            
            <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50 border border-slate-300">
                <label for="gambar" class="px-5 py-2.5 bg-primary hover:bg-primary-container text-white text-xs font-bold rounded-xl cursor-pointer transition shadow-xs inline-flex items-center gap-2 shrink-0">
                    <span class="material-symbols-outlined text-sm">cloud_upload</span>
                    <span>Pilih Berkas Foto</span>
                </label>
                <span class="text-xs text-slate-600 font-semibold truncate" x-text="fileName"></span>
            </div>
            
            <p class="text-[11px] text-slate-500 mt-1.5">Format foto yang didukung: JPG, PNG, WEBP (Maksimal 5 MB).</p>
        </div>

        <!-- Submit Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.wisata.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold hover:bg-slate-200 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-primary text-white text-xs font-bold rounded-xl hover:bg-primary-container transition shadow-md inline-flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                <span>Simpan Destinasi Wisata</span>
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
