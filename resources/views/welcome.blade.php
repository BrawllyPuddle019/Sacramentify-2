<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <title>SACRAMENTIFY - Sistema de Gestión de Sacramentos</title>

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
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        /* Navbar Styles */
        .navbar {
            background: rgba(7, 33, 70, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            z-index: 1000;
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
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><path d="M0,1000V0h1000L0,1000z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
            color: var(--white);
            max-width: 900px;
            padding: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        .hero h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 700;
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .hero .subtitle {
            font-size: clamp(1.1rem, 2vw, 1.3rem);
            margin-bottom: 2rem;
            opacity: 0.9;
            font-weight: 300;
        }

        .cta-button {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(45deg, var(--gold-color), #f4d03f);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow);
            border: none;
        }

        .cta-button:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
            color: var(--primary-color);
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: var(--light-blue);
            position: relative;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .section-title p {
            font-size: 1.2rem;
            color: var(--text-light);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--white);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            height: 100%;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--gold-color));
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-hover);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(45deg, var(--accent-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--white);
            font-size: 1.5rem;
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 1rem;
            line-height: 1.7;
        }

        /* Video Section */
        .video-section {
            padding: 80px 0;
            background: var(--white);
        }

        .video-container {
            position: relative;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-hover);
        }

        .video-container iframe {
            width: 100%;
            height: 500px;
            border: none;
        }

        /* Statistics Section */
        .stats-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: var(--white);
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gold-color);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: var(--white);
            padding: 40px 0 20px;
            text-align: center;
        }

        .footer-content {
            margin-bottom: 2rem;
        }

        .footer-logo {
            height: 40px;
            margin-bottom: 1rem;
        }

        .footer p {
            margin-bottom: 0;
            opacity: 0.8;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                min-height: 80vh;
                padding: 2rem 1rem;
            }

            .hero-content {
                padding: 1rem;
            }

            .video-container iframe {
                height: 300px;
            }

            .feature-card {
                margin-bottom: 2rem;
            }

            .stats-section {
                padding: 60px 0;
            }

            .stat-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 576px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero .subtitle {
                font-size: 1rem;
            }

            .section-title h2 {
                font-size: 1.8rem;
            }

            .feature-card {
                padding: 1.5rem;
            }

            .video-container iframe {
                height: 250px;
            }
        }

        /* Scroll Progress Bar */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--gold-color));
            z-index: 1001;
            transition: width 0.3s ease;
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
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>

    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <div class="navbar-brand">
                <img src="assets/logo.png" alt="SACRAMENTIFY" class="logo">
                <a class="site-name" href="#" style="text-decoration: none;">SACRAMENTIFY</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#caracteristicas">Características</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#demo">Demo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn" href="{{ route('login') }}" style="background: var(--gold-color); color: var(--primary-color); font-weight: 600;">
                            <i class="fas fa-sign-in-alt me-2"></i>Acceder
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="inicio" class="hero">
        <div class="hero-content">
            <h1 class="font-display">Bienvenido a SACRAMENTIFY</h1>
            <p class="subtitle">Sistema integral para la gestión digital de sacramentos católicos. Simplifica, organiza y moderniza la administración de tu parroquia.</p>
            <a href="#caracteristicas" class="cta-button">
                <i class="fas fa-rocket me-2"></i>Descubre más
            </a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="caracteristicas" class="features-section">
        <div class="container">
            <div class="section-title">
                <h2 class="font-display">¿Por qué elegir SACRAMENTIFY?</h2>
                <p>Una solución completa diseñada específicamente para las necesidades de la Iglesia Católica moderna</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-cross"></i>
                        </div>
                        <h3>Gestión de Sacramentos</h3>
                        <p>Administra bautismos, confirmaciones, matrimonios y más con un sistema intuitivo y seguro.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3>Búsqueda Avanzada</h3>
                        <p>Encuentra cualquier registro en segundos con filtros inteligentes y búsqueda por múltiples criterios.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Seguridad Garantizada</h3>
                        <p>Protección de datos con encriptación avanzada y copias de seguridad automáticas.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <h3>Certificados Digitales</h3>
                        <p>Genera certificados oficiales en formato PDF con sellos digitales y códigos QR de verificación.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Reportes y Estadísticas</h3>
                        <p>Obtén insights valiosos sobre la actividad sacramental de tu parroquia con reportes detallados.</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3>100% Responsive</h3>
                        <p>Accede desde cualquier dispositivo: computadora, tablet o móvil. Siempre disponible cuando lo necesites.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Demo Section -->
    <section id="demo" class="video-section">
        <div class="container">
            <div class="section-title">
                <h2 class="font-display">Ve SACRAMENTIFY en acción</h2>
                <p>Descubre cómo nuestro sistema puede transformar la gestión de tu parroquia</p>
            </div>
            
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/iQUzGSehkKQ?si=vmOSySY1_np_wMSq" allowfullscreen></iframe>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">0+</div>
                        <div class="stat-label">Parroquias</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">0+</div>
                        <div class="stat-label">Sacramentos Registrados</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Tiempo de Actividad</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Soporte Técnico</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <img src="assets/logo.png" alt="SACRAMENTIFY" class="footer-logo">
                <a class="site-name" href="#" style="text-decoration: none;">SACRAMENTIFY</a>
                <p>&copy; 2024 SACRAMENTIFY. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll Progress Bar
        window.addEventListener('scroll', function() {
            const scrollProgress = document.getElementById('scrollProgress');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const progress = (scrollTop / scrollHeight) * 100;
            scrollProgress.style.width = progress + '%';
        });

        // Navbar scroll effect
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.querySelector('.navbar');
            const logo = document.querySelector('.logo');
            const siteName = document.querySelector('.site-name');
            
            window.addEventListener('scroll', function() {
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
        });

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

        // Animate numbers in statistics section
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const statNumbers = entry.target.querySelectorAll('.stat-number');
                    statNumbers.forEach(num => {
                        const finalNumber = num.textContent;
                        num.textContent = '0';
                        animateNumber(num, finalNumber);
                    });
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.stats-section');
        if (statsSection) {
            observer.observe(statsSection);
        }

        function animateNumber(element, finalNumber) {
            const duration = 2000;
            const steps = 60;
            const stepDuration = duration / steps;
            let currentStep = 0;
            
            const numericValue = parseInt(finalNumber.replace(/[^\d]/g, ''));
            const suffix = finalNumber.replace(/[\d]/g, '');
            
            const timer = setInterval(() => {
                currentStep++;
                const progress = currentStep / steps;
                const currentNumber = Math.floor(numericValue * progress);
                element.textContent = currentNumber + suffix;
                
                if (currentStep >= steps) {
                    clearInterval(timer);
                    element.textContent = finalNumber;
                }
            }, stepDuration);
        }
    </script>
</body>
</html>

