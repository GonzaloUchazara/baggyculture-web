<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hombre | AURA Studio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .cart-slide { transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        .toast-slide { transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #999; }
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
                    <a href="baggyinicio.php" class="hover:text-black transition-colors py-2">Inicio</a>
                    <a href="#" class="text-black font-bold py-2 border-b-2 border-aura-accent">Catalogo</a>
                    <a href="subastas.php" class="hover:text-black transition-colors py-2">Subastas</a>
                    <a href="index.html#categorias" class="hover:text-black transition-colors py-2">Asesores</a>
                </nav>

                <div class="flex items-center space-x-5">
                    <button id="cart-btn" onclick="toggleCart(true)" class="text-gray-700 hover:text-black transition-colors p-1 relative" title="Carrito">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                        <span id="cart-count" class="absolute -top-1 -right-2 bg-aura-accent text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center scale-0 transition-transform duration-200">0</span>
                    </button>
                </div>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-white border-b border-gray-200 px-4 py-4 space-y-3">
            <a href="index.html" class="block text-base font-medium text-gray-800">Inicio</a>
            <a href="#" class="block text-base font-bold text-aura-accent">Hombre</a>
            <a href="index.html#categorias" class="block text-base font-medium text-gray-800">Mujer</a>
            <a href="index.html#categorias" class="block text-base font-medium text-gray-800">Accesorios</a>
        </div>
    </header>

    <section class="relative bg-aura-900 text-white py-16 sm:py-24 mb-12">
        <div class="absolute inset-0 z-0 opacity-40">
            <img src="https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?q=80&w=2000&auto=format&fit=crop" 
                 alt="Moda Hombre Banner" 
                 class="w-full h-full object-cover object-center"
                 onerror="this.onerror=null; this.src='https://placehold.co/1920x600/171717/ffffff?text=Moda+Hombre';">
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <span class="text-xs text-aura-accent uppercase tracking-widest font-semibold">Colección Exclusiva</span>
            <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mt-2">Ropa de Hombre</h1>
            <p class="mt-3 text-sm sm:text-base text-gray-300 max-w-xl">
                Cortes limpios, materiales premium y estilo contemporáneo diseñados para el uso diario.
            </p>
        </div>
    </section>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-gray-200">
            <p class="text-xs text-gray-500 font-medium">Mostrando <span class="text-aura-900 font-bold">5 productos</span></p>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Ordenar por:</span>
                <select class="text-xs font-semibold bg-white border border-gray-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-1 focus:ring-aura-accent">
                    <option>Destacados</option>
                    <option>Precio: Menor a Mayor</option>
                    <option>Precio: Mayor a Menor</option>
                </select>
            </div>
        </div>

        <div id="products-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        </div>
    </main>

    <footer class="bg-aura-900 text-gray-400 text-xs py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-6">
            <div>
                <a href="index.html" class="text-xl font-extrabold tracking-widest text-white uppercase">
                    BaggyCulture<span class="text-aura-accent">.</span>
                </a>
                <p class="mt-2 text-gray-500">&copy; 2026 Baggy Studio. Todos los derechos reservados.</p>
            </div>
            <div class="flex space-x-6 text-sm text-gray-400">
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#" class="hover:text-white transition-colors"><i class="fa-brands fa-x-twitter"></i></a>
            </div>
        </div>
    </footer>

    <div id="cart-overlay" class="fixed inset-0 bg-black/50 z-50 opacity-0 pointer-events-none transition-opacity duration-300" onclick="toggleCart(false)"></div>
    <aside id="cart-drawer" class="fixed top-0 right-0 h-full w-full max-w-md bg-white z-50 shadow-2xl transform translate-x-full cart-slide flex flex-col">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <h3 class="text-lg font-bold text-aura-900">Tu Carrito</h3>
                <span id="cart-items-count-badge" class="bg-gray-100 text-xs px-2 py-0.5 rounded-full font-semibold">0 items</span>
            </div>
            <button onclick="toggleCart(false)" class="p-2 text-gray-400 hover:text-black transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
        </div>

        <div class="p-6 border-t border-gray-100 bg-aura-50">
            <div class="flex justify-between items-center mb-4 text-base font-bold text-aura-900">
                <span>Total:</span>
                <span id="cart-total" class="text-xl">$0.00</span>
            </div>
            <button onclick="checkout()" class="w-full py-3.5 bg-aura-900 hover:bg-black text-white text-sm font-semibold rounded-xl transition-colors shadow-lg">
                Procesar Pago
            </button>
        </div>
    </aside>

    <div id="toast" class="fixed bottom-5 right-5 z-50 bg-aura-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center space-x-3 transform translate-y-20 opacity-0 toast-slide">
        <i class="fa-solid fa-circle-check text-green-400 text-lg"></i>
        <span id="toast-message" class="text-xs font-medium">Producto añadido al carrito</span>
    </div>

    <script>
        const MEN_PRODUCTS = [
            {
                id: 101,
                name: "Chaqueta Blazer Estructurada",
                category: "Sastrería",
                price: 145.00,
                rating: 4.8,
                image: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 102,
                name: "Pantalón Minimalist Tailored",
                category: "Pantalones",
                price: 98.00,
                rating: 4.6,
                image: "https://images.unsplash.com/photo-1479064555552-3ef4979f8908?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 103,
                name: "Camisa Oxford Algodón Puro",
                category: "Camisas",
                price: 65.00,
                rating: 4.9,
                image: "https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 104,
                name: "Abrigo Lana Merino Urbano",
                category: "Abrigos",
                price: 210.00,
                rating: 5.0,
                image: "https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=800&auto=format&fit=crop"
            },
            {
                id: 105,
                name: "Sudadera Essential Crewneck",
                category: "Casuales",
                price: 52.00,
                rating: 4.7,
                image: "https://images.unsplash.com/photo-1556905055-8f358a7a47b2?q=80&w=800&auto=format&fit=crop"
            }
        ];

        let cart = [];

        window.addEventListener('DOMContentLoaded', () => {
            renderProducts();
            document.getElementById('mobile-menu-btn').addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.toggle('hidden');
            });
        });

        function renderProducts() {
            const container = document.getElementById('products-grid');
            container.innerHTML = MEN_PRODUCTS.map(product => `
                <div class="group relative bg-white rounded-2xl overflow-hidden border border-gray-100 flex flex-col justify-between hover:shadow-xl transition-all duration-300">
                    <div>
                        <div class="relative h-72 w-full overflow-hidden bg-gray-100">
                            <img src="${product.image}" 
                                 alt="${product.name}" 
                                 class="h-full w-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x500/eee/333?text=Hombre';">
                            <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/80 backdrop-blur-md flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors">
                                <i class="fa-regular fa-heart text-xs"></i>
                            </button>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">${product.category}</span>
                                <div class="flex items-center space-x-1 text-amber-400 text-xs">
                                    <i class="fa-solid fa-star"></i>
                                    <span class="text-gray-700 font-semibold text-[10px]">${product.rating}</span>
                                </div>
                            </div>
                            <h3 class="text-xs font-semibold text-aura-900 group-hover:text-aura-accent transition-colors line-clamp-1">${product.name}</h3>
                            <p class="text-sm font-bold text-aura-900 mt-2">${product.price.toFixed(2)}Bs</p>
                        </div>
                    </div>
                    <div class="px-4 pb-4">
                        <button onclick="addToCart(${product.id})" class="w-full py-2 bg-gray-100 hover:bg-aura-900 hover:text-white text-aura-900 text-xs font-bold rounded-xl transition-all duration-200 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-bag-shopping"></i>
                            <span>Agregar al carrito</span>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function addToCart(productId) {
            const product = MEN_PRODUCTS.find(p => p.id === productId);
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
                        <img src="${item.image}" alt="${item.name}" class="w-14 h-16 object-cover rounded-lg bg-gray-100">
                        <div class="flex-1">
                            <h4 class="text-xs font-semibold text-aura-900">${item.name}</h4>
                            <p class="text-xs font-bold text-gray-900 mt-0.5">$${item.price.toFixed(2)}</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <button onclick="updateQuantity(${item.id}, -1)" class="w-5 h-5 bg-gray-100 hover:bg-gray-200 rounded flex items-center justify-center text-xs font-bold">-</button>
                                <span class="text-xs font-semibold px-1">${item.quantity}</span>
                                <button onclick="updateQuantity(${item.id}, 1)" class="w-5 h-5 bg-gray-100 hover:bg-gray-200 rounded flex items-center justify-center text-xs font-bold">+</button>
                            </div>
                        </div>
                        <button onclick="removeFromCart(${item.id})" class="text-gray-400 hover:text-red-500 p-1">
                            <i class="fa-solid fa-trash-can text-xs"></i>
                        </button>
                    </div>
                `).join('');
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
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

        function showToast(message) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-message').innerText = message;
            toast.classList.remove('translate-y-20', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
            }, 3000);
        }

        function checkout() {
            if (cart.length === 0) {
                showToast("Añade productos antes de pagar");
                return;
            }
            alert("¡Gracias por tu compra en la sección de Hombre!");
            cart = [];
            updateCartUI();
            toggleCart(false);
        }
    </script>
</body>
</html>