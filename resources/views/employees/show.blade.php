<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pegawai: {{ $employee->user->name }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- CARD: Informasi Pegawai                                       --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Informasi Pegawai</h3>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('employees.edit', $employee) }}"
                           class="px-4 py-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-sm font-medium rounded-md transition">
                            Edit
                        </a>
                        <a href="{{ route('employees.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>

                {{-- Isi Info --}}
                <div class="px-6 py-6 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4">

                    {{-- Nama --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Lengkap</p>
                        <p class="mt-1 text-base font-medium text-gray-800">{{ $employee->user->name }}</p>
                    </div>

                    {{-- Email --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</p>
                        <p class="mt-1 text-base text-gray-700">{{ $employee->user->email }}</p>
                    </div>

                    {{-- NIK --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">NIK</p>
                        <p class="mt-1 text-base text-gray-700">{{ $employee->nik }}</p>
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Jabatan</p>
                        <p class="mt-1 text-base text-gray-700">{{ $employee->jabatan }}</p>
                    </div>

                    {{-- Gaji Pokok --}}
                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Gaji Pokok</p>
                        <p class="mt-1 text-base font-semibold text-indigo-700">
                            {{ \App\Models\Payroll::formatRupiah($employee->gaji_pokok) }}
                        </p>
                    </div>

                </div>
            </div>

            {{-- ============================================================ --}}
            {{-- CARD: Riwayat Absensi (10 Terakhir)                          --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Riwayat Absensi Terakhir</h3>
                    <p class="text-sm text-gray-500 mt-1">Menampilkan 10 catatan absensi terbaru.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-10">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($employee->attendances->sortByDesc('tanggal')->take(10) as $index => $attendance)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ \Carbon\Carbon::parse($attendance->tanggal)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($attendance->status === 'Hadir')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Hadir
                                            </span>
                                        @elseif ($attendance->status === 'Izin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Izin
                                            </span>
                                        @elseif ($attendance->status === 'Alpa')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Alpa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                {{ $attendance->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center text-gray-400 italic">
                                        Belum ada data absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- ============================================================ --}}
            {{-- CARD: Riwayat Payroll / Slip Gaji                            --}}
            {{-- ============================================================ --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Riwayat Slip Gaji</h3>
                    <p class="text-sm text-gray-500 mt-1">Semua catatan penggajian untuk pegawai ini.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-10">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Bulan</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tahun</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Gaji Bersih</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($employee->payrolls->sortByDesc('tahun')->sortByDesc('bulan') as $index => $p)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-gray-700">
                                        {{ \App\Models\Payroll::getNamaBulan($p->bulan) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700">{{ $p->tahun }}</td>
                                    <td class="px-4 py-3 font-semibold text-indigo-700">
                                        {{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ route('payrolls.show', $p) }}"
                                           class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition">
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-400 italic">
                                        Belum ada data slip gaji.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- Tombol Bawah --}}
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('employees.edit', $employee) }}"
                   class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-medium rounded-md transition">
                    Edit Pegawai
                </a>
                <a href="{{ route('employees.index') }}"
                   class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm font-medium rounded-md transition">
                    Kembali ke Daftar
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
