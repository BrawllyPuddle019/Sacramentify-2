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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Dashboard CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    
    <style>
        /* Compatibilidad con estilos específicos del layout */
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Select2 Customization */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 8px;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .select2-results__option--highlighted {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        /* Fix para el título del sidebar */
        .sidebar .dashboard-title {
            color: var(--text-light) !important;
            font-size: 1.4rem !important;
            font-weight: 700 !important;
            padding: 2rem 1.5rem 1.5rem !important;
            display: block !important;
            text-decoration: none !important;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1) !important;
            background: rgba(255, 255, 255, 0.05) !important;
            transition: background 0.3s ease !important;
        }

        .sidebar .dashboard-title:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            color: var(--text-light) !important;
            text-decoration: none !important;
        }

        .sidebar .dashboard-title:focus {
            color: var(--text-light) !important;
            text-decoration: none !important;
        }

        .sidebar .dashboard-title i {
            margin-right: 12px !important;
            font-size: 1.2em !important;
        }

        /* Fix para el dropdown de "Otros" */
        .sidebar-menu-container {
            max-height: calc(100vh - 200px) !important;
            overflow-y: auto !important;
            overflow-x: hidden !important;
        }

        .sidebar-menu .collapse {
            transition: all 0.3s ease !important;
        }

        .sidebar-menu .collapse.show {
            animation: none !important;
        }

        #dropdown-otros {
            max-height: 500px !important;
            overflow-y: auto !important;
        }

        #dropdown-otros.collapsing {
            transition: height 0.35s ease !important;
        }

        /* Evitar conflictos con scrollbars */
        .sidebar-menu li ul li {
            white-space: nowrap !important;
        }

        .sidebar-menu li ul li a {
            padding: 8px 15px 8px 40px !important;
            font-size: 0.9rem !important;
        }
    </style>
    
    @stack('styles')
</head>

<body>
    <div class="sidebar">
        <a href="{{ route('home') }}" id="dashboard-title" class="dashboard-title">
            <i class="fas fa-church"></i> Gestión de Sacramentos
        </a>
        
        <div class="sidebar-menu-container">
            <ul class="sidebar-menu">
                <li>
                    <a href="#" data-bs-toggle="collapse" data-bs-target="#dropdown-Actas">
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
                        <!-- Elementos disponibles para todos -->
                        <li><a href="{{url('personas')}}"><i class="fas fa-users"></i> Personas</a></li>
                        <li><a href="{{url('platicas')}}"><i class="fas fa-comments"></i> Platicas</a></li>
                        <li><a href="{{url('sacramentos')}}"><i class="fas fa-hand-holding-heart"></i> Sacramentos</a></li>
                        
                        <!-- Elementos solo para administradores -->
                        @if(auth()->user() && auth()->user()->is_admin)
                        <li><hr class="dropdown-divider"></li>
                        <li><small class="text-muted px-3">Administración</small></li>
                        <li><a href="{{url('municipios')}}"><i class="fas fa-map-marker-alt"></i> Municipios y Estados</a></li>
                        <li><a href="{{url('diocesis')}}"><i class="fas fa-church"></i> Diocesis</a></li>
                        <li><a href="{{url('parroquias')}}"><i class="fas fa-building"></i> Parroquias</a></li>
                        <li><a href="{{url('ermitas')}}"><i class="fas fa-place-of-worship"></i> Ermitas</a></li>
                        <li><a href="{{url('sacerdotes')}}"><i class="fas fa-cross"></i> Sacerdotes y Obispos</a></li>
                        <li><a href="{{url('users')}}"><i class="fas fa-user-cog"></i> Usuarios</a></li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="{{ route('calendario.index') }}">
                        <i class="fas fa-calendar-alt"></i> Calendario
                    </a>
                </li>

                <li>
                    <a href="{{ route('payments.index') }}">
                        <i class="fas fa-coins"></i> Mis Créditos
                        @if(auth()->check())
                            @php $userCredits = auth()->user()->getCredits(); @endphp
                            <span class="badge bg-primary float-end">{{ $userCredits->available_credits }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content-area">
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-left">
                    <button class="btn sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <span class="navbar-brand">GESTIÓN DE SACRAMENTOS</span>
                </div>

                <div class="navbar-right">
                    <div class="user-dropdown">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle user-menu-btn" type="button" id="navbarDropdown" data-bs-toggle="dropdown">
                                @if(auth()->user()->avatar)
                                    <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="user-avatar">
                                @else
                                    <i class="fas fa-user-circle"></i>
                                @endif
                                <span class="user-name">{{ auth()->user()->name }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
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
            </div>
        </nav>

        <main class="container mt-5">  
            <div class="row">
                <div class="col-md-12">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

     <!-- Scripts -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     
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
         
         // Guardar la función para uso posterior
         window.modalCompatibility = jQuery.fn.modal;
     }
     </script>
     
     <!-- Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
     
     <!-- Select2 JS -->
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     
     <!-- Google Maps API -->
     <!-- <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places&callback=initMap" async defer></script> -->
     
     <!-- Mapbox Configuration -->
     <script>
         // Configuración global de Mapbox - token directo
         window.MAPBOX_ACCESS_TOKEN = 'pk.eyJ1IjoiYWRyaWFuZ2FyY2lhMDE5IiwiYSI6ImNtZHR3aDgyZTE2dW8ybG9obGpxYjdla2UifQ.03Yqkk_SxLuEiVgmtJn9_A';
         console.log('Token configurado directamente:', window.MAPBOX_ACCESS_TOKEN);
     </script>
     
     <!-- SweetAlert2 -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Sidebar toggle functionality
    $('#sidebarToggle').click(function() {
        $('.sidebar').toggleClass('hidden');
        $('.content-area').toggleClass('sidebar-hidden');
    });

    // Auto-scroll para el sidebar cuando se expande/colapsa menús
    function autoScrollSidebar(targetElement) {
        const container = $('.sidebar-menu-container');
        const target = $(targetElement);
        
        if (target.length) {
            const containerHeight = container.height();
            const containerScrollTop = container.scrollTop();
            const targetOffset = target.position().top;
            const targetHeight = target.outerHeight();
            
            // Solo hacer scroll si el elemento no está completamente visible
            if (targetOffset < 0 || targetOffset + targetHeight > containerHeight) {
                const newScrollTop = containerScrollTop + targetOffset - (containerHeight / 2) + (targetHeight / 2);
                container.animate({
                    scrollTop: Math.max(0, newScrollTop)
                }, 300);
            }
        }
    }

    // Event listener para colapsar/expandir menús
    $('[data-bs-toggle="collapse"]').on('shown.bs.collapse', function () {
        autoScrollSidebar(this);
    });

    // Select2 initialization
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Loading indicator for ajax requests
    $(document).ajaxStart(function() {
        Swal.fire({
            title: 'Cargando...',
            html: 'Por favor espere',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading()
        });
    }).ajaxStop(function() {
        Swal.close();
    });

    // Responsive sidebar behavior
    function checkWidth() {
        if ($(window).width() < 768) {
            $('.sidebar').addClass('hidden');
            $('.content-area').addClass('sidebar-hidden');
        } else {
            $('.sidebar').removeClass('hidden');
            $('.content-area').removeClass('sidebar-hidden');
        }
    }
    $(window).on('load resize', checkWidth);

    // Dropdown menu animations
    $('.dropdown').on('show.bs.dropdown', function() {
        $(this).find('.dropdown-menu').first().addClass('animate_animated animate_fadeIn');
    });

    // Form submission loading indicator
    $('form').submit(function() {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espere mientras se procesa su solicitud',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => Swal.showLoading()
        });
    });
});

