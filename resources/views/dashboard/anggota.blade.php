<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Anggota' : 'Anggota'">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['anggota_terbaru', 'no_anggota'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'anggota_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'no_anggota' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'anggota_terbaru' ? $anggota_terbaru->nama ?? '-' : '' }}
                        {{ $type == 'no_anggota' ? $anggota_terbaru->no_anggota ?? '-' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @foreach (['tambah_anggota'] as $item)
            <div onclick="{{ $item . '_modal' }}.showModal()"
                class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                <div>
                    <h1
                        class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60 text-white">
                        {{ $item == 'tambah_anggota' ? 'Fitur Tambah anggota memungkinkan pengguna untuk menambahkan anggota baru.' : '' }}
                    </p>
                </div>
                <x-lucide-plus
                    class="{{ $item == 'tambah_anggota' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['daftar_anggota'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui anggota terbaru.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50 my-4">
                    <input type="text" id="searchInput" placeholder="Cari data disini...." name="judul"
                        class="input input-sm shadow-md w-full bg-zinc-100">
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full" id="anggotaTable">
                            <thead>
                                <tr>
                                    @foreach (['No', 'nik', 'nama', 'tempat/tanggal lahir', 'no anggota', 'alamat', 'no handphone', 'email', 'tanggal bergabung', 'last update'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($anggota as $i => $item)
                                    <tr>
                                        <th class="font-semibold capitalize text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold capitalize text-center">{{ $item->nik }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->nama }}</td>
                                        <td class="font-semibold capitalize text-center">
                                            {{ $item->tempat . '/' . $item->tanggal_lahir }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->no_anggota }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->alamat }}</td>
                                        <td class="font-semibold capitalize text-center">
                                            <a href="https://wa.me/{{ $item->no_hp }}"
                                                class="text-blue-500 cursor-pointer" target="_blank"
                                                rel="noopener noreferrer">
                                                {{ $item->no_hp }}
                                            </a>
                                        </td>
                                        <td class="font-semibold capitalize text-center">{{ $item->email }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->tanggal_bergabung }}
                                        </td>
                                        <td class="font-semibold capitalize text-center">{{ $item->updated_at }}</td>
                                        @if (Auth::user()->role === 'admin')
                                            <td class="flex items-center gap-4">
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('update_anggota_{{ $item->id_anggota }}').showModal();" />
                                                <dialog id="update_anggota_{{ $item->id_anggota }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Update Anggota</h3>
                                                        <div class="mt-3">
                                                            <form method="POST"
                                                                action="{{ route('update.anggota', $item->id_anggota) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                @foreach (['nik', 'nama', 'tempat', 'tanggal_lahir', 'no_anggota', 'alamat', 'no_hp', 'email'] as $type)
                                                                    <div class="mb-4 capitalize">
                                                                        <label for="{{ $type }}"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>

                                                                        @if ($type === 'tanggal_lahir')
                                                                            <input type="date"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}"
                                                                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                                value="{{ old($type, $item->$type) }}" />
                                                                        @elseif ($type === 'no_hp')
                                                                            <input type="number"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}"
                                                                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                                value="{{ old($type, $item->$type) }}" />
                                                                        @elseif ($type === 'email')
                                                                            <input type="email"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}"
                                                                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                                value="{{ old($type, $item->$type) }}" />
                                                                        @elseif ($type === 'no_anggota')
                                                                            <input type="text"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}" readonly
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 capitalize"
                                                                                value="{{ $item->$type }}" />
                                                                        @else
                                                                            <input type="text"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}"
                                                                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                                value="{{ old($type, $item->$type) }}" />
                                                                            @error($type)
                                                                                <span
                                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                                            @enderror
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                                <div class="modal-action">
                                                                    <button type="button"
                                                                        onclick="document.getElementById('update_anggota_{{ $item->id_anggota }}').close()"
                                                                        class="btn">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>

                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('hapus_modal_{{ $item->id_anggota }}').showModal();" />
                                                <dialog id="hapus_modal_{{ $item->id_anggota }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-base-100">
                                                        <h3 class="text-lg font-bold capitalize">Hapus
                                                            {{ $item->no_anggota . ' - ' . $item->nama }}
                                                        </h3>
                                                        <div class="mt-3">
                                                            <p class="text-red-800 font-semibold">Perhatian! Anda
                                                                sedang
                                                                mencoba untuk menghapus anggota
                                                                <strong
                                                                    class="text-red-800 font-bold capitalize">{{ $item->no_anggota . ' - ' . $item->nama }}</strong>.
                                                                <span class="text-black">Tindakan ini akan menghapus
                                                                    semua data terkait. Apakah Anda yakin ingin
                                                                    melanjutkan?</span>
                                                            </p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button"
                                                                onclick="document.getElementById('hapus_modal_{{ $item->id_anggota }}').close()"
                                                                class="btn">Batal</button>
                                                            <form
                                                                action="{{ route('destroy.anggota', $item->id_anggota) }}"
                                                                method="POST" class="inline-block">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr id="noDataRow">
                                        <td colspan="9" class="text-center opacity-60 text-gray-500">Tidak ada
                                            Anggota</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="tambah_anggota_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Anggota</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('store.anggota') }}">
                    @csrf
                    @foreach (['nik', 'nama', 'tempat', 'tanggal_lahir', 'no_anggota', 'alamat', 'no_hp', 'email'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>

                            @if ($type === 'tanggal_lahir')
                                <input type="date" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @elseif ($type === 'no_hp')
                                <input type="number" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @elseif ($type === 'email')
                                <input type="email" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @elseif ($type === 'no_anggota')
                                <input type="text" id="{{ $type }}" name="{{ $type }}" readonly
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 capitalize"
                                    value="{{ $no_anggota }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @else
                                <input type="text" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_anggota_modal').close()"
                            class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const table = document.getElementById('anggotaTable');
            const tbody = table.querySelector('tbody');
            const noDataRow = document.getElementById('noDataRow');
            const rows = tbody.querySelectorAll('tr:not(#noDataRow)');

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();
                let hasData = false;

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const nameCell = cells[0];
                    const noAnggotaCell = cells[2];

                    const nameText = nameCell ? nameCell.textContent.toLowerCase() : '';
                    const noAnggotaText = noAnggotaCell ? noAnggotaCell.textContent.toLowerCase() :
                        '';

                    if (nameText.includes(searchTerm) || noAnggotaText.includes(searchTerm)) {
                        row.style.display = '';
                        hasData = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (hasData) {
                    noDataRow.style.display = 'none';
                } else {
                    noDataRow.style.display = 'table-row';
                }
            });
        });
    </script>

</x-dashboard.main>
