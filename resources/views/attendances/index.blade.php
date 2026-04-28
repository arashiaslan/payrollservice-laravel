<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Absensi
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div class="px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Card Filter --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Filter Data</h3>
                </div>
                <div class="px-6 py-4">
                    <form action="{{ route('attendances.index') }}" method="GET">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">

                            {{-- Filter Pegawai --}}
                            <div>
                                <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pegawai
                                </label>
                                <select id="employee_id" name="employee_id"
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Semua Pegawai --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ $employeeId == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Bulan --}}
                            <div>
                                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Bulan
                                </label>
                                <select id="bulan" name="bulan"
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Semua Bulan --</option>
                                    @php
                                        $namaBulan = [
                                            '01' => 'Januari',
                                            '02' => 'Februari',
                                            '03' => 'Maret',
                                            '04' => 'April',
                                            '05' => 'Mei',
                                            '06' => 'Juni',
                                            '07' => 'Juli',
                                            '08' => 'Agustus',
                                            '09' => 'September',
                                            '10' => 'Oktober',
                                            '11' => 'November',
                                            '12' => 'Desember',
                                        ];
                                    @endphp
                                    @foreach ($namaBulan as $num => $nama)
                                        <option value="{{ $num }}"
                                            {{ $bulan == $num ? 'selected' : '' }}>
                                            {{ $nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Filter Tahun --}}
                            <div>
                                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tahun
                                </label>
                                <select id="tahun" name="tahun"
                                        class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">-- Semua Tahun --</option>
                                    @php
                                        $tahunSekarang = (int) date('Y');
                                    @endphp
                                    @for ($y = $tahunSekarang - 2; $y <= $tahunSekarang + 1; $y++)
                                        <option value="{{ $y }}"
                                            {{ $tahun == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            {{-- Tombol Aksi Filter --}}
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                    Filter
                                </button>
                                <a href="{{ route('attendances.index') }}"
                                   class="flex-1 text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- TABS AREA --}}
            <div x-data="{ tab: 'rekap' }" class="w-full">
                
                {{-- Tabs Navigation --}}
                <div class="flex border-b border-gray-200 mb-6 bg-white overflow-hidden rounded-t-lg shadow-sm">
                    <button @click="tab = 'rekap'"
                            :class="{'border-b-2 border-indigo-500 text-indigo-600 bg-indigo-50': tab === 'rekap', 'text-gray-500 hover:text-gray-700': tab !== 'rekap'}"
                            class="px-6 py-4 font-medium text-sm transition focus:outline-none w-1/2 md:w-auto text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Rekap Karyawan
                    </button>
                    <button @click="tab = 'tabel'"
                            :class="{'border-b-2 border-indigo-500 text-indigo-600 bg-indigo-50': tab === 'tabel', 'text-gray-500 hover:text-gray-700': tab !== 'tabel'}"
                            class="px-6 py-4 font-medium text-sm transition focus:outline-none w-1/2 md:w-auto text-center flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Tabel Absensi
                    </button>
                </div>

                {{-- Tab 1: Rekap Karyawan --}}
                <div x-show="tab === 'rekap'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($employeesStats as $emp)
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition">
                            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                                <div class="bg-indigo-100 text-indigo-700 p-2.5 rounded-full flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">{{ $emp->user->name }}</h4>
                                    <p class="text-xs text-gray-500">{{ $emp->jabatan }}</p>
                                </div>
                            </div>
                            
                            <div class="px-5 py-4">
                                <p class="text-xs text-center text-gray-400 mb-3 uppercase tracking-wider font-semibold">Terkumpul Bulan Ini</p>
                                <div class="grid grid-cols-3 gap-2 text-center divide-x divide-gray-100">
                                    <div class="px-2">
                                        <p class="text-xs text-gray-500 mb-1">Hadir</p>
                                        <p class="font-bold text-lg text-green-600">{{ $emp->hadir_count }}</p>
                                    </div>
                                    <div class="px-2">
                                        <p class="text-xs text-gray-500 mb-1">Izin</p>
                                        <p class="font-bold text-lg text-yellow-500">{{ $emp->izin_count }}</p>
                                    </div>
                                    <div class="px-2">
                                        <p class="text-xs text-gray-500 mb-1">Alpa</p>
                                        <p class="font-bold text-lg text-red-600">{{ $emp->alpa_count }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-14 text-center bg-white rounded-lg shadow-sm border border-gray-200">
                            <p class="text-gray-500">Tidak ada data rekap karyawan ditemukan berdasarkan filter Anda.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Tab 2: Tabel Absensi --}}
                <div x-show="tab === 'tabel'" style="display: none;" x-cloak>
                    <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-200">
        
                        {{-- Header Card --}}
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 border-b border-gray-200 gap-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-700">Detail Tabel Absensi</h3>
                                <p class="text-sm text-gray-500 mt-0.5">Semua riwayat *check-in* individu.</p>
                            </div>
                            @can('admin')
                                <a href="{{ route('attendances.create') }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                    Catat Absensi
                                </a>
                            @endcan
                        </div>
        
                        {{-- Tampilan Desktop (Tabel) --}}
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50 uppercase tracking-wider text-xs">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-500 w-10">No</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Nama Pegawai</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Tanggal</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-500">Status</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-500 w-24">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @forelse ($attendances as $index => $att)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3"><div class="font-medium text-gray-800">{{ $att->employee->user->name }}</div></td>
                                            <td class="px-4 py-3 text-gray-600">{{ \Carbon\Carbon::parse($att->tanggal)->format('d F Y') }}</td>
                                            <td class="px-4 py-3">
                                                @if ($att->status === 'Hadir')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">Hadir</span>
                                                @elseif ($att->status === 'Izin')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">Izin</span>
                                                @elseif ($att->status === 'Alpa')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">Alpa</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-200">{{ $att->status }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center justify-center gap-2">
                                                    @can('admin')
                                                        <a href="{{ route('attendances.edit', $att) }}" class="px-2 py-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 hover:text-yellow-700 text-xs font-medium rounded border border-yellow-200 transition">Edit</a>
                                                        <form action="{{ route('attendances.destroy', $att) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="px-2 py-1 bg-red-50 hover:bg-red-100 text-red-600 hover:text-red-700 text-xs font-medium rounded border border-red-200 transition">Hapus</button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs text-gray-400 italic">Lihat</span>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-14 text-center">
                                                <div class="flex flex-col items-center justify-center gap-2 text-gray-400">
                                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    <span class="text-sm italic">Belum ada data absensi di tabel ini.</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Tampilan Mobile (Cards) --}}
                        <div class="block md:hidden border-t border-gray-200 divide-y divide-gray-100 bg-white">
                            @forelse ($attendances as $att)
                                <div class="p-4 flex flex-col gap-3">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="font-semibold text-gray-800">{{ $att->employee->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($att->tanggal)->format('d M Y') }}</p>
                                        </div>
                                        <div>
                                            @if ($att->status === 'Hadir')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">Hadir</span>
                                            @elseif ($att->status === 'Izin')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">Izin</span>
                                            @elseif ($att->status === 'Alpa')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">Alpa</span>
                                            @endif
                                        </div>
                                    </div>
                                    @can('admin')
                                    <div class="flex justify-end gap-2 mt-1">
                                        <a href="{{ route('attendances.edit', $att) }}" class="px-3 py-1.5 bg-yellow-50 text-yellow-600 border border-yellow-200 focus:ring-2 focus:ring-yellow-500 text-xs font-medium rounded transition">Edit</a>
                                        <form action="{{ route('attendances.destroy', $att) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 focus:ring-2 focus:ring-red-500 text-xs font-medium rounded transition">Hapus</button>
                                        </form>
                                    </div>
                                    @endcan
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-400 text-sm">Belum ada data absensi.</div>
                            @endforelse
                        </div>
        
                        {{-- Footer / Pagination --}}
                        @if ($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages())
                            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                                {{ $attendances->appends(request()->query())->links() }}
                            </div>
                        @endif
        
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
