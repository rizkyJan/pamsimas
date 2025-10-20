<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PAMSIMAS - Tagihan Air</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            {{-- Logo / Judul Navbar --}}
            <a class="navbar-brand fw-bold"
                href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}">
                💧 PAMSIMAS
            </a>

            {{-- Tombol collapse untuk layar kecil --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Isi Navbar --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('pelanggan.dashboard') }}"
                            class="nav-link {{ request()->routeIs('admin.dashboard') || request()->routeIs('pelanggan.dashboard') ? 'active' : '' }}">
                            Dashboard
                        </a>
                    </li>

                    {{-- Menu khusus admin --}}
                    @if (auth()->user()->role === 'admin')
                        <li class="nav-item"><a href="{{ route('pelanggan.index') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.index') ? 'active' : '' }}">Pelanggan</a>
                        </li>
                        <li class="nav-item"><a href="{{ route('bulan.index') }}"
                                class="nav-link {{ request()->routeIs('bulan.index') ? 'active' : '' }}">Bulan</a></li>
                        <li class="nav-item"><a href="{{ route('tarif.index') }}"
                                class="nav-link {{ request()->routeIs('tarif.index') ? 'active' : '' }}">Tarif</a></li>
                        <li class="nav-item"><a href="{{ route('tagihan.index') }}"
                                class="nav-link {{ request()->routeIs('tagihan.index') ? 'active' : '' }}">Tagihan</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('informasi.edit') }}"
                                class="nav-link {{ request()->routeIs('informasi.edit') ? 'active' : '' }}">
                                Informasi
                            </a>
                        </li>
                    @endif

                    {{-- ✅ Menu khusus pelanggan --}}
                    @if (auth()->user()->role === 'pelanggan')
                        <li class="nav-item">
                            <a href="{{ route('pelanggan.tagihan_lunas') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.tagihan_lunas') ? 'active' : '' }}">
                                Tagihan Lunas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pelanggan.tagihan_belumlunas') }}"
                                class="nav-link {{ request()->routeIs('pelanggan.tagihan_belumlunas') ? 'active' : '' }}">
                                Tagihan Belum Lunas
                            </a>
                        </li>
                    @endif
                </ul>

                {{-- Dropdown User --}}
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white fw-semibold" href="#" id="userDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            👤 {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <h6 class="dropdown-header text-muted text-capitalize">
                                    {{ auth()->user()->role }}
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-semibold">
                                        Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Konten utama --}}
    <main class="container py-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
