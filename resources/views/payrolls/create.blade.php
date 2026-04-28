<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Generate Payroll
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- ============================================================ --}}
            {{-- Info Box: Aturan Kalkulasi                                    --}}
            {{-- ============================================================ --}}
            <div class="flex items-start gap-3 px-4 py-4 bg-blue-50 border border-blue-300 text-blue-800 rounded-lg">
                <svg class="w-5 h-5 flex-shrink-0 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                          clip-rule="evenodd" />
                </svg>
                <div class="text-sm leading-relaxed">
                    <p class="font-semibold mb-1">Aturan Kalkulasi</p>
                    <ul class="space-y-0.5 text-blue-700">
                        <li>
                            <span class="font-medium">Potongan</span>
                            = Jumlah Alpa &times; <span class="font-mono">Rp 100.000</span>
                        </li>
                        <li>
                            <span class="font-medium">Gaji Bersih</span>
                            = Gaji Pokok + Tunjangan &minus; Potongan
                        </li>
                    </ul>
                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- Card Form Generate Payroll                                    --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Form Generate Payroll</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Pilih periode dan pegawai, lalu lihat estimasi gaji sebelum generate.
                    </p>
                </div>

                {{-- Form --}}
                <form action="{{ route('payrolls.store') }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf

                    {{-- ---------------------------------------------------- --}}
                    {{-- Bulan                                                  --}}
                    {{-- ---------------------------------------------------- --}}
                    <div>
                        <x-input-label for="bulan" value="Bulan" />
                        <select id="bulan"
                                name="bulan"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            @php
                                $daftarBulan = [
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                    '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
                                    '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                                ];
                            @endphp
                            @foreach ($daftarBulan as $num => $nama)
                                <option value="{{ $num }}"
                                    {{ old('bulan', date('m')) == $num ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('bulan')" class="mt-1" />
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Tahun                                                  --}}
                    {{-- ---------------------------------------------------- --}}
                    <div>
                        <x-input-label for="tahun" value="Tahun" />
                        <x-text-input
                            id="tahun"
                            name="tahun"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('tahun', date('Y')) }}"
                            maxlength="4"
                            placeholder="{{ date('Y') }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('tahun')" class="mt-1" />
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Pegawai (opsional)                                     --}}
                    {{-- ---------------------------------------------------- --}}
                    <div>
                        <x-input-label for="employee_id" value="Pegawai" />
                        <p class="text-xs text-gray-400 mb-1">
                            Kosongkan untuk generate semua pegawai sekaligus.
                        </p>
                        <select id="employee_id"
                                name="employee_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Semua Pegawai --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name }} &mdash; {{ $emp->jabatan }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-1" />
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Preview Card (muncul otomatis saat pegawai dipilih)   --}}
                    {{-- ---------------------------------------------------- --}}
                    <div id="preview-card" class="hidden rounded-lg border border-indigo-200 bg-indigo-50 overflow-hidden">

                        {{-- Header preview --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-indigo-100 border-b border-indigo-200">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-semibold text-indigo-700">Estimasi Gaji</span>
                            </div>
                            {{-- Spinner loading --}}
                            <svg id="preview-spinner" class="hidden animate-spin w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                            </svg>
                        </div>

                        {{-- Isi preview --}}
                        <div class="px-4 py-4 space-y-3">

                            {{-- Info Pegawai --}}
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide">Nama</p>
                                    <p id="prev-nama" class="font-semibold text-gray-800">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide">NIK</p>
                                    <p id="prev-nik" class="font-mono text-gray-700">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide">Jabatan</p>
                                    <p id="prev-jabatan" class="text-gray-700">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 font-medium uppercase tracking-wide">Alpa Bulan Ini</p>
                                    <p id="prev-alpa" class="text-gray-700">-</p>
                                </div>
                            </div>

                            {{-- Garis pemisah --}}
                            <div class="border-t border-indigo-200"></div>

                            {{-- Rincian Gaji --}}
                            <div class="space-y-2 text-sm">

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Gaji Pokok</span>
                                    <span id="prev-gaji-pokok" class="font-medium text-gray-800">-</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Tunjangan</span>
                                    {{-- Nilai tunjangan diambil real-time dari input --}}
                                    <span id="prev-tunjangan" class="font-medium text-gray-800">Rp 0</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">
                                        Potongan
                                        <span id="prev-alpa-label" class="text-xs text-red-400"></span>
                                    </span>
                                    <span id="prev-potongan" class="font-medium text-red-600">-</span>
                                </div>

                                {{-- Garis total --}}
                                <div class="border-t border-indigo-300 pt-2 flex justify-between items-center">
                                    <span class="font-semibold text-gray-700">Estimasi Gaji Bersih</span>
                                    <span id="prev-gaji-bersih" class="text-lg font-bold text-green-600">-</span>
                                </div>

                            </div>

                            {{-- Catatan --}}
                            <p class="text-xs text-indigo-400 italic">
                                * Estimasi dihitung dari data absensi yang sudah dicatat. Gaji bersih final mungkin berbeda jika ada perubahan data.
                            </p>
                        </div>
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Total Tunjangan                                        --}}
                    {{-- ---------------------------------------------------- --}}
                    <div>
                        <x-input-label for="total_tunjangan" value="Total Tunjangan (Rp)" />
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <span class="text-gray-400 text-sm">Rp</span>
                            </div>
                            <x-text-input
                                id="total_tunjangan"
                                name="total_tunjangan"
                                type="number"
                                class="block w-full pl-10"
                                value="{{ old('total_tunjangan', 0) }}"
                                min="0"
                                step="1000"
                                placeholder="0"
                            />
                        </div>
                        <p class="text-xs text-gray-400 mt-1">
                            Contoh: <span class="font-mono">500000</span> untuk Rp 500.000. Kosongkan atau isi 0 jika tidak ada tunjangan.
                        </p>
                        <x-input-error :messages="$errors->get('total_tunjangan')" class="mt-1" />
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Tombol Aksi                                            --}}
                    {{-- ---------------------------------------------------- --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('payrolls.index') }}"
                           class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                            Kembali
                        </a>
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Generate
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- JAVASCRIPT: Fetch preview gaji saat pegawai + periode dipilih    --}}
    {{-- ================================================================ --}}
    <script>
        // Simpan URL endpoint preview dari Laravel ke variabel JS
        const previewUrl = "{{ route('payrolls.preview') }}";

        // Referensi ke elemen-elemen form
        const selectEmployee = document.getElementById('employee_id');
        const selectBulan    = document.getElementById('bulan');
        const inputTahun     = document.getElementById('tahun');
        const inputTunjangan = document.getElementById('total_tunjangan');

        // Referensi ke elemen-elemen preview card
        const previewCard    = document.getElementById('preview-card');
        const spinner        = document.getElementById('preview-spinner');

        // Menyimpan data gaji pokok dan potongan yang terakhir di-fetch
        // agar perhitungan ulang bisa dilakukan saat tunjangan berubah
        let cachedGajiPokok   = 0;
        let cachedPotongan    = 0;
        let cachedJumlahAlpa  = 0;

        /**
         * Format angka menjadi format rupiah.
         * Contoh: 5000000 -> "Rp 5.000.000"
         */
        function formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        /**
         * Perbarui tampilan Estimasi Gaji Bersih berdasarkan
         * data yang sudah di-cache + nilai tunjangan saat ini.
         * Dipanggil saat input tunjangan berubah (tanpa fetch ulang).
         */
        function recalcPreview() {
            // Ambil nilai tunjangan dari input (default 0 jika kosong)
            const tunjangan = parseInt(inputTunjangan.value) || 0;

            // Hitung gaji bersih = gaji pokok + tunjangan - potongan
            const gajiBersih = cachedGajiPokok + tunjangan - cachedPotongan;

            // Perbarui tampilan
            document.getElementById('prev-tunjangan').textContent = formatRupiah(tunjangan);
            document.getElementById('prev-gaji-bersih').textContent = formatRupiah(gajiBersih);
        }

        /**
         * Fetch data preview dari server berdasarkan employee + periode.
         * Dipanggil saat salah satu dari: pegawai, bulan, atau tahun berubah.
         */
        function fetchPreview() {
            const employeeId = selectEmployee.value;
            const bulan      = selectBulan.value;
            const tahun      = inputTahun.value.trim();

            // Sembunyikan preview jika pegawai belum dipilih atau tahun belum 4 digit
            if (!employeeId || tahun.length !== 4) {
                previewCard.classList.add('hidden');
                return;
            }

            // Tampilkan card dan spinner saat loading
            previewCard.classList.remove('hidden');
            spinner.classList.remove('hidden');

            // Bangun URL dengan query parameters
            const url = previewUrl + '?employee_id=' + employeeId
                      + '&bulan=' + bulan
                      + '&tahun=' + tahun;

            // Kirim request GET ke endpoint preview
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(function(response) {
                // Jika server mengembalikan error, lempar exception
                if (!response.ok) {
                    throw new Error('Gagal mengambil data preview.');
                }
                return response.json();
            })
            .then(function(data) {
                // Simpan nilai ke cache untuk perhitungan ulang saat tunjangan berubah
                cachedGajiPokok  = data.gaji_pokok;
                cachedPotongan   = data.estimasi_potongan;
                cachedJumlahAlpa = data.jumlah_alpa;

                // Isi elemen-elemen preview dengan data dari server
                document.getElementById('prev-nama').textContent    = data.nama;
                document.getElementById('prev-nik').textContent     = data.nik;
                document.getElementById('prev-jabatan').textContent = data.jabatan;
                document.getElementById('prev-gaji-pokok').textContent = data.gaji_pokok_fmt;
                document.getElementById('prev-potongan').textContent   = '- ' + data.estimasi_potongan_fmt;

                // Tampilkan keterangan alpa di samping label Potongan
                const alpaLabel = document.getElementById('prev-alpa-label');
                if (cachedJumlahAlpa > 0) {
                    alpaLabel.textContent = '(' + cachedJumlahAlpa + ' hari × Rp 100.000)';
                    document.getElementById('prev-alpa').textContent = cachedJumlahAlpa + ' hari';
                    document.getElementById('prev-alpa').className = 'font-semibold text-red-600';
                } else {
                    alpaLabel.textContent = '';
                    document.getElementById('prev-alpa').textContent = '0 hari (tidak ada)';
                    document.getElementById('prev-alpa').className = 'font-semibold text-green-600';
                }

                // Hitung dan tampilkan estimasi gaji bersih (sudah memperhitungkan tunjangan)
                recalcPreview();
            })
            .catch(function(err) {
                // Sembunyikan preview jika terjadi error
                previewCard.classList.add('hidden');
                console.error('Preview error:', err);
            })
            .finally(function() {
                // Sembunyikan spinner setelah selesai (baik sukses maupun gagal)
                spinner.classList.add('hidden');
            });
        }

        // ================================================================
        // EVENT LISTENERS
        // ================================================================

        // Fetch ulang saat pegawai, bulan, atau tahun berubah
        selectEmployee.addEventListener('change', fetchPreview);
        selectBulan.addEventListener('change', fetchPreview);

        // Tahun: fetch ulang hanya setelah pengguna selesai mengetik (pakai debounce)
        let tahunTimer = null;
        inputTahun.addEventListener('input', function() {
            clearTimeout(tahunTimer);
            tahunTimer = setTimeout(fetchPreview, 600); // tunggu 600ms setelah ketik berhenti
        });

        // Tunjangan: tidak perlu fetch, cukup hitung ulang di sisi klien
        inputTunjangan.addEventListener('input', function() {
            // Hanya recalc jika preview sudah tampil (sudah ada data di cache)
            if (!previewCard.classList.contains('hidden')) {
                recalcPreview();
            }
        });

        // Jalankan fetch saat halaman pertama kali dimuat
        // (berguna jika form diisi ulang setelah validasi gagal)
        fetchPreview();
    </script>

</x-app-layout>
