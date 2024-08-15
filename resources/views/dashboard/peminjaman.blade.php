<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Peminjaman' : 'Peminjaman'">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['tanggal', 'buku_baru_dipinjam'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'tanggal' ? 'bg-pink-300' : '' }}
                    {{ $type == 'buku_baru_dipinjam' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'tanggal' ? date('Y-m-d') ?? '0' : '' }}
                        {{ $type == 'buku_baru_dipinjam' ? $buku_baru_dipinjam->judul ?? 'Belum ada peminjaman buku' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_peminjaman'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1
                        class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60 text-white">
                        {{ $item == 'tambah_peminjaman' ? 'Fitur Tambah peminjaman memungkinkan pengguna untuk menambahkan peminjaman baru.' : '' }}
                    </p>
                </div>
                <x-lucide-plus
                    class="{{ $item == 'tambah_peminjaman' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['daftar_peminjaman'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui peminjaman terbaru.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full" id="anggotaTable">
                            <thead>
                                <tr>
                                    @foreach (['No', 'anggota', 'judul buku', 'tanggal peminjaman', 'tanggal pengembalian', 'tanggal dikembalikan', 'status', 'petugas', 'jumlah', 'last update'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($peminjaman as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold text-center capitalize">
                                            {{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->buku->judul }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->tanggal_peminjaman }}
                                        </td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->tanggal_pengembalian }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            {{ $item->tanggal_dikembalikan ?? 'Buku Belum Dikembalikan' }}</td>
                                        <td class="font-semibold uppercase text-center">
                                            @if ($item->status === 'dipinjam')
                                                <span
                                                    class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">{{ $item->status }}</span>
                                            @else
                                                <span
                                                    class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td class="font-semibold uppercase text-center">{{ $item->user->nama }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->jumlah }}</td>
                                        <td class="font-semibold uppercase text-center">{{ $item->updated_at }}</td>
                                        <td>
                                            @if ($item->status === 'dipinjam')
                                                <button
                                                    class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs transition-transform transform hover:scale-105 active:scale-95 uppercase font-semibold"
                                                    onclick="document.getElementById('kembali_{{ $item->id_peminjaman }}').showModal();">
                                                    Kembalikan
                                                </button>

                                                <dialog id="kembali_{{ $item->id_peminjaman }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div
                                                        class="modal-box bg-neutral text-white p-6 rounded-lg shadow-lg">
                                                        <h3 class="text-2xl font-bold mb-4">
                                                            Konfirmasi Pengembalian Buku
                                                            <span class="block text-lg font-semibold mt-1">
                                                                {{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}
                                                            </span>
                                                        </h3>
                                                        <div class="mt-4">
                                                            <p class="text-white font-semibold mb-4">
                                                                Anda sedang memproses pengembalian buku <strong
                                                                    class="text-lg">{{ $item->buku->judul }}</strong>
                                                                oleh
                                                                <strong
                                                                    class="text-white">{{ $item->anggota->no_anggota . ' - ' . $item->anggota->nama }}</strong>.
                                                            </p>
                                                            <p class="text-gray-300">
                                                                <span class="font-medium text-white">Penting:</span>
                                                                Proses ini akan memperbarui status pengembalian buku
                                                                dan memastikan bahwa buku tersebut sudah diterima
                                                                kembali. Pastikan semua informasi sudah benar
                                                                sebelum melanjutkan. Apakah Anda yakin ingin melanjutkan
                                                                proses ini?
                                                            </p>
                                                        </div>
                                                        <div class="modal-action flex justify-end mt-4">
                                                            <button type="button"
                                                                onclick="document.getElementById('kembali_{{ $item->id_peminjaman }}').close()"
                                                                class="bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 transition">
                                                                Batal
                                                            </button>
                                                            <form action="{{ route('update.peminjaman', $item->id_peminjaman) }}" method="POST" class="inline-block ml-2">
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
                                        <td class="font-gray-500 capitalize text-center opacity-60" colspan="9">Tidak
                                            ada data peminjaman</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="tambah_peminjaman_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Peminjaman</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('store.peminjaman') }}">
                    @csrf
                    @foreach (['petugas', 'anggota', 'buku'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            @if (Auth::user()->role === 'petugas' && $type === 'petugas')
                                <input type="text" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-700 border border-gray-600 text-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ Auth::user()->nama }}" disabled />
                                <input type="hidden" name="{{ $type }}" value="{{ Auth::user()->id_user }}" />
                            @else
                                <select name="{{ $type }}" id="{{ $type }}"
                                    class="bg-gray-700 border border-gray-600 text-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror">
                                    <option value="">--- Pilih {{ ucfirst($type) }} ---</option>
                                    @foreach (${$type} as $item)
                                        @php
                                            $id = null;
                                            $name = '';
                
                                            if ($type === 'petugas') {
                                                $id = $item->id_user;
                                                $name = $item->nama ?: 'Nama Tidak Tersedia';
                                            } elseif ($type === 'anggota') {
                                                $id = $item->id_anggota;
                                                $name = $item->no_anggota ? $item->no_anggota . ' - ' . $item->nama : 'Anggota Tidak Tersedia';
                                            } elseif ($type === 'buku') {
                                                $id = $item->id_buku;
                                                $name = $item->judul ?? 'Judul Tidak Tersedia';
                                            }
                                        @endphp
                                        <option value="{{ $id }}" {{ old($type) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error($type)
                                <p class="mt-2 text-red-500 text-xs">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                    @foreach (['tanggal_peminjaman', 'tanggal_pengembalian', 'jumlah'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            @if ($type === 'jumlah')
                                <input type="number" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-700 border border-gray-600 text-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @else
                                <input type="date" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-700 border border-gray-600 text-gray-300 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @endif
                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_peminjaman_modal').close()" class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

</x-dashboard.main>
