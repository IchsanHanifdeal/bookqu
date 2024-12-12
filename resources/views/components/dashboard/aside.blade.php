<div class="drawer-side border-r z-20">
    <label for="aside-dashboard" aria-label="close sidebar" class="drawer-overlay"></label>
    <ul
        class="bg-[#f7e8f3] menu flex flex-col justify-between p-4 w-64 lg:w-72 min-h-full [&>li>a]:gap-4 [&>li]:my-1.5 [&>li]:text-[14.3px] [&>li]:font-medium [&>li]:text-opacity-80 [&>li]:text-base [&>_*_svg]:stroke-[1.5] [&>_*_svg]:size-[23px]">
        <div>
            <div class="pb-4 border-b border-gray-300">
                @include('components.brands', ['class' => 'btn btn-ghost text-2xl'])
            </div>
            <span class="label text-xs font-extrabold opacity-50">GENERAL</span>
            <li>
                <a href="{{ route('dashboard') }}" class="{!! Request::path() == 'dashboard' ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                    <x-lucide-bar-chart-2 />
                    Dashboard
                </a>
            </li>
            @if (Auth::user()->role === 'pengunjung' ||
                    Auth::user()->role === 'admin' ||
                    Auth::user()->role === 'pimpinan' ||
                    Auth::user()->role === 'petugas')
                <li>
                    <a href="{{ route('buku') }}" class="{!! preg_match('#^dashboard/buku.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-library />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Buku
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'pimpinan')
                <li>
                    <a href="{{ route('admin') }}"
                        class="{!! preg_match('#^dashboard/admin.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-shield />
                        Admin
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pimpinan')
                <li>
                    <a href="{{ route('petugas') }}"
                        class="{!! preg_match('#^dashboard/petugas.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-briefcase />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Petugas
                    </a>
                </li>
            @endif
            @if (Auth::user()->role === 'pimpinan' || Auth::user()->role === 'admin' || Auth::user()->role === 'petugas')
                <li>
                    <a href="{{ route('anggota') }}"
                        class="{!! preg_match('#^dashboard/anggota.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-users />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Anggota
                    </a>
                </li>
                <li>
                    <a href="{{ route('peminjaman') }}"
                        class="{!! preg_match('#^dashboard/peminjaman.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-cloud-upload />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Peminjaman
                    </a>
                </li>
                <li>
                    <a href="{{ route('laporan') }}"
                        class="{!! preg_match('#^dashboard/laporan.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-file />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Laporan
                    </a>
                </li>
                <li>
                    <a href="{{ route('denda') }}"
                        class="{!! preg_match('#^dashboard/denda.*#', Request::path()) ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                        <x-lucide-receipt />
                        @if (Auth::user()->role === 'admin')
                            Kelola
                        @endif Denda
                    </a>
                </li>
            @endif
        </div>
        <div class="flex flex-col">
            <span class="label text-xs font-extrabold opacity-50">ADVANCE</span>
            <li>
                <a href="{{ route('profile') }}" class="{!! Request::path() == 'dashboard/profile' ? 'active' : '' !!} flex items-center px-2.5 font-semibold">
                    <x-lucide-user-2 />
                    Profile
                </a>
            </li>
            <li>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="px-0">
                    @csrf
                    <a class="flex items-center px-2.5 gap-2 font-semibold" href="#"
                        onclick="event.preventDefault(); confirmLogout();">
                        <x-lucide-log-out />
                        Logout
                    </a>
                </form>
            </li>
        </div>
    </ul>
</div>
