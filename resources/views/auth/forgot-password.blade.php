<x-guest-layout>
    <div class="login-page">
        <div class="login-left">
            <div class="login-form-wrapper">
                <h1 class="login-title" style="font-size: 2.2rem; margin-bottom: 1rem;">RESET PASSWORD</h1>

                <p class="text-muted small mb-4">
                    Quên mật khẩu? Vui lòng nhập địa chỉ email đã đăng ký. Hệ thống sẽ gửi cho bạn liên kết để đặt lại mật khẩu mới.
                </p>

                @if (session('status'))
                    <div class="alert alert-success py-2 px-3 mb-4 rounded-pill small text-center">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-4 rounded-3 text-start small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="login-input-group mb-4">
                        <input
                            type="email"
                            name="email"
                            class="form-control login-input"
                            placeholder="Enter your registered email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>

                    <button type="submit" class="btn login-button w-100 mb-3">
                        SEND RESET LINK
                    </button>

                    <div class="register-link mt-2">
                        <a href="{{ route('login') }}"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
                    </div>
                </form>
            </div>

            <svg class="login-wave-divider" viewBox="0 0 100 1000" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0,0 L50,0 C68,140 85,280 62,440 C38,620 90,820 68,1000 L0,1000 Z" fill="#ffffff"></path>
            </svg>
        </div>

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
            </div>
        </div>
    </div>
</x-guest-layout>
