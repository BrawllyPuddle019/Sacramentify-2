<!doctype html>
<html lang="es">
<head>
    <title>Iniciar Sesión - Sacramentify</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #072146;
            --secondary-color: #0063A8;
            --accent-color: #1a73e8;
            --gold-color: #d4af37;
            --light-blue: #e8f2ff;
            --text-dark: #2c3e50;
            --text-light: #6c757d;
            --white: #ffffff;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 6px 16px rgba(0, 0, 0, 0.12);
            --bg-light: #f8fafc;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Optimize scrolling performance */
        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            position: relative;
            /* Optimize for scroll performance */
            -webkit-overflow-scrolling: touch;
            transform: translateZ(0);
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Static elegant background - No animations for better performance */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(circle at 25% 25%, rgba(212, 175, 55, 0.12) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.06) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(26, 115, 232, 0.1) 0%, transparent 60%);
            background-size: 100% 100%;
            pointer-events: none;
            z-index: 0;
        }

        /* Simple geometric pattern overlay */
        body::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60"><defs><pattern id="dots" x="0" y="0" width="60" height="60" patternUnits="userSpaceOnUse"><circle cx="30" cy="30" r="1.5" fill="rgba(255,255,255,0.04)"/></pattern></defs><rect width="60" height="60" fill="url(%23dots)"/></svg>');
            pointer-events: none;
            z-index: 1;
        }

        /* Optimized scrollbar */

        /* Navbar Styles - Optimized for performance */
        .navbar {
            background: rgba(7, 33, 70, 0.98) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
            will-change: background-color, box-shadow;
        }

        .navbar.navbar-scrolled {
            background: rgba(7, 33, 70, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            height: 50px;
            width: auto;
            transition: all 0.3s ease;
        }

        .logo.img-small {
            height: 40px;
        }

        .site-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
            transition: all 0.3s ease;
            font-family: 'Playfair Display', serif;
        }

        .site-name:hover {
            color: var(--gold-color);
        }

        .site-name.site-name-small {
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.8rem 1.5rem !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin-left: 10px;
            color: var(--white) !important;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
            color: var(--gold-color) !important;
        }

        /* Main Content */
        .main-container {
            position: relative;
            z-index: 10;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 20px 40px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            box-shadow: 
                0 20px 40px -12px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            padding: 3rem 2.5rem;
            max-width: 460px;
            width: 100%;
            margin: 0 auto;
            animation: slideIn 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--gold-color), var(--primary-color));
            background-size: 300% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes shimmer {
            0% { background-position: -300% 0; }
            100% { background-position: 300% 0; }
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--white);
            font-size: 2rem;
            box-shadow: var(--shadow);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: var(--shadow);
            }
            50% {
                transform: scale(1.05);
                box-shadow: var(--shadow-hover);
            }
        }

        .login-title {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-family: 'Playfair Display', serif;
        }

        .login-subtitle {
            color: var(--text-light);
            font-size: 1.1rem;
            font-weight: 400;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-label {
            font-weight: 500;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
            display: block;
        }

        .form-control {
            background: rgba(248, 250, 252, 0.9);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1rem 1rem 1rem 3.5rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
            background: rgba(255, 255, 255, 0.95);
            transform: translateY(-1px);
        }

        .input-icon {
            position: absolute;
            left: 1.2rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: all 0.3s ease;
            z-index: 10;
            font-size: 1.1rem;
        }

        .form-control:focus + .input-icon {
            color: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 1.2rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            margin-top: 1rem;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        /* Separador "O" */
        .separator {
            position: relative;
            text-align: center;
            margin: 2rem 0 !important;
        }

        .separator::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: var(--border-color);
        }

        .separator-text {
            background: var(--white);
            padding: 0 1rem;
            color: var(--text-light);
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Botón de Google */
        .btn-google {
            background: var(--white);
            border: 2px solid var(--border-color);
            border-radius: 12px;
            padding: 1.2rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-dark);
            width: 100%;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 1rem;
        }

        .btn-google:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border-color: #dadce0;
            color: var(--text-dark);
            text-decoration: none;
        }

        .btn-google:active {
            transform: translateY(0);
        }

        .google-icon {
            flex-shrink: 0;
        }

        /* Additional Options */
        .login-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .forgot-password {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Register Link */
        .register-link {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .register-link p {
            color: var(--text-light);
            margin-bottom: 0;
            font-size: 1rem;
        }

        .register-link a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        /* Loading Animation */
        .loading {
            position: relative;
        }

        .loading .btn-text {
            opacity: 0;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin: -10px 0 0 -10px;
            border: 2px solid transparent;
            border-top: 2px solid #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Success/Error Messages */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            font-weight: 500;
            position: relative;
            border-left: 4px solid;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #166534;
            border-left-color: #22c55e;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #991b1b;
            border-left-color: #ef4444;
        }

        /* Performance Optimizations */
        @media (max-width: 768px) {
            .main-container {
                padding: 80px 15px 30px;
            }

            .login-card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }
            
            .login-title {
                font-size: 1.8rem;
            }

            .login-subtitle {
                font-size: 1rem;
            }

            .login-icon {
                width: 70px;
                height: 70px;
                font-size: 1.8rem;
            }

            .form-control {
                padding: 0.9rem 0.9rem 0.9rem 3rem;
            }

            .input-icon {
                left: 1rem;
            }

            /* No background animation on mobile for better performance */
            body::before {
                background: 
                    radial-gradient(circle at 25% 25%, rgba(212, 175, 55, 0.08) 0%, transparent 50%),
                    radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.04) 0%, transparent 50%);
            }
        }

        @media (max-width: 576px) {
            .login-card {
                margin: 0 10px;
                padding: 1.5rem 1.2rem;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            /* Simplified background for small screens */
            body::before {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            }
            
            body::after {
                display: none; /* Remove pattern on very small screens */
            }
        }

        /* Reduce motion for users who prefer it */
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>
</head>

<body>
    <!-- Optimized login page - No scroll progress bar for better performance -->

    <!-- Static Background - No particles for better performance -->

    <!-- Navbar - Matching Welcome Page -->
    <header class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <div class="navbar-brand">
                <img src="assets/logo.png" alt="SACRAMENTIFY" class="logo">
                <a class="site-name" href="{{ route('welcome') }}" style="text-decoration: none;">SACRAMENTIFY</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('welcome') }}">
                            <i class="fas fa-home me-2"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#ayuda">
                            <i class="fas fa-question-circle me-2"></i>Ayuda
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="login-icon">
                                <i class="fas fa-cross"></i>
                            </div>
                            <h1 class="login-title">Bienvenido</h1>
                            <p class="login-subtitle">Accede a tu cuenta de SACRAMENTIFY</p>
                        </div>

                        <!-- Error Messages -->
                        <div class="alert alert-danger" style="display: none;" id="errorAlert">
                            Por favor verifica tus credenciales
                        </div>

                        <!-- Success Messages -->
                        <div class="alert alert-success" style="display: none;" id="successAlert">
                            Bienvenido de vuelta
                        </div>

                        <form action="{{ route('login') }}" method="post" id="loginForm">
                            @csrf
                            
                            <div class="form-group">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <div class="position-relative">
                                    <input 
                                        type="email" 
                                        name="email" 
                                        id="email" 
                                        class="form-control @error('email') is-invalid @enderror" 
                                        value="{{ old('email') }}"
                                        required 
                                        autocomplete="email"
                                        placeholder="tu@email.com"
                                    />
                                    <i class="input-icon" data-lucide="mail"></i>
                                </div>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="position-relative">
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        class="form-control @error('password') is-invalid @enderror" 
                                        required 
                                        autocomplete="current-password"
                                        placeholder="••••••••"
                                    />
                                    <i class="input-icon" data-lucide="lock"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="btn-text">Iniciar Sesión</span>
                            </button>
                        </form>

                        <!-- Separador "O" -->
                        <div class="separator my-4">
                            <span class="separator-text">O</span>
                        </div>

                        <!-- Botón de Google -->
                        <a href="{{ route('google.redirect') }}" class="btn btn-google d-flex align-items-center justify-content-center">
                            <svg class="google-icon" width="20" height="20" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="ms-2">Continuar con Google</span>
                        </a>

                        <div class="register-link">\
                            <p>¿No tienes una cuenta? 
                                <a href="{{ route('register') }}">Regístrate aquí</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script>
        // Optimized scroll handling - throttled for better performance
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const logo = document.querySelector('.logo');
            const siteName = document.querySelector('.site-name');
            
            let ticking = false;

            function updateNavbar() {
                if (window.scrollY > 100) {
                    navbar.classList.add('navbar-scrolled');
                    logo.classList.add('img-small');
                    siteName.classList.add('site-name-small');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                    logo.classList.remove('img-small');
                    siteName.classList.remove('site-name-small');
                }
                ticking = false;
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(updateNavbar);
                    ticking = true;
                }
            }

            window.addEventListener('scroll', requestTick, { passive: true });

            // No particles - Better performance
            console.log('Login page loaded - Static background for optimal performance');
            
            // Form submission with loading state
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submitBtn');
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                // Basic validation
                if (!email || !password) {
                    showAlert('errorAlert', 'Por favor completa todos los campos');
                    return;
                }

                if (!isValidEmail(email)) {
                    showAlert('errorAlert', 'Por favor ingresa un email válido');
                    return;
                }
                
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
                
                
            });

            // Input focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('focused');
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
        });

        // Utility functions
        function showAlert(alertId, message) {
            const alert = document.getElementById(alertId);
            alert.textContent = message;
            alert.style.display = 'block';
            
            // Hide other alerts
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(a => {
                if (a.id !== alertId) {
                    a.style.display = 'none';
                }
            });
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Demo credentials hint
        setTimeout(() => {
            const hint = document.createElement('div');
            hint.innerHTML = `
                <div style="position: fixed; bottom: 20px; right: 20px; background: rgba(26, 115, 232, 0.9); color: white; padding: 1rem; border-radius: 12px; font-size: 0.9rem; max-width: 300px; z-index: 1000; box-shadow: var(--shadow-hover);">
                    <strong>💡 Demo:</strong><br>
                    Email: admin@sacramentify.com<br>
                    Contraseña: admin123
                    <button onclick="this.parentElement.remove()" style="position: absolute; top: 5px; right: 8px; background: none; border: none; color: white; font-size: 1.2rem; cursor: pointer;">&times;</button>
                </div>
            `;
            document.body.appendChild(hint);
            
            // Auto remove after 10 seconds
            setTimeout(() => {
                if (hint.parentElement) {
                    hint.remove();
                }
            }, 10000
