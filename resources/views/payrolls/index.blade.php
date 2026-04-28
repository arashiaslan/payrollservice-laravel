<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if(auth()->user()->isAdmin())
                Data Penggajian
            @else
                Slip Gaji Saya
            @endif
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div class="flex items-center gap-3 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                    <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            {{-- ============================================================ --}}
            {{-- TAMPILAN ADMIN: Filter Periode + Tabel Semua Pegawai          --}}
            {{-- ============================================================ --}}
            @if(auth()->user()->isAdmin())

                {{-- Card Filter --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Filter Periode</h3>
                    </div>
                    <div class="px-6 py-4">
                        <form action="{{ route('payrolls.index') }}" method="GET">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">

                                {{-- Dropdown Bulan --}}
                                <div>
                                    <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                                    <select id="bulan" name="bulan"
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @php
                                            $daftarBulan = [
                                                '01' => 'Januari',  '02' => 'Februari', '03' => 'Maret',
                                                '04' => 'April',    '05' => 'Mei',       '06' => 'Juni',
                                                '07' => 'Juli',     '08' => 'Agustus',  '09' => 'September',
                                                '10' => 'Oktober',  '11' => 'November', '12' => 'Desember',
                                            ];
                                        @endphp
                                        @foreach ($daftarBulan as $num => $nama)
                                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>
                                                {{ $nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Dropdown Tahun --}}
                                <div>
                                    <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                                    <select id="tahun" name="tahun"
                                            class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        @php $tahunSekarang = (int) date('Y'); @endphp
                                        @for ($y = $tahunSekarang - 3; $y <= $tahunSekarang + 1; $y++)
                                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                {{-- Tombol Filter --}}
                                <div>
                                    <button type="submit"
                                            class="w-full px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                                        Filter
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Card Tabel Payroll Admin --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                    {{-- Header Card --}}
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-700">Daftar Slip Gaji</h3>
                            <p class="text-sm text-gray-500 mt-0.5">
                                Periode:
                                <span class="font-medium text-gray-700">
                                    {{ \App\Models\Payroll::getNamaBulan($bulan) }} {{ $tahun }}
                                </span>
                            </p>
                        </div>
                        <a href="{{ route('payrolls.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Generate Payroll
                        </a>
                    </div>

                    {{-- Tampilan Desktop (Tabel) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 w-10">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Nama Pegawai</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Jabatan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Gaji Pokok</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Tunjangan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Potongan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Gaji Bersih</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($payrolls as $index => $p)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ $p->employee->user->name }}</td>
                                        <td class="px-4 py-3 text-gray-600">{{ $p->employee->jabatan }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700 tabular-nums">{{ \App\Models\Payroll::formatRupiah($p->employee->gaji_pokok) }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700 tabular-nums">{{ \App\Models\Payroll::formatRupiah($p->total_tunjangan) }}</td>
                                        <td class="px-4 py-3 text-right tabular-nums">
                                            @if ($p->total_potongan > 0)
                                                <span class="text-red-600 font-medium">- {{ \App\Models\Payroll::formatRupiah($p->total_potongan) }}</span>
                                            @else
                                                <span class="text-gray-400">{{ \App\Models\Payroll::formatRupiah(0) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right tabular-nums">
                                            <span class="font-bold text-green-700">{{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('payrolls.show', $p) }}" class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition whitespace-nowrap">Detail</a>
                                                <form action="{{ route('payrolls.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus slip gaji {{ $p->employee->user->name }} periode {{ \App\Models\Payroll::getNamaBulan($p->bulan) }} {{ $p->tahun }}?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-14 text-center">
                                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                                <p class="text-sm italic">Belum ada data payroll untuk periode ini.</p>
                                                <a href="{{ route('payrolls.create') }}" class="mt-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium underline underline-offset-2 transition">Generate payroll sekarang</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tampilan Mobile (Cards) --}}
                    <div class="block md:hidden border-t border-gray-200 divide-y divide-gray-100">
                        @forelse ($payrolls as $p)
                            <div class="p-4 bg-white flex flex-col gap-3">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $p->employee->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $p->employee->jabatan }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded">{{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-2 text-xs border border-gray-100 rounded-lg p-2 bg-gray-50">
                                    <div>
                                        <span class="text-gray-500 block">Gaji Pokok</span>
                                        <span class="font-medium text-gray-700">{{ \App\Models\Payroll::formatRupiah($p->employee->gaji_pokok) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-500 block">Tunjangan</span>
                                        <span class="font-medium text-gray-700">{{ \App\Models\Payroll::formatRupiah($p->total_tunjangan) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Potongan</span>
                                        <span class="font-medium text-red-600">- {{ \App\Models\Payroll::formatRupiah($p->total_potongan) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-500 block">Gaji Bersih</span>
                                        <span class="font-bold text-green-700">{{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}</span>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 justify-end mt-1">
                                    <a href="{{ route('payrolls.show', $p) }}" class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 focus:ring-2 focus:ring-blue-500 text-xs font-medium rounded transition">Lihat Slip</a>
                                    <form action="{{ route('payrolls.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 border border-red-200 focus:ring-2 focus:ring-red-500 text-xs font-medium rounded transition">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-sm italic text-gray-400">Belum ada data payroll.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer: ringkasan total --}}
                    @if ($payrolls->count() > 0)
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Menampilkan <span class="font-semibold text-gray-700">{{ $payrolls->count() }}</span> slip gaji.
                            </p>
                            <p class="text-xs text-gray-500">
                                Total Gaji Bersih:
                                <span class="font-bold text-green-700">
                                    {{ \App\Models\Payroll::formatRupiah($payrolls->sum('gaji_bersih')) }}
                                </span>
                            </p>
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if ($payrolls instanceof \Illuminate\Pagination\LengthAwarePaginator && $payrolls->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $payrolls->links() }}
                        </div>
                    @endif

                </div>

            {{-- ============================================================ --}}
            {{-- TAMPILAN PEGAWAI: Hanya slip gaji milik mereka sendiri        --}}
            {{-- ============================================================ --}}
            @else

                {{-- Info card: identitas pegawai --}}
                @php $myEmployee = auth()->user()->employee; @endphp
                @if($myEmployee)
                    <div class="bg-white shadow-sm sm:rounded-lg px-6 py-4 flex items-center gap-4">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Slip gaji untuk</p>
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">{{ $myEmployee->jabatan }} &bull; NIK: {{ $myEmployee->nik }}</p>
                        </div>
                    </div>
                @endif

                {{-- Tabel slip gaji milik pegawai ini --}}
                <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-700">Riwayat Slip Gaji</h3>
                        <p class="text-sm text-gray-500 mt-0.5">Semua slip gaji yang telah digenerate untuk Anda.</p>
                    </div>

                    {{-- Tampilan Desktop (Tabel) --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-50 uppercase tracking-wider text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500 w-10">No</th>
                                    <th class="px-4 py-3 text-left font-semibold text-gray-500">Periode</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Gaji Pokok</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Tunjangan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Potongan</th>
                                    <th class="px-4 py-3 text-right font-semibold text-gray-500">Gaji Bersih</th>
                                    <th class="px-4 py-3 text-center font-semibold text-gray-500">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @forelse ($payrolls as $index => $p)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 font-medium text-gray-800">{{ \App\Models\Payroll::getNamaBulan($p->bulan) }} {{ $p->tahun }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700 tabular-nums">{{ \App\Models\Payroll::formatRupiah($p->employee->gaji_pokok) }}</td>
                                        <td class="px-4 py-3 text-right text-gray-700 tabular-nums">{{ \App\Models\Payroll::formatRupiah($p->total_tunjangan) }}</td>
                                        <td class="px-4 py-3 text-right tabular-nums">
                                            @if ($p->total_potongan > 0)
                                                <span class="text-red-600 font-medium">- {{ \App\Models\Payroll::formatRupiah($p->total_potongan) }}</span>
                                            @else
                                                <span class="text-gray-400">{{ \App\Models\Payroll::formatRupiah(0) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right tabular-nums"><span class="font-bold text-green-700">{{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}</span></td>
                                        <td class="px-4 py-3 text-center">
                                            <a href="{{ route('payrolls.show', $p) }}" class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition whitespace-nowrap">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-4 py-14 text-center">
                                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                                                <p class="text-sm italic">Belum ada slip gaji yang digenerate untuk Anda.</p>
                                                <p class="text-xs text-gray-300">Hubungi admin jika ada pertanyaan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Tampilan Mobile (Cards) --}}
                    <div class="block md:hidden border-t border-gray-200 divide-y divide-gray-100">
                        @forelse ($payrolls as $p)
                            <div class="p-4 bg-white flex flex-col gap-3">
                                <div class="flex justify-between items-center">
                                    <p class="font-semibold text-gray-800 text-base">{{ \App\Models\Payroll::getNamaBulan($p->bulan) }} {{ $p->tahun }}</p>
                                    <span class="font-bold text-green-700 bg-green-50 px-2 py-0.5 rounded text-sm">{{ \App\Models\Payroll::formatRupiah($p->gaji_bersih) }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs border border-gray-100 rounded-lg p-2 bg-gray-50">
                                    <div>
                                        <span class="text-gray-500 block">Gaji Pokok</span>
                                        <span class="font-medium text-gray-700">{{ \App\Models\Payroll::formatRupiah($p->employee->gaji_pokok) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-500 block">Tunjangan</span>
                                        <span class="font-medium text-gray-700">{{ \App\Models\Payroll::formatRupiah($p->total_tunjangan) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 block">Potongan</span>
                                        <span class="font-medium text-red-600">- {{ \App\Models\Payroll::formatRupiah($p->total_potongan) }}</span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-gray-500 block">Status</span>
                                        <span class="font-medium text-gray-700">Lunas</span>
                                    </div>
                                </div>
                                <a href="{{ route('payrolls.show', $p) }}" class="block w-full text-center px-4 py-2 mt-1 bg-blue-50 text-blue-600 border border-blue-200 focus:ring-2 focus:ring-blue-500 text-xs font-medium rounded transition">Lihat Slip Detail</a>
                            </div>
                        @empty
                            <div class="p-8 text-center">
                                <p class="text-sm italic text-gray-400">Belum ada slip gaji.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer ringkasan --}}
                    @if ($payrolls->count() > 0)
                        <div class="px-6 py-3 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                            <p class="text-xs text-gray-500">
                                Total <span class="font-semibold text-gray-700">{{ $payrolls->count() }}</span> slip gaji.
                            </p>
                            <p class="text-xs text-gray-500">
                                Total Gaji Diterima:
                                <span class="font-bold text-green-700">
                                    {{ \App\Models\Payroll::formatRupiah($payrolls->sum('gaji_bersih')) }}
                                </span>
                            </p>
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if ($payrolls instanceof \Illuminate\Pagination\LengthAwarePaginator && $payrolls->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $payrolls->links() }}
                        </div>
                    @endif

                </div>

            @endif

        </div>
    </div>
</x-app-layout>
