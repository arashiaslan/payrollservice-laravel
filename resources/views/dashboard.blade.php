<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    @if(auth()->user()->isAdmin())
    {{-- ================================================================ --}}
    {{-- SECTION ADMIN: Statistik, Absensi Terbaru, Aksi Cepat           --}}
    {{-- ================================================================ --}}
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Sambutan Admin --}}
            <div class="mb-6">
                <p class="text-gray-600 text-lg">
                    Selamat datang, <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>! 👋
                </p>
                <p class="text-gray-400 text-sm">{{ now()->translatedFormat('l, d F Y') }}</p>
            </div>

            {{-- ============================================================ --}}
            {{-- KARTU STATISTIK RINGKASAN (4 kartu)                          --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- Kartu 1: Total Pegawai --}}
                @can('admin')
                <a href="{{ route('employees.index') }}" class="block">
                @endcan
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Pegawai</p>
                            <p class="mt-1 text-3xl font-bold text-indigo-600">{{ $totalPegawai }}</p>
                            <p class="text-xs text-gray-400 mt-1">Pegawai terdaftar</p>
                        </div>
                        {{-- Ikon Orang --}}
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                @can('admin')
                </a>
                @endcan

                {{-- Kartu 2: Absensi Hari Ini --}}
                <a href="{{ route('attendances.index') }}" class="block">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Absensi Hari Ini</p>
                            <p class="mt-1 text-3xl font-bold text-green-600">{{ $hadirHariIni }}</p>
                            <p class="text-xs text-gray-400 mt-1">Hadir dari {{ $absensiHariIni }} tercatat</p>
                        </div>
                        {{-- Ikon Kalender --}}
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                </a>

                {{-- Kartu 3: Payroll Bulan Ini --}}
                <a href="{{ route('payrolls.index') }}" class="block">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Payroll {{ $namaBulanIni }}</p>
                            <p class="mt-1 text-3xl font-bold text-yellow-600">{{ $totalPayrollBulanIni }}</p>
                            <p class="text-xs text-gray-400 mt-1">Slip gaji digenerate</p>
                        </div>
                        {{-- Ikon Uang --}}
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                </a>

                {{-- Kartu 4: Belum Absen Hari Ini (untuk admin) --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-400 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Belum Absen</p>
                            {{-- Hitung: total pegawai dikurang yang sudah absen hari ini --}}
                            <p class="mt-1 text-3xl font-bold text-red-500">{{ max(0, $totalPegawai - $absensiHariIni) }}</p>
                            <p class="text-xs text-gray-400 mt-1">Pegawai belum tercatat</p>
                        </div>
                        {{-- Ikon Peringatan --}}
                        <div class="p-3 bg-red-100 rounded-full">
                            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
            {{-- Akhir Kartu Statistik --}}

            {{-- ============================================================ --}}
            {{-- TABEL ABSENSI TERBARU                                        --}}
            {{-- ============================================================ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Absensi Terbaru</h3>
                        <a href="{{ route('attendances.index') }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($absensiTerbaru->isEmpty())
                        {{-- Tampilan jika belum ada data absensi --}}
                        <div class="text-center py-10 text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p>Belum ada data absensi yang dicatat.</p>
                            <a href="{{ route('attendances.create') }}"
                               class="mt-3 inline-block text-sm text-indigo-600 hover:underline">
                                + Catat Absensi Sekarang
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pegawai</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($absensiTerbaru as $absen)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Nama pegawai dari relasi employee -> user --}}
                                            <td class="px-4 py-3 font-medium text-gray-800">
                                                {{ $absen->employee->user->name ?? '-' }}
                                            </td>
                                            {{-- Tanggal format: 28/04/2026 --}}
                                            <td class="px-4 py-3 text-gray-500">
                                                {{ $absen->tanggal->format('d/m/Y') }}
                                            </td>
                                            {{-- Badge status dengan warna berbeda --}}
                                            <td class="px-4 py-3">
                                                @if($absen->status === 'Hadir')
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        Hadir
                                                    </span>
                                                @elseif($absen->status === 'Izin')
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                        Izin
                                                    </span>
                                                @else
                                                    {{-- Status Alpa --}}
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                        Alpa
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Akhir Tabel Absensi Terbaru --}}

            {{-- ============================================================ --}}
            {{-- SHORTCUT AKSI CEPAT (hanya untuk admin)                      --}}
            {{-- ============================================================ --}}
            @can('admin')
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('employees.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Pegawai
                    </a>
                    <a href="{{ route('attendances.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Catat Absensi
                    </a>
                    <a href="{{ route('payrolls.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-md hover:bg-yellow-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Generate Payroll
                    </a>
                </div>
            </div>
            @endcan

        </div>
    </div>

    @else
    {{-- ================================================================ --}}
    {{-- SECTION PEGAWAI: Sambutan, Absensi Hari Ini, Riwayat, Payroll   --}}
    {{-- ================================================================ --}}
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- ============================================================ --}}
            {{-- A. KARTU SAMBUTAN + STATUS ABSEN HARI INI                    --}}
            {{-- ============================================================ --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kartu Sambutan --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="flex items-center gap-4">
                        {{-- Avatar inisial nama --}}
                        <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-xl font-bold text-indigo-600">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Selamat datang 👋</p>
                            <p class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</p>
                            {{-- Jabatan dari data employee --}}
                            <p class="text-sm text-indigo-600 font-medium">{{ $employee->jabatan ?? 'Pegawai' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ now()->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>
                    {{-- Info NIK --}}
                    @if($employee->nik)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400">NIK</p>
                        <p class="text-sm font-medium text-gray-700">{{ $employee->nik }}</p>
                    </div>
                    @endif
                </div>

                {{-- Kartu Status Absen Hari Ini --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-base font-semibold text-gray-800 mb-4">Status Kehadiran Hari Ini</h3>

                    @if($todayAttendance !== null)
                        {{-- Absensi sudah dicatat: tampilkan badge status --}}
                        <div class="flex items-center gap-3">
                            @if($todayAttendance->status === 'Hadir')
                                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                        Hadir
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">Absensi hari ini sudah dicatat: <strong>Hadir</strong></p>
                                </div>
                            @elseif($todayAttendance->status === 'Izin')
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-700">
                                        Izin
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">Absensi hari ini sudah dicatat: <strong>Izin</strong></p>
                                </div>
                            @else
                                {{-- Status Alpa --}}
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </div>
                                <div>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                                        Alpa
                                    </span>
                                    <p class="text-xs text-gray-400 mt-1">Absensi hari ini sudah dicatat: <strong>Alpa</strong></p>
                                </div>
                            @endif
                        </div>

                    @else
                        {{-- Belum absen: tampilkan form absensi mandiri --}}
                        <div>
                            <p class="text-sm font-semibold text-gray-700 mb-1">Catat Kehadiran Hari Ini</p>
                            <p class="text-xs text-gray-400 mb-4">
                                {{ now()->translatedFormat('l, d F Y') }}
                            </p>
                            <div class="flex gap-3">
                                {{-- Tombol Hadir --}}
                                <form method="POST" action="/my-attendance" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="status" value="Hadir">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-semibold rounded-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Hadir
                                    </button>
                                </form>

                                {{-- Tombol Izin --}}
                                <form method="POST" action="/my-attendance" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="status" value="Izin">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-white font-semibold rounded-lg transition-colors shadow-sm">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20A10 10 0 0012 2z" />
                                        </svg>
                                        Izin
                                    </button>
                                </form>
                                {{-- Catatan: tidak ada tombol Alpa karena pegawai tidak bisa menandai diri sendiri Alpa --}}
                            </div>
                        </div>
                    @endif
                </div>

            </div>
            {{-- Akhir Kartu Sambutan + Absen --}}

            {{-- ============================================================ --}}
            {{-- B. TABEL ABSENSI TERBARU (milik pegawai ini)                 --}}
            {{-- ============================================================ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Riwayat Absensi Terakhir</h3>
                        <a href="{{ route('attendances.index') }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentAttendances->isEmpty())
                        {{-- Belum ada data absensi --}}
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm">Belum ada data absensi.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    @foreach($recentAttendances as $absen)
                                        <tr class="hover:bg-gray-50">
                                            {{-- Tanggal format: 28/04/2026 --}}
                                            <td class="px-4 py-3 text-gray-600">
                                                {{ $absen->tanggal->format('d/m/Y') }}
                                            </td>
                                            {{-- Badge status dengan warna sesuai --}}
                                            <td class="px-4 py-3">
                                                @if($absen->status === 'Hadir')
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                        Hadir
                                                    </span>
                                                @elseif($absen->status === 'Izin')
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                        Izin
                                                    </span>
                                                @else
                                                    {{-- Status Alpa --}}
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                        Alpa
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Akhir Tabel Absensi Terbaru --}}

            {{-- ============================================================ --}}
            {{-- C. RINGKASAN PAYROLL TERBARU (milik pegawai ini)             --}}
            {{-- ============================================================ --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Slip Gaji Terakhir</h3>
                        <a href="{{ route('payrolls.index') }}"
                           class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentPayrolls->isEmpty())
                        {{-- Belum ada data penggajian --}}
                        <div class="text-center py-8 text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <p class="text-sm">Belum ada data penggajian.</p>
                        </div>
                    @else
                        {{-- Grid kartu slip gaji, maks 3 per baris --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($recentPayrolls as $p)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow bg-gray-50">
                                    {{-- Periode: nama bulan + tahun --}}
                                    <p class="text-sm font-semibold text-gray-700 mb-1">
                                        {{ \App\Models\Payroll::getNamaBulan($p->bulan) }} {{ $p->tahun }}
                                    </p>
                                    {{-- Gaji bersih dalam format rupiah --}}
                                    <p class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($p->gaji_bersih, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mb-3">Gaji Bersih</p>
                                    {{-- Link ke detail slip gaji --}}
                                    <a href="{{ route('payrolls.show', $p) }}"
                                       class="inline-block text-xs text-indigo-600 hover:text-indigo-800 font-medium border border-indigo-200 rounded px-3 py-1 hover:bg-indigo-50 transition-colors">
                                        Lihat Slip →
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            {{-- Akhir Ringkasan Payroll --}}

        </div>
    </div>
    @endif
    {{-- Akhir percabangan Admin / Pegawai --}}

</x-app-layout>
