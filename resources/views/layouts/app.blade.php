<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Library System - Quản Lý Thư Viện'))</title>

    <!-- Google Fonts: Playfair Display & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,800;1,400;1,700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS Giao diện Library Theme #821827 -->
    <link rel="stylesheet" href="{{ asset('css/library.css') }}">

    @stack('styles')
</head>

<body>
    <!-- Header Navigation với tone màu #821827 -->
    <nav class="navbar navbar-expand-lg lib-navbar">
        <div class="container">
            <a class="lib-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/library-logo.png') }}" alt="Logo" class="lib-brand-icon">
                <span class="lib-brand-text">LIBRARY SYSTEM</span>
            </a>

            <button class="navbar-toggler text-white border-0" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar"
                    aria-controls="mainNavbar"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <i class="bi bi-list fs-2 text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('books.*') || request()->is('/') ? 'active' : '' }}"
                           href="{{ route('books.index') }}">
                            <i class="bi bi-book me-1"></i> Kho Sách Thư Viện
                        </a>
                    </li>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i> Trang Quản Trị
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.borrows') ? 'active' : '' }}"
                                   href="{{ route('admin.borrows') }}">
                                    <i class="bi bi-card-checklist me-1"></i> Duyệt Phiếu Mượn
                                </a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('borrows.*') ? 'active' : '' }}"
                                   href="{{ route('borrows.index') }}">
                                    <i class="bi bi-journal-bookmark me-1"></i> Sách Đang Mượn
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle lib-user-pill" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span>{{ auth()->user()->name }}</span>
                                @if(auth()->user()->role === 'admin')
                                    <span class="badge bg-warning text-dark ms-1">Admin</span>
                                @else
                                    <span class="badge bg-light text-dark ms-1">Độc giả</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                @if(Route::has('profile.edit'))
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                                            <i class="bi bi-person me-2 text-muted"></i> Hồ sơ cá nhân
                                        </a>
                                    </li>
                                @endif
                                @if(auth()->user()->role !== 'admin')
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('borrows.index') }}">
                                            <i class="bi bi-clock-history me-2 text-muted"></i> Lịch sử mượn trả
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        @if(Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập
                                </a>
                            </li>
                        @endif

                        @if(Route::has('register'))
                            <li class="nav-item ms-lg-2">
                                <a class="btn btn-sm btn-light px-3 py-1.5 fw-semibold text-danger rounded-pill shadow-sm"
                                   href="{{ route('register') }}">
                                    <i class="bi bi-person-plus me-1"></i> Đăng ký
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="container py-4 flex-grow-1">
        <!-- Thông báo thành công -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
                <span class="align-middle">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Thông báo lỗi -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
                <span class="align-middle">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger rounded-3 shadow-sm border-0 mb-4">
                <div class="fw-bold mb-1"><i class="bi bi-x-circle-fill me-1"></i> Đã xảy ra lỗi:</div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Hỗ trợ cả Slot (Component) và Yield (Extends) -->
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    <!-- Footer hệ thống thư viện -->
    <footer class="lib-footer">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-2 mb-2">
                        <img src="{{ asset('images/library-logo.png') }}" alt="Logo" style="height: 28px;">
                        <span class="font-serif fw-bold text-white fs-5">LIBRARY SYSTEM</span>
                    </div>
                    <p class="small text-white-50 mb-0">
                        Hệ thống số hóa thư viện học thuật phục vụ giảng viên, nghiên cứu sinh và sinh viên.
                    </p>
                </div>
                <div class="col-lg-6 text-center text-lg-end">
                    <p class="small text-white-50 mb-1">
                        <i class="bi bi-clock me-1"></i> Giờ mở cửa: Thứ Hai - Thứ Bảy (07:30 - 21:00)
                    </p>
                    <p class="small text-white-50 mb-0">
                        &copy; {{ date('Y') }} Library System. Nhóm 2. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
