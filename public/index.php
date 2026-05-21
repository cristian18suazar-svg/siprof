<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPROF | Sistema Profesional de Gestión Fincaria</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="../img/logo.jpg">

    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        }
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <style>
        .hero-pattern {
            background-color: #14532d;
            background-image: linear-gradient(rgba(20, 83, 45, 0.75), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 antialiased selection:bg-primary-500 selection:text-white font-sans">

    <!-- Navigation -->
    <nav class="glass-nav fixed w-full z-50 transition-all duration-300 shadow-sm" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3 cursor-pointer">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <span class="font-bold text-3xl tracking-tight text-gray-900">SIPROF</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#inicio" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Inicio</a>
                    <a href="#beneficios" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Beneficios</a>
                    <a href="#modulos" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Módulos</a>
                    <a href="#nosotros" class="text-gray-600 hover:text-primary-600 font-medium transition-colors">Nosotros</a>
                    
                    <a href="desarrollador.php" class="flex items-center gap-2 text-gray-600 hover:text-primary-600 font-medium transition-colors border border-gray-200 hover:border-primary-400 px-4 py-2 rounded-xl">
                        <i class="fas fa-code text-sm"></i> Desarrollador
                    </a>

                    <a href="../views/usuarios/login.php" class="group relative px-6 py-2.5 font-semibold text-white rounded-xl bg-gradient-to-r from-primary-600 to-primary-500 overflow-hidden shadow-lg shadow-primary-500/30 transition-all hover:scale-105 hover:shadow-primary-500/50">
                        <span class="relative z-10 flex items-center gap-2">
                            Ingresar <i class="fas fa-arrow-right text-sm transition-transform group-hover:translate-x-1"></i>
                        </span>
                        <div class="absolute inset-0 h-full w-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300"></div>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button onclick="document.getElementById('mobileMenu').classList.toggle('hidden')" class="text-gray-600 hover:text-primary-600 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden md:hidden border-t border-gray-100 bg-white/95 backdrop-blur-md px-4 py-4 space-y-2">
            <a href="#inicio"     class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-medium transition-colors">Inicio</a>
            <a href="#beneficios" class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-medium transition-colors">Beneficios</a>
            <a href="#modulos"    class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-medium transition-colors">Módulos</a>
            <a href="#nosotros"   class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-medium transition-colors">Nosotros</a>
            <a href="desarrollador.php" class="block px-4 py-2.5 rounded-xl text-gray-700 hover:bg-primary-50 hover:text-primary-600 font-medium transition-colors">Desarrollador</a>
            <a href="../views/usuarios/login.php" class="block px-4 py-2.5 rounded-xl bg-primary-600 text-white font-bold text-center hover:bg-primary-700 transition-colors">Ingresar</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="inicio" class="hero-pattern min-h-screen flex items-center relative overflow-hidden pt-20">
        <!-- Decorative background elements -->
        <div class="absolute top-1/4 left-10 w-72 h-72 bg-primary-500/30 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-1/3 right-10 w-72 h-72 bg-yellow-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white space-y-8">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sm font-medium shadow-xl">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        Sistema de Gestión Agrícola Profesional
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-extrabold leading-tight">
                        Cultiva el éxito de tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-primary-500">finca</span>
                    </h1>
                    
                    <p class="text-lg text-gray-300 max-w-xl leading-relaxed font-light">
                        SIPROF es la plataforma integral diseñada para modernizar la administración agrícola. Controla inventarios, monitorea producción y gestiona a tu personal desde un solo lugar con tecnología de punta.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="../views/usuarios/login.php" class="px-8 py-4 bg-primary-500 hover:bg-primary-600 text-white rounded-xl font-bold text-center transition-all shadow-lg shadow-primary-500/40 hover:-translate-y-1">
                            Comenzar Ahora
                        </a>
                        <a href="#modulos" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/20 text-white rounded-xl font-bold text-center transition-all hover:-translate-y-1">
                            Explorar Funciones
                        </a>
                    </div>
                </div>
                
                <div class="hidden lg:block relative perspective-1000">
                    <!-- Glass Dashboard Mockup -->
                    <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-8 shadow-2xl transform rotate-3 hover:rotate-0 transition-transform duration-700 ease-out">
                        <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-4">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="text-white/60 text-sm font-mono tracking-wider">Resumen SIPROF</div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Mock chart bars -->
                            <div class="flex items-end gap-4 h-40 border-b border-white/10 pb-4">
                                <div class="w-1/5 bg-gradient-to-t from-primary-600 to-primary-400 rounded-t-lg h-[40%] hover:h-[45%] transition-all"></div>
                                <div class="w-1/5 bg-gradient-to-t from-primary-600 to-primary-400 rounded-t-lg h-[65%] relative group hover:h-[70%] transition-all">
                                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 bg-white text-gray-900 text-xs font-bold py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity shadow-lg">+15%</div>
                                </div>
                                <div class="w-1/5 bg-gradient-to-t from-primary-600 to-primary-400 rounded-t-lg h-[30%] hover:h-[35%] transition-all"></div>
                                <div class="w-1/5 bg-gradient-to-t from-primary-600 to-primary-400 rounded-t-lg h-[80%] hover:h-[85%] transition-all"></div>
                                <div class="w-1/5 bg-gradient-to-t from-green-400 to-green-300 rounded-t-lg h-[100%] shadow-[0_0_20px_rgba(74,222,128,0.4)]"></div>
                            </div>
                            
                            <!-- Mock stat cards -->
                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <div class="text-white/60 text-sm mb-1"><i class="fas fa-seedling mr-2"></i>Cosecha Total</div>
                                    <div class="text-white font-bold text-2xl">4,250 kg</div>
                                </div>
                                <div class="bg-white/5 border border-white/10 rounded-xl p-4 backdrop-blur-sm">
                                    <div class="text-white/60 text-sm mb-1"><i class="fas fa-chart-line mr-2"></i>Eficiencia</div>
                                    <div class="text-green-400 font-bold text-2xl flex items-center gap-2">
                                        94% <i class="fas fa-arrow-trend-up text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce">
            <a href="#beneficios" class="w-12 h-12 flex items-center justify-center rounded-full bg-white/10 text-white backdrop-blur-sm border border-white/20 hover:bg-white/20 transition-colors shadow-lg">
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Beneficios Section -->
    <section id="beneficios" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-primary-600 font-bold tracking-widest uppercase text-sm mb-3">¿Por qué elegir SIPROF?</h2>
                <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">Lleva tu finca al siguiente nivel</h3>
                <p class="text-gray-600 text-xl font-light">Reemplazamos los cuadernos y hojas de cálculo por una plataforma intuitiva que te da control total y visibilidad en tiempo real.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Benefit 1 -->
                <div class="bg-gray-50 rounded-3xl p-10 border border-gray-100 hover:shadow-2xl hover:border-primary-200 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:scale-110 transition-transform group-hover:bg-primary-50 text-primary-600">
                        <i class="fas fa-chart-pie text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Decisiones con Datos</h4>
                    <p class="text-gray-600 leading-relaxed">Obtén reportes detallados y gráficos en tiempo real sobre la productividad y los gastos de tu operación agrícola para maximizar ganancias.</p>
                </div>

                <!-- Benefit 2 -->
                <div class="bg-gray-50 rounded-3xl p-10 border border-gray-100 hover:shadow-2xl hover:border-primary-200 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:scale-110 transition-transform group-hover:bg-primary-50 text-primary-600">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Ahorro de Tiempo</h4>
                    <p class="text-gray-600 leading-relaxed">Automatiza registros y centraliza la información para reducir horas de trabajo administrativo y enfocarte en lo que de verdad importa: el campo.</p>
                </div>

                <!-- Benefit 3 -->
                <div class="bg-gray-50 rounded-3xl p-10 border border-gray-100 hover:shadow-2xl hover:border-primary-200 transition-all duration-300 group hover:-translate-y-2">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-8 group-hover:scale-110 transition-transform group-hover:bg-primary-50 text-primary-600">
                        <i class="fas fa-shield-halved text-3xl"></i>
                    </div>
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Seguridad Total</h4>
                    <p class="text-gray-600 leading-relaxed">Mantén un registro exacto de quién hace qué. Controla el acceso a la información confidencial y evita pérdidas o descuadres en el inventario.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Módulos Principales -->
    <section id="modulos" class="py-24 bg-gray-50 relative overflow-hidden border-y border-gray-200">
        <!-- Decoraciones -->
        <div class="absolute right-0 top-0 w-96 h-96 bg-primary-200 rounded-full blur-3xl opacity-30 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-96 h-96 bg-green-200 rounded-full blur-3xl opacity-30 translate-y-1/3 -translate-x-1/3 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div class="max-w-2xl">
                    <h2 class="text-primary-600 font-bold tracking-widest uppercase text-sm mb-3">Herramientas Integradas</h2>
                    <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900">Todo lo que necesitas en una sola plataforma</h3>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Modulo Produccion -->
                <div class="group relative bg-white rounded-3xl p-10 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-primary-50 to-transparent rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125"></div>
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-primary-500/30 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="fas fa-tractor text-3xl"></i>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Gestión de Producción</h4>
                    <p class="text-gray-600 mb-8 font-light leading-relaxed">
                        Registra cada etapa del ciclo de cultivo o cría. Monitorea rendimientos, fechas clave y obtén estimaciones precisas de tus futuras cosechas.
                    </p>
                    
                    <ul class="space-y-3 text-sm text-gray-700 mb-8 font-medium">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-primary-500 text-lg"></i> Registro de cosechas y ciclos</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-primary-500 text-lg"></i> Administración de lotes y áreas</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-primary-500 text-lg"></i> Historial productivo detallado</li>
                    </ul>
                </div>

                <!-- Modulo Inventario -->
                <div class="group relative bg-white rounded-3xl p-10 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-blue-50 to-transparent rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125"></div>
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-blue-500/30 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="fas fa-boxes-stacked text-3xl"></i>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Control de Inventario</h4>
                    <p class="text-gray-600 mb-8 font-light leading-relaxed">
                        Administra insumos, agroquímicos, herramientas y productos finales. Recibe alertas de stock bajo y lleva un control financiero exacto.
                    </p>
                    
                    <ul class="space-y-3 text-sm text-gray-700 mb-8 font-medium">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500 text-lg"></i> Control de entradas y salidas</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500 text-lg"></i> Alertas automáticas de stock</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-blue-500 text-lg"></i> Trazabilidad de herramientas</li>
                    </ul>
                </div>

                <!-- Modulo Trabajadores -->
                <div class="group relative bg-white rounded-3xl p-10 shadow-sm hover:shadow-xl transition-all duration-500 overflow-hidden border border-gray-100">
                    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-orange-50 to-transparent rounded-bl-full -z-10 transition-transform duration-500 group-hover:scale-125"></div>
                    
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center text-white mb-8 shadow-lg shadow-orange-500/30 transform group-hover:-translate-y-2 transition-transform duration-300">
                        <i class="fas fa-users-gear text-3xl"></i>
                    </div>
                    
                    <h4 class="text-2xl font-bold text-gray-900 mb-4">Gestión de Personal</h4>
                    <p class="text-gray-600 mb-8 font-light leading-relaxed">
                        Asigna tareas diarias, controla asistencias y calcula pagos. Organiza a tu equipo de trabajo para maximizar la eficiencia operativa en campo.
                    </p>
                    
                    <ul class="space-y-3 text-sm text-gray-700 mb-8 font-medium">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-orange-500 text-lg"></i> Asignación y seguimiento de tareas</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-orange-500 text-lg"></i> Registro de jornales y asistencia</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-orange-500 text-lg"></i> Análisis de rendimiento por operario</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Nosotros Section -->
    <section id="nosotros" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="relative order-2 lg:order-1 mt-12 lg:mt-0">
                    <div class="aspect-[4/5] rounded-[2.5rem] overflow-hidden shadow-2xl relative z-10">
                        <img src="https://images.unsplash.com/photo-1586771107445-d3ca888129ff?q=80&w=1000&auto=format&fit=crop" alt="Agricultor usando tecnología" class="w-full h-full object-cover hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-10 text-white w-full">
                            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-full bg-primary-500 flex items-center justify-center shadow-lg">
                                        <i class="fas fa-seedling text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xl">Compromiso Agrícola</p>
                                        <p class="text-white/80">Tecnología con propósito</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Elementos decorativos -->
                    <div class="absolute -top-8 -right-8 w-40 h-40 border-4 border-primary-200 rounded-[2rem] -z-0"></div>
                    <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-primary-50 rounded-[2rem] -z-0"></div>
                </div>

                <div class="space-y-12 order-1 lg:order-2">
                    <div>
                        <h2 class="text-primary-600 font-bold tracking-widest uppercase text-sm mb-3">Nuestra Identidad</h2>
                        <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6">Uniendo la tradición del campo con la innovación</h3>
                        <p class="text-gray-600 text-xl font-light leading-relaxed">
                            Nacimos de la necesidad real de los productores por tener herramientas que se adapten a su día a día. <strong class="font-semibold text-gray-900">SIPROF</strong> no es solo un software, es el aliado estratégico para el crecimiento sustentable de tu agroempresa.
                        </p>
                    </div>

                    <div class="space-y-8">
                        <div class="flex gap-6 p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-14 h-14 bg-white shadow-sm rounded-xl flex items-center justify-center text-primary-600">
                                    <i class="fas fa-bullseye text-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-3">Nuestra Misión</h4>
                                <p class="text-gray-600 leading-relaxed">Proveer una plataforma tecnológica accesible y robusta que permita a los productores administrar sus fincas de manera eficiente, optimizando recursos operativos y maximizando utilidades.</p>
                            </div>
                        </div>

                        <div class="flex gap-6 p-6 bg-gray-50 rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-14 h-14 bg-white shadow-sm rounded-xl flex items-center justify-center text-primary-600">
                                    <i class="fas fa-eye text-2xl"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-2xl font-bold text-gray-900 mb-3">Nuestra Visión</h4>
                                <p class="text-gray-600 leading-relaxed">Ser el sistema de gestión agrícola líder en la región, impulsando la digitalización integral del campo y promoviendo prácticas sostenibles a través del análisis inteligente de datos.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-primary-900"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1592982537447-6f2b6a0b9d90?q=80&w=1920&auto=format&fit=crop')] mix-blend-overlay opacity-20 bg-cover bg-center"></div>
        
        <div class="max-w-4xl mx-auto px-4 relative z-10 text-center text-white">
            <h2 class="text-4xl md:text-6xl font-extrabold mb-8">¿Listo para transformar tu finca?</h2>
            <p class="text-xl md:text-2xl text-primary-100 mb-12 max-w-3xl mx-auto font-light">
                Únete a la nueva generación de productores que ya están mejorando su rentabilidad y organización con SIPROF.
            </p>
            <a href="../views/usuarios/login.php" class="inline-flex items-center gap-3 px-10 py-5 bg-white text-primary-900 font-bold text-xl rounded-xl shadow-2xl hover:bg-gray-50 hover:scale-105 transition-all duration-300">
                Ingresar al Sistema <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 pt-20 pb-10 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center text-white">
                            <i class="fas fa-leaf text-2xl"></i>
                        </div>
                        <span class="font-bold text-3xl tracking-tight text-white">SIPROF</span>
                    </div>
                    <p class="text-gray-400 max-w-md mb-8 leading-relaxed text-lg font-light">
                        Sistema de Producción Fincaria profesional. Diseñado para simplificar la vida del productor y potenciar el rendimiento del campo mediante tecnología inteligente.
                    </p>
                    <div class="flex space-x-5">
                        <span class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-gray-600 cursor-default">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </span>
                        <span class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-gray-600 cursor-default">
                            <i class="fab fa-instagram text-xl"></i>
                        </span>
                        <span class="w-12 h-12 rounded-full bg-gray-800 flex items-center justify-center text-gray-600 cursor-default">
                            <i class="fab fa-whatsapp text-xl"></i>
                        </span>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold text-lg mb-6 tracking-wide">Enlaces Rápidos</h4>
                    <ul class="space-y-4">
                        <li><a href="#inicio" class="text-gray-400 hover:text-primary-400 transition-colors font-medium">Inicio</a></li>
                        <li><a href="#beneficios" class="text-gray-400 hover:text-primary-400 transition-colors font-medium">Beneficios</a></li>
                        <li><a href="#modulos" class="text-gray-400 hover:text-primary-400 transition-colors font-medium">Módulos</a></li>
                        <li><a href="#nosotros" class="text-gray-400 hover:text-primary-400 transition-colors font-medium">Nosotros</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold text-lg mb-6 tracking-wide">Contacto</h4>
                    <ul class="space-y-5 text-gray-400">
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-primary-500">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>contacto@siprof.com</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-primary-500">
                                <i class="fas fa-phone"></i>
                            </div>
                            <span>+1 234 567 890</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-primary-500 flex-shrink-0">
                                <i class="fas fa-location-dot"></i>
                            </div>
                            <span class="mt-1">Sector Agrícola Central<br>Zona Rural, CP 00000</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-gray-500 font-medium">
                    &copy; <?= date('Y') ?> SIPROF. Todos los derechos reservados.
                </p>
                <a href="desarrollador.php" class="text-gray-500 hover:text-primary-400 font-medium transition-colors">
                    Desarrollado por Cristian Alejandro Suaza Ruiz
                </a>
            </div>
        </div>
    </footer>
    <script>
        // Navbar scroll effect for glassmorphism
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 20) {
                nav.classList.add('shadow-md', 'bg-white/95');
                nav.classList.remove('bg-white/90');
            } else {
                nav.classList.remove('shadow-md', 'bg-white/95');
                nav.classList.add('bg-white/90');
            }
        });
    </script>
</body>
</html>