<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login - Inventory System</title>
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <meta name="msapplication-TileColor" content="#206bc4" />
    <meta name="theme-color" content="#206bc4" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="mobile-web-app-capable" content="yes" />
    <meta name="HandheldFriendly" content="True" />
    <meta name="MobileOptimized" content="320" />
    <meta name="robots" content="noindex,nofollow,noarchive" />
<link rel="icon" href="{{ asset('DAS.png') }}" type="image/x-icon" />
<link rel="shortcut icon" href="{{ asset('DAS.png') }}" type="image/x-icon" />
<style>
    .login-logo {
        width: 200px !important;
        max-width: 100%;
        height: auto;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
    <!-- Tabler Core -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
</head>

<body class="antialiased d-flex flex-column bg-light">
    <div class="page page-center">
        <div class="container container-tight py-4">
            <form class="card shadow-lg border-0 rounded-3" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <img src="{{ asset('DAS.png') }}" alt="Logo" class="mb-2" width="64">
                        <h2 class="fw-bold text-primary">Inventory System Das.net</h2>
                        <p class="text-muted">Silakan masuk ke akun anda</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Masukan email anda"
                            name="email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukan kata sandi anda"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-check">
                            <input type="checkbox" class="form-check-input"/>
                            <span class="form-check-label">Ingat saya</span>
                        </label>
                        <a href="#" class="small text-primary">Lupa password?</a>
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary w-100">
                            Masuk
                        </button>
                    </div>
                </div>
            </form>
            <div class="text-center text-muted mt-3">
                Belum punya akun? <a href="#" class="text-primary">Daftar sekarang</a>
            </div>
        </div>
    </div>
</body>

</html>
