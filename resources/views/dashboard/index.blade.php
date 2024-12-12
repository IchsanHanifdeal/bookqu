@if (Auth::user()->role === 'pimpinan' || Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
    <x-dashboard.main title="Dashboard">
        <div class="grid sm:grid-cols-1 xl:grid-cols-1 gap-5 md:gap-6">
            @foreach (['jumlah_peminjaman'] as $type)
                <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                    <span
                        class="
                    {{ $type == 'jumlah_peminjaman' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                    </span>
                    <div>
                        <p class="text-sm font-medium capitalize text-white">
                            {{ str_replace('_', ' ', $type) }}
                        </p>
                        <p id="{{ $type }}" class="text-lg font-semibold text-white">
                            {{ $type == 'jumlah_peminjaman' ? $jumlah_peminjaman ?? '0' : '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        @if (Auth::user()->role === 'admin')
            <div class="grid sm:grid-cols-1 xl:grid-cols-3 gap-5 md:gap-6">
                @foreach (['jumlah_buku', 'jumlah_petugas', 'jumlah_anggota'] as $type)
                    <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                        <span
                            class="
                        {{ $type == 'jumlah_buku' ? 'bg-pink-300' : '' }}
                        {{ $type == 'jumlah_petugas' ? 'bg-pink-300' : '' }}
                        {{ $type == 'jumlah_anggota' ? 'bg-pink-300' : '' }}
                        p-3 mr-4 rounded-full">
                        </span>
                        <div>
                            <p class="text-sm font-medium capitalize text-white">
                                {{ str_replace('_', ' ', $type) }}
                            </p>
                            <p id="{{ $type }}" class="text-lg font-semibold text-white">
                                {{ $type == 'jumlah_buku' ? $jumlah_buku ?? '0' : '' }}
                                {{ $type == 'jumlah_petugas' ? $jumlah_petugas ?? '0' : '' }}
                                {{ $type == 'jumlah_anggota' ? $jumlah_anggota ?? '0' : '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="grid sm:grid-cols-1 xl:grid-cols-2 gap-5 md:gap-6">
                @foreach (['jumlah_buku', 'jumlah_anggota'] as $type)
                    <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                        <span
                            class="
                        {{ $type == 'jumlah_buku' ? 'bg-pink-300' : '' }}
                        {{ $type == 'jumlah_anggota' ? 'bg-pink-300' : '' }}
                        p-3 mr-4 rounded-full">
                        </span>
                        <div>
                            <p class="text-sm font-medium capitalize text-white">
                                {{ str_replace('_', ' ', $type) }}
                            </p>
                            <p id="{{ $type }}" class="text-lg font-semibold text-white">
                                {{ $type == 'jumlah_buku' ? $jumlah_buku ?? '0' : '' }}
                                {{ $type == 'jumlah_anggota' ? $jumlah_anggota ?? '0' : '' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
        <div class="grid sm:grid-cols-1 xl:grid-cols-3 gap-5 md:gap-6">
            @foreach (['jumlah_denda', 'denda_belum_lunas', 'denda_lunas'] as $type)
                <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                    <span
                        class="
                        {{ $type == 'jumlah_denda' ? 'bg-pink-300' : '' }}
                        {{ $type == 'denda_belum_lunas' ? 'bg-pink-300' : '' }}
                        {{ $type == 'denda_lunas' ? 'bg-pink-300' : '' }}
                        p-3 mr-4 rounded-full">
                    </span>
                    <div>
                        <p class="text-sm font-medium capitalize text-white">
                            {{ str_replace('_', ' ', $type) }}
                        </p>
                        <p id="{{ $type }}" class="text-lg font-semibold text-white">
                            @if ($type == 'jumlah_denda')
                                Rp{{ number_format($jumlah_denda ?? 0, 0, ',', '.') }}
                            @elseif ($type == 'denda_belum_lunas')
                                Rp{{ number_format($denda_belum_lunas ?? 0, 0, ',', '.') }}
                            @elseif ($type == 'denda_lunas')
                                Rp{{ number_format($denda_lunas ?? 0, 0, ',', '.') }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        @if (Auth::user()->role === 'admin')
            <div class="flex flex-col xl:flex-row gap-5">
                @foreach (['buku', 'petugas', 'anggota'] as $jenis)
                    <div class="flex flex-col border rounded-xl w-full">
                        <div class="p-5 sm:p-7 bg-neutral rounded-t-xl text-white">
                            <h1 class="flex items-start gap-3 font-semibold sm:text-lg capitalize">
                                {{ str_replace('_', ' ', $jenis) }}
                                <span class="badge badge-xs sm:badge-sm uppercase badge-primary text-white">baru</span>
                            </h1>
                            <p class="text-sm opacity-60">Berdasarkan data pada {{ date('d-m-Y') }}</p>
                        </div>
                        <div
                            class="flex flex-col bg-zinc-300 rounded-b-xl gap-3 divide-y pt-0 p-5 max-h-64 overflow-y-auto">
                            @forelse (${$jenis}->take(5) as $index => $data)
                                <div class="flex items-center gap-5 pt-3">
                                    <h1>{{ $index + 1 }}</h1>
                                    <div>
                                        <h1 class="opacity-50 text-sm font-semibold">
                                            @if ($jenis === 'anggota')
                                                #{{ $data->no_anggota }}
                                            @elseif ($jenis === 'buku')
                                                #{{ $data->penerbit }}
                                            @else
                                                #{{ $data->email }}
                                            @endif
                                        </h1>
                                        <h1 class="font-bold text-sm sm:text-[15px] hover:underline cursor-pointer">
                                            @if ($jenis === 'buku')
                                                {{ ucfirst($data->judul) }}
                                            @elseif ($jenis === 'petugas')
                                                {{ ucfirst($data->nama) }}
                                            @elseif ($jenis === 'anggota')
                                                {{ ucfirst($data->nama) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center gap-5 pt-3">
                                    <h1>Tidak ada {{ str_replace('_', ' ', $jenis) }} terbaru.</h1>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col xl:flex-row gap-5">
                @foreach (['buku', 'anggota'] as $jenis)
                    <div class="flex flex-col border rounded-xl w-full">
                        <div class="p-5 sm:p-7 bg-neutral rounded-t-xl text-white">
                            <h1 class="flex items-start gap-3 font-semibold sm:text-lg capitalize">
                                {{ str_replace('_', ' ', $jenis) }}
                                <span class="badge badge-xs sm:badge-sm uppercase badge-primary text-white">baru</span>
                            </h1>
                            <p class="text-sm opacity-60">Berdasarkan data pada {{ date('d-m-Y') }}</p>
                        </div>
                        <div
                            class="flex flex-col bg-zinc-300 rounded-b-xl gap-3 divide-y pt-0 p-5 max-h-64 overflow-y-auto">
                            @forelse (${$jenis}->take(5) as $index => $data)
                                <div class="flex items-center gap-5 pt-3">
                                    <h1>{{ $index + 1 }}</h1>
                                    <div>
                                        <h1 class="opacity-50 text-sm font-semibold">
                                            @if ($jenis === 'anggota')
                                                #{{ $data->no_anggota }}
                                            @elseif ($jenis === 'buku')
                                                #{{ $data->penerbit }}
                                            @else
                                                #{{ $data->{'kode_' . $jenis} }}
                                            @endif
                                        </h1>
                                        <h1 class="font-bold text-sm sm:text-[15px] hover:underline cursor-pointer">
                                            @if ($jenis === 'buku')
                                                {{ ucfirst($data->judul) }}
                                            @elseif ($jenis === 'anggota')
                                                {{ ucfirst($data->nama) }}
                                            @endif
                                        </h1>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center gap-5 pt-3">
                                    <h1>Tidak ada {{ str_replace('_', ' ', $jenis) }} terbaru.</h1>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </x-dashboard.main>
@else
    <x-dashboard.main title="Dashboard">
    </x-dashboard.main>
@endif
