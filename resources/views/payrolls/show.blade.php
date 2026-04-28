<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Slip Gaji
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    Periode:
                    <span class="font-medium text-gray-700">
                        {{ \App\Models\Payroll::getNamaBulan($payroll->bulan) }} {{ $payroll->tahun }}
                    </span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- Card Utama: Slip Gaji                                         --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            {{-- Ikon Dokumen --}}
                            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                     stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Slip Gaji Karyawan</h3>
                                <p class="text-sm text-gray-500">
                                    {{ \App\Models\Payroll::getNamaBulan($payroll->bulan) }} {{ $payroll->tahun }}
                                </p>
                            </div>
                        </div>
                        {{-- Badge Periode --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">
                            {{ \App\Models\Payroll::getNamaBulan($payroll->bulan) }} {{ $payroll->tahun }}
                        </span>
                    </div>
                </div>

                {{-- ========================================================== --}}
                {{-- Layout 2 Kolom: Info Pegawai | Rincian Gaji                --}}
                {{-- ========================================================== --}}
                <div class="grid grid-cols-1 md:grid-cols-2 divide-y md:divide-y-0 md:divide-x divide-gray-200">

                    {{-- ---------------------------------------------------- --}}
                    {{-- Kolom Kiri: Informasi Pegawai                          --}}
                    {{-- ---------------------------------------------------- --}}
                    <div class="px-6 py-6 space-y-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Informasi Pegawai
                        </h4>

                        {{-- Nama --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama</p>
                                <p class="mt-0.5 text-base font-semibold text-gray-800">
                                    {{ $payroll->employee->user->name }}
                                </p>
                            </div>
                        </div>

                        {{-- NIK --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">NIK</p>
                                <p class="mt-0.5 text-base font-mono text-gray-700">
                                    {{ $payroll->employee->nik }}
                                </p>
                            </div>
                        </div>

                        {{-- Jabatan --}}
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                     stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jabatan</p>
                                <p class="mt-0.5 text-base text-gray-700">
                                    {{ $payroll->employee->jabatan }}
                                </p>
                            </div>
                        </div>

                        {{-- Info Alpa --}}
                        @php
                            $jumlahAlpa = $payroll->total_potongan > 0
                                ? (int) ($payroll->total_potongan / 100000)
                                : 0;
                        @endphp
                        <div class="mt-2 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                                Rekap Ketidakhadiran
                            </p>
                            <div class="inline-flex items-center gap-2 px-3 py-2 rounded-lg
                                {{ $jumlahAlpa > 0 ? 'bg-red-50 border border-red-200' : 'bg-green-50 border border-green-200' }}">
                                @if ($jumlahAlpa > 0)
                                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-red-700">
                                        {{ $jumlahAlpa }} hari Alpa
                                    </span>
                                @else
                                    <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                              clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-sm font-semibold text-green-700">
                                        Tidak ada Alpa
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ---------------------------------------------------- --}}
                    {{-- Kolom Kanan: Rincian Gaji                              --}}
                    {{-- ---------------------------------------------------- --}}
                    <div class="px-6 py-6 space-y-4">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Rincian Gaji
                        </h4>

                        {{-- Gaji Pokok --}}
                        <div class="flex items-center justify-between py-2">
                            <span class="text-sm text-gray-600">Gaji Pokok</span>
                            <span class="text-sm font-medium text-gray-800 tabular-nums">
                                {{ \App\Models\Payroll::formatRupiah($payroll->employee->gaji_pokok) }}
                            </span>
                        </div>

                        {{-- Total Tunjangan --}}
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <span class="text-sm text-gray-600">Total Tunjangan</span>
                            <span class="text-sm font-medium text-gray-800 tabular-nums">
                                + {{ \App\Models\Payroll::formatRupiah($payroll->total_tunjangan) }}
                            </span>
                        </div>

                        {{-- Total Potongan --}}
                        <div class="flex items-center justify-between py-2 border-t border-gray-100">
                            <div>
                                <span class="text-sm text-gray-600">Total Potongan</span>
                                @if ($jumlahAlpa > 0)
                                    <span class="ml-1.5 text-xs text-gray-400">
                                        ({{ $jumlahAlpa }} hari &times; Rp 100.000)
                                    </span>
                                @endif
                            </div>
                            @if ($payroll->total_potongan > 0)
                                <span class="text-sm font-medium text-red-600 tabular-nums">
                                    &minus; {{ \App\Models\Payroll::formatRupiah($payroll->total_potongan) }}
                                </span>
                            @else
                                <span class="text-sm font-medium text-gray-400 tabular-nums">
                                    {{ \App\Models\Payroll::formatRupiah(0) }}
                                </span>
                            @endif
                        </div>

                        {{-- Garis Pemisah --}}
                        <div class="border-t-2 border-gray-300"></div>

                        {{-- Gaji Bersih --}}
                        <div class="flex items-center justify-between py-3 px-4 bg-green-50 border border-green-200 rounded-lg">
                            <span class="text-base font-bold text-gray-700">Gaji Bersih</span>
                            <span class="text-xl font-extrabold text-green-700 tabular-nums">
                                {{ \App\Models\Payroll::formatRupiah($payroll->gaji_bersih) }}
                            </span>
                        </div>

                        {{-- Catatan kalkulasi --}}
                        <div class="pt-1">
                            <p class="text-xs text-gray-400 leading-relaxed">
                                Gaji Bersih = Gaji Pokok + Tunjangan &minus; Potongan
                                <br>
                                = {{ \App\Models\Payroll::formatRupiah($payroll->employee->gaji_pokok) }}
                                + {{ \App\Models\Payroll::formatRupiah($payroll->total_tunjangan) }}
                                &minus; {{ \App\Models\Payroll::formatRupiah($payroll->total_potongan) }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ============================================================ --}}
            {{-- Tombol Aksi Bawah                                             --}}
            {{-- ============================================================ --}}
            <div class="flex items-center justify-between gap-3 flex-wrap">

                {{-- Tombol Kembali --}}
                <a href="{{ route('payrolls.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Daftar
                </a>

                {{-- Tombol Hapus (khusus admin) --}}
                @can('admin')
                    <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus slip gaji {{ $payroll->employee->user->name }} periode {{ \App\Models\Payroll::getNamaBulan($payroll->bulan) }} {{ $payroll->tahun }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Slip Gaji
                        </button>
                    </form>
                @endcan

            </div>

        </div>
    </div>
</x-app-layout>
