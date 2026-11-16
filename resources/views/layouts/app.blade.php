
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Quản lý thư viện'))</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                Quản lý thư viện
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav me-auto">

                    @auth
                        @if(auth()->user()->role === 'admin')
                            @if(Route::has('admin.dashboard'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('admin.dashboard') }}">
                                        Trang quản trị
                                    </a>
                                </li>
                            @endif
                        @else
                            @if(Route::has('books.index'))
                                <li class="nav-item">
                                    <a class="nav-link"
                                       href="{{ route('books.index') }}">
                                        Danh sách sách
                                    </a>
                                </li>
                            @endif
                        @endif
                    @endauth

                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    @auth
                        <li class="nav-item">
                            <span class="nav-link text-white">
                                Xin chào, {{ auth()->user()->name }}
                            </span>
                        </li>

                        @if(Route::has('profile.edit'))
                            <li class="nav-item">
                                <a class="nav-link"
                                   href="{{ route('profile.edit') }}">
                                    Hồ sơ
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-light btn-sm ms-lg-2">
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    @else
                        @if(Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    Đăng nhập
                                </a>
                            </li>
                        @endif

                        @if(Route::has('register'))
                            <li class="nav-item">
                                <a class="btn btn-light btn-sm ms-lg-2"
                                   href="{{ route('register') }}">
                                    Đăng ký
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close"
                        data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
