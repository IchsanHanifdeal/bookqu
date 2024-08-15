<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Buku' : 'Buku'">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['buku_terbaru', 'jumlah_buku'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'buku_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'jumlah_buku' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'buku_terbaru' ? $buku_terbaru ?? 'Tidak ada buku terbaru' : '' }}
                        {{ $type == 'jumlah_buku' ? $jumlah_buku ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'admin')
            @foreach (['tambah_buku'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1
                            class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'tambah_buku' ? 'Fitur Tambah buku memungkinkan pengguna untuk menambahkan buku baru.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus
                        class="{{ $item == 'tambah_buku' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex gap-5">
        @foreach (['Daftar_buku'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui buku terbaru.
                    </p>
                </div>
                <div class="w-full px-5 sm:px-7 bg-zinc-50 my-4">
                    <input type="text" id="searchInput" placeholder="Cari data disini...." name="judul"
                        class="input input-sm shadow-md w-full bg-zinc-100">
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'cover', 'judul', 'penerbit', 'penulis', 'tahun', 'stok'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @foreach ($buku as $i => $item)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td class="font-semibold capitalize text-center">
                                            <label for="lihat_modal_{{ $item->id_buku }}"
                                                class="w-full btn btn-neutral flex items-center justify-center gap-2 text-white font-bold">
                                                <span>Lihat</span>
                                            </label>

                                            <input type="checkbox" id="lihat_modal_{{ $item->id_buku }}"
                                                class="modal-toggle" />
                                            <div class="modal" role="dialog">
                                                <div class="modal-box" id="modal_box_{{ $item->id_buku }}">
                                                    <div class="modal-header flex justify-between items-center">
                                                        <h3 class="text-lg font-bold">Cover Buku</h3>
                                                        <label for="lihat_modal_{{ $item->id_buku }}"
                                                            class="btn btn-sm btn-circle btn-ghost">&times;</label>
                                                    </div>
                                                    <div class="modal-body">
                                                        @php
                                                            $imagePath = $item->cover ? asset('storage/buku/' . $item->cover) : 'https://www.seoptimer.com/storage/images/2019/05/2744-404-redirection-1.png';
                                                        @endphp
                                                        <img 
                                                            src="{{ $imagePath }}" 
                                                            alt="Image" 
                                                            class="w-full h-auto"
                                                            onerror="this.onerror=null; this.src='https://www.seoptimer.com/storage/images/2019/05/2744-404-redirection-1.png'">
                                                    </div>                                                                                                    
                                                </div>
                                            </div>
                                        </td>
                                        <td class="font-semibold capitalize text-center">{{ $item->judul }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->penerbit }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->penulis }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->tahun }}</td>
                                        <td class="font-semibold capitalize text-center">{{ $item->stock }}</td>
                                        @if (Auth::user()->role === 'admin')
                                            <td class="flex items-center gap-4">
                                                <x-lucide-pencil class="size-5 hover:stroke-yellow-500 cursor-pointer"
                                                    onclick="document.getElementById('update_buku_{{ $item->id_buku }}').showModal();" />

                                                <dialog id="update_buku_{{ $item->id_buku }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Edit Buku</h3>
                                                        <div class="mt-3">
                                                            <form method="POST"
                                                                action="{{ route('update.buku', $item->id_buku) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-4">
                                                                    <label for="cover"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover
                                                                        Buku</label>
                                                                    <input type="file" id="cover" name="cover"
                                                                        accept="image/*"
                                                                        class="file-input w-full bg-gray-300 text-black"
                                                                        onchange="previewImage(event)" />
                                                                    @error('cover')
                                                                        <span
                                                                            class="text-red-500 text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                    <div id="preview_update" class="mt-2">
                                                                        @if ($item->cover)
                                                                            <img src="{{ asset('storage/' . $item->cover) }}"
                                                                                class="rounded-lg cursor-pointer"
                                                                                onclick="document.getElementById('cover').value = '';" />
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                @foreach (['judul', 'penerbit', 'penulis'] as $type)
                                                                    <div class="mb-4 capitalize">
                                                                        <label for="{{ $type }}"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                                                                        <input type="text" id="{{ $type }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                        @error($type)
                                                                            <span
                                                                                class="text-red-500 text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                @endforeach
                                                                @foreach (['tahun', 'stock'] as $type)
                                                                    <div class="mb-4 capitalize">
                                                                        @if ($type === 'stock')
                                                                            <input type="hidden"
                                                                                id="{{ $type }}"
                                                                                name="{{ $type }}"
                                                                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                                value="{{ old($type, $item->$type) }}" />
                                                                            @error($type)
                                                                                <span
                                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                                            @enderror
                                                                        @else
                                                                            <label for="{{ $type }}"
                                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                                                                            <input type="number"
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
                                                                        onclick="document.getElementById('update_buku_{{ $item->id_buku }}').close()"
                                                                        class="btn">Batal</button>
                                                                    <button type="submit"
                                                                        class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>

                                                <x-lucide-trash class="size-5 hover:stroke-red-500 cursor-pointer"
                                                    onclick="document.getElementById('hapus_modal_{{ $item->id_buku }}').showModal();" />
                                                <dialog id="hapus_modal_{{ $item->id_buku }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-base-100">
                                                        <h3 class="text-lg font-bold capitalize">Hapus
                                                            {{ $item->penerbit . ' - ' . $item->judul }}
                                                        </h3>
                                                        <div class="mt-3">
                                                            <p class="text-red-800 font-semibold">Perhatian! Anda
                                                                sedang
                                                                mencoba untuk menghapus buku
                                                                <strong
                                                                    class="text-red-800 font-bold">{{ $item->penerbit . ' - ' . $item->judul }}</strong>.
                                                                <span class="text-black">Tindakan ini akan menghapus
                                                                    semua data terkait. Apakah Anda yakin ingin
                                                                    melanjutkan?</span>
                                                            </p>
                                                        </div>
                                                        <div class="modal-action">
                                                            <button type="button"
                                                                onclick="document.getElementById('hapus_modal_{{ $item->id_buku }}').close()"
                                                                class="btn">Batal</button>
                                                            <form action="{{ route('delete.buku', $item->id_buku) }}"
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div id="pagination-controls" class="join mt-4  flex justify-center items-center"></div>
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="tambah_buku_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Buku</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('store.buku') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="cover"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover
                            Buku</label>
                        <input type="file" id="cover" name="cover" accept="image/*"
                            class="file-input w-full bg-gray-300 text-black" onchange="previewImage(event)" />
                        @error('cover')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <div id="preview_tambah" class="mt-2"></div>
                    </div>
                    @foreach (['judul', 'penerbit', 'penulis'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            <input type="text" id="{{ $type }}" name="{{ $type }}"
                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                value="{{ old($type) }}" />
                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    @foreach (['tahun', 'stock'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            <input type="number" id="{{ $type }}" name="{{ $type }}"
                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                value="{{ old($type) }}" />
                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_buku_modal').close()"
                            class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bukuTableBody = document.getElementById('buku-tbody');
            const searchInput = document.getElementById('searchInput');
            const paginationControls = document.getElementById('pagination-controls');

            let rows = Array.from(bukuTableBody.querySelectorAll('tr'));
            let filteredRows = rows;
            const itemsPerPage = 10;
            let currentPage = 1;

            const displayRows = (rowsToDisplay) => {
                bukuTableBody.innerHTML = '';

                if (rowsToDisplay.length === 0) {
                    bukuTableBody.innerHTML =
                        `<tr><td colspan="8" class="text-sm opacity-60 text-center">Buku dengan data terkait tidak ditemukan</td></tr>`;
                    return;
                }

                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                const rowsToShow = rowsToDisplay.slice(start, end);

                rowsToShow.forEach(row => {
                    bukuTableBody.appendChild(row);
                });

                updatePaginationControls();
            };

            const updatePaginationControls = () => {
                paginationControls.innerHTML = '';
                const totalPages = Math.ceil(filteredRows.length / itemsPerPage);

                for (let i = 1; i <= totalPages; i++) {
                    const radio = document.createElement('input');
                    radio.className = 'join-item btn btn-square';
                    radio.type = 'radio';
                    radio.name = 'options';
                    radio.setAttribute('aria-label', i);
                    radio.checked = (i === currentPage);
                    radio.addEventListener('change', () => {
                        currentPage = i;
                        displayRows(filteredRows);
                    });
                    paginationControls.appendChild(radio);
                }
            };

            // Function to filter rows based on search input
            const filterRows = () => {
                const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
                filteredRows = rows.filter(row => {
                    const judul = row.cells[2].textContent.toLowerCase();
                    const penerbit = row.cells[3].textContent.toLowerCase();
                    const penulis = row.cells[4].textContent.toLowerCase();
                    return judul.includes(searchTerm) || penerbit.includes(searchTerm) || penulis
                        .includes(searchTerm);
                });

                currentPage = 1; // Reset to the first page when filter is applied
                displayRows(filteredRows);
            };

            // Event listener for Search Input
            if (searchInput) {
                searchInput.addEventListener('input', filterRows);
            }

            // Initial call to display all rows
            displayRows(rows);
        });

        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('preview_tambah');
            previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileType = file.type;

                if (fileType.startsWith('image/')) {
                    const previewElement = document.createElement('img');
                    previewElement.src = URL.createObjectURL(file);
                    previewElement.classList.add('rounded-lg', 'cursor-pointer');
                    previewElement.style.maxWidth = '100%'; // Make sure the image fits within its container
                    previewElement.style.maxHeight = '500px'; // Set a max height for the image

                    previewElement.onclick = function() {
                        input.value = ''; // Clear the input value
                        previewContainer.innerHTML = ''; // Clear the preview container
                    };

                    previewContainer.appendChild(previewElement);
                }
            }
        }
    </script>
</x-dashboard.main>
