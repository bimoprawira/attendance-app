<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - ABSEN.IN</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            margin: 0;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 2rem;
            box-shadow: 0 8px 32px rgba(102,126,234,0.13);
            display: flex;
            overflow: hidden;
            max-width: 900px;
            width: 100%;
            margin: 2rem 1rem;
        }
        .login-illustration {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            flex: 1.1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            min-width: 320px;
        }
        .login-illustration img {
            width: 220px;
            max-width: 80vw;
            margin-bottom: 2rem;
        }
        .login-illustration .tagline {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.7rem;
            text-shadow: 0 2px 12px rgba(102,126,234,0.18);
        }
        .login-illustration .desc {
            font-size: 1.05rem;
            font-weight: 400;
            opacity: 0.93;
        }
        .login-form-section {
            flex: 1;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .login-form-section .logo {
            font-size: 2rem;
            font-weight: 800;
            color: #667eea;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }
        .login-form-section h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #4f46e5;
            margin-bottom: 0.5rem;
        }
        .login-form-section h2 {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 2rem;
        }
        .login-form-section form {
            width: 100%;
        }
        .login-form-section input {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border-radius: 0.8rem;
            border: 1px solid #d1d5db;
            margin-bottom: 1.2rem;
            font-size: 1rem;
            background: #f3f4f6;
            transition: border 0.2s;
        }
        .login-form-section input:focus {
            border: 1.5px solid #667eea;
            outline: none;
            background: #fff;
        }
        .login-form-section button[type="submit"] {
            width: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            font-weight: 700;
            padding: 0.95rem 0;
            border-radius: 0.8rem;
            font-size: 1.1rem;
            border: none;
            margin-top: 0.5rem;
            box-shadow: 0 2px 8px rgba(102,126,234,0.10);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .login-form-section button[type="submit"]:hover {
            background: linear-gradient(90deg, #5a67d8 0%, #6d28d9 100%);
            box-shadow: 0 4px 16px rgba(102,126,234,0.18);
        }
        .login-form-section .back-link {
            display: block;
            margin-top: 1.5rem;
            text-align: center;
            color: #667eea;
            font-size: 1rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .login-form-section .back-link:hover {
            color: #4f46e5;
        }
        @media (max-width: 900px) {
            .login-card { flex-direction: column; min-width: 0; }
            .login-illustration { min-width: 0; padding: 2rem 1rem; }
            .login-form-section { padding: 2rem 1.2rem; }
        }
        @media (max-width: 600px) {
            .login-card { border-radius: 0; box-shadow: none; }
            .login-illustration { display: none; }
            .login-form-section { padding: 2rem 0.5rem; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-illustration">
                <img src="{{ asset('img/undraw_login_weas.svg') }}" alt="Login Illustration" loading="lazy">
                <div class="tagline">Selamat Datang di ABSEN.IN</div>
                <div class="desc">Sistem manajemen kehadiran dan gaji karyawan yang modern, aman, dan mudah digunakan.</div>
            </div>
            <div class="login-form-section">
                <div class="logo">ABSEN.IN</div>
                <h1>Masuk</h1>
                <h2>Silakan login ke akun Anda</h2>
                <form class="space-y-6" action="{{ route('login') }}" method="POST">
                    @csrf
                    <input id="login" name="login" type="text" required placeholder="Email atau Username" value="{{ old('login') }}" class="@error('login') border-red-500 @enderror">
                    @error('login')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <input id="password" name="password" type="password" required placeholder="Kata Sandi" class="@error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <button type="submit">Masuk</button>
                </form>
                <a href="/" class="back-link">&larr; Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
