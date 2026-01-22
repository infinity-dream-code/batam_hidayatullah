<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Batam Hidayatullah</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 450px;
            width: 100%;
        }
        .login-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .login-header {
            background: #1e293b;
            padding: 3rem 2rem;
            text-align: center;
        }
        .logo-wrapper {
            width: 80px;
            height: 80px;
            background: #334155;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(30, 41, 59, 0.15);
        }
        .btn-login {
            background: #1e293b;
            border: none;
            padding: 0.875rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #334155;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(30, 41, 59, 0.25);
        }
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus + .input-group-text {
            border-color: #1e293b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container mx-auto">
            <div class="card login-card">
                <div class="login-header text-white">
                    <div class="logo-wrapper">
                        <img src="{{ asset('batam.png') }}" alt="Logo" style="width: 100%; height: 100%; object-fit: contain;">
                    </div>
                    <h1 class="h4 mb-2 fw-bold">Batam Hidayatullah</h1>
                    <p class="mb-0 small opacity-75">Sistem Manajemen Pembayaran</p>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div>{{ $errors->first() }}</div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold small text-secondary">Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-envelope text-secondary"></i>
                                </span>
                                <input 
                                    type="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}"
                                    placeholder="Masukkan email"
                                    required 
                                    autofocus
                                >
                            </div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold small text-secondary">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-end-0">
                                    <i class="bi bi-lock text-secondary"></i>
                                </span>
                                <input 
                                    type="password" 
                                    class="form-control @error('password') is-invalid @enderror" 
                                    id="password" 
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                >
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label small text-secondary" for="remember">
                                Ingat saya
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-login w-100 text-white">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Masuk
                        </button>
                    </form>

                    <!-- Footer -->
                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">© 2026 Batam Hidayatullah</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
