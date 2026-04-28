<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pegawai Baru
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Form Tambah Pegawai</h3>
                    <p class="text-sm text-gray-500 mt-1">Isi semua data pegawai dengan lengkap dan benar.</p>
                </div>

                {{-- Form --}}
                <form action="{{ route('employees.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    {{-- Nama Lengkap --}}
                    <div>
                        <x-input-label for="name" value="Nama Lengkap" />
                        <x-text-input
                            id="name"
                            name="name"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Masukkan nama lengkap"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        />
                        <x-input-error :messages="$errors->get('name')" class="mt-1" />
                    </div>

                    {{-- Email --}}
                    <div>
                        <x-input-label for="email" value="Alamat Email" />
                        <x-text-input
                            id="email"
                            name="email"
                            type="email"
                            class="mt-1 block w-full"
                            placeholder="contoh@email.com"
                            value="{{ old('email') }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    {{-- Password --}}
                    <div>
                        <x-input-label for="password" value="Password" />
                        <x-text-input
                            id="password"
                            name="password"
                            type="password"
                            class="mt-1 block w-full"
                            placeholder="Minimal 8 karakter"
                            required
                        />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                        <x-text-input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            class="mt-1 block w-full"
                            placeholder="Ulangi password yang sama"
                            required
                        />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                    </div>

                    {{-- Divider --}}
                    <div class="border-t border-gray-100 pt-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Kepegawaian</p>
                    </div>

                    {{-- NIK --}}
                    <div>
                        <x-input-label for="nik" value="NIK (Nomor Induk Karyawan)" />
                        <x-text-input
                            id="nik"
                            name="nik"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Masukkan NIK pegawai"
                            value="{{ old('nik') }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('nik')" class="mt-1" />
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <x-input-label for="jabatan" value="Jabatan" />
                        <x-text-input
                            id="jabatan"
                            name="jabatan"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Contoh: Staff Keuangan, Manajer HRD"
                            value="{{ old('jabatan') }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('jabatan')" class="mt-1" />
                    </div>

                    {{-- Gaji Pokok --}}
                    <div>
                        <x-input-label for="gaji_pokok" value="Gaji Pokok (Rp)" />
                        <x-text-input
                            id="gaji_pokok"
                            name="gaji_pokok"
                            type="number"
                            class="mt-1 block w-full"
                            placeholder="Contoh: 5000000"
                            value="{{ old('gaji_pokok') }}"
                            min="0"
                            required
                        />
                        <x-input-error :messages="$errors->get('gaji_pokok')" class="mt-1" />
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('employees.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                            Kembali
                        </a>
                        <button type="submit"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                            Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
