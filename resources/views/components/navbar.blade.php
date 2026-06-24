<style>
    #adminNav {
        background: #0f172a;
        border-bottom: 1px solid rgba(255,255,255,.06);
    }
    #adminNav .navbar-brand {
        color: #22c55e;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: .3px;
    }
    #adminNav .navbar-brand:hover { color: #4ade80; }
    #adminNav .nav-link {
        color: rgba(255,255,255,.65);
        border-radius: 8px;
        padding: .38rem .85rem;
        font-size: .875rem;
        font-weight: 500;
        transition: color .15s, background .15s;
    }
    #adminNav .nav-link:hover,
    #adminNav .nav-link.is-active {
        color: #22c55e;
        background: rgba(34,197,94,.1);
    }
    #adminNav .nav-link.dropdown-toggle:after { opacity: .5; }
    .nav-avatar {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 50%;
        background: #22c55e; color: #0f172a;
        font-weight: 700; font-size: .75rem; flex-shrink: 0;
    }
    #adminNav .dropdown-menu {
        background: #1e293b;
        border: 1px solid rgba(255,255,255,.1);
        border-radius: 10px;
        padding: 4px;
        margin-top: 6px;
    }
    #adminNav .dropdown-item {
        color: rgba(255,255,255,.7);
        border-radius: 7px;
        padding: .45rem .85rem;
        font-size: .875rem;
        transition: background .12s;
    }
    #adminNav .dropdown-item:hover { background: rgba(255,255,255,.08); color: white; }
    #adminNav .dropdown-item.text-danger-soft { color: #f87171 !important; }
    #adminNav .dropdown-divider { border-color: rgba(255,255,255,.08); margin: 4px 0; }
    #adminNav .navbar-toggler { border: none; }
</style>

<nav class="navbar navbar-expand-lg" id="adminNav">
    <div class="container-fluid px-3 px-lg-4">

        {{-- Brand --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            <i class="fa-solid fa-earth-asia fa-sm"></i>ARCADĪPA
        </a>

        {{-- Hamburger --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#navContent"
                aria-controls="navContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars text-white fa-sm"></i>
        </button>

        <div class="collapse navbar-collapse" id="navContent">

            {{-- Kiri: link navigasi utama --}}
            <ul class="navbar-nav me-auto gap-1 mt-2 mt-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }}"
                       href="{{ route('home') }}">
                        <i class="fa-solid fa-house fa-xs me-1 opacity-75"></i>Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('map') ? 'is-active' : '' }}"
                       href="{{ route('map') }}">
                        <i class="fa-solid fa-map fa-xs me-1 opacity-75"></i>Peta
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('table') ? 'is-active' : '' }}"
                       href="{{ route('table') }}">
                        <i class="fa-solid fa-table fa-xs me-1 opacity-75"></i>Tabel
                    </a>
                </li>
            </ul>

            {{-- Kanan: auth / guest --}}
            <ul class="navbar-nav ms-auto gap-1 align-items-lg-center mb-2 mb-lg-0">

                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}"
                           href="{{ route('dashboard') }}">
                            <i class="fa-solid fa-gauge fa-xs me-1 opacity-75"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="nav-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <div class="dropdown-item disabled pb-0 pt-1">
                                    <small class="text-muted">{{ auth()->user()->email }}</small>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fa-solid fa-user-gear fa-xs me-2 opacity-75"></i>Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger-soft">
                                        <i class="fa-solid fa-right-from-bracket fa-xs me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}"
                           class="btn btn-outline-success btn-sm rounded-pill px-3 fw-semibold">
                            <i class="fa-solid fa-right-to-bracket fa-xs me-1"></i>Masuk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}"
                           class="btn btn-success btn-sm rounded-pill px-3 fw-semibold ms-1">
                            <i class="fa-solid fa-user-plus fa-xs me-1"></i>Daftar
                        </a>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>
