<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Absensi
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Header Card --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">Form Edit Absensi</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Perbarui data absensi
                        <span class="font-medium text-gray-700">{{ $attendance->employee->user->name }}</span>
                        pada tanggal
                        <span class="font-medium text-gray-700">{{ $attendance->tanggal->format('d/m/Y') }}</span>.
                    </p>
                </div>

                {{-- Form --}}
                <form action="{{ route('attendances.update', $attendance) }}" method="POST" class="px-6 py-6 space-y-5">
                    @csrf
                    @method('PATCH')

                    {{-- Pegawai --}}
                    <div>
                        <x-input-label for="employee_id" value="Pegawai" />
                        <select id="employee_id"
                                name="employee_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500"
                                required>
                            <option value="">-- Pilih Pegawai --</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ old('employee_id', $attendance->employee_id) == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->user->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('employee_id')" class="mt-1" />
                    </div>

                    {{-- Tanggal --}}
                    <div>
                        <x-input-label for="tanggal" value="Tanggal" />
                        <x-text-input
                            id="tanggal"
                            name="tanggal"
                            type="date"
                            class="mt-1 block w-full"
                            value="{{ old('tanggal', $attendance->tanggal->format('Y-m-d')) }}"
                            required
                        />
                        <x-input-error :messages="$errors->get('tanggal')" class="mt-1" />
                    </div>

                    {{-- Status --}}
                    <div>
                        <x-input-label value="Status Kehadiran" />
                        <div class="mt-2 flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">

                            {{-- Hadir --}}
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio"
                                       name="status"
                                       value="Hadir"
                                       {{ old('status', $attendance->status) === 'Hadir' ? 'checked' : '' }}
                                       class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500" />
                                <span class="text-sm font-medium text-gray-700 group-hover:text-green-700 transition">
                                    Hadir
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    ✓
                                </span>
                            </label>

                            {{-- Izin --}}
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio"
                                       name="status"
                                       value="Izin"
                                       {{ old('status', $attendance->status) === 'Izin' ? 'checked' : '' }}
                                       class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500" />
                                <span class="text-sm font-medium text-gray-700 group-hover:text-yellow-700 transition">
                                    Izin
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                    !
                                </span>
                            </label>

                            {{-- Alpa --}}
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio"
                                       name="status"
                                       value="Alpa"
                                       {{ old('status', $attendance->status) === 'Alpa' ? 'checked' : '' }}
                                       class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500" />
                                <span class="text-sm font-medium text-gray-700 group-hover:text-red-700 transition">
                                    Alpa
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                    ✗
                                </span>
                            </label>

                        </div>
                        <x-input-error :messages="$errors->get('status')" class="mt-1" />
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                        <a href="{{ route('attendances.index') }}"
                           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-md transition">
                            Kembali
                        </a>
                        <button type="submit"
                                class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">
                            Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
