<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Docs Clone</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Google Sans', Arial, sans-serif;
            background: #f8f9fa;
        }

        /* NAVBAR */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 24px;
            background: white;
            border-bottom: 1px solid #e0e0e0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 22px;
            color: #5f6368;
            text-decoration: none;
        }

        .navbar-brand span {
            color: #1a8be8;
            font-weight: 500;
        }

        .navbar-brand svg {
            width: 40px;
        }

        .nav-buttons {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-login {
            padding: 8px 20px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            color: #1a73e8;
            background: white;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-login:hover { background: #f1f3f4; }

        .btn-register {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            background: #1a73e8;
            color: white;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-register:hover { background: #1557b0; }

        /* HERO */
        .hero {
            text-align: center;
            padding: 80px 24px 60px;
        }

        .hero-icon {
            font-size: 64px;
            margin-bottom: 24px;
        }

        .hero h1 {
            font-size: 48px;
            color: #202124;
            font-weight: 400;
            margin-bottom: 16px;
        }

        .hero h1 span { color: #1a73e8; }

        .hero p {
            font-size: 18px;
            color: #5f6368;
            max-width: 500px;
            margin: 0 auto 36px;
        }

        .hero-buttons {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary {
            padding: 14px 32px;
            background: #1a73e8;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-primary:hover { background: #1557b0; }

        .btn-secondary {
            padding: 14px 32px;
            background: white;
            color: #1a73e8;
            border: 1px solid #dadce0;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-secondary:hover { background: #f1f3f4; }

        /* FEATURES */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 24px 80px;
        }

        .feature-card {
            background: white;
            border-radius: 8px;
            padding: 28px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .feature-card .icon { font-size: 36px; margin-bottom: 16px; }

        .feature-card h3 {
            font-size: 16px;
            color: #202124;
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 14px;
            color: #5f6368;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            📄 <span>MY</span>DOCUMENTS
        </a>
        <div class="nav-buttons">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-register">Dashboard</a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-icon">📝</div>
        <h1>Buat Dokumen <span>Bersama</span></h1>
        <p>Tulis dan edit dokument sesuka hati.</p>
        <div class="hero-buttons">
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-primary">Buka Dokumen Saya</a>
            @else
                <a href="{{ route('register') }}" class="btn-primary">Daftar</a>
                <a href="{{ route('login') }}" class="btn-secondary">Masuk</a>
            @endauth
        </div>
</body>
</html>