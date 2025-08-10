<!doctype html>
<html lang="en">
<head>
    <title>GESTIÓN DE SACRAMENTOS</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- Dashboard CSS - Sacramentify -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet" />
    
    <!-- Google Fonts para mejor tipografía -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
@section('content')
<div class="container-fluid px-4">
    <!-- Header Mejorado -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="welcome-section bg-white rounded-4 shadow-sm border-0 p-4 p-md-5 position-relative overflow-hidden">
                <!-- Decorative Background Elements -->
                <div class="position-absolute top-0 end-0 opacity-10">
                    <i class="fas fa-church" style="font-size: 8rem; color: var(--bs-primary);"></i>
                </div>
                
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-7">
                        <div class="welcome-content position-relative z-index-2">
                            <div class="d-flex align-items-center mb-3">
                                <div class="welcome-avatar me-3">
                                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center">
                                        <i class="fas fa-user fa-lg"></i>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="display-6 fw-bold text-dark mb-1">
                                        Bienvenido/a, {{ Auth::user()->name }}
                                    </h1>
                                    <p class="text-muted mb-0 fs-5">Dashboard del Sistema de Gestión de Sacramentos</p>
                                </div>
                            </div>
                            
                            <div class="quick-stats d-flex flex-wrap gap-3 mt-4">
                                <div class="stat-badge bg-light rounded-pill px-3 py-2">
                                    <i class="fas fa-calendar-check text-success me-2"></i>
                                    <span class="fw-semibold">{{ date('d/m/Y') }}</span>
                                </div>
                                <div class="stat-badge bg-light rounded-pill px-3 py-2">
                                    <i class="fas fa-clock text-info me-2"></i>
                                    <span class="fw-semibold">{{ date('H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-5 mt-4 mt-md-0">
                        <div class="credits-card bg-gradient-primary text-white rounded-3 p-4 text-center position-relative">
                            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10">
                                <div class="bg-white rounded-circle position-absolute" 
                                     style="width: 100px; height: 100px; top: -30px; right: -30px;"></div>
                                <div class="bg-white rounded-circle position-absolute" 
                                     style="width: 60px; height: 60px; bottom: -20px; left: -20px;"></div>
                            </div>
                            <div class="position-relative z-index-2">
                                <i class="fas fa-coins fa-2x mb-3 opacity-75"></i>
                                <h3 class="fw-bold mb-1">{{ $userCredits->available_credits }}</h3>
                                <p class="mb-0 opacity-90">Créditos Disponibles</p>
                                @if($userCredits->available_credits <= 5)
                                    <a href="{{ route('payments.index') }}" 
                                       class="btn btn-light btn-sm mt-2 text-primary fw-semibold">
                                        <i class="fas fa-plus me-1"></i>Comprar más
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                @if($userCredits->available_credits <= 5)
                    <div class="alert alert-warning border-0 rounded-3 mt-4 mb-0" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fa-lg"></i>
                            <div class="flex-grow-1">
                                <h6 class="alert-heading mb-1">⚠️ Créditos Bajos</h6>
                                @if($userCredits->available_credits == 0)
                                    <p class="mb-0">No tienes créditos disponibles para crear nuevas actas. 
                                    <a href="{{ route('payments.index') }}" class="alert-link fw-semibold">Compra más créditos aquí</a>.</p>
                                @else
                                    <p class="mb-0">Te quedan solo {{ $userCredits->available_credits }} créditos. 
                                    <a href="{{ route('payments.index') }}" class="alert-link fw-semibold">Considera comprar más</a>.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas Principales -->
    <div class="stats-grid-container mb-4">
        <div class="stats-card bg-gradient-primary">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-scroll"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalActas) }}</h3>
                    <p>Total Actas</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-success">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalPersonas) }}</h3>
                    <p>Personas</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-info">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-church"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalErmitas) }}</h3>
                    <p>Ermitas</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-warning">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalSacerdotes) }}</h3>
                    <p>Sacerdotes</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-danger">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ number_format($totalUsuarios) }}</h3>
                    <p>Usuarios</p>
                </div>
            </div>
        </div>
        
        <div class="stats-card bg-gradient-secondary">
            <div class="stats-card-body simple-layout">
                <div class="stats-content-simple">
                    <div class="simple-main-line">
                        <span class="simple-number">{{ $thisMonth }}</span>
                        <span class="simple-percentage-inline">
                            @if($growth > 0)
                                +{{ number_format($growth, 1) }}%
                            @elseif($growth < 0)
                                {{ number_format($growth, 1) }}%
                            @else
                                0.0%
                            @endif
                        </span>
                    </div>
                    <div class="simple-label">Este Mes</div>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de Créditos -->
        <div class="stats-card {{ $userCredits->available_credits <= 5 ? 'bg-gradient-warning' : 'bg-gradient-info' }}">
            <div class="stats-card-body">
                <div class="stats-icon">
                    <i class="fas fa-coins"></i>
                </div>
                <div class="stats-content">
                    <h3>{{ $userCredits->available_credits }}</h3>
                    <p>Créditos</p>
                    @if($userCredits->available_credits <= 5)
                        <small class="text-warning-emphasis">¡Pocos créditos!</small>
                    @endif
                </div>
            </div>
            <div class="stats-card-footer">
                <a href="{{ route('payments.index') }}" class="text-decoration-none text-white-50">
                    <small><i class="fas fa-plus me-1"></i>Comprar más</small>
                </a>
            </div>
        </div>
    </div>

    <!-- Gráficos por Sacramento -->
    <div class="row mb-4">
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-pie"></i> Distribución por Sacramento</h5>
                </div>
                <div class="chart-body">
                    <canvas id="sacramentChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-6 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-line"></i> Tendencia Mensual</h5>
                </div>
                <div class="chart-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Detalladas -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-history"></i> Actividad Reciente</h5>
                </div>
                <div class="chart-body">
                    <div class="activity-list">
                        @foreach($recentActas as $acta)
                        <div class="activity-item">
                            <div class="activity-icon">
                                @if($acta->tipoActa && $acta->tipoActa->nombre == 'Bautizo')
                                    <i class="fas fa-baby text-primary"></i>
                                @elseif($acta->tipoActa && $acta->tipoActa->nombre == 'Matrimonio')
                                    <i class="fas fa-rings-wedding text-danger"></i>
                                @elseif($acta->tipoActa && $acta->tipoActa->nombre == 'Confirmación')
                                    <i class="fas fa-hand-paper text-warning"></i>
                                @else
                                    <i class="fas fa-scroll text-secondary"></i>
                                @endif
                            </div>
                            <div class="activity-content">
                                <h6>{{ $acta->tipoActa->nombre ?? 'Sacramento' }}</h6>
                                <p>{{ $acta->persona ? trim($acta->persona->nombre . ' ' . $acta->persona->paterno . ' ' . $acta->persona->materno) : 'Sin persona asignada' }}</p>
                                <small class="text-muted">
                                    <i class="fas fa-calendar"></i> {{ $acta->fecha ? \Carbon\Carbon::parse($acta->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                    @if($acta->ermita)
                                        • <i class="fas fa-church"></i> {{ $acta->ermita->nombre }}
                                    @endif
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 mb-4">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-star"></i> Top Ermitas</h5>
                </div>
                <div class="chart-body">
                    <div class="top-list">
                        @foreach($topErmitas as $index => $ermita)
                        <div class="top-item">
                            <div class="top-rank">{{ $index + 1 }}</div>
                            <div class="top-content">
                                <h6>{{ $ermita->nombre }}</h6>
                                <p>{{ $ermita->total }} registro{{ $ermita->total != 1 ? 's' : '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen por Año -->
    @if($yearlyStats->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-card">
                <div class="chart-header">
                    <h5><i class="fas fa-chart-bar"></i> Resumen por Año</h5>
                </div>
                <div class="chart-body">
                    <div class="yearly-stats">
                        @foreach($yearlyStats as $yearStat)
                        <div class="year-stat">
                            <h3>{{ $yearStat->year }}</h3>
                            <p>{{ number_format($yearStat->total) }} registros</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
</head>

<body>
</head>

<body>
    <!-- Sidebar Overlay para móviles -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <div class="sidebar" id="sidebar">
        <a href="{{ route('home') }}" id="dashboard-title">
            <i class="fas fa-church"></i> Gestión de Sacramentos
        </a>
        
        <div class="sidebar-menu-container">
            <ul>
                <li>
                <a href="#" data-bs-toggle="collapse" data-bs-target="#dropdown-Actas" class="active">
                    <i class="fas fa-file-alt"></i> Actas
                    <i class="fas fa-chevron-down float-end"></i>
                </a>
            <ul class="collapse show" id="dropdown-Actas">
            <li>
                <a href="{{ url('actas') }}">
                    <i class="fas fa-file-alt"></i> Todas las Actas
                </a>
            </li>
                </ul>
            </li>

                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#dropdown-otros">
                        <i class="fas fa-list"></i> Otros
                        <i class="fas fa-chevron-down float-end"></i>
                    </a>
                    <ul class="collapse" id="dropdown-otros">
                        <li><a href="{{url('personas')}}"><i class="fas fa-users"></i> Personas</a></li>
                        @if(auth()->user() && auth()->user()->is_admin)
                        <li><a href="{{url('municipios')}}"><i class="fas fa-map-marker-alt"></i> Municipios y Estados</a></li>
                        <li><a href="{{url('sacerdotes')}}"><i class="fas fa-user-tie"></i> Sacerdotes y Obispos</a></li>
                        <li><a href="{{url('ermitas')}}"><i class="fas fa-church"></i> Ermitas</a></li>
                        <li><a href="{{url('diocesis')}}"><i class="fas fa-cross"></i> Diocesis</a></li>
                        <li><a href="{{url('parroquias')}}"><i class="fas fa-place-of-worship"></i> Parroquias</a></li>
                        @endif
                        <li><a href="{{url('platicas')}}"><i class="fas fa-comments"></i> Platicas</a></li>
                        <li><a href="{{url('sacramentos')}}"><i class="fas fa-hands-praying"></i> Sacramentos</a></li>
                        @if(auth()->user() && auth()->user()->is_admin)
                        <li><a href="{{url('users')}}"><i class="fas fa-user-shield"></i> Usuarios</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="{{ url('calendario') }}">
                        <i class="fas fa-calendar-alt"></i> Calendario
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('payments.index') }}">
                        <i class="fas fa-credit-card"></i> Pagos y Créditos
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-area">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <button class="btn" id="sidebarToggle">
                    <i class="fas fa-bars text-white"></i>
                </button>
                <span class="navbar-brand ms-3" id="dashboard-brand">GESTIÓN DE SACRAMENTOS</span>

                <div class="ms-auto user-dropdown">
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="navbarDropdown" data-bs-toggle="dropdown">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="user-avatar me-2">
                            @else
                                <i class="fas fa-user-circle me-2"></i>
                            @endif
                            {{ auth()->user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end animate_animated animate_fadeIn">
                            
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('Cerrar Sesión') }}
                                </a>
                            </li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <main class="container-fluid mt-3 px-3 px-md-4">  
            <div class="row">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

     <!-- Scripts -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Chart.js for Dashboard Charts -->
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
     
     <!-- Bootstrap Modal Compatibility Fix - DEBE IR INMEDIATAMENTE DESPUÉS DE JQUERY -->
     <script>
     // Verificar y corregir la función modal ANTES de cargar Bootstrap
     if (typeof jQuery !== 'undefined') {
         console.log('jQuery cargado, configurando compatibilidad modal');
         
         // Crear un wrapper inmediato para compatibilidad con código antiguo
         jQuery.fn.modal = function(action) {
             console.log('Modal wrapper called with action:', action);
             return this.each(function() {
                 try {
                     var modalElement = this;
                     
                     // Intentar usar Bootstrap 5 nativo si está disponible
                     if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                         var modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                         
                         if (action === 'show') {
                             modal.show();
                         } else if (action === 'hide') {
                             modal.hide();
                         } else if (action === 'toggle') {
                             modal.toggle();
                         }
                     } else {
                         // Fallback para antes de que Bootstrap se cargue
                         console.warn('Bootstrap no disponible aún, usando fallback');
                         
                         if (action === 'show') {
                             modalElement.style.display = 'block';
                             modalElement.classList.add('show');
                             modalElement.setAttribute('aria-hidden', 'false');
                         } else if (action === 'hide') {
                             modalElement.style.display = 'none';
                             modalElement.classList.remove('show');
                             modalElement.setAttribute('aria-hidden', 'true');
                         }
                     }
                 } catch (error) {
                     console.error('Error al manejar modal:', error);
                 }
             });
         };
         
         // También definir la función modal globalmente como precaución
         window.modalCompatibility = jQuery.fn.modal;
     }
     
     // Manejo global de errores inmediato
     window.addEventListener('error', function(e) {
         if (e.error && e.error.message && e.error.message.includes('modal is not a function')) {
             console.warn('Error de modal interceptado:', e.error.message, 'en', e.filename, 'línea', e.lineno);
             e.preventDefault();
             return false;
         }
     });
     
     // Interceptor adicional para debugging
     window.addEventListener('unhandledrejection', function(e) {
         if (e.reason && e.reason.message && e.reason.message.includes('modal')) {
             console.warn('Promise rejection relacionada con modal:', e.reason.message);
             e.preventDefault();
         }
     });
     </script>
     
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
     
     <!-- Verificación post-Bootstrap -->
     <script>
     // Verificar que todo esté funcionando después de cargar Bootstrap
     $(document).ready(function() {
         console.log('Bootstrap cargado. Verificando compatibilidad modal...');
         
         // Verificar que Bootstrap Modal esté disponible
         if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
             console.log('Bootstrap 5 Modal API disponible');
         } else {
             console.error('Bootstrap Modal API no está disponible');
         }
         
         // Verificar que jQuery modal wrapper esté funcionando
         if (typeof $.fn.modal === 'function') {
             console.log('jQuery modal wrapper disponible');
         } else {
             console.error('jQuery modal wrapper no está disponible');
         }
         
         // Reemplazar cualquier uso problemático de modal en el DOM
         $('[data-bs-toggle="modal"]').off('click.modal').on('click.modal', function(e) {
             e.preventDefault();
             var target = $(this).attr('data-bs-target') || $(this).attr('href');
             if (target) {
                 var modalElement = document.querySelector(target);
                 if (modalElement) {
                     var modal = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                     modal.show();
                 }
             }
         });
     });
     </script>
     
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <!-- Select2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script><script>
$(document).ready(function(){
    try {
        if (!localStorage.getItem('bienvenidaMostrada')) {
            Swal.fire({
                title: '¡Bienvenido!',
                text: 'Sistema de Gestión de Sacramentos',
                icon: 'success',
                confirmButtonColor: '#2c3e50',
                timer: 2000,
                timerProgressBar: true
            });
            localStorage.setItem('bienvenidaMostrada', 'true');
        }
    } catch (error) {
        console.error("Error al acceder a localStorage: ", error);
    }

    // Variables para el sidebar responsive
    const sidebar = $('#sidebar');
    const sidebarOverlay = $('#sidebarOverlay');
    const contentArea = $('.content-area');
    const sidebarToggle = $('#sidebarToggle');

    // Funcionalidad mejorada para el toggle de la barra lateral
    function toggleSidebar() {
        const windowWidth = $(window).width();
        
        if (windowWidth < 992) {
            // Comportamiento móvil
            sidebar.toggleClass('show');
            sidebarOverlay.toggleClass('show');
            $('body').toggleClass('sidebar-open', sidebar.hasClass('show'));
        } else {
            // Comportamiento desktop
            sidebar.toggleClass('hidden');
            contentArea.toggleClass('sidebar-hidden');
            localStorage.setItem('sidebarState', sidebar.hasClass('hidden'));
        }
    }

    // Event listeners para el sidebar
    sidebarToggle.on('click', function(e) {
        e.preventDefault();
        toggleSidebar();
    });

    // Cerrar sidebar en móvil al hacer clic en overlay
    sidebarOverlay.on('click', function() {
        sidebar.removeClass('show');
        sidebarOverlay.removeClass('show');
        $('body').removeClass('sidebar-open');
    });

    // Cerrar sidebar en móvil al hacer clic en un enlace
    sidebar.find('a:not([data-bs-toggle])').on('click', function() {
        if ($(window).width() < 992) {
            sidebar.removeClass('show');
            sidebarOverlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        }
    });

    // Mejorar scroll del sidebar cuando se abren/cierran elementos
    sidebar.find('[data-bs-toggle="collapse"]').on('shown.bs.collapse', function() {
        const menuContainer = $('.sidebar-menu-container');
        const expandedElement = $(this).closest('li');
        
        // Scroll suave hacia el elemento expandido si es necesario
        setTimeout(() => {
            const elementTop = expandedElement.position().top;
            const containerHeight = menuContainer.height();
            const scrollTop = menuContainer.scrollTop();
            
            if (elementTop > containerHeight + scrollTop - 100) {
                menuContainer.animate({
                    scrollTop: elementTop - 50
                }, 300);
            }
        }, 150);
    });

    // Scroll al inicio cuando se colapsan elementos
    sidebar.find('[data-bs-toggle="collapse"]').on('hidden.bs.collapse', function() {
        const menuContainer = $('.sidebar-menu-container');
        if (menuContainer.scrollTop() > 0) {
            menuContainer.animate({
                scrollTop: Math.max(0, menuContainer.scrollTop() - 100)
            }, 200);
        }
    });

    // Restaurar estado de la barra lateral en desktop
    function initializeSidebarState() {
        const windowWidth = $(window).width();
        
        if (windowWidth >= 992) {
            // Desktop: restaurar estado guardado
            if (localStorage.getItem('sidebarState') === 'true') {
                sidebar.addClass('hidden');
                contentArea.addClass('sidebar-hidden');
            }
        } else {
            // Móvil: asegurar que esté oculto por defecto
            sidebar.removeClass('show hidden');
            contentArea.removeClass('sidebar-hidden');
            sidebarOverlay.removeClass('show');
            $('body').removeClass('sidebar-open');
        }
    }

    // Manejar cambios de tamaño de ventana
    $(window).on('resize', function() {
        initializeSidebarState();
    });

    // Inicializar estado del sidebar
    initializeSidebarState();

    // Actualización dinámica del título
    sidebar.find('ul li a').on('click', function() {
        const selectedOption = $(this).text().trim();
        if (!['Gestión de Sacramentos', 'Otros'].includes(selectedOption)) {
            $('#dashboard-brand').text(selectedOption);
            localStorage.setItem('selectedTitle', selectedOption);
        }
    });

    // Animaciones mejoradas para las tarjetas
    $('.stats-card, .chart-card').each(function(index) {
        $(this).css({
            'animation-delay': (index * 0.1) + 's',
            'animation-fill-mode': 'both'
        });
    });

    // Hover effects mejorados
    $('.stats-card').hover(
        function() { 
            $(this).addClass('animate__pulse');
            $(this).find('.stats-icon').addClass('animate__bounceIn');
        },
        function() { 
            $(this).removeClass('animate__pulse');
            $(this).find('.stats-icon').removeClass('animate__bounceIn');
        }
    );

    // Inicializar tooltips con configuración mejorada
    $('[data-bs-toggle="tooltip"]').each(function() {
        new bootstrap.Tooltip(this, {
            animation: true,
            delay: { show: 500, hide: 100 },
            html: true
        });
    });

    // Resaltar el elemento activo del menú
    const currentLocation = window.location.href;
    sidebar.find('a').each(function() {
        if (this.href === currentLocation) {
            $(this).addClass('active');
            $(this).closest('.collapse').addClass('show');
        }
    });

    // Smooth scroll para navegación interna
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 600, 'easeInOutCubic');
        }
    });

    // Loading mejorado para peticiones AJAX
    $(document).ajaxStart(function() {
        Swal.fire({
            title: 'Cargando...',
            html: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)'
        });
    }).ajaxStop(function() {
        Swal.close();
    });

    // Comportamiento responsive mejorado
    function handleResponsiveChanges() {
        const windowWidth = $(window).width();
        
        if (windowWidth < 576) {
            // Extra small devices
            $('.stats-card').addClass('mb-2');
            $('.welcome-section').addClass('px-2');
        } else if (windowWidth < 768) {
            // Small devices
            $('.stats-card').removeClass('mb-2').addClass('mb-3');
            $('.welcome-section').removeClass('px-2');
        } else {
            // Medium and larger devices
            $('.stats-card').removeClass('mb-2 mb-3');
            $('.welcome-section').removeClass('px-2');
        }
    }

    // Ejecutar al cargar y redimensionar
    handleResponsiveChanges();
    $(window).on('resize', handleResponsiveChanges);

    // Animaciones de entrada para elementos
    function animateOnScroll() {
        $('.chart-card, .stats-card').each(function() {
            const elementTop = $(this).offset().top;
            const elementBottom = elementTop + $(this).outerHeight();
            const viewportTop = $(window).scrollTop();
            const viewportBottom = viewportTop + $(window).height();
            
            if (elementBottom > viewportTop && elementTop < viewportBottom) {
                $(this).addClass('animate__animated animate__fadeInUp');
            }
        });
    }

    // Ejecutar animaciones en scroll
    $(window).on('scroll', animateOnScroll);
    animateOnScroll(); // Ejecutar inmediatamente

    // Dropdown menu animations mejoradas
    $('.dropdown').on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first()
            .addClass('animate__animated animate__fadeIn animate__faster');
    });

    // Form submission con loading mejorado
    $('form').submit(function() {
        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        
        // Deshabilitar botón y mostrar loading
        submitButton.prop('disabled', true);
        submitButton.html('<span class="spinner-border spinner-border-sm me-2" role="status"></span>Procesando...');
        
        // Mostrar SweetAlert
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espere mientras se procesa su solicitud',
            allowOutsideClick: false,
            showConfirmButton: false,
            background: 'rgba(255, 255, 255, 0.95)',
            backdrop: 'rgba(0, 0, 0, 0.4)',
            willOpen: () => Swal.showLoading()
        });
    });

    // Prevenir errores de performance en dispositivos móviles
    if ('ontouchstart' in window) {
        // Es un dispositivo táctil, reducir algunas animaciones
        $('.stats-card').css('transition', 'transform 0.2s ease');
    }
});

