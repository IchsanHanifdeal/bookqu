<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Denda' : 'Denda'">
    <div class="grid sm:grid-cols-1 xl:grid-cols-1 gap-5 md:gap-6">
        <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
            <span class="bg-pink-300 p-3 mr-4 rounded-full"></span>
            <div>
                <p class="text-sm font-medium capitalize text-white">
                    Saldo Denda
                </p>
                <p class="text-lg font-semibold text-white capitalize">
                    Rp{{ number_format($total_denda, 0, ',', '.') }}
                </p>
            </div>
        </div>
    </div>
    
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['denda_lunas', 'denda_belum_lunas'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'denda_lunas' ? 'bg-pink-300' : '' }}
                    {{ $type == 'denda_belum_lunas' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        Rp{{ number_format($type == 'denda_lunas' ? $denda_lunas : $denda_belum_lunas, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>    
    <div class="flex gap-5">
        @foreach (['daftar_denda'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui denda terbaru.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'anggota', 'buku dipinjam', 'tanggal peminjaman', 'tanggal pengembalian', 'tanggal dikembalikan', 'telat', 'total denda', 'status'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($denda as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->peminjaman->buku->judul }}
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->peminjaman->tanggal_peminjaman }}
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->peminjaman->tanggal_pengembalian }}
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->peminjaman->tanggal_dikembalikan ?? '-' }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">
                                            @php
                                                $lateDays = 0;
                                                if ($item->peminjaman->tanggal_dikembalikan) {
                                                    $tanggalPengembalian = \Carbon\Carbon::parse(
                                                        $item->peminjaman->tanggal_pengembalian,
                                                    );
                                                    $tanggalDikembalikan = \Carbon\Carbon::parse(
                                                        $item->peminjaman->tanggal_dikembalikan,
                                                    );
                                                    if ($tanggalDikembalikan->gt($tanggalPengembalian)) {
                                                        $lateDays = $tanggalDikembalikan->diffInDays(
                                                            $tanggalPengembalian,
                                                        );
                                                    }
                                                }
                                            @endphp
                                            {{ $lateDays }} hari
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            Rp{{ number_format($item->total_denda, 0, ',', '.') }}
                                        </td>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->status }}
                                        </td>
                                        <td>
                                            @if ($item->status === 'belum bayar')
                                                <button
                                                    class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs transition-transform transform hover:scale-105 active:scale-95 uppercase font-semibold"
                                                    onclick="document.getElementById('bayar_{{ $item->id_peminjaman }}').showModal();">
                                                    Bayar Sekarang
                                                </button>

                                                <dialog id="bayar_{{ $item->id_peminjaman }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div
                                                        class="modal-box bg-neutral text-white p-6 rounded-lg shadow-lg">
                                                        <h3 class="text-2xl font-bold mb-4">
                                                            Konfirmasi Pembayaran Denda
                                                            <span class="block text-lg font-semibold mt-1">
                                                                {{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}
                                                            </span>
                                                        </h3>
                                                        <div class="mt-4">
                                                            <p class="text-white font-semibold mb-4">
                                                                Anda sedang memproses pembayaran denda untuk anggota
                                                                <strong
                                                                    class="text-lg">{{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}</strong>.
                                                            </p>
                                                            <p class="text-gray-300 mb-4">
                                                                <span class="font-medium text-white">Penting:</span>
                                                                Proses ini akan memperbarui status denda dan memastikan
                                                                bahwa denda tersebut telah dibayar. Pastikan semua
                                                                informasi sudah benar sebelum melanjutkan. Apakah Anda
                                                                yakin ingin melanjutkan proses ini?
                                                            </p>
                                                            <p class="text-gray-300">
                                                                Dengan membayar denda ini, Anda membantu menjaga
                                                                kerapihan dan keadilan dalam sistem perpustakaan kita.
                                                                Terima kasih atas kerjasamanya!
                                                            </p>
                                                        </div>
                                                        <div class="modal-action flex justify-end mt-4">
                                                            <button type="button"
                                                                onclick="document.getElementById('bayar_{{ $item->id_peminjaman }}').close()"
                                                                class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                                                                Batal
                                                            </button>
                                                            <form
                                                                action="{{ route('update.denda', $item->id_denda) }}"
                                                                method="POST" class="inline-block ml-2">
                                                                @csrf
                                                                @method('PUT')
                                                                <button type="submit"
                                                                    class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-400 transition">
                                                                    Konfirmasi
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>
                                            @else
                                                <span
                                                    class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs uppercase font-semibold">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-gray-300 opacity-60 text-center">Tidak ada data
                                            denda</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>
