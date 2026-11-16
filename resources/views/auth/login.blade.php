
<x-guest-layout>
    <div class="login-page">
        <!-- Bên trái: Form đăng nhập -->
        <div class="login-left">
            <div class="login-form">
                <h1 class="login-title">WELCOME</h1>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-4">
                        <input
                            type="email"
                            name="email"
                            class="form-control login-input"
                            placeholder="Enter email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="username"
                        >

                        @error('email')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <input
                            type="password"
                            name="password"
                            class="form-control login-input"
                            placeholder="Enter Password"
                            required
                            autocomplete="current-password"
                        >

                        @error('password')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="login-options">
                        <label class="remember-label">
                            <input type="checkbox" name="remember">
                            <span>Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn login-button">
                        LOGIN
                    </button>

                    @if (Route::has('register'))
                        <div class="register-link">
                            Don't have an account?
                            <a href="{{ route('register') }}">Register</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Bên phải: Thương hiệu -->
        <div class="login-right">
            <div class="library-brand">
                <div class="library-logo">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>

                <div class="library-brand-name">
                    LIBRARY<br>SYSTEM
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
