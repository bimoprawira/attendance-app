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
        .center-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .logo {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
        }
        .welcome {
            font-size: 1.3rem;
            color: #f3f4f6;
            margin-bottom: 2.5rem;
            text-align: center;
        }
        .login-btn {
            background: #fff;
            color: #667eea;
            font-weight: 600;
            padding: 0.9rem 2.5rem;
            border-radius: 0.75rem;
            font-size: 1.1rem;
            text-decoration: none;
            transition: background 0.2s, color 0.2s;
            box-shadow: 0 2px 8px rgba(102,126,234,0.08);
        }
        .login-btn:hover {
            background: #e0e7ff;
            color: #4f46e5;
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
        }
    </style>
</head>
<body>
    <div class="center-container">
        <div class="logo">ABSEN.IN</div>
        <div class="welcome">Selamat datang di sistem manajemen kehadiran perusahaan Anda.<br>Silakan masuk untuk melanjutkan.</div>
        <a href="{{ route('login') }}" class="login-btn">Masuk</a>
    </div>
    <footer>
        &copy; 2024 ABSEN.IN. Hak Cipta Dilindungi.
    </footer>
</body>
</html> 