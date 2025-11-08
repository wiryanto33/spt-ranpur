<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>{{ config('app.name') }} </title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.11.3/datatables.min.css" />

    <!-- CSS Libraries -->
    @stack('customStyle')
    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/components.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
</head>

<body class="sidebar-mini">
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <form class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                                    class="fas fa-search"></i></a></li>
                    </ul>
                    <div class="search-element">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search"
                            data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                        <div class="search-result">
                            <div class="search-header">
                                Histories
                            </div>
                            <div class="search-item">
                                <a href="#">How to hack NASA using CSS</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">Kodinger.com</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-item">
                                <a href="#">#Stisla</a>
                                <a href="#" class="search-close"><i class="fas fa-times"></i></a>
                            </div>
                            <div class="search-header">
                                Result
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30" src="/assets/img/products/product-3-50.png"
                                        alt="product">
                                    oPhone S9 Limited Edition
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30" src="/assets/img/products/product-2-50.png"
                                        alt="product">
                                    Drone X2 New Gen-7
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <img class="mr-3 rounded" width="30" src="/assets/img/products/product-1-50.png"
                                        alt="product">
                                    Headphone Blitz
                                </a>
                            </div>
                            <div class="search-header">
                                Projects
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-danger text-white mr-3">
                                        <i class="fas fa-code"></i>
                                    </div>
                                    Stisla Admin Template
                                </a>
                            </div>
                            <div class="search-item">
                                <a href="#">
                                    <div class="search-icon bg-primary text-white mr-3">
                                        <i class="fas fa-laptop"></i>
                                    </div>
                                    Create a new Homepage Design
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
                <ul class="navbar-nav navbar-right">
                    @php
                        $unreadCount = auth()->user()->unreadNotifications()->count();
                        $unread = auth()->user()->unreadNotifications()->latest()->limit(10)->get();
                    @endphp
                    <li class="dropdown dropdown-list-toggle">
                        <a href="#" data-toggle="dropdown" class="nav-link nav-link-lg notification-toggle {{ $unreadCount ? 'beep' : '' }}">
                            <i class="far fa-bell"></i>
                        </a>
                        <div class="dropdown-menu dropdown-list dropdown-menu-right">
                            <div class="dropdown-header">Notifikasi
                                <div class="float-right">
                                    <a href="{{ route('notifications.readAll') }}">Tandai semua dibaca</a>
                                </div>
                            </div>
                            <div class="dropdown-list-content dropdown-list-icons">
                                @forelse ($unread as $n)
                                    <a href="{{ route('notifications.read', $n->id) }}" class="dropdown-item dropdown-item-unread">
                                        <div class="dropdown-item-icon bg-primary text-white">
                                            <i class="fas fa-info"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                            {{ data_get($n->data, 'title', 'Notifikasi') }}
                                            <div class="time text-primary">{{ $n->created_at->diffForHumans() }}</div>
                                            @if (data_get($n->data, 'message'))
                                                <div class="text-muted small">{{ data_get($n->data, 'message') }}</div>
                                            @endif
                                        </div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center text-muted">Tidak ada notifikasi baru</div>
                                @endforelse
                            </div>
                            <div class="dropdown-footer text-center">
                                <a href="#" onclick="event.preventDefault(); location.reload();">Tutup</a>
                            </div>
                        </div>
                    </li>

                    <li class="dropdown"><a href="#" data-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @php
                                $avatar = asset('assets/img/avatar/avatar-1.png');
                                if (auth()->check() && auth()->user()->image) {
                                    $img = auth()->user()->image;
                                    $avatar = preg_match('/^uploads\//', $img) ? asset($img) : asset('storage/'.$img);
                                }
                            @endphp
                            <img alt="avatar" src="{{ $avatar }}" class="rounded-circle mr-1"
                                style="width:32px;height:32px;object-fit:cover;">
                            @php
                                $roleName = auth()->user()->getRoleNames()->first();
                                $roleText = $roleName ? strtoupper(str_replace('-', ' ', $roleName)) : null;
                                $vehicleType = $roleName === 'crew' ? optional(auth()->user()->vehicle)->tipe : null;
                            @endphp
                            <div class="d-sm-none d-lg-inline-block">
                                Hi, {{ auth()->user()->name }}
                                @if($roleText)
                                    <small> {{ $roleText }}@if($vehicleType) · {{ $vehicleType }} @endif</small>
                                @endif
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('profile') }}" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="main-sidebar">
                <x-sidebar title="Dashboard Ranpur" />
                {{-- @include('layouts.sidebar') --}}
            </div>

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>
            <footer class="main-footer">
                @include('layouts.footer')
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="/assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="/assets/js/page/modules-sweetalert.js"></script>

    <!-- Template JS File -->
    <script src="/assets/js/scripts.js"></script>
    <script src="/assets/js/custom.js"></script>

    <!-- Page Specific JS File -->
    @stack('customScript')

    <script>
        // Persist sidebar state across refresh using localStorage
        (function() {
            const KEY = 'sidebar-mini-state';
            const body = document.body;

            function applySavedState() {
                const saved = localStorage.getItem(KEY);
                if (saved === '1') {
                    body.classList.add('sidebar-mini');
                } else {
                    // default: open sidebar
                    body.classList.remove('sidebar-mini');
                    if (saved === null) localStorage.setItem(KEY, '0');
                }
            }

            function saveState() {
                const minimized = body.classList.contains('sidebar-mini');
                localStorage.setItem(KEY, minimized ? '1' : '0');
            }

            document.addEventListener('DOMContentLoaded', applySavedState);

            // Listen to sidebar toggle clicks from Stisla
            document.addEventListener('click', function(e) {
                const toggle = e.target.closest('[data-toggle="sidebar"], .hide-sidebar-mini');
                if (toggle) {
                    setTimeout(saveState, 100); // wait Stisla to toggle classes
                }
            });
        })();
    </script>
</body>

</html>
