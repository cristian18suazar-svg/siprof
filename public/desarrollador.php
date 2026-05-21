<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desarrollador | SIPROF</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Outfit', 'sans-serif'] },
                    colors: {
                        primary: {
                            50:  '#f0fdf4', 100: '#dcfce7', 200: '#bbf7d0',
                            300: '#86efac', 400: '#4ade80', 500: '#22c55e',
                            600: '#16a34a', 700: '#15803d', 800: '#166534',
                            900: '#14532d',
                        }
                    },
                    animation: { 'blob': 'blob 7s infinite' },
                    keyframes: {
                        blob: {
                            '0%':   { transform: 'translate(0px, 0px) scale(1)' },
                            '33%':  { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%':  { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Outfit', sans-serif; }

        .hero-bg {
            background-color: #14532d;
            background-image: linear-gradient(rgba(20,83,45,0.85), rgba(15,23,42,0.95)),
                              url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-card {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
        }

        .glass-nav {
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .animation-delay-2000 { animation-delay: 2s; }

        /* Tooltip */
        .tooltip-wrap { position: relative; }
        .tooltip-wrap .tooltip {
            visibility: hidden; opacity: 0;
            background: #1f2937; color: #fff;
            font-size: 0.7rem; font-weight: 600;
            padding: 4px 10px; border-radius: 8px;
            white-space: nowrap;
            position: absolute;
            bottom: calc(100% + 8px); left: 50%;
            transform: translateX(-50%) translateY(4px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        }
        .tooltip-wrap .tooltip::after {
            content: ''; position: absolute;
            top: 100%; left: 50%; transform: translateX(-50%);
            border: 5px solid transparent;
            border-top-color: #1f2937;
        }
        .tooltip-wrap:hover .tooltip {
            visibility: visible; opacity: 1;
            transform: translateX(-50%) translateY(0);
        }

        /* Iconos de contacto */
        .contact-btn {
            transition: transform 0.25s cubic-bezier(.34,1.56,.64,1), box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease;
        }
        .contact-btn:hover { transform: translateY(-6px) scale(1.15); box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .contact-btn.github:hover    { background: #24292e !important; color: #fff !important; border-color: #24292e !important; }
        .contact-btn.email:hover     { background: #EA4335 !important; color: #fff !important; border-color: #EA4335 !important; }
        .contact-btn.instagram:hover { background: linear-gradient(135deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888) !important; color: #fff !important; border-color: transparent !important; }
        .contact-btn.whatsapp:hover  { background: #25D366 !important; color: #fff !important; border-color: #25D366 !important; }

        /* Tags tecnologías */
        .tech-tag {
            transition: all 0.2s ease;
        }
        .tech-tag:hover {
            background: rgba(34,197,94,0.2);
            border-color: #22c55e;
            color: #86efac;
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="hero-bg min-h-screen font-sans">

    <!-- Blobs decorativos -->
    <div class="fixed top-1/4 left-10 w-72 h-72 bg-primary-500/20 rounded-full mix-blend-screen filter blur-3xl opacity-60 animate-blob pointer-events-none"></div>
    <div class="fixed top-1/3 right-10 w-72 h-72 bg-yellow-500/10 rounded-full mix-blend-screen filter blur-3xl opacity-60 animate-blob animation-delay-2000 pointer-events-none"></div>

    <!-- Navbar -->
    <nav class="glass-nav fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <!-- Logo -->
            <a href="index.php" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center text-white shadow-lg shadow-primary-500/30 group-hover:scale-110 transition-transform">
                    <i class="fas fa-leaf text-base"></i>
                </div>
                <span class="font-bold text-xl text-white tracking-tight">SIPROF</span>
            </a>

            <!-- Volver -->
            <a href="index.php"
               class="flex items-center gap-2 text-sm text-white/70 hover:text-white font-medium transition-colors px-4 py-2 rounded-xl hover:bg-white/10">
                <i class="fas fa-arrow-left text-xs"></i> Volver al inicio
            </a>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="min-h-screen flex items-center justify-center px-4 pt-24 pb-12">
        <div class="w-full max-w-lg">

            <!-- Tarjeta principal -->
            <div class="glass-card rounded-3xl p-8 text-center shadow-2xl">

                <!-- Badge superior -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-xs font-semibold text-primary-300 mb-6 tracking-wide uppercase">
                    <i class="fas fa-code text-primary-400"></i>
                    Desarrollador del Sistema
                </div>

                <!-- Foto de perfil -->
                <div class="flex justify-center mb-5">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/20 shadow-2xl ring-2 ring-primary-500/40">
                            <img src="../img/yo.jpeg"
                                 alt="Cristian Alejandro Suaza Ruiz"
                                 class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center\'><i class=\'fas fa-user text-white text-4xl\'></i></div>'">
                        </div>
                        <!-- Indicador activo -->
                        <span class="absolute bottom-1 right-1 w-4 h-4 bg-primary-400 border-2 border-white/30 rounded-full shadow-lg">
                            <span class="absolute inset-0 rounded-full bg-primary-400 animate-ping opacity-75"></span>
                        </span>
                    </div>
                </div>

                <!-- Nombre y rol -->
                <h1 class="text-2xl font-bold text-white mb-1">
                    Cristian Alejandro Suaza Ruiz
                </h1>
                <p class="text-primary-400 font-medium text-sm mb-5">
                    Desarrollador de Software
                </p>

                <!-- Descripción -->
                <p class="text-white/60 text-sm leading-relaxed mb-6 max-w-sm mx-auto">
                    Desarrollador de software enfocado en construir sistemas web funcionales y bien organizados.
                    Me apasiona transformar ideas en soluciones reales, escribiendo código limpio que sea fácil
                    de mantener y escalar. Cada proyecto es una oportunidad para seguir creciendo.
                </p>

                <div class="border-t border-white/10 mb-6"></div>

                <!-- Tecnologías -->
                <div class="mb-6">
                    <p class="text-xs font-bold tracking-widest text-white/40 uppercase mb-4">Tecnologías</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        <?php
                        $tecnologias = ['PHP','JavaScript','React','Laravel','MySQL','HTML / CSS'];
                        foreach ($tecnologias as $tech):
                        ?>
                        <span class="tech-tag px-4 py-1.5 border border-white/20 rounded-full text-sm text-white/70 font-medium cursor-default">
                            <?= htmlspecialchars($tech) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="border-t border-white/10 mb-6"></div>

                <!-- Contacto -->
                <div>
                    <p class="text-xs font-bold tracking-widest text-white/40 uppercase mb-4">Contacto</p>
                    <div class="flex justify-center gap-3">

                        <!-- GitHub -->
                        <div class="tooltip-wrap">
                            <a href="https://github.com/cristian18suazar-svg"
                               target="_blank" rel="noopener noreferrer"
                               class="contact-btn github w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white/80">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/>
                                </svg>
                            </a>
                            <span class="tooltip">GitHub</span>
                        </div>

                        <!-- Email -->
                        <div class="tooltip-wrap">
                            <a href="mailto:cristiancamiloramireztorres89@gmail.com"
                               class="contact-btn email w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white/80">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                            </a>
                            <span class="tooltip">Email</span>
                        </div>

                        <!-- Instagram -->
                        <div class="tooltip-wrap">
                            <a href="https://www.instagram.com/sruizcristian?igsh=MXEyZnJyODk4NmQ4bQ%3D%3D&utm_source=qr"
                               target="_blank" rel="noopener noreferrer"
                               class="contact-btn instagram w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white/80">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                    <circle cx="12" cy="12" r="4"/>
                                    <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                                </svg>
                            </a>
                            <span class="tooltip">Instagram</span>
                        </div>

                        <!-- WhatsApp -->
                        <div class="tooltip-wrap">
                            <a href="https://wa.me/qr/F25VJAT2KPQUO1"
                               target="_blank" rel="noopener noreferrer"
                               class="contact-btn whatsapp w-12 h-12 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-white/80">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                                </svg>
                            </a>
                            <span class="tooltip">WhatsApp</span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- Footer de la tarjeta -->
            <p class="text-center text-white/30 text-xs mt-6 font-medium tracking-wide">
                SIPROF &mdash; Sistema de Gestión Fincaria &copy; <?= date('Y') ?>
            </p>

        </div>
    </main>

</body>
</html>