$('#miModal').modal({
    backdrop: 'static', // Evita cerrar el modal al hacer clic fuera de él
    keyboard: false     // Desactiva cierre con la tecla Escape
});

// Dashboard Charts Initialization
$(document).ready(function() {
    // Chart Colors - Paleta profesional y minimalista
    const chartColors = {
        primary: '#2c3e50',
        success: '#27ae60',
        info: '#3498db',
        warning: '#f39c12',
        danger: '#e74c3c',
        secondary: '#95a5a6',
        light: '#ecf0f1',
        dark: '#2c3e50'
    };

    // Sacramento Distribution Chart (Pie Chart)
    const sacramentCtx = document.getElementById('sacramentChart');
    if (sacramentCtx) {
        new Chart(sacramentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Bautizos', 'Matrimonios', 'Confirmaciones'],
                datasets: [{
                    data: [{{ $bautizoCount }}, {{ $matrimonioCount }}, {{ $confirmacionCount }}],
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.danger,
                        chartColors.info
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // Monthly Trend Chart (Line Chart)
    const monthlyCtx = document.getElementById('monthlyChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyStats['labels']) !!},
                datasets: [{
                    label: 'Registros por Mes',
                    data: {!! json_encode($monthlyStats['data']) !!},
                    borderColor: chartColors.primary,
                    backgroundColor: chartColors.primary + '20',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }
});
</script>
</body>
</html>