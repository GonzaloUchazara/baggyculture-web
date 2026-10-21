<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAGGYCULTURE - Asesores de Moda & Cotizaciones</title>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            accent: '#B83B32', // Tono rojo/coral para menú activo y detalles
                            dark: '#111111',
                            subtle: '#666666',
                            cardBg: '#FAFAFA',
                            border: '#EEEEEE'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .custom-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        }
        .custom-shadow-hover:hover {
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.12);
            transform: translateY(-4px);
        }
    </style>
</head>
<body class="bg-white text-gray-900 font-sans antialiased selection:bg-brand-accent selection:text-white">

    <!-- ================= NAVBAR ================= -->
    <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            
            <!-- Logo BAGGYCULTURE -->
            <a href="#" class="text-2xl sm:text-3xl font-extrabold tracking-tight text-black flex items-center">
                BAGGYCULTURE <span class="text-brand-accent text-3xl ml-0.5 leading-none">•</span>
            </a>

            <!-- Nav Links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium">
                <a href="baggyinicio.php" class="text-gray-600 hover:text-black transition-colors">Inicio</a>
                <a href="hombres.php" class="text-gray-600 hover:text-black transition-colors">Catálogo</a>
                <a href="subastas.php" class="text-gray-600 hover:text-black transition-colors">Subastas</a>
                <!-- Active Link -->
                <a href="#" class="text-brand-accent font-semibold relative py-1 after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full after:h-[2px] after:bg-brand-accent">
                    Asesores
                </a>
            </nav>

            <!-- Search Bar & Actions -->
            <div class="flex items-center space-x-4">
                <!-- Search Box -->
                <div class="relative hidden sm:block w-48 md:w-60">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" placeholder="Buscar productos..." class="w-full pl-9 pr-4 py-2 bg-gray-100 text-xs rounded-full focus:outline-none focus:ring-1 focus:ring-gray-300 transition-all placeholder-gray-400">
                </div>

                <!-- User Profile -->
                <button class="p-2 text-gray-700 hover:text-black transition-colors rounded-full hover:bg-gray-100" aria-label="Usuario">
                    <i class="fa-regular fa-user text-lg"></i>
                </button>

                <!-- Shopping Bag -->
                <button class="p-2 text-gray-700 hover:text-black transition-colors rounded-full hover:bg-gray-100 relative" aria-label="Carrito">
                    <i class="fa-solid fa-bag-shopping text-lg"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-brand-accent rounded-full"></span>
                </button>
            </div>
        </div>
    </header>

    <!-- ================= HERO SECTION ================= -->
    <section class="relative bg-gradient-to-b from-gray-50 to-white py-16 px-4 sm:px-6 lg:px-8 border-b border-gray-100">
        <div class="max-w-4xl mx-auto text-center">
            <span class="inline-block px-3.5 py-1 bg-black text-white text-[11px] font-semibold tracking-wider uppercase rounded-full mb-4">
                Atención Personalizada
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-4">
                Nuestros Asesores de Moda & Cotizaciones
            </h1>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto font-normal">
                Selecciona a un especialista para cotizar prendas exclusivas, confección personalizada, asesoría de imagen o pedidos al por mayor.
            </p>
        </div>
    </section>

    <!-- ================= MAIN CONTENT (ASESORES GRID) ================= -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        
        <!-- Filter / Info Bar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-10 pb-4 border-b border-gray-100 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Equipo de Asesores</h2>
                <p class="text-xs text-gray-500">5 especialistas disponibles para tu consulta</p>
            </div>
            <div class="flex items-center space-x-2 text-xs font-medium text-gray-500">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Respuesta promedio: &lt; 15 min</span>
            </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Card 1 -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 transition-all duration-300 custom-shadow custom-shadow-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=200" 
                                 alt="Camila Morales" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-sm"
                                 onerror="this.src='https://placehold.co/150x150/111/fff?text=CM'">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Disponible ahora"></span>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                            Disponible
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Camila Morales</h3>
                    <p class="text-xs font-medium text-brand-accent mb-3">Streetwear & Outfit Personalizado</p>
                    
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Especialista en combinaciones urbanas, conjuntos oversize y personalización de siluetas streetwear exclusivas.
                    </p>

                    <div class="space-y-2 border-t border-gray-200/60 pt-4 mb-6">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Experiencia</span>
                            <span class="font-medium text-gray-800">4+ años</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Calificación</span>
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-star text-amber-400 text-[10px] mr-1"></i> 4.9 (120+)
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Camila Morales', 'Streetwear & Outfit Personalizado')" 
                        class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2 group">
                    <span>Cotizar con Camila</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <!-- Card 2 -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 transition-all duration-300 custom-shadow custom-shadow-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80&w=200" 
                                 alt="Mateo Vargas" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-sm"
                                 onerror="this.src='https://placehold.co/150x150/111/fff?text=MV'">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Disponible ahora"></span>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                            Disponible
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Mateo Vargas</h3>
                    <p class="text-xs font-medium text-brand-accent mb-3">Colecciones & Tendencias Urbanas</p>
                    
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Te ayuda a cotizar prendas cápsula de la nueva temporada 2026 y accesorios vanguardistas para eventos.
                    </p>

                    <div class="space-y-2 border-t border-gray-200/60 pt-4 mb-6">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Experiencia</span>
                            <span class="font-medium text-gray-800">5 años</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Calificación</span>
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-star text-amber-400 text-[10px] mr-1"></i> 5.0 (98)
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Mateo Vargas', 'Colecciones & Tendencias Urbanas')" 
                        class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2 group">
                    <span>Cotizar con Mateo</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <!-- Card 3 -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 transition-all duration-300 custom-shadow custom-shadow-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=200" 
                                 alt="Valeria Ríos" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-sm"
                                 onerror="this.src='https://placehold.co/150x150/111/fff?text=VR'">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Disponible ahora"></span>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                            Disponible
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Valeria Ríos</h3>
                    <p class="text-xs font-medium text-brand-accent mb-3">Talles, Estilo & Ajuste Perfect</p>
                    
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Asesoría técnica en medidas exactas, materiales de alta calidad y recomendaciones de ajuste holgado.
                    </p>

                    <div class="space-y-2 border-t border-gray-200/60 pt-4 mb-6">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Experiencia</span>
                            <span class="font-medium text-gray-800">3+ años</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Calificación</span>
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-star text-amber-400 text-[10px] mr-1"></i> 4.8 (145)
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Valeria Ríos', 'Talles, Estilo & Ajuste Perfect')" 
                        class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2 group">
                    <span>Cotizar con Valeria</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <!-- Card 4 -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 transition-all duration-300 custom-shadow custom-shadow-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=200" 
                                 alt="Lucas Mendoza" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-sm"
                                 onerror="this.src='https://placehold.co/150x150/111/fff?text=LM'">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Disponible ahora"></span>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full">
                            B2B / Mayoreo
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Lucas Mendoza</h3>
                    <p class="text-xs font-medium text-brand-accent mb-3">Pedidos al Mayor & Cotizaciones B2B</p>
                    
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Atención directa a tiendas, boutique y pedidos especiales por volumen con tarifas corporativas competitivas.
                    </p>

                    <div class="space-y-2 border-t border-gray-200/60 pt-4 mb-6">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Experiencia</span>
                            <span class="font-medium text-gray-800">6 años</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Calificación</span>
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-star text-amber-400 text-[10px] mr-1"></i> 5.0 (210+)
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Lucas Mendoza', 'Pedidos al Mayor & Cotizaciones B2B')" 
                        class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2 group">
                    <span>Cotizar con Lucas</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <!-- Card 5 -->
            <div class="bg-brand-cardBg border border-brand-border rounded-2xl p-6 transition-all duration-300 custom-shadow custom-shadow-hover flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-4">
                        <div class="relative">
                            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&q=80&w=200" 
                                 alt="Sofia Castro" 
                                 class="w-16 h-16 rounded-full object-cover ring-2 ring-white shadow-sm"
                                 onerror="this.src='https://placehold.co/150x150/111/fff?text=SC'">
                            <span class="absolute bottom-0 right-0 w-4 h-4 bg-emerald-500 border-2 border-white rounded-full" title="Disponible ahora"></span>
                        </div>
                        <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full">
                            Disponible
                        </span>
                    </div>

                    <h3 class="text-lg font-bold text-gray-900">Sofia Castro</h3>
                    <p class="text-xs font-medium text-brand-accent mb-3">Moda Oversize & Estilo Minimalista</p>
                    
                    <p class="text-xs text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        Experta en estilismo minimalista, paleta de colores neutros y piezas clave para armar un guardarropa versátil.
                    </p>

                    <div class="space-y-2 border-t border-gray-200/60 pt-4 mb-6">
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Experiencia</span>
                            <span class="font-medium text-gray-800">4 años</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500">
                            <span>Calificación</span>
                            <span class="font-medium text-gray-800 flex items-center">
                                <i class="fa-solid fa-star text-amber-400 text-[10px] mr-1"></i> 4.9 (88)
                            </span>
                        </div>
                    </div>
                </div>

                <button onclick="openModal('Sofia Castro', 'Moda Oversize & Estilo Minimalista')" 
                        class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2 group">
                    <span>Cotizar con Sofia</span>
                    <i class="fa-solid fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>

            <!-- Card Banner Callout -->
            <div class="bg-gradient-to-br from-gray-900 to-black text-white rounded-2xl p-6 flex flex-col justify-between shadow-xl">
                <div>
                    <span class="text-[10px] tracking-widest uppercase text-gray-400 font-semibold mb-2 block">¿Tienes dudas rápidas?</span>
                    <h3 class="text-xl font-bold mb-2">Cotización Express vía WhatsApp</h3>
                    <p class="text-xs text-gray-300 leading-relaxed mb-6">
                        Envíanos la foto del producto o la idea que deseas cotizar y te responderemos en minutos con precios y disponibilidad.
                    </p>
                </div>
                <a href="#" onclick="alert('Redirigiendo al chat de WhatsApp oficial...'); return false;" 
                   class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-xs rounded-xl transition-colors flex items-center justify-center space-x-2">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>Escribir por WhatsApp</span>
                </a>
            </div>

        </div>
    </main>

    <!-- ================= MODAL DE COTIZACIÓN (UI VISUAL) ================= -->
    <div id="quoteModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 sm:p-8 shadow-2xl relative transform transition-all animate-in fade-in zoom-in duration-200">
            
            <!-- Close Button -->
            <button onclick="closeModal()" class="absolute top-5 right-5 text-gray-400 hover:text-black text-lg p-1 rounded-full hover:bg-gray-100 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>

            <!-- Modal Header -->
            <div class="mb-6">
                <span class="text-[10px] font-bold text-brand-accent tracking-wider uppercase">Solicitud de Cotización</span>
                <h3 class="text-xl font-extrabold text-gray-900 mt-1">Cotizar con <span id="advisorName" class="text-black">...</span></h3>
                <p id="advisorRole" class="text-xs text-gray-500 mt-0.5">Especialista</p>
            </div>

            <!-- Form Visual (Non-functional/UI Only) -->
            <form onsubmit="handleSubmit(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nombre Completo</label>
                    <input type="text" placeholder="Ej. Juan Pérez" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-black focus:bg-white transition-all">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" placeholder="correo@ejemplo.com" required class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-black focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Teléfono / WhatsApp</label>
                        <input type="tel" placeholder="+591 70000000" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-black focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tipo de Consulta / Producto</label>
                    <select class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-black focus:bg-white transition-all text-gray-700">
                        <option>Cotización de Prenda Individual</option>
                        <option>Pedido al por Mayor (Mayoreo)</option>
                        <option>Confección & Diseño Personalizado</option>
                        <option>Asesoría Completa de Imagen</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Detalles de tu pedido</label>
                    <textarea rows="3" placeholder="Describe prendas, talles aproximados, colores o requerimientos especiales..." class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-xs focus:outline-none focus:border-black focus:bg-white transition-all"></textarea>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 px-4 bg-black hover:bg-gray-800 text-white text-xs font-semibold rounded-xl transition-all shadow-md active:scale-[0.98]">
                        Enviar Solicitud de Cotización
                    </button>
                    <p class="text-[10px] text-gray-400 text-center mt-2">
                        <i class="fa-solid fa-lock text-[9px] mr-1"></i> Recibirás la cotización detallada en tu correo/WhatsApp.
                    </p>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Notification (Success Message) -->
    <div id="toast" class="fixed bottom-6 right-6 bg-black text-white px-5 py-3.5 rounded-xl shadow-2xl text-xs flex items-center space-x-3 hidden z-50">
        <i class="fa-solid fa-circle-check text-emerald-400 text-base"></i>
        <div>
            <p class="font-bold">¡Solicitud enviada con éxito!</p>
            <p class="text-gray-400 text-[11px]">Tu asesor te contactará a la brevedad.</p>
        </div>
    </div>

    <!-- ================= FOOTER SIMPLE ================= -->
    <footer class="border-t border-gray-100 py-8 bg-white mt-10">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-gray-400">
            <p>© 2026 BAGGYCULTURE. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- JS simple para interacción del Modal -->
    <script>
        function openModal(name, role) {
            document.getElementById('advisorName').innerText = name;
            document.getElementById('advisorRole').innerText = role;
            document.getElementById('quoteModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('quoteModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function handleSubmit(e) {
            e.preventDefault();
            closeModal();
            
            // Mostrar notificación simulada
            const toast = document.getElementById('toast');
            toast.classList.remove('hidden');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3500);
        }
    </script>
</body>
</html>