<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
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
        /* Estilos personalizados para animaciones suaves */
        .cart-slide {
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .toast-slide {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }
        /* Scrollbar personalizado estético */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
    </style>
</head>
<body class="bg-aura-50 text-aura-900 font-sans antialiased selection:bg-aura-accent selection:text-white">

    

    <!-- HEADER / NAVEGACIÓN -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-100 transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="lg:hidden text-gray-700 hover:text-black focus:outline-none p-2">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <!-- LOGO -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="#" class="text-2xl sm:text-3xl font-extrabold tracking-widest text-aura-900 uppercase">
                        BaggyCulture
                        <span class="text-aura-accent">.</span>
                    </a>
                </div>

                <!-- Navigation Links (Desktop) -->
                <nav class="hidden lg:flex space-x-8 text-sm font-medium tracking-wide text-gray-700">
                    <a href="#inicio" class="hover:text-black transition-colors py-2 border-b-2 border-transparent hover:border-aura-accent">Inicio</a>
                    <a href="hombres.php" class="hover:text-black transition-colors py-2 border-b-2 border-transparent hover:border-aura-accent">Catalogo</a>
                    
                    <a href="subastas.php" class="hover:text-black transition-colors py-2 border-b-2 border-transparent hover:border-aura-accent">Subastas</a>
                    <a href="asesores.php" class="text-red-600 font-semibold hover:text-red-700 transition-colors py-2">Asesores</a>
                </nav>

                <!-- Action Icons (Search, User, Cart) -->
                <div class="flex items-center space-x-5">
                    <!-- Search Input Toggle -->
                    <div class="relative hidden sm:block">
                        <input type="text" id="search-input" placeholder="Buscar productos..." class="w-36 md:w-48 pl-9 pr-4 py-1.5 text-xs bg-gray-100 rounded-full focus:outline-none focus:w-60 focus:bg-white focus:ring-1 focus:ring-aura-accent transition-all duration-300">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    </div>

                    <!-- User Account -->
                    <button onclick="toggleModal('user-modal')" class="text-gray-700 hover:text-black transition-colors p-1 relative" title="Cuenta">
                        <i class="fa-regular fa-user text-lg"></i>
                    </button>

                    <!-- Cart Icon with Dynamic Counter -->
                    <button id="cart-btn" onclick="toggleCart(true)" class="text-gray-700 hover:text-black transition-colors p-1 relative" title="Carrito">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                        <span id="cart-count" class="absolute -top-1 -right-2 bg-aura-accent text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center scale-0 transition-transform duration-200">0</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-menu" class="hidden lg:hidden bg-white border-b border-gray-200 px-4 pt-2 pb-6 space-y-3">
            <div class="pt-2 pb-3">
                <input type="text" placeholder="Buscar en AURA..." class="w-full pl-9 pr-4 py-2 text-sm bg-gray-100 rounded-lg focus:outline-none">
            </div>
            <a href="#inicio" class="block text-base font-medium text-gray-800 hover:text-black">Inicio</a>
            <a href="#catalogo" class="block text-base font-medium text-gray-800 hover:text-black">Hombre</a>
            <a href="#catalogo" class="block text-base font-medium text-gray-800 hover:text-black">Mujer</a>
            <a href="#categorias" class="block text-base font-medium text-gray-800 hover:text-black">Colecciones</a>
            <a href="#oferta" class="block text-base font-semibold text-red-600">Ofertas Especiales</a>
        </div>
    </header>

    <!-- HERO BANNER PRINCIPAL -->
    <section id="inicio" class="relative bg-stone-900 text-white overflow-hidden min-h-[550px] lg:min-h-[680px] flex items-center">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img src="https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop" 
                 alt="Colección Otoño/Invierno AURA" 
                 class="w-full h-full object-cover object-center opacity-60 scale-105 transition-transform duration-1000 ease-out"
                 onerror="this.onerror=null; this.src='https://placehold.co/1920x1080/1a1a1a/cccccc?text=Moda+Contemporanea';">
            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/40 to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
            <div class="max-w-xl">
                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md text-xs tracking-widest uppercase rounded-full mb-4 font-semibold text-gray-200">
                    Nueva Temporada 2026
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6 leading-tight">
                    Colección <br/>
                    <span class="italic font-light text-aura-accent">Otoño & Invierno</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-300 mb-8 font-light leading-relaxed">
                    Descubre elegancia atemporal y minimalismo refinado. Diseños sostenibles creados para destacar en cualquier ocasión.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#catalogo" class="px-8 py-4 bg-white text-aura-900 font-semibold rounded-full hover:bg-aura-accent hover:text-white transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Explorar Colección <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </a>
                    <a href="#oferta" class="px-8 py-4 bg-transparent border border-white/60 text-white font-medium rounded-full hover:bg-white/10 hover:border-white transition-all duration-300 text-center backdrop-blur-sm">
                        Ver Ofertas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECCIÓN DE BENEFICIOS DE MARCA -->
    <section class="border-b border-gray-200 bg-white py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <!-- Benefit 1 -->
                <div class="flex items-center space-x-4 p-2">
                    <div class="w-12 h-12 rounded-full bg-aura-50 flex items-center justify-center text-aura-900 shrink-0">
                        <i class="fa-solid fa-truck-fast text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-aura-900">Envío Gratuito</h4>
                        <p class="text-xs text-gray-500">En compras mayores a bs 75</p>
                    </div>
                </div>
                <!-- Benefit 2 -->
                <div class="flex items-center space-x-4 p-2">
                    <div class="w-12 h-12 rounded-full bg-aura-50 flex items-center justify-center text-aura-900 shrink-0">
                        <i class="fa-solid fa-shield-halved text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-aura-900">Pago 100% Seguro</h4>
                        <p class="text-xs text-gray-500">Múltiples métodos de pago</p>
                    </div>
                </div>
                <!-- Benefit 3 -->
                <div class="flex items-center space-x-4 p-2">
                    <div class="w-12 h-12 rounded-full bg-aura-50 flex items-center justify-center text-aura-900 shrink-0">
                        <i class="fa-solid fa-rotate-left text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-aura-900">Devoluciones Fáciles</h4>
                        <p class="text-xs text-gray-500">Hasta 30 días garantizados</p>
                    </div>
                </div>
                <!-- Benefit 4 -->
                <div class="flex items-center space-x-4 p-2">
                    <div class="w-12 h-12 rounded-full bg-aura-50 flex items-center justify-center text-aura-900 shrink-0">
                        <i class="fa-solid fa-headset text-lg"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-aura-900">Soporte 24/7</h4>
                        <p class="text-xs text-gray-500">Atención rápida y personalizada</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CATEGORÍAS DESTACADAS -->
    <section id="categorias" class="py-16 sm:py-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold tracking-tight text-aura-900">Categorías Destacadas</h2>
                <p class="mt-2 text-sm text-gray-500">Explora nuestras selecciones diseñadas para cada estilo.</p>
            </div>
            <a href="#catalogo" class="mt-4 md:mt-0 text-sm font-semibold text-aura-900 hover:text-aura-accent flex items-center group transition-colors">
                Ver todo el catálogo <i class="fa-solid fa-arrow-right ml-2 text-xs group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Categoría 1: Mujer -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 h-96">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1000&auto=format&fit=crop" 
                     alt="Moda Mujer" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.onerror=null; this.src='https://placehold.co/600x800/e0e0e0/333333?text=Mujer';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col justify-end">
                    <span class="text-xs text-aura-accent font-semibold tracking-wider uppercase mb-1">Colección</span>
                    <h3 class="text-2xl font-bold text-white mb-2">Mujer</h3>
                    <a href="#catalogo" class="inline-flex items-center text-xs font-semibold text-white/90 group-hover:text-white uppercase tracking-wider">
                        Comprar Ahora <i class="fa-solid fa-chevron-right ml-2 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Categoría 2: Hombre -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 h-96">
                <img src="https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?q=80&w=1000&auto=format&fit=crop" 
                     alt="Moda Hombre" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.onerror=null; this.src='https://placehold.co/600x800/d0d0d0/333333?text=Hombre';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col justify-end">
                    <span class="text-xs text-aura-accent font-semibold tracking-wider uppercase mb-1">Colección</span>
                    <h3 class="text-2xl font-bold text-white mb-2">Hombre</h3>
                    <a href="#catalogo" class="inline-flex items-center text-xs font-semibold text-white/90 group-hover:text-white uppercase tracking-wider">
                        Comprar Ahora <i class="fa-solid fa-chevron-right ml-2 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Categoría 3: Accesorios -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 h-96">
                <img src="https://images.unsplash.com/photo-1523206489230-c012c64b2b48?q=80&w=1000&auto=format&fit=crop" 
                     alt="Accesorios" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.onerror=null; this.src='https://placehold.co/600x800/c0c0c0/333333?text=Accesorios';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col justify-end">
                    <span class="text-xs text-aura-accent font-semibold tracking-wider uppercase mb-1">Esenciales</span>
                    <h3 class="text-2xl font-bold text-white mb-2">Accesorios</h3>
                    <a href="#catalogo" class="inline-flex items-center text-xs font-semibold text-white/90 group-hover:text-white uppercase tracking-wider">
                        Comprar Ahora <i class="fa-solid fa-chevron-right ml-2 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <!-- Categoría 4: Calzado -->
            <div class="group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500 h-96">
                <img src="https://images.unsplash.com/photo-1560769629-975ec94e6a86?q=80&w=1000&auto=format&fit=crop" 
                     alt="Calzado" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     onerror="this.onerror=null; this.src='https://placehold.co/600x800/b0b0b0/333333?text=Calzado';">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                <div class="absolute bottom-0 left-0 right-0 p-6 flex flex-col justify-end">
                    <span class="text-xs text-aura-accent font-semibold tracking-wider uppercase mb-1">Calzado</span>
                    <h3 class="text-2xl font-bold text-white mb-2">Urban & Luxury</h3>
                    <a href="#catalogo" class="inline-flex items-center text-xs font-semibold text-white/90 group-hover:text-white uppercase tracking-wider">
                        Comprar Ahora <i class="fa-solid fa-chevron-right ml-2 text-[10px] group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- PRODUCTOS TENDENCIA / POPULARES -->
    <section id="catalogo" class="bg-white py-16 sm:py-24 border-t border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-aura-900 sm:text-4xl">Productos Destacados</h2>
                <p class="mt-3 text-base text-gray-500">Descubre las prendas más codiciadas de esta temporada.</p>
                
                <!-- Category Filter Pills -->
                <div class="flex flex-wrap justify-center gap-2 mt-8">
                    <button class="px-5 py-2 rounded-full text-xs font-semibold bg-aura-900 text-white shadow-sm">Todos</button>
                    <button class="px-5 py-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Mujer</button>
                    <button class="px-5 py-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Hombre</button>
                    <button class="px-5 py-2 rounded-full text-xs font-semibold bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">Novedades</button>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Se renderizará dinámicamente con JS -->
            </div>
        </div>
    </section>

    <!-- BANNER DE OFERTA ESPECIAL -->
    <section id="oferta" class="py-16 my-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-aura-900 rounded-3xl overflow-hidden shadow-2xl text-white">
            <div class="absolute inset-0 z-0 opacity-40">
                <img src="https://images.unsplash.com/photo-1445205170230-053b83016050?q=80&w=1800&auto=format&fit=crop" 
                     alt="Oferta Especial Moda" 
                     class="w-full h-full object-cover object-center"
                     onerror="this.onerror=null; this.src='https://placehold.co/1200x500/000/fff?text=Oferta+Especial';">
            </div>
            <div class="relative z-10 p-8 sm:p-12 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="max-w-xl text-center lg:text-left">
                    <span class="inline-block px-3 py-1 bg-red-500/20 text-red-300 border border-red-500/30 text-xs font-bold tracking-wider rounded-full uppercase mb-4">
                        Descuento de Bienvenida
                    </span>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                        ¡Obtén un <span class="text-aura-accent">20% OFF</span> en tu primera compra!
                    </h2>
                    <p class="text-gray-300 text-sm sm:text-base leading-relaxed mb-6">
                        Aprovecha nuestra promoción por tiempo limitado en prendas seleccionadas. Utiliza el código al finalizar tu pago.
                    </p>
                    <div class="inline-flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20">
                        <span class="text-xs text-gray-300 uppercase tracking-widest font-semibold">Código:</span>
                        <span id="coupon-code" class="text-base font-mono font-bold text-aura-accent">BIENVENIDO20</span>
                        <button onclick="copyCoupon()" class="ml-2 text-xs text-white hover:text-aura-accent transition-colors underline focus:outline-none">
                            <i class="fa-regular fa-copy"></i> Copiar
                        </button>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <a href="#catalogo" class="px-8 py-4 bg-aura-accent hover:bg-aura-accentHover text-white font-bold rounded-full transition-all duration-300 shadow-xl hover:shadow-2xl text-center inline-block transform hover:-translate-y-1">
                        Aprovechar Descuento
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SUSCRIPCIÓN NEWSLETTER -->
    <section class="bg-stone-100 py-16 sm:py-20">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <i class="fa-regular fa-envelope-open text-3xl text-aura-accent mb-4 block"></i>
            <h2 class="text-3xl font-bold tracking-tight text-aura-900">Únete al Club BaggyCulture</h2>
            <p class="mt-3 text-sm text-gray-600 max-w-lg mx-auto">
                Suscríbete a nuestra newsletter para recibir lanzamientos exclusivos, ofertas secretas y un 10% adicional de descuento.
            </p>
            <form id="newsletter-form" onsubmit="handleNewsletter(event)" class="mt-8 sm:flex sm:max-w-md sm:mx-auto gap-3">
                <input type="email" id="newsletter-email" required placeholder="Ingresa tu correo electrónico" class="w-full px-5 py-3 text-sm rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-aura-accent focus:border-transparent bg-white shadow-sm">
                <button type="submit" class="mt-3 sm:mt-0 w-full sm:w-auto px-8 py-3 bg-aura-900 hover:bg-black text-white text-sm font-semibold rounded-full transition-all duration-300 shrink-0 shadow-md">
                    Suscribirse
                </button>
            </form>
            <p class="text-[11px] text-gray-400 mt-4">Sin spam. Puedes desuscribirte en cualquier momento.</p>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-aura-900 text-gray-400 text-sm py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 mb-12">
                
                <!-- Brand Info -->
                <div class="lg:col-span-2">
                    <a href="#" class="text-2xl font-extrabold tracking-widest text-white uppercase block mb-4">
                        BaggyCulture<span class="text-aura-accent">.</span>
                    </a>
                    <p class="text-xs text-gray-400 leading-relaxed mb-6 max-w-sm">
                        Redefiniendo el guardarropa moderno con piezas minimalistas, duraderas y de producción responsable. Elegancia para el día a día.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center hover:bg-aura-accent hover:text-white transition-all text-gray-300">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center hover:bg-aura-accent hover:text-white transition-all text-gray-300">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center hover:bg-aura-accent hover:text-white transition-all text-gray-300">
                            <i class="fa-brands fa-pinterest-p"></i>
                        </a>
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 flex items-center justify-center hover:bg-aura-accent hover:text-white transition-all text-gray-300">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    </div>
                </div>

                <!-- Column 1: Navegación -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Tienda</h3>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#catalogo" class="hover:text-white transition-colors">Mujer</a></li>
                        <li><a href="#catalogo" class="hover:text-white transition-colors">Hombre</a></li>
                        <li><a href="#categorias" class="hover:text-white transition-colors">Accesorios</a></li>
                        <li><a href="#catalogo" class="hover:text-white transition-colors">Novedades</a></li>
                        <li><a href="#oferta" class="hover:text-white transition-colors">Rebajas</a></li>
                    </ul>
                </div>

                <!-- Column 2: Ayuda -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Soporte</h3>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">Estado del pedido</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Guía de Tallas</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Política de Envíos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Devoluciones</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>

                <!-- Column 3: Empresa -->
                <div>
                    <h3 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Nosotros</h3>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="#" class="hover:text-white transition-colors">Sobre AURA</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sostenibilidad</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Prensa</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Carreras</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Términos y Privacidad</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Footer bar -->
            <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs">
                <p>&copy; 2026 baggy studio Inc. Todos los derechos reservados.</p>
                
                <!-- Payment Icons -->
                <div class="flex items-center space-x-4 text-gray-400 text-lg">
                    <i class="fa-brands fa-cc-visa hover:text-white transition-colors" title="Visa"></i>
                    <i class="fa-brands fa-cc-mastercard hover:text-white transition-colors" title="Mastercard"></i>
                    <i class="fa-brands fa-cc-paypal hover:text-white transition-colors" title="PayPal"></i>
                    <i class="fa-brands fa-cc-apple-pay hover:text-white transition-colors" title="Apple Pay"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- CARRITO MODAL (SLIDE-OVER) -->
    <div id="cart-overlay" class="fixed inset-0 bg-black/50 z-50 opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleCart(false)"></div>
    <aside id="cart-drawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full cart-slide flex flex-col">
        <!-- Cart Header -->
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h3 class="text-lg font-bold text-aura-900">Tu Carrito</h3>
                <span id="cart-items-count-badge" class="bg-gray-100 text-xs px-2 py-0.5 rounded-full font-semibold">0 items</span>
            </div>
            <button onclick="toggleCart(false)" class="p-2 text-gray-400 hover:text-black transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Cart Items List -->
        <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
            <!-- Renderizado dinámico -->
        </div>

        <!-- Cart Footer -->
        <div class="p-6 border-t border-gray-100 bg-aura-50">
            <div class="flex justify-between items-center mb-2 text-sm text-gray-600">
                <span>Subtotal:</span>
                <span id="cart-subtotal" class="font-semibold text-aura-900">bs 0.00</span>
            </div>
            <div class="flex justify-between items-center mb-4 text-sm text-gray-600">
                <span>Envío estimado:</span>
                <span class="text-green-600 font-medium">GRATIS</span>
            </div>
            <div class="flex justify-between items-center mb-6 text-base font-bold text-aura-900 pt-2 border-t border-gray-200">
                <span>Total:</span>
                <span id="cart-total" class="text-xl">$0.00</span>
            </div>
            <button onclick="checkout()" class="w-full py-3.5 bg-aura-900 hover:bg-black text-white text-sm font-semibold rounded-xl transition-colors shadow-lg flex items-center justify-center space-x-2">
                <span>Procesar Pago</span>
                <i class="fa-solid fa-arrow-right text-xs"></i>
            </button>
        </div>
    </aside>

    <!-- MODAL DE USUARIO / LOGIN SIMPLIFICADO -->
    <div id="user-modal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 relative shadow-2xl">
            <button onclick="toggleModal('user-modal')" class="absolute top-4 right-4 text-gray-400 hover:text-black">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-aura-900">Iniciar Sesión</h3>
                <p class="text-xs text-gray-500 mt-1">Accede a tus pedidos y lista de deseos</p>
            </div>
            <form onsubmit="handleLogin(event)" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" required placeholder="tu@email.com" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-aura-accent">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Contraseña</label>
                    <input type="password" required placeholder="••••••••" class="w-full px-4 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-aura-accent">
                </div>
                <button type="submit" class="w-full py-3 bg-aura-900 hover:bg-black text-white text-xs font-bold rounded-lg transition-colors">
                    Entrar
                </button>
            </form>
        </div>
    </div>

    <!-- NOTIFICACIÓN TOAST SIMULADA -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 bg-aura-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center space-x-3 transform translate-y-20 opacity-0 toast-slide">
        <i class="fa-solid fa-circle-check text-green-400 text-lg"></i>
        <span id="toast-message" class="text-xs font-medium">Producto añadido al carrito</span>
    </div>

    <!-- JAVASCRIPT DE LA APLICACIÓN -->
    <script>
        // Array de datos de productos populares
        const PRODUCTS = [
            {
                id: 1,
                name: "Abrigo Oversize Lana Premium",
                category: "Mujer",
                price: 189.00,
                rating: 4.9,
                reviews: 42,
                tag: "Bestseller",
                image: "https://images.unsplash.com/photo-1539533018447-63fcce2678e3?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 2,
                name: "Chaqueta Blazer Estructurada",
                category: "Hombre",
                price: 145.00,
                rating: 4.8,
                reviews: 28,
                tag: "Nuevo",
                image: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 3,
                name: "Jersey Algodón Orgánico Neutral",
                category: "Mujer",
                price: 79.50,
                rating: 4.7,
                reviews: 56,
                tag: null,
                image: "https://images.unsplash.com/photo-1576995853123-5a10305d93c0?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 4,
                name: "Pantalón Minimalist Tailored",
                category: "Hombre",
                price: 98.00,
                rating: 4.6,
                reviews: 19,
                tag: null,
                image: "https://images.unsplash.com/photo-1479064555552-3ef4979f8908?q=80&w=800&auto=format&fit=crop"
            }
        ];

        // Estado Global del Carrito
        let cart = [];

        // Inicialización de la App
        window.addEventListener('DOMContentLoaded', () => {
            renderProducts();
            setupEventListeners();
        });

        // Configuración de listeners generales
        function setupEventListeners() {
            const mobileBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            mobileBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // Renderizado de tarjetas de productos
        function renderProducts() {
            const container = document.getElementById('products-grid');
            container.innerHTML = PRODUCTS.map(product => `
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                    <div>
                        <!-- Imagen con hover zoom y tag -->
                        <div class="relative h-80 w-full overflow-hidden bg-gray-100">
                            <img src="${product.image}" 
                                 alt="${product.name}" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='https://placehold.co/500x600/eee/333?text=AURA+Prenda';">
                            ${product.tag ? `<span class="absolute top-3 left-3 bg-aura-900 text-white text-[10px] font-bold tracking-wider uppercase px-2.5 py-1 rounded-full">${product.tag}</span>` : ''}
                            
                            <!-- Wishlist button overlay -->
                            <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors">
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </div>

                        <!-- Detalles del producto -->
                        <div class="p-5">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">${product.category}</span>
                                <div class="flex items-center space-x-1 text-amber-400 text-xs">
                                    <i class="fa-solid fa-star"></i>
                                    <span class="text-gray-700 font-semibold text-[11px]">${product.rating}</span>
                                </div>
                            </div>
                            <h3 class="text-sm font-semibold text-aura-900 group-hover:text-aura-accent transition-colors">${product.name}</h3>
                            <p class="text-base font-bold text-aura-900 mt-2">${product.price.toFixed(2)} Bs</p>
                        </div>
                    </div>

                    <!-- Botón de añadir al carrito -->
                    <div class="px-5 pb-5">
                        <button onclick="addToCart(${product.id})" class="w-full py-2.5 bg-gray-100 hover:bg-aura-900 hover:text-white text-aura-900 text-xs font-bold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>Añadir al Carrito</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Lógica del Carrito
        function addToCart(productId) {
            const product = PRODUCTS.find(p => p.id === productId);
            if (!product) return;

            const existingIndex = cart.findIndex(item => item.id === productId);
            if (existingIndex > -1) {
                cart[existingIndex].quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }

            updateCartUI();
            showToast(`"${product.name}" añadido al carrito`);
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartUI();
        }

        function updateQuantity(productId, delta) {
            const item = cart.find(i => i.id === productId);
            if (item) {
                item.quantity += delta;
                if (item.quantity <= 0) {
                    removeFromCart(productId);
                } else {
                    updateCartUI();
                }
            }
        }

        function updateCartUI() {
            // Actualizar contadores
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountBadge = document.getElementById('cart-count');
            const itemsBadge = document.getElementById('cart-items-count-badge');
            
            cartCountBadge.innerText = totalItems;
            itemsBadge.innerText = `${totalItems} ${totalItems === 1 ? 'item' : 'items'}`;

            if (totalItems > 0) {
                cartCountBadge.classList.remove('scale-0');
            } else {
                cartCountBadge.classList.add('scale-0');
            }

            // Renderizar items
            const container = document.getElementById('cart-items-container');
            if (cart.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fa-solid fa-bag-shopping text-4xl text-gray-300 mb-3 block"></i>
                        <p class="text-sm text-gray-500">Tu carrito está vacío.</p>
                    </div>
                `;
            } else {
                container.innerHTML = cart.map(item => `
                    <div class="flex items-center space-x-4 border-b border-gray-100 pb-4">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-20 object-cover rounded-lg bg-gray-100">
                        <div class="flex-1">
                            <h4 class="text-xs font-semibold text-aura-900">${item.name}</h4>
                            <p class="text-xs font-bold text-gray-900 mt-1">$${item.price.toFixed(2)}</p>
                            
                            <!-- Selector de cantidad -->
                            <div class="flex items-center space-x-2 mt-2">
                                <button onclick="updateQuantity(${item.id}, -1)" class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded flex items-center justify-center text-xs font-bold">-</button>
                                <span class="text-xs font-semibold px-2">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 1)" class="w-6 h-6 bg-gray-100 hover:bg-gray-200 rounded flex items-center justify-center text-xs font-bold">+</button>
                            </div>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500 p-1">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `).join('');
            }

            // Actualizar Totales
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            document.getElementById('cart-subtotal').innerText = `$${subtotal.toFixed(2)}`;
            document.getElementById('cart-total').innerText = `$${subtotal.toFixed(2)}`;
        }

        function toggleCart(open) {
            const drawer = document.getElementById('cart-drawer');
            const overlay = document.getElementById('cart-overlay');
            if (open) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
                drawer.classList.remove('translate-x-full');
            } else {
                overlay.classList.add('opacity-0', 'pointer-events-none');
                drawer.classList.add('translate-x-full');
            }
        }

        // Modales y Alertas Interactivas
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle('opacity-0');
            modal.classList.toggle('pointer-events-none');
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function copyCoupon() {
            const code = document.getElementById('coupon-code').innerText;
            navigator.clipboard.writeText(code);
            showToast("¡Código BIENVENIDO20 copiado!");
        }

        function handleNewsletter(e) {
            e.preventDefault();
            const input = document.getElementById('newsletter-email');
            showToast("¡Gracias por suscribirte a BaggyCulture!");
            input.value = '';
        }

        function handleLogin(e) {
            e.preventDefault();
            toggleModal('user-modal');
            showToast("Sesión iniciada correctamente");
        }

        function checkout() {
            if (cart.length === 0) {
                showToast("Añade productos antes de pagar");
                return;
            }
            alert("¡Gracias por tu compra de prueba en AURA Studio!");
            cart = [];
            updateCartUI();
            toggleCart(false);
        }
    </script>
</body>
</html>