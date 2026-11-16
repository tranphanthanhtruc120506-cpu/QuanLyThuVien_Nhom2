<x-guest-layout>
    <div class="login-page">
        <!-- Bên trái: Form đăng nhập -->
        <div class="login-left">
            <div class="login-form-wrapper">
                <h1 class="login-title">WELCOME</h1>

                @if (session('status'))
                    <div class="alert alert-success py-2 px-3 mb-4 rounded-pill text-center small" role="alert">
                        <i class="bi bi-check-circle-fill me-1"></i> {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 text-start small" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf

                    <!-- Nhập Email / Username -->
                    <div class="login-input-group">
                        <input
                            type="text"
                            name="email"
                            id="loginEmail"
                            class="form-control login-input @error('email') is-invalid @enderror"
                            placeholder="Enter email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                        >
                    </div>

                    <!-- Nhập Password -->
                    <div class="login-input-group">
                        <input
                            type="password"
                            name="password"
                            id="loginPassword"
                            class="form-control login-input @error('password') is-invalid @enderror"
                            placeholder="Enter Password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle-btn" id="togglePasswordBtn" title="Hiện/ẩn mật khẩu">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>

                    <!-- Tùy chọn: Ghi nhớ & Quên mật khẩu -->
                    <div class="login-options">
                        <label class="remember-label">
                            <input type="checkbox" name="remember" id="remember_me">
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Nút Đăng nhập -->
                    <button type="submit" class="btn login-button">
                        LOGIN
                    </button>

                    <!-- Liên kết Đăng ký -->
                    @if (Route::has('register'))
                        <div class="register-link">
                            Don't have an account?
                            <a href="{{ route('register') }}">Register</a>
                        </div>
                    @endif

                    <!-- Gợi ý tài khoản demo phục vụ kiểm thử hệ thống -->
                    <div class="demo-accounts">
                        <span class="d-block mb-1">Tài khoản demo kiểm thử:</span>
                        <div class="demo-btn-group">
                            <button type="button" class="demo-chip" onclick="fillCredentials('admin@library.test', '12345678')">
                                <i class="bi bi-shield-lock me-1"></i> Admin (Thủ thư)
                            </button>
                            <button type="button" class="demo-chip" onclick="fillCredentials('lilian@library.test', '12345678')">
                                <i class="bi bi-person me-1"></i> Độc giả (Lilian)
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Đường lượn sóng phân tách mềm mại giữa 2 khối -->
            <svg class="login-wave-divider" viewBox="0 0 100 1000" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0,0 L50,0 C68,140 85,280 62,440 C38,620 90,820 68,1000 L0,1000 Z" fill="#ffffff"></path>
            </svg>
        </div>

        <!-- Bên phải: Thương hiệu & Thông tin hệ thống -->
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
                        <i class="bi bi-mortarboard-fill me-1"></i> Hệ Thống Quản Lý Thư Viện
                    </div>
                    <p class="mb-2">
                        Nền tảng số hóa hỗ trợ tìm kiếm tài liệu học thuật, mượn trả sách thông minh và theo dõi hồ sơ độc giả toàn diện.
                    </p>
                    <div class="system-badges">
                        <span class="system-badge"><i class="bi bi-book"></i> 10,000+ Sách</span>
                        <span class="system-badge"><i class="bi bi-lightning-charge"></i> Mượn Trả Nhanh</span>
                        <span class="system-badge"><i class="bi bi-shield-check"></i> Bảo Mật Cao</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Toggle ẩn/hiện mật khẩu
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('loginPassword');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.classList.toggle('bi-eye', !isPassword);
                toggleIcon.classList.toggle('bi-eye-slash', isPassword);
            });
        }

        // Hỗ trợ điền nhanh tài khoản demo
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('loginEmail');
            const passInput = document.getElementById('loginPassword');
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = password;
                emailInput.focus();
            }
        }
    </script>
    @endpush
</x-guest-layout>
