<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Laporan' : 'Laporan'">
    <div class="flex gap-5">
        @foreach (['laporan'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Temukan riwayat peminjaman buku oleh anggota, dan pastikan pengembalian tepat waktu untuk
                        kenyamanan bersama.
                    </p>
                    <!-- Responsive Print Button -->
                    <div class="flex justify-end mt-4">
                        <button onclick="window.print()" class="btn btn-valentine print:hidden">
                            Cetak
                        </button>
                    </div>
                </div>

                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full" id="anggotaTable">
                            <thead>
                                <tr>
                                    @foreach (['No', 'nama anggota', 'buku', 'tanggal peminjaman', 'tanggal pengembalian'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($laporan as $i => $item)
                                    <tr>
                                        <th class="font-semibold capitalize text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold capitalize text-center">{{ $item->anggota->nama }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->buku->judul }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->peminjaman->tanggal_pengembalian }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->peminjaman->tanggal_dikembalikan ?? 'Buku Belum Dikembalikan' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-gray-800 text-center" colspan="5">Tidak ada laporan</td>
                                    </tr>
                                @endforelse
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>
