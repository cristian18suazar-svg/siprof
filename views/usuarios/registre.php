<?php
session_start();
$error = $_SESSION['error'] ?? '';
$exito = $_SESSION['exito'] ?? '';
unset($_SESSION['error'], $_SESSION['exito']);
?>
<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro | SIPROF</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Favicon original -->
    <link rel="shortcut icon" href="../../img/logo.jpg">

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
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-primary-900 via-primary-800 to-primary-600 text-gray-800 antialiased font-sans min-h-screen flex items-center justify-center relative py-12 px-4 overflow-x-hidden">


    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-5xl flex justify-center">
        
        <div class="bg-white/95 backdrop-blur-2xl rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col md:flex-row w-full max-w-5xl border border-white/20 transform transition-all duration-500 hover:shadow-primary-900/50">
            
            <!-- Left Side: Branding & Info -->
            <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-primary-600 to-primary-900 p-10 flex-col justify-between text-white relative overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -top-20 -left-20 w-72 h-72 bg-primary-400/20 rounded-full blur-3xl"></div>
                
                <div class="relative z-10">
                    <a href="../../public/index.php" class="inline-flex items-center gap-2 text-white/70 hover:text-white transition-colors mb-10 font-medium">
                        <i class="fas fa-arrow-left"></i> Volver al inicio
                    </a>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-primary-600 shadow-xl">
                            <i class="fas fa-leaf text-3xl"></i>
                        </div>
                        <span class="font-bold text-3xl tracking-tight">SIPROF</span>
                    </div>
                    
                    <h2 class="text-3xl font-bold mb-5 leading-tight">Únete a la nueva era agrícola</h2>
                    <p class="text-primary-100 text-lg font-light leading-relaxed">
                        Crea tu cuenta y comienza a gestionar tu producción, inventario y trabajadores desde una plataforma moderna, rápida y segura.
                    </p>
                </div>
                
                <div class="relative z-10 mt-8 space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-primary-300">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="font-medium text-white/90">Gestión de Cosechas</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-primary-300">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="font-medium text-white/90">Control de Insumos</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-primary-300">
                            <i class="fas fa-check"></i>
                        </div>
                        <span class="font-medium text-white/90">Reportes Detallados</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Registration Form -->
            <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-white relative">
                
                <!-- Mobile Header -->
                <div class="md:hidden flex flex-col items-center mb-8">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center text-white shadow-lg mb-3">
                        <i class="fas fa-leaf text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">SIPROF</h1>
                </div>

                <div class="mb-6 text-center md:text-left">
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-2">Crear Cuenta</h3>
                    <p class="text-gray-500 font-medium">Completa tus datos para registrarte en el sistema</p>
                </div>

                <!-- Messages -->
                <?php if (!empty($error)): ?>
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded-r-xl flex items-start gap-3 shadow-sm">
                        <i class="fas fa-circle-exclamation mt-1"></i>
                        <span class="font-medium text-sm"><?php echo htmlspecialchars($error); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($exito)): ?>
                    <div class="mb-6 p-4 bg-green-50 border-l-4 border-primary-500 text-primary-800 rounded-r-xl flex items-start gap-3 shadow-sm">
                        <i class="fas fa-check-circle mt-1"></i>
                        <span class="font-medium text-sm"><?php echo htmlspecialchars($exito); ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="../../controllers/AuthController.php?accion=registrar" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-bold text-gray-700 mb-2">Nombre</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input type="text" id="nombre" name="nombre" required
                                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-medium"
                                    placeholder="Tu nombre">
                            </div>
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label for="apellido" class="block text-sm font-bold text-gray-700 mb-2">Apellido</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-user"></i>
                                </div>
                                <input type="text" id="apellido" name="apellido" required
                                    class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-medium"
                                    placeholder="Tu apellido">
                            </div>
                        </div>
                    </div>

                    <!-- Correo -->
                    <div>
                        <label for="correo" class="block text-sm font-bold text-gray-700 mb-2">Correo Electrónico</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <input type="email" id="correo" name="correo" required
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-medium"
                                placeholder="tu@correo.com">
                        </div>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label for="telefono" class="block text-sm font-bold text-gray-700 mb-2">Teléfono / Celular</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input type="tel" id="telefono" name="telefono"
                                class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-medium"
                                placeholder="Ej. 123456789">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="block w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl text-gray-900 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all outline-none font-medium"
                                placeholder="••••••••">
                            
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-none">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" name="registrar" class="w-full py-4 mt-6 bg-primary-600 hover:bg-primary-700 text-white font-extrabold rounded-xl shadow-lg shadow-primary-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-primary-500/50 flex justify-center items-center gap-3 group text-lg">
                        Registrarse
                        <i class="fas fa-user-plus text-sm transition-transform group-hover:scale-110"></i>
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 font-medium">
                        ¿Ya tienes una cuenta? 
                        <a href="login.php" class="text-primary-600 hover:text-primary-700 font-bold ml-1 hover:underline transition-all">
                            Inicia sesión aquí
                        </a>
                    </p>
                </div>
                
                <div class="mt-6 text-center md:hidden">
                    <a href="../../public/index.php" class="text-sm text-gray-500 hover:text-primary-600 font-bold flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i> Volver al inicio
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Show/Hide Password -->
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>