<x-dashboard.main :title="Auth::user()->role === 'admin' ? 'Kelola Profile' : 'Profile'">

    <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-5 md:gap-6">
        @foreach (['waktu', 'role', 'terakhir_login', 'register'] as $type)
            <div class="flex items-center px-4 py-3 bg-white border-back rounded-xl">
                <span
                    class="
                        {{ $type == 'waktu' ? 'bg-blue-300' : '' }}
                        {{ $type == 'role' ? 'bg-green-300' : '' }}
                        {{ $type == 'terakhir_login' ? 'bg-rose-300' : '' }}
                        {{ $type == 'register' ? 'bg-amber-300' : '' }}
                        p-3 mr-4 text-gray-700 rounded-full"></span>
                <div>
                    <p class="text-sm font-medium capitalize text-gray-600 line-clamp-1">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg uppercase font-semibold text-gray-700 line-clamp-1">
                        {{ $type == 'waktu' ? '0' : '' }}
                        {{ $type == 'role' ? Auth::user()->role : '' }}
                        {{ $type == 'terakhir_login' ? $last_login : '' }}
                        {{ $type == 'register' ? Auth::user()->created_at->format('d M Y, H:i') : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid sm:grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Profile Info Section -->
        <div class="bg-white border-back rounded-xl shadow-sm p-6">
            <div class="flex items-center gap-6 border-b pb-6 mb-6">
                <!-- User Image and Role -->
                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-2 border-gray-300 mb-3">
                        <img class="w-full h-full object-cover rounded-full"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}" />
                    </div>
                    <h1 class="badge badge-sm badge-neutral font-medium uppercase">
                        {{ Auth::user()->role }}
                    </h1>
                </div>
                <!-- User Details -->
                <div>
                    <h1 class="text-2xl font-semibold mb-1">
                        {{ '@' . Auth::user()->nama }}
                    </h1>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ Auth::user()->email }}
                    </p>
                    <div class="space-y-2">
                        <div>
                            <h2 class="font-semibold">Nama:</h2>
                            <p>{{ Auth::user()->nama }}</p>
                        </div>
                        <div>
                            <h2 class="font-semibold">Email:</h2>
                            <p>{{ Auth::user()->email }}</p>
                        </div>
                        <div>
                            <h2 class="font-semibold">No HP:</h2>
                            <p>
                                <a href="https://wa.me/{{ Auth::user()->no_hp }}" class="text-blue-500 hover:underline"
                                    target="_blank">
                                    {{ Auth::user()->no_hp }}
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Toggle Button for Update Profile Form -->
            <div class="mb-4">
                <button id="toggleButton" type="button" class="w-full text-center btn btn-primary">
                    Update Profile
                </button>
            </div>
            <!-- Update Profile Form -->
            <div id="updateProfileForm" class="max-h-0 overflow-hidden">
                <form class="space-y-4" method="POST"
                    action="{{ route('update.profile', ['id_user' => Auth::user()->id_user]) }}">
                    @csrf
                    @method('PUT')
                    <div class="input-label">
                        <h1 class="label">Nama:</h1>
                        <input required name="nama" value="{{ Auth::user()->nama }}" type="text"
                            placeholder="...">
                        @error('nama')
                            <span class="validated">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-label">
                        <h1 class="label">Email:</h1>
                        <input disabled name="email" value="{{ Auth::user()->email }}" type="email"
                            placeholder="...">
                    </div>
                    <div class="input-label">
                        <h1 class="label">Tempat:</h1>
                        <input name="tempat" value="{{ Auth::user()->tempat }}" type="text" placeholder="...">
                    </div>
                    <div class="input-label">
                        <h1 class="label">Tanggal Lahir:</h1>
                        <input name="tanggal_lahir" value="{{ Auth::user()->tanggal_lahir ?? 'Tidak ditemukan' }}"
                            type="date">
                    </div>
                    <div class="input-label">
                        <h1 class="label">Alamat:</h1>
                        <input name="alamat" value="{{ Auth::user()->alamat }}" type="text" placeholder="...">
                    </div>
                    <div class="input-label">
                        <h1 class="label">No HP:</h1>
                        <input name="no_hp" value="{{ Auth::user()->no_hp }}" type="text" placeholder="...">
                    </div>
                    <button type="submit" class="btn btn-secondary mt-4 w-full">Konfirmasi Profile</button>
                </form>
            </div>
        </div>

        <!-- Change Password Section -->
        <div class="bg-white border-back rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-semibold mb-4">Change Password</h2>
            <form class="space-y-4" method="POST"
                action="{{ route('change_password.profile', ['id_user' => Auth::user()->id_user]) }}">
                @csrf
                @method('PUT')
                <div class="input-label">
                    <h1 class="label">Password Lama:</h1>
                    <input required name="password_lama" type="password" placeholder="...">
                    @error('password_lama')
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Password Baru:</h1>
                    <input required name="password_baru" type="password" placeholder="...">
                    @error('password_baru')
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-label">
                    <h1 class="label">Konfirmasi Password Baru:</h1>
                    <input required name="konfirmasi_password_baru" type="password" placeholder="...">
                    @error('konfirmasi_password_baru')
                        <span class="validated">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-secondary mt-4 w-full">Update Password</button>
            </form>
        </div>
    </div>

    <script>
        setInterval(() => {
            document.getElementById('waktu')
                .innerText = dayjs().format('HH:mm:ss DD/MM/YY')
        }, 1000);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleButton');
            const collapseContent = document.getElementById('updateProfileForm');

            collapseContent.style.maxHeight = '0';
            collapseContent.style.transition =
            'max-height 0.3s ease-out'; // Adding transition for smooth collapsing

            function toggleCollapse() {
                if (collapseContent.style.maxHeight === '0px' || collapseContent.style.maxHeight === '') {
                    collapseContent.style.maxHeight = collapseContent.scrollHeight + 'px';
                } else {
                    collapseContent.style.maxHeight = '0';
                }
            }

            toggleButton.addEventListener('click', toggleCollapse);
        });
    </script>

</x-dashboard.main>
