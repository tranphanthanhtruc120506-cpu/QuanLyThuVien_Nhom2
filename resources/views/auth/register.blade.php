<x-guest-layout>
    <div class="login-page">
        <!-- Bên trái: Form đăng ký tài khoản -->
        <div class="login-left">
            <div class="login-form-wrapper" style="max-width: 480px;">
                <h1 class="login-title" style="font-size: 2.35rem; margin-bottom: 1.5rem;">REGISTER</h1>

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 text-start small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Họ và tên -->
                    <div class="login-input-group mb-3">
                        <input
                            type="text"
                            name="name"
                            id="regName"
                            class="form-control login-input"
                            placeholder="Full Name / Họ và tên"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                        >
                    </div>

                    <!-- Email -->
                    <div class="login-input-group mb-3">
                        <input
                            type="email"
                            name="email"
                            id="regEmail"
                            class="form-control login-input"
                            placeholder="Email Address"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                        >
                    </div>

                    <!-- Số điện thoại -->
                    <div class="login-input-group mb-3">
                        <input
                            type="tel"
                            name="tel"
                            id="regTel"
                            class="form-control login-input"
                            placeholder="Phone Number / Số điện thoại"
                            value="{{ old('tel') }}"
                            autocomplete="tel"
                        >
                    </div>

                    <!-- Mật khẩu -->
                    <div class="login-input-group mb-3">
                        <input
                            type="password"
                            name="password"
                            id="regPassword"
                            class="form-control login-input"
                            placeholder="Password / Mật khẩu"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="login-input-group mb-4">
                        <input
                            type="password"
                            name="password_confirmation"
                            id="regPasswordConfirm"
                            class="form-control login-input"
                            placeholder="Confirm Password / Nhập lại mật khẩu"
                            required
                            autocomplete="new-password"
                        >
                    </div>

                    <!-- Nút Đăng ký -->
                    <button type="submit" class="btn login-button w-100 mb-3">
                        CREATE ACCOUNT
                    </button>

                    <div class="register-link mt-2">
                        Already have an account?
                        <a href="{{ route('login') }}">Login</a>
                    </div>
                </form>
            </div>

            <!-- Đường lượn sóng phân tách -->
            <svg class="login-wave-divider" viewBox="0 0 100 1000" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0,0 L50,0 C68,140 85,280 62,440 C38,620 90,820 68,1000 L0,1000 Z" fill="#ffffff"></path>
            </svg>
        </div>

        <!-- Bên phải: Thương hiệu -->
        <div class="login-right">
            <div class="library-brand">
                <div class="library-logo-container">
                    <img src="{{ asset('images/library-logo.png') }}"
                         alt="Library System Logo"
                         class="library-logo-img">
                </div>

                <div class="library-brand-name">
                    LIBRARY<br>SYSTEM
                </div>

                <div class="system-info-card">
                    <div class="system-info-title">
                        <i class="bi bi-person-plus-fill me-1"></i> Tham Gia Thư Viện
                    </div>
                    <p class="mb-0">
                        Đăng ký tài khoản độc giả ngay hôm nay để mượn hàng ngàn đầu sách giá trị, theo dõi lịch sử đọc và tiếp cận nguồn tri thức phong phú.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
