<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABSEN.IN - Selamat Datang</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
        }
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem 1rem 5rem 1rem;
        }
        .logo {
            font-size: 3rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 24px rgba(102,126,234,0.18);
            animation: fadeInDown 1s;
        }
        .hero-illustration {
            width: 320px;
            max-width: 90vw;
            margin: 0 auto 2.5rem auto;
            display: block;
            animation: fadeIn 1.2s;
        }
        .welcome {
            font-size: 1.5rem;
            color: #f3f4f6;
            margin-bottom: 2.5rem;
            font-weight: 500;
            animation: fadeInUp 1.2s;
        }
        .login-btn {
            background: #fff;
            color: #667eea;
            font-weight: 700;
            padding: 1rem 2.7rem;
            border-radius: 0.9rem;
            font-size: 1.2rem;
            text-decoration: none;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 24px rgba(102,126,234,0.13);
            animation: fadeInUp 1.4s;
        }
        .login-btn:hover {
            background: #e0e7ff;
            color: #4f46e5;
            box-shadow: 0 8px 32px rgba(102,126,234,0.18);
        }
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: rgba(31, 41, 55, 0.95);
            color: #fff;
            text-align: center;
            padding: 1rem 0;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 600px) {
            .logo { font-size: 2rem; }
            .welcome { font-size: 1.1rem; }
            .login-btn { font-size: 1rem; padding: 0.8rem 1.5rem; }
            .hero-illustration { width: 200px; }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="hero">
        <div class="logo">ABSEN.IN</div>
        <img src="{{ asset('img/undraw_cat_lqdj.svg') }}" alt="Attendance Illustration" class="hero-illustration" loading="lazy">
        <div class="welcome">Selamat datang di <b>ABSEN.IN</b> — solusi modern untuk manajemen kehadiran dan gaji karyawan.<br>Kelola kehadiran, cuti, dan gaji dengan mudah, cepat, dan aman.</div>
        <a href="{{ route('login') }}" class="login-btn">Masuk ke Sistem</a>
    </div>
    <footer>
        &copy; 2024 ABSEN.IN. Hak Cipta Dilindungi.
    </footer>
</body>
</html> 