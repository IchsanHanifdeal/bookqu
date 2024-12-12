<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Admin' : 'Admin'">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['admin_terbaru', 'jumlah_admin'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'admin_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'jumlah_admin' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'admin_terbaru' ? $admin_terbaru->nama ?? 'Tidak ada Admin terbaru' : '' }}
                        {{ $type == 'jumlah_admin' ? $jumlah_admin ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex gap-5">
        @foreach (['daftar_admin'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui admin terbaru.
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
                                    @foreach (['No', 'nama', 'email', 'no handphone', 'last update'] as $header)
                                        <th class="uppercase font-bold text-center">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($admin as $i => $item)
                                <tr>
                                    <th class="font-semibold capitalize text-center">{{ $i + 1 }}</th>
                                    <td class="font-semibold capitalize text-center">{{ $item->nama }}</td>
                                    <td class="font-semibold capitalize text-center">{{ $item->email }}</td>
                                    <td class="font-semibold capitalize text-center">{{ $item->no_hp }}</td>
                                    <td class="font-semibold capitalize text-center">{{ $item->last_upddated }}</td>
                                </tr>
                                @empty
                                <tr id="noDataRow">
                                    <td colspan="5" class="text-center opacity-60 text-gray-500">Tidak ada Admin</td>
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