$('#miModal').modal({
    backdrop: 'static', // Evita cerrar el modal al hacer clic fuera de él
    keyboard: false     // Desactiva cierre con la tecla Escape
});

// Session timeout handling - Logout automático por inactividad
let sessionTimeout;
const SESSION_TIMEOUT = 30 * 60 * 1000; // 30 minutos en milisegundos

function resetSessionTimeout() {
    clearTimeout(sessionTimeout);
    sessionTimeout = setTimeout(function() {
        Swal.fire({
            title: 'Sesión Expirada',
            text: 'Su sesión ha expirado por inactividad (30 minutos). Será redirigido al login.',
            icon: 'warning',
            confirmButtonColor: '#2c3e50',
            allowOutsideClick: false,
            showCancelButton: false,
            confirmButtonText: 'Entendido'
        }).then(() => {
            // Hacer logout del usuario y luego redirigir
            fetch('{{ route("logout") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(() => {
                window.location.href = '{{ route("login") }}';
            }).catch(() => {
                // Si falla el logout, forzar redirección igual
                window.location.href = '{{ route("login") }}';
            });
        });
    }, SESSION_TIMEOUT);
}

// Detectar actividad del usuario (movimiento del mouse, teclas, clics, scroll)
$(document).on('mousemove keypress click scroll touchstart', resetSessionTimeout);

// Inicializar el timeout al cargar la página
resetSessionTimeout();

console.log('Sistema de logout automático iniciado: 30 minutos de inactividad');

// Fix para el dropdown "Otros" del sidebar
$(document).ready(function() {
    // Evitar conflictos con el dropdown "Otros"
    $('#dropdown-otros').on('show.bs.collapse', function () {
        console.log('Abriendo dropdown Otros');
    });
    
    $('#dropdown-otros').on('hide.bs.collapse', function () {
        console.log('Cerrando dropdown Otros');
    });
    
    // Prevenir que el dropdown se cierre accidentalmente
    $('#dropdown-otros').on('click', function(e) {
        e.stopPropagation();
    });
    
    // Manejar el toggle del dropdown "Otros" de manera más estable
    $('a[data-bs-target="#dropdown-otros"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('data-bs-target'));
        
        if (target.hasClass('show')) {
            target.collapse('hide');
        } else {
            target.collapse('show');
        }
    });
});
</script>

@yield('scripts')
@stack('scripts')
</body>
</html>
