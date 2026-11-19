document.addEventListener('DOMContentLoaded', () => {
    
    const formDetalle = document.querySelector('.purchase-form');

    if (formDetalle) {
        formDetalle.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = formDetalle.querySelector('.btn-add-cart');
            const originalText = submitBtn.textContent;

            
            const formData = new FormData(formDetalle);
            
            
            if (!formData.get('talla') || !formData.get('color')) {
                alert('Por favor selecciona una talla y un color.');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Añadiendo...';

            try {
                const response = await fetch('agregar_carrito.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'auth_required') {
                    alert('Debes iniciar sesión para agregar prendas a tu carrito.');
                    window.location.href = 'login.php'; 
                    return;
                }

                if (data.status === 'success') {
                    
                    actualizarContadorNavbar(data.total_items);
                    submitBtn.textContent = '¡Añadido!';
                    setTimeout(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                    }, 1500);
                } else {
                    alert(data.message || 'Error al agregar el producto');
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud.');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
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
});