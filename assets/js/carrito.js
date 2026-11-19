document.addEventListener('DOMContentLoaded', () => {
    
    const formDetalle = document.querySelector('.purchase-form');

    if (formDetalle) {
        formDetalle.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = formDetalle.querySelector('.btn-add-cart');
            const originalText = submitBtn ? submitBtn.textContent : 'Añadir al carrito';

            const formData = new FormData(formDetalle);
            
            const hasColor = !!formDetalle.querySelector('input[name="color"]');
            const hasTalla = !!formDetalle.querySelector('input[name="talla"]');

            if (hasColor && !formData.get('color')) {
                alert('Por favor selecciona un color.');
                return;
            }

            if (hasTalla && !formData.get('talla')) {
                alert('Por favor selecciona una talla.');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Añadiendo...';
            }

            try {
                const actionUrl = formDetalle.getAttribute('action') || 'agregar-carrito.php';

                const response = await fetch(actionUrl, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.status === 'auth_required') {
                    alert('Debes iniciar sesión para agregar prendas a tu carrito.');
                    window.location.href = 'login.php'; 
                    return;
                }

                if (data.status === 'success') {
                    actualizarContadorNavbar(data.total_items);
                    if (submitBtn) {
                        submitBtn.textContent = '¡Añadido al carrito!';
                    }
                    setTimeout(() => {
                        window.location.href = 'carrito.php';
                    }, 600);
                } else {
                    alert(data.message || 'Error al agregar el producto');
                    if (submitBtn) {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }
                }
            } catch (error) {
                console.error('Error al agregar al carrito:', error);
                alert('Ocurrió un error al procesar la solicitud.');
                if (submitBtn) {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            }
        });
    }

    
    function actualizarContadorNavbar(total) {
        const cartBadge = document.querySelector('.cart-count, #cart-badge');
        if (cartBadge) {
            cartBadge.textContent = total;
            cartBadge.style.display = total > 0 ? 'inline-block' : 'none';
        }
    }
    
    document.querySelectorAll('.btn-add').forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault();
        const idProducto = btn.dataset.id;
        if (idProducto) {
            window.location.href = `detalles.php?id_producto=${idProducto}`;
        }
    });
});
});