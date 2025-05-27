<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.html">Credi<span>vent</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="oi oi-menu"></span> Menu
        </button>

        {{-- @if (session()->has('user'))
            <p>Halo, {{ session('user.name') }}</p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endif --}}

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item {{ Route::is('member.index') ? 'active' : '' }}"><a href="{{ route('member.index') }}" class="nav-link">Home</a></li>
                <li class="nav-item {{ Route::is('member.schedule.index') ? 'active' : '' }}" ><a href="{{ route('member.schedule.index') }}" class="nav-link">Jadwal</a></li>
                <li class="nav-item"><a href="schedule.html" class="nav-link">Pembicara</a></li>
                <li class="nav-item"><a href="contact.html" class="nav-link">Tentang Kami</a></li>

                @if (session()->has('user') && session('user.role') == 1)
                    <!-- Tampilkan login jika belum login -->
                    <li class="nav-item cta ml-2 mb-1">
                        <a href="{{ route('admin.index') }}" class="nav-link">
                            Pusat Administrator
                        </a>
                    </li>
                @elseif (session()->has('user') && session('user.role') == 2)
                    <!-- Tampilkan profil saat user login -->
                    <li class="nav-item dropdown" style="padding-left: 20px;">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center"
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="width: 40px; height: 40px; padding: 0; border-radius: 50%; overflow: hidden;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                    <img src="{{ Auth::user()->profile_picture ?? 'account-icon-default.jpg' }}"
                                        alt="Profile"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                            </a>

                            <!-- Menu dropdown -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Event Terdaftar</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @elseif (session()->has('user') && session('user.role') == 3)
                    <!-- Tampilkan profil saat user login -->
                    <li class="nav-item dropdown" style="padding-left: 20px;">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center"
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="width: 40px; height: 40px; padding: 0; border-radius: 50%; overflow: hidden;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                    <img src="{{ Auth::user()->profile_picture ?? '../account-icon-default.jpg' }}"
                                        alt="Profile"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                            </a>

                            <!-- Menu dropdown -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('committee.event.index') }}">Kelola event</a></li>
                                <li><a class="dropdown-item" href="#">Scan kode</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @elseif (session()->has('user') && session('user.role') == 4)
                    <!-- Tampilkan profil saat user login -->
                    <li class="nav-item dropdown" style="padding-left: 20px;">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center justify-content-center"
                                href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false"
                                style="width: 40px; height: 40px; padding: 0; border-radius: 50%; overflow: hidden;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; overflow: hidden;">
                                    <img src="{{ Auth::user()->profile_picture ?? 'account-icon-default.jpg' }}"
                                        alt="Profile"
                                        style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                            </a>

                            <!-- Menu dropdown -->
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Profil</a></li>
                                <li><a class="dropdown-item" href="#">Kelola Keuangan</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </li>
                @else
                    <!-- Tampilkan login jika belum login -->
                    <li class="nav-item cta ml-2 mb-1">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                @endif
            </ul>
        </div>

    </div>
</nav>
<!-- END nav -->
