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

            {{-- Card Tabel --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Absensi</h3>
                    {{-- Tombol Catat Absensi: hanya tampil untuk admin --}}
                    @can('admin')
                        <a href="{{ route('attendances.create') }}"
                           class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                            + Catat Absensi
                        </a>
                    @endcan
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-10">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama Pegawai</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Tanggal</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($attendances as $index => $att)
                                <tr class="hover:bg-gray-50 transition">

                                    {{-- No --}}
                                    <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>

                                    {{-- Nama Pegawai --}}
                                    <td class="px-4 py-3 font-medium text-gray-800">
                                        {{ $att->employee->user->name }}
                                    </td>

                                    {{-- Tanggal --}}
                                    <td class="px-4 py-3 text-gray-600">
                                        {{ \Carbon\Carbon::parse($att->tanggal)->format('d/m/Y') }}
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="px-4 py-3">
                                        @if ($att->status === 'Hadir')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Hadir
                                            </span>
                                        @elseif ($att->status === 'Izin')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                Izin
                                            </span>
                                        @elseif ($att->status === 'Alpa')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Alpa
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                                {{ $att->status }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Aksi: hanya tampil untuk admin --}}
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            @can('admin')
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('attendances.edit', $att) }}"
                                                   class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-xs font-medium rounded transition">
                                                    Edit
                                                </a>

                                                {{-- Tombol Hapus --}}
                                                <form action="{{ route('attendances.destroy', $att) }}" method="POST"
                                                      onsubmit="return confirm('Yakin ingin menghapus data absensi ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Pegawai hanya bisa melihat, tidak bisa ubah --}}
                                                <span class="text-xs text-gray-400 italic">Hanya lihat</span>
                                            @endcan
                                        </div>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-gray-400 italic">
                                        Belum ada data absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer / Pagination --}}
                @if ($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator && $attendances->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $attendances->appends(request()->query())->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
