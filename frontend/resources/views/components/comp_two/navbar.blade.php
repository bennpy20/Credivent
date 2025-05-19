<nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="index.html">Even<span>talk.</span></a>
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
            <a href="{{ route('login.form') }}">Login</a>
        @endif --}}

        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav ml-auto align-items-center">
                <li class="nav-item active"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="about.html" class="nav-link">About</a></li>
                <li class="nav-item"><a href="speakers.html" class="nav-link">Speakers</a></li>
                <li class="nav-item"><a href="schedule.html" class="nav-link">Schedule</a></li>
                <li class="nav-item"><a href="blog.html" class="nav-link">Blog</a></li>
                <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>

                @if (session()->has('user'))
                    <!-- Tampilkan profil saat user login -->
                    <li class="nav-item dropdown" style="padding-left: 20px">
                        <a class="nav-link dropdown-toggle rounded-circle d-flex align-items-center justify-content-center"
                            href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false" style="width: 40px; height: 40px; padding: 0; overflow: hidden;">
                            <img src="{{ Auth::user()->profile_picture ?? 'account-icon-default.jpg' }}" alt="Profile"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @else
                    <!-- Tampilkan login jika belum login -->
                    <li class="nav-item cta mr-md-2">
                        <a href="{{ route('login.form') }}" class="nav-link">Login</a>
                    </li>
                @endif
            </ul>
        </div>

    </div>
</nav>
<!-- END nav -->
