<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Data Pegawai
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash Message Sukses --}}
            @if (session('success'))
                <div class="mb-5 px-4 py-3 bg-green-100 border border-green-400 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Daftar Semua Pegawai</h3>
                    <a href="{{ route('employees.create') }}"
                       class="inline-flex items-center gap-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                        + Tambah Pegawai
                    </a>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 w-10">No</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Nama</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">NIK</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Jabatan</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600">Gaji Pokok</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($employees as $index => $emp)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-800">{{ $emp->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $emp->user->email }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $emp->nik }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $emp->jabatan }}</td>
                                    <td class="px-4 py-3 text-gray-700 font-medium">
                                        Rp {{ number_format($emp->gaji_pokok, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-2">
                                            {{-- Tombol Lihat --}}
                                            <a href="{{ route('employees.show', $emp) }}"
                                               class="px-3 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded transition">
                                                Lihat
                                            </a>

                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('employees.edit', $emp) }}"
                                               class="px-3 py-1 bg-yellow-100 hover:bg-yellow-200 text-yellow-700 text-xs font-medium rounded transition">
                                                Edit
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('employees.destroy', $emp) }}" method="POST"
                                                  onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="px-3 py-1 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-10 text-center text-gray-400 italic">
                                        Belum ada data pegawai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer / Pagination --}}
                @if ($employees instanceof \Illuminate\Pagination\LengthAwarePaginator && $employees->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $employees->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
