<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="layout-wide customizer-hide" data-assets-path="{{ asset('assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | POS System</title>
    <meta name="description" content="Sistem Point of Sale Laravel" />

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    {{-- Icons --}}
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    {{-- Custom CSS --}}
    <style>
        .pos-logo {
            background: linear-gradient(135deg, #696cff 0%, #00d4ff 100%);
            border-radius: 50%;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 20px rgba(105, 108, 255, 0.3);
        }

        .app-brand-text {
            color: #696cff;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .welcome-text {
            color: #697a8d;
        }

        .login-card {
            border-radius: 15px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .btn-login {
            background: linear-gradient(135deg, #696cff 0%, #00d4ff 100%);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(105, 108, 255, 0.4);
        }
    </style>
</head>

<body>
    <div class="container-xxl">

        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                    <div class="card login-card">
                        <div class="card-body">
                            {{-- Alerts inside the login box --}}
                            @if(session('success'))
                                <div class="alert alert-success mb-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger mb-3">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger mb-3">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- Logo dan Brand --}}
                            <div class="app-brand justify-content-center mb-4 text-center">
                                <a href="{{ url('/') }}"
                                    class="app-brand-link d-flex flex-column align-items-center gap-1">
                                    <div class="pos-logo">
                                        <i class='bx bx-store-alt' style="font-size: 2.5rem; color: white;"></i>
                                    </div>
                                    <span class="app-brand-text demo text-body fw-bolder mt-2">POS SYSTEM</span>
                                </a>
                            </div>

                            {{-- Welcome Message --}}
                            <h4 class="mb-2 text-center welcome-text">Selamat Datang! 👋</h4>
                            <p class="mb-4 text-center">Silakan login ke sistem Point of Sale</p>

                            {{-- Login Form --}}
                            <form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
                                @csrf

                                {{-- Email Input --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Masukkan email Anda" autofocus required />
                                    </div>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- Password Input --}}
                                <div class="mb-3 form-password-toggle">
                                    <div class="d-flex justify-content-between">
                                        <label class="form-label" for="password">Password</label>
                                        @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}">
                                            <small>Lupa Password?</small>
                                        </a>
                                        @endif
                                    </div>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                        <input type="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                            aria-describedby="password-addon" required />
                                        <span id="password-addon" class="input-group-text cursor-pointer">
                                            <i class="bx bx-hide"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- Remember Me --}}
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember" {{
                                            old('remember') ? 'checked' : '' }} />
                                        <label class="form-check-label" for="remember">
                                            Ingat saya
                                        </label>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <div class="mb-3">
                                    <button class="btn btn-login d-grid w-100" type="submit">
                                        <i class="bx bx-log-in me-2"></i>Login
                                    </button>
                                </div>
                            </form>

                            {{-- Demo Account Info (Optional) --}}
                            <div class="text-center">
                                <p class="mb-1">Akun Demo:</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <small class="text-muted">Admin: admin@pos.com / password</small>
                                    <small class="text-muted">Kasir: cashier@pos.com / password</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- JavaScript Libraries --}}
        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ asset('assets/js/config.js') }}"></script>

        {{-- Password Toggle Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.querySelector('.form-password-toggle .cursor-pointer');
            if (passwordToggle) {
                passwordToggle.addEventListener('click', function() {
                    const passwordInput = document.getElementById('password');
                    const icon = this.querySelector('i');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.replace('bx-hide', 'bx-show');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.replace('bx-show', 'bx-hide');
                    }
                });
            }
        });
    </script>
</body>

</html>