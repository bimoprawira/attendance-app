<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Pro - Smart Attendance Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .hero-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .feature-card {
            transition: transform 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-600">Attendance Pro</a>
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#about" class="text-gray-600 hover:text-indigo-600 transition">About Us</a>
                    <a href="#contact" class="text-gray-600 hover:text-indigo-600 transition">Contact</a>
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Sign In</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-gradient pt-32 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Smart Attendance Management
                </h1>
                <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Streamline your attendance tracking with our modern, efficient, and user-friendly solution.
                </p>
                <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-indigo-50 transition">
                    Get Started
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose Attendance Pro?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="text-indigo-600 text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-semibold mb-2">Easy Check-in/out</h3>
                    <p class="text-gray-600">Simple and quick attendance tracking with just a few clicks.</p>
                </div>
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="text-indigo-600 text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Real-time Analytics</h3>
                    <p class="text-gray-600">Get instant insights into attendance patterns and trends.</p>
                </div>
                <div class="feature-card bg-white p-6 rounded-xl shadow-lg">
                    <div class="text-indigo-600 text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-semibold mb-2">Secure & Reliable</h3>
                    <p class="text-gray-600">Your data is protected with enterprise-grade security.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">About Us</h2>
            <div class="text-center max-w-3xl mx-auto">
                <p class="text-gray-600 text-lg">
                    We are dedicated to revolutionizing attendance management with cutting-edge technology and user-friendly solutions. Our platform helps organizations of all sizes streamline their attendance tracking processes.
                </p>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12">Contact Us</h2>
            <div class="text-center">
                <p class="text-gray-600 mb-4">Have questions? We'd love to hear from you.</p>
                <p class="text-gray-600">Email: support@attendancepro.com</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 Attendance Pro. All rights reserved.</p>
        </div>
    </footer>
</body>
</html> 