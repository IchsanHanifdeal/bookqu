<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Petugas' : 'Petugas'">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['petugas_terbaru', 'jumlah_petugas'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'petugas_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'jumlah_petugas' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'petugas_terbaru' ? $petugas_terbaru ?? '0' : '' }}
                        {{ $type == 'jumlah_petugas' ? $jumlah_petugas ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    @if (Auth::user()->role === 'admin')
        <div class="flex flex-col lg:flex-row gap-5">
            @foreach (['tambah_petugas'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1
                            class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'tambah_petugas' ? 'Fitur Tambah petugas memungkinkan pengguna untuk menambahkan petugas baru.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus
                        class="{{ $item == 'tambah_petugas' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                </div>
            @endforeach
        </div>
    @endif
    <div class="flex gap-5">
        @foreach (['daftar_petugas'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui petugas terbaru.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'nama', 'email', 'no handphone', 'tanggal bergabung', 'terakhir diperbarui'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($petugas as $i => $item)
                                    <tr>
                                        <th class="font-semibold capitalize text-center">{{ $i + 1 }}</th>
                                        <td class="font-semibold capitalize text-center">{{ $item->nama }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->email }}</td>
                                        <td class="font-semibold capitalize text-center">
                                            <a href="https://wa.me/{{ $item->no_hp }}"
                                                class="text-blue-500 cursor-pointer" target="_blank"
                                                rel="noopener noreferrer">
                                                {{ $item->no_hp }}
                                            </a>
                                        </td>
                                        <td class="font-semibold capitalize text-center">{{ $item->created_at }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->updated_at }}</td>
                                        <td class="flex items-center gap-4">
                                            <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                onclick="document.getElementById('update_petugas_{{ $item->id_user }}').showModal();" />

                                            <dialog id="update_petugas_{{ $item->id_user }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-neutral text-white">
                                                    <h3 class="text-lg font-bold">Update Petugas</h3>
                                                    <div class="mt-3">
                                                        <form method="POST"
                                                            action="{{ route('update.petugas', $item->id_user) }}">
                                                            @csrf
                                                            @method('PUT')
                                                            @foreach (['nama', 'email', 'no_hp', 'password'] as $type)
                                                                <div class="mb-4 capitalize">
                                                                    <label
                                                                        for="{{ $type }}_{{ $item->id_user }}"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>

                                                                    @if ($type === 'no_hp')
                                                                        <input type="number"
                                                                            id="{{ $type }}_{{ $item->id_user }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                    @elseif ($type === 'password')
                                                                        <input type="password"
                                                                            id="{{ $type }}_{{ $item->id_user }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Kosongkan jika tidak ingin mengubah password"
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror" />
                                                                    @elseif ($type === 'email')
                                                                        <input type="email"
                                                                            id="{{ $type }}_{{ $item->id_user }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                    @else
                                                                        <input type="text"
                                                                            id="{{ $type }}_{{ $item->id_user }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                    @endif

                                                                    @error($type)
                                                                        <span
                                                                            class="text-red-500 text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                            @endforeach
                                                            <div class="modal-action">
                                                                <button type="button"
                                                                    onclick="document.getElementById('update_petugas_{{ $item->id_user }}').close()"
                                                                    class="btn">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-primary">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                            <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                onclick="document.getElementById('hapus_modal_{{ $item->id_user }}').showModal();" />
                                            <dialog id="hapus_modal_{{ $item->id_user }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-base-100">
                                                    <h3 class="text-lg font-bold capitalize">Hapus
                                                        {{ $item->role . ' - ' . $item->nama }}
                                                    </h3>
                                                    <div class="mt-3">
                                                        <p class="text-red-800 font-semibold">Perhatian! Anda
                                                            sedang
                                                            mencoba untuk menghapus petugas
                                                            <strong
                                                                class="text-red-800 font-bold capitalize">{{ $item->role . ' - ' . $item->nama }}</strong>.
                                                            <span class="text-black">Tindakan ini akan menghapus
                                                                semua data terkait. Apakah Anda yakin ingin
                                                                melanjutkan?</span>
                                                        </p>
                                                    </div>
                                                    <div class="modal-action">
                                                        <button type="button"
                                                            onclick="document.getElementById('hapus_modal_{{ $item->id_user }}').close()"
                                                            class="btn">Batal</button>
                                                        <form action="{{ route('destroy.petugas', $item->id_user) }}"
                                                            method="POST" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </dialog>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Tidak ada Data Petugas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="tambah_petugas_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Petugas</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('store.petugas') }}">
                    @csrf
                    @foreach (['nama', 'email', 'no_hp', 'password'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>

                            @if ($type === 'no_hp')
                                <input type="number" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @elseif ($type === 'password')
                                <input type="password" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @elseif ($type === 'email')
                                <input type="email" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @else
                                <input type="text" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror"
                                    value="{{ old($type) }}" />
                            @endif

                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_petugas_modal').close()"
                            class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>
</x-dashboard.main>
