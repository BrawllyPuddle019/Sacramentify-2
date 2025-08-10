<!doctype html>
<html lang="es">
<head>
    <title>Registro - Sacramentify</title>
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

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            position: relative;
        }
        .font-display { font-family: 'Playfair Display', serif; }

        /* Enhanced Animated Background */
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


        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed; top: 0; left: 0; width: 0%; height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--gold-color));
            z-index: 1001; transition: width 0.3s ease;
        }

        /* Navbar Styles - Matching Welcome Page */
        .navbar {
            background: rgba(7, 33, 70, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease; z-index: 1000;
        }
        .navbar.navbar-scrolled {
            background: rgba(7,33,70,0.98) !important;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
        }
        .navbar-brand { display: flex; align-items: center; gap: 15px;}
        .logo { height: 50px; width: auto; transition: all 0.3s;}
        .logo.img-small { height: 40px;}
        .site-name { font-size: 1.8rem; font-weight: 700; color: var(--white); text-decoration: none; transition: all 0.3s; font-family: 'Playfair Display', serif;}
        .site-name:hover { color: var(--gold-color);}
        .site-name.site-name-small { font-size: 1.5rem;}
        .nav-link {
            font-weight: 500; padding: 0.8rem 1.5rem !important;
            border-radius: 25px; transition: all 0.3s; margin-left: 10px;
            color: var(--white) !important;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
            color: var(--gold-color) !important;
        }

        /* Main Content */
        .main-container {
            position: relative; z-index: 10; min-height: 100vh; display: flex; align-items: center;
            padding: 100px 20px 40px;
        }
        .register-card {
            background: rgba(255,255,255,0.95); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2); border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25), 0 0 0 1px rgba(255,255,255,0.1);
            padding: 3rem 2.5rem; max-width: 460px; width: 100%; margin: 0 auto;
            animation: slideIn 0.8s ease-out; position: relative; overflow: hidden;
        }
        .register-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--gold-color), var(--primary-color));
            background-size: 300% 100%; animation: shimmer 3s linear infinite;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px) scale(0.95);}
            to   { opacity: 1; transform: translateY(0) scale(1);}
        }
        @keyframes shimmer {
            0% { background-position: -300% 0; }
            100% { background-position: 300% 0; }
        }
        .register-header { text-align: center; margin-bottom: 2.5rem;}
        .register-icon {
            width: 80px; height: 80px; background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem; color: var(--white); font-size: 2rem; box-shadow: var(--shadow); animation: pulse 2s ease-in-out infinite;
        }
        @keyframes pulse {
            0%,100% { transform: scale(1); box-shadow: var(--shadow);}
            50% { transform: scale(1.05); box-shadow: var(--shadow-hover);}
        }
        .register-title {
            font-size: 2.2rem; font-weight: 700; color: var(--text-dark); margin-bottom: 0.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
            font-family: 'Playfair Display', serif;
        }
        .register-subtitle { color: var(--text-light); font-size: 1.1rem; font-weight: 400;}

        .form-group { margin-bottom: 1.5rem; position: relative;}
        .form-label { font-weight: 500; color: var(--text-dark); margin-bottom: 0.5rem; font-size: 0.95rem; display: block;}
        .form-control {
            background: rgba(248,250,252,0.9);
            border: 2px solid var(--border-color); border-radius: 12px;
            padding: 1rem 1rem 1rem 3.5rem; font-size: 1rem; transition: all 0.3s; box-shadow: var(--shadow); width: 100%;
        }
        .form-control:focus {
            outline: none; border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(26,115,232,0.1);
            background: rgba(255,255,255,0.95); transform: translateY(-1px);
        }
        .input-icon {
            position: absolute; left: 1.2rem; top: 50%; transform: translateY(-50%);
            color: var(--text-light); transition: all 0.3s; z-index: 10; font-size: 1.1rem;
        }
        .form-control:focus + .input-icon {
            color: var(--accent-color); transform: translateY(-50%) scale(1.1);
        }

        /* Button Styles */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none; border-radius: 12px; padding: 1.2rem 2rem;
            font-weight: 600; font-size: 1.1rem; color: white; width: 100%;
            transition: all 0.3s; box-shadow: var(--shadow); position: relative; overflow: hidden; margin-top: 1rem;
        }
        .btn-primary::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.6s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px); box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
        }
        .btn-primary:hover::before { left: 100%; }
        .btn-primary:active { transform: translateY(0);}

        /* Register Link */
        .login-link {
            text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--border-color);
        }
        .login-link p { color: var(--text-light); margin-bottom: 0; font-size: 1rem;}
        .login-link a {
            color: var(--accent-color); text-decoration: none; font-weight: 600; transition: all 0.3s;
        }
        .login-link a:hover { color: var(--primary-color); text-decoration: underline;}

        /* Loading Animation */
        .loading { position: relative;}
        .loading .btn-text { opacity: 0;}
        .loading::after {
            content: ''; position: absolute; top: 50%; left: 50%; width: 20px; height: 20px; margin: -10px 0 0 -10px;
            border: 2px solid transparent; border-top: 2px solid #fff; border-radius: 50%; animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);}}

        .alert {
            border: none; border-radius: 12px; padding: 1.2rem; margin-bottom: 1.5rem; font-weight: 500; position: relative; border-left: 4px solid;
        }
        .alert-success {
            background: rgba(34,197,94,0.1); color: #166534; border-left-color: #22c55e;
        }
        .alert-danger {
            background: rgba(239,68,68,0.1); color: #991b1b; border-left-color: #ef4444;
        }

        @media (max-width: 768px) {
            .main-container { padding: 80px 15px 30px;}
            .register-card { padding: 2rem 1.5rem; border-radius: 20px;}
            .register-title { font-size: 1.8rem;}
            .register-subtitle { font-size: 1rem;}
            .register-icon { width: 70px; height: 70px; font-size: 1.8rem;}
            .form-control { padding: 0.9rem 0.9rem 0.9rem 3rem;}
            .input-icon { left: 1rem;}
        }
        @media (max-width: 576px) {
            .register-card { margin: 0 10px; padding: 1.5rem 1.2rem;}
            .register-title { font-size: 1.6rem;}
        }
        ::-webkit-scrollbar { width: 8px;}
        ::-webkit-scrollbar-track { background: #f1f1f1;}
        ::-webkit-scrollbar-thumb { background: var(--accent-color); border-radius: 4px;}
        ::-webkit-scrollbar-thumb:hover { background: var(--primary-color);}
    </style>
</head>

<body>
    <div class="scroll-progress" id="scrollProgress"></div>
    <div class="particles"></div>

    <!-- Navbar -->
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

    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
                    <div class="register-card">
                        <div class="register-header">
                            <div class="register-icon">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h1 class="register-title">Crear Cuenta</h1>
                            <p class="register-subtitle">Crea tu cuenta y únete a SACRAMENTIFY</p>
                        </div>

                        <!-- Error Messages -->
                        <div class="alert alert-danger" style="display: none;" id="errorAlert">
                            Por favor verifica los campos
                        </div>
                        <!-- Success Messages -->
                        <div class="alert alert-success" style="display: none;" id="successAlert">
                            ¡Cuenta creada correctamente!
                        </div>

                        <form action="{{ route('register') }}" method="post" id="registerForm">
                            @csrf
                            <div class="form-group">
                                <label class="form-label" for="name">Nombre completo</label>
                                <div class="position-relative">
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" required autocomplete="name" placeholder="Tu nombre completo" />
                                    <i class="input-icon fas fa-user"></i>
                                </div>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Correo electrónico</label>
                                <div class="position-relative">
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required autocomplete="email" placeholder="tu@email.com" />
                                    <i class="input-icon fas fa-envelope"></i>
                                </div>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password">Contraseña</label>
                                <div class="position-relative">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror" required autocomplete="new-password" placeholder="••••••••" />
                                    <i class="input-icon fas fa-lock"></i>
                                </div>
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" required autocomplete="new-password" placeholder="••••••••" />
                                    <i class="input-icon fas fa-check-circle"></i>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <span class="btn-text">Crear Cuenta</span>
                            </button>
                        </form>

                        <div class="login-link">
                            <p>¿Ya tienes una cuenta?
                                <a href="{{ route('login') }}">Inicia sesión aquí</a>
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
        // Scroll Progress Bar
        window.addEventListener('scroll', function () {
            const scrollProgress = document.getElementById('scrollProgress');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = (scrollTop / scrollHeight) * 100;
            scrollProgress.style.width = progress + '%';
        });

        // Navbar scroll effect
        document.addEventListener('DOMContentLoaded', function () {
            const navbar = document.querySelector('.navbar');
            const logo = document.querySelector('.logo');
            const siteName = document.querySelector('.site-name');
            window.addEventListener('scroll', function () {
                if (window.scrollY > 100) {
                    navbar.classList.add('navbar-scrolled');
                    logo.classList.add('img-small');
                    siteName.classList.add('site-name-small');
                } else {
                    navbar.classList.remove('navbar-scrolled');
                    logo.classList.remove('img-small');
                    siteName.classList.remove('site-name-small');
                }
            });

            // Create floating particles
            createParticles();

            // Form submission with loading state and simple validation
            document.getElementById('registerForm').addEventListener('submit', function (e) {
    // Validaciones JS opcionales:
    const submitBtn = document.getElementById('submitBtn');
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('password_confirmation').value;
    if (!name || !email || !password || !confirm) {
        e.preventDefault();
        showAlert('errorAlert', 'Por favor completa todos los campos');
        return;
    }
    if (!isValidEmail(email)) {
        e.preventDefault();
        showAlert('errorAlert', 'Por favor ingresa un email válido');
        return;
    }
    if (password.length < 8) {
        e.preventDefault();
        showAlert('errorAlert', 'La contraseña debe tener al menos 8 caracteres');
        return;
    }
    if (password !== confirm) {
        e.preventDefault();
        showAlert('errorAlert', 'Las contraseñas no coinciden');
        return;
    }
    // Si pasa todas las validaciones, permite el submit normal (Laravel se encarga del registro)
    submitBtn.classList.add('loading');
});


            // Input focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function () {
                    this.parentElement.classList.add('focused');
                });
                input.addEventListener('blur', function () {
                    this.parentElement.classList.remove('focused');
                    if (this.value.trim() !== '') {
                        this.classList.add('has-value');
                    } else {
                        this.classList.remove('has-value');
                    }
                });
            });
        });

        function createParticles() {
            const particlesContainer = document.querySelector('.particles');
            const particleCount = 20;
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const size = Math.random() * 6 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 8 + 's';
                particle.style.animationDuration = (4 + Math.random() * 4) + 's';
                particlesContainer.appendChild(particle);
            }
        }
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
    </script>
</body>
</html>



