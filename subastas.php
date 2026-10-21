<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subastas Exclusivas | AURA Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        aura: {
                            50: '#f8f8f8',
                            100: '#e8e8e8',
                            800: '#262626',
                            900: '#171717',
                            accent: '#d4a373',
                            accentHover: '#bc8a5f'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .toast-slide { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }
        @keyframes pulse-red {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .animate-live { animation: pulse-red 1.5s infinite; }
    </style>
</head>
<body class="bg-aura-50 text-aura-900 font-sans antialiased selection:bg-aura-accent selection:text-white">

   

    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <button id="mobile-menu-btn" class="md:hidden text-gray-700 hover:text-black focus:outline-none p-2">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <div class="flex-shrink-0 flex items-center">
                    <a href="index.html" class="text-2xl sm:text-3xl font-extrabold tracking-widest text-aura-900 uppercase">
                        BaggyCulture<span class="text-aura-accent">.</span>
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8 text-sm font-medium tracking-wide text-gray-700">
                    <a href="index.html" class="hover:text-black transition-colors py-2">Inicio</a>
                    <a href="hombres.php" class="hover:text-black transition-colors py-2">Catalogo</a>
                    <a href="#" class="text-black font-bold py-2 border-b-2 border-aura-accent flex items-center gap-2">
                        Subastas <span class="bg-red-500 text-white text-[9px] px-1.5 py-0.5 rounded-full uppercase tracking-wider font-extrabold animate-pulse">En Vivo</span>
                    </a>
                    <a href="asesores.php" class="hover:text-black transition-colors py-2">Asesores</a>
                    
                </nav>

                <div class="flex items-center space-x-4">
                    <button onclick="showToast('Sistema de notificaciones activado')" class="text-gray-700 hover:text-black p-2 relative" title="Notificaciones de pujas">
                        <i class="fa-regular fa-bell text-lg"></i>
                        <span class="absolute top-1 right-1 bg-red-500 w-2 h-2 rounded-full"></span>
                    </button>
                    <button onclick="showToast('Inicia sesión para ver tu historial de pujas')" class="px-4 py-2 bg-aura-900 text-white text-xs font-semibold rounded-xl hover:bg-black transition-colors">
                        Mi Panel
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-200 px-4 py-4 space-y-3">
            <a href="index.html" class="block text-base font-medium text-gray-800">Inicio</a>
            <a href="hombre.html" class="block text-base font-medium text-gray-800">Hombre</a>
            <a href="mujer.html" class="block text-base font-medium text-gray-800">Mujer</a>
            <a href="#" class="block text-base font-bold text-aura-accent">Subastas En Vivo</a>
        </div>
    </header>

    <section class="relative bg-aura-900 text-white py-16 sm:py-24 mb-12 overflow-hidden">
        <div class="absolute inset-0 z-0 opacity-30">
            <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?q=80&w=2000&auto=format&fit=crop" 
                 alt="Subastas de Moda" 
                 class="w-full h-full object-cover object-center"
                 onerror="this.onerror=null; this.src='https://placehold.co/1920x600/171717/ffffff?text=AURA+Subastas';">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-white/10 backdrop-blur-md rounded-full text-xs text-aura-accent uppercase tracking-widest font-semibold mb-4 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-live"></span>
                Baggy Culture • Piezas Únicas & Prototipos
            </span>
            <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight">Subastas en Tiempo Real</h1>
            <p class="mt-4 text-sm sm:text-base text-gray-300 max-w-2xl mx-auto font-light">
                Consigue prendas exclusivas de edición limitada, muestras de pasarela y colaboraciones únicas. Puja directo en bolivianos (Bs).
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-6 text-xs text-gray-400">
                <div class="flex items-center gap-2"><i class="fa-solid fa-gavel text-aura-accent"></i> Pujas Transparentes</div>
                <div class="flex items-center gap-2"><i class="fa-solid fa-certificate text-aura-accent"></i> Autenticidad Garantizada</div>
                <div class="flex items-center gap-2"><i class="fa-solid fa-truck-fast text-aura-accent"></i> Envío Asegurado</div>
            </div>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 pb-4 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-aura-900 text-white rounded-xl text-xs font-bold shadow-sm">Activas Ahora (4)</button>
                <button class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 rounded-xl text-xs font-semibold transition-colors">Próximas</button>
                <button class="px-4 py-2 bg-white text-gray-600 hover:bg-gray-100 rounded-xl text-xs font-semibold transition-colors">Finalizadas</button>
            </div>
            <div class="text-xs text-gray-500">
                Incremento mínimo de puja: <span class="font-bold text-aura-900">20 Bs</span>
            </div>
        </div>

        <div id="auctions-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <!-- Subasta 1 -->
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1548883354-7622d03aca27?q=80&w=800&auto=format&fit=crop" 
                             alt="Chaqueta Cuero Edición Limitada" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> EN VIVO
                        </span>
                        <div class="absolute bottom-3 left-3 right-3 bg-black/70 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl flex items-center justify-between font-mono">
                            <span class="text-[10px] text-gray-300 uppercase tracking-wider">Tiempo restante</span>
                            <span class="font-bold text-amber-400"><i class="fa-regular fa-clock mr-1"></i>02h 14m 08s</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-semibold text-aura-accent uppercase tracking-wider">Lote #041 • Abrigos</span>
                        <h3 class="text-sm font-bold text-aura-900 mt-1 line-clamp-1">Chaqueta Biker Cuero Italiano Prototype</h3>
                        <p class="text-xs text-gray-500 mt-1">Talla M • Pieza 1 de 5 producidas</p>
                        
                        <div class="mt-4 p-3 bg-aura-50 rounded-xl flex items-center justify-between border border-gray-100">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Puja Actual</span>
                                <span class="text-lg font-extrabold text-aura-900">1.250 Bs</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Pujas</span>
                                <span class="text-xs font-bold text-gray-700"><i class="fa-solid fa-gavel text-[10px] text-aura-accent"></i> 18 pujas</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <button onclick="openBidModal('Chaqueta Biker Cuero Italiano', 1250, 1)" class="w-full py-3 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-gavel"></i>
                        <span>Pujar Ahora (+50 Bs)</span>
                    </button>
                </div>
            </div>

            <!-- Subasta 2 -->
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?q=80&w=800&auto=format&fit=crop" 
                             alt="Vestido Alta Costura" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> EN VIVO
                        </span>
                        <div class="absolute bottom-3 left-3 right-3 bg-black/70 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl flex items-center justify-between font-mono">
                            <span class="text-[10px] text-gray-300 uppercase tracking-wider">Tiempo restante</span>
                            <span class="font-bold text-amber-400"><i class="fa-regular fa-clock mr-1"></i>05h 42m 19s</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-semibold text-aura-accent uppercase tracking-wider">Lote #042 • Vestidos</span>
                        <h3 class="text-sm font-bold text-aura-900 mt-1 line-clamp-1">Vestido Asimétrico Seda Atelier 2026</h3>
                        <p class="text-xs text-gray-500 mt-1">Talla S • Edición Única Pasarela</p>
                        
                        <div class="mt-4 p-3 bg-aura-50 rounded-xl flex items-center justify-between border border-gray-100">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Puja Actual</span>
                                <span class="text-lg font-extrabold text-aura-900">890 Bs</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Pujas</span>
                                <span class="text-xs font-bold text-gray-700"><i class="fa-solid fa-gavel text-[10px] text-aura-accent"></i> 12 pujas</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <button onclick="openBidModal('Vestido Asimétrico Seda Atelier', 890, 2)" class="w-full py-3 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-gavel"></i>
                        <span>Pujar Ahora (+50 Bs)</span>
                    </button>
                </div>
            </div>

            <!-- Subasta 3 -->
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?q=80&w=800&auto=format&fit=crop" 
                             alt="Sneakers de Colección" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> EN VIVO
                        </span>
                        <div class="absolute bottom-3 left-3 right-3 bg-black/70 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl flex items-center justify-between font-mono">
                            <span class="text-[10px] text-gray-300 uppercase tracking-wider">Tiempo restante</span>
                            <span class="font-bold text-amber-400"><i class="fa-regular fa-clock mr-1"></i>00h 48m 55s</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-semibold text-aura-accent uppercase tracking-wider">Lote #043 • Calzado</span>
                        <h3 class="text-sm font-bold text-aura-900 mt-1 line-clamp-1">Sneakers Custom AURA x Craft Collab</h3>
                        <p class="text-xs text-gray-500 mt-1">Talla 42 EUR • Firmados por el Diseñador</p>
                        
                        <div class="mt-4 p-3 bg-aura-50 rounded-xl flex items-center justify-between border border-gray-100">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Puja Actual</span>
                                <span class="text-lg font-extrabold text-aura-900">1.500 Bs</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Pujas</span>
                                <span class="text-xs font-bold text-gray-700"><i class="fa-solid fa-gavel text-[10px] text-aura-accent"></i> 31 pujas</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <button onclick="openBidModal('Sneakers Custom AURA x Craft', 1500, 3)" class="w-full py-3 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-gavel"></i>
                        <span>Pujar Ahora (+50 Bs)</span>
                    </button>
                </div>
            </div>

            <!-- Subasta 4 -->
            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 hover:shadow-2xl transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <div class="relative h-72 w-full bg-gray-100 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1584917865442-de89df76afd3?q=80&w=800&auto=format&fit=crop" 
                             alt="Bolso de Mano Exclusivo" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-extrabold uppercase px-2.5 py-1 rounded-md flex items-center gap-1.5 shadow-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> EN VIVO
                        </span>
                        <div class="absolute bottom-3 left-3 right-3 bg-black/70 backdrop-blur-md text-white text-xs px-3 py-1.5 rounded-xl flex items-center justify-between font-mono">
                            <span class="text-[10px] text-gray-300 uppercase tracking-wider">Tiempo restante</span>
                            <span class="font-bold text-amber-400"><i class="fa-regular fa-clock mr-1"></i>08h 12m 30s</span>
                        </div>
                    </div>
                    <div class="p-5">
                        <span class="text-[10px] font-semibold text-aura-accent uppercase tracking-wider">Lote #044 • Accesorios</span>
                        <h3 class="text-sm font-bold text-aura-900 mt-1 line-clamp-1">Bolso Structure Piel Nobuk Minimalist</h3>
                        <p class="text-xs text-gray-500 mt-1">Edición Vault • Incluye estuche especial</p>
                        
                        <div class="mt-4 p-3 bg-aura-50 rounded-xl flex items-center justify-between border border-gray-100">
                            <div>
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Puja Actual</span>
                                <span class="text-lg font-extrabold text-aura-900">720 Bs</span>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-gray-400 block font-medium uppercase">Pujas</span>
                                <span class="text-xs font-bold text-gray-700"><i class="fa-solid fa-gavel text-[10px] text-aura-accent"></i> 9 pujas</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-5 pt-0">
                    <button onclick="openBidModal('Bolso Structure Piel Nobuk', 720, 4)" class="w-full py-3 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-all shadow-md flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-gavel"></i>
                        <span>Pujar Ahora (+50 Bs)</span>
                    </button>
                </div>
            </div>

        </div>
    </main>

    <section class="bg-stone-100 py-16 border-t border-b border-gray-200 mb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold text-center text-aura-900 mb-10">¿Cómo funcionan las subastas en BaggyCulture?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-white rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-aura-900 text-aura-accent rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">1</div>
                    <h3 class="font-bold text-sm text-aura-900">Haz tu Oferta en Bolivianos</h3>
                    <p class="text-xs text-gray-500 mt-2">Ingresa tu puja superando la oferta actual según el incremento mínimo establecido.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-aura-900 text-aura-accent rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">2</div>
                    <h3 class="font-bold text-sm text-aura-900">Monitorea en Tiempo Real</h3>
                    <p class="text-xs text-gray-500 mt-2">Recibirás alertas instantáneas si otro participante supera tu postura antes de que cierre el reloj.</p>
                </div>
                <div class="p-6 bg-white rounded-2xl shadow-sm">
                    <div class="w-12 h-12 bg-aura-900 text-aura-accent rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-4">3</div>
                    <h3 class="font-bold text-sm text-aura-900">Gana & Recibe en Casa</h3>
                    <p class="text-xs text-gray-500 mt-2">Si eres el mejor postor al finalizar el tiempo, la prenda es tuya con envío exprés incluido.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-aura-900 text-gray-400 text-xs py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <a href="index.html" class="text-xl font-extrabold tracking-widest text-white uppercase">
                    Baggy<span class="text-aura-accent">.</span>
                </a>
                <p class="mt-2 text-gray-500">&copy; 2026 Baggy Studio Inc. Todos los derechos reservados.</p>
            </div>
            <div class="flex space-x-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </footer>

    <div id="bid-modal" class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 relative shadow-2xl">
            <button onclick="closeBidModal()" class="absolute top-4 right-4 text-gray-400 hover:text-black">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
            
            <div class="text-center mb-6">
                <span class="text-[10px] font-bold text-aura-accent uppercase tracking-widest">Confirmar Puja</span>
                <h3 id="modal-item-title" class="text-lg font-bold text-aura-900 mt-1">Nombre del producto</h3>
                <p class="text-xs text-gray-500 mt-1">Puja actual: <span id="modal-current-price" class="font-bold text-aura-900">0 Bs</span></p>
            </div>

            <form onsubmit="submitBid(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tu postura (mínimo <span id="modal-min-bid">0</span> Bs)</label>
                    <div class="relative">
                        <input type="number" id="bid-amount-input" required min="0" step="10" class="w-full pl-4 pr-12 py-3 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-aura-accent font-bold text-aura-900">
                        <span class="absolute right-4 top-3 text-xs font-bold text-gray-400">Bs</span>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 py-2">
                    <button type="button" onclick="addBidIncrement(20)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs font-bold text-aura-900 transition-colors">+20 Bs</button>
                    <button type="button" onclick="addBidIncrement(50)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs font-bold text-aura-900 transition-colors">+50 Bs</button>
                    <button type="button" onclick="addBidIncrement(100)" class="py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-xs font-bold text-aura-900 transition-colors">+100 Bs</button>
                </div>

                <button type="submit" class="w-full py-3.5 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-xl transition-colors shadow-lg flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-gavel"></i>
                    <span>Confirmar y Enviar Puja</span>
                </button>
            </form>
        </div>
    </div>

    <div id="toast" class="fixed bottom-5 right-5 z-50 bg-aura-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center space-x-3 transform translate-y-20 opacity-0 toast-slide">
        <i class="fa-solid fa-circle-check text-green-400 text-lg"></i>
        <span id="toast-message" class="text-xs font-medium">Puja realizada exitosamente</span>
    </div>

    <script>
        let currentModalPrice = 0;

        window.addEventListener('DOMContentLoaded', () => {
            document.getElementById('mobile-menu-btn').addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });
        });

        function openBidModal(title, price, itemId) {
            currentModalPrice = price;
            const minBid = price + 50;

            document.getElementById('modal-item-title').innerText = title;
            document.getElementById('modal-current-price').innerText = `${price} Bs`;
            document.getElementById('modal-min-bid').innerText = minBid;
            
            const input = document.getElementById('bid-amount-input');
            input.value = minBid;
            input.min = minBid;

            const modal = document.getElementById('bid-modal');
            modal.classList.remove('opacity-0', 'pointer-events-none');
        }

        function closeBidModal() {
            const modal = document.getElementById('bid-modal');
            modal.classList.add('opacity-0', 'pointer-events-none');
        }

        function addBidIncrement(amount) {
            const input = document.getElementById('bid-amount-input');
            const currentValue = parseInt(input.value) || currentModalPrice;
            input.value = currentValue + amount;
        }

        function submitBid(e) {
            e.preventDefault();
            const bidValue = document.getElementById('bid-amount-input').value;
            closeBidModal();
            showToast(`¡Puja exitosa registrada por ${bidValue} Bs! Eres el líder actual.`);
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3500);
        }
    </script>
</body>
</html>