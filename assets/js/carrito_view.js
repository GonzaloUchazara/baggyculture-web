document.addEventListener('DOMContentLoaded', () => {
    const cartContainer = document.querySelector('.cart-container');
    if (!cartContainer) return;

   
    cartContainer.addEventListener('click', async (e) => {
        const btnQty = e.target.closest('.btn-qty');
        const btnRemove = e.target.closest('.btn-remove');

        if (!btnQty && !btnRemove) return;

        const cartItem = (btnQty || btnRemove).closest('.cart-item');
        if (!cartItem) return;

        const idItem = cartItem.dataset.idItem;
        let action = '';

        if (btnQty) {
            action = btnQty.dataset.action; 
        } else if (btnRemove) {
            action = 'remove';
        }

   
        const buttonsInRow = cartItem.querySelectorAll('button');
        buttonsInRow.forEach(b => b.disabled = true);

        try {
            const response = await fetch('actualizar_carrito.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_item: idItem,
                    action: action
                })
            });

            const data = await response.json();

            if (data.status === 'auth_required') {
                alert('Tu sesión ha expirado. Inicia sesión nuevamente.');
                window.location.href = 'login.php';
                return;
            }

            if (data.status === 'success') {
                if (data.action_done === 'remove') {
                    
                    cartItem.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                    cartItem.style.opacity = '0';
                    cartItem.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        cartItem.remove();

                    
                        if (data.total_filas === 0) {
                            window.location.reload();
                        }
                    }, 250);
                } else {
                    
                    const qtyNumber = cartItem.querySelector('.qty-number');
                    if (qtyNumber) qtyNumber.textContent = data.item_cantidad;

                    
                    const subtotalEl = cartItem.querySelector('.cart-item-subtotal strong');
                    if (subtotalEl) subtotalEl.textContent = `Bs ${parseFloat(data.item_subtotal).toFixed(2)}`;
                }

                
                const summarySubtotal = document.getElementById('summary-subtotal');
                const summaryTotal = document.getElementById('summary-total');
                if (summarySubtotal) summarySubtotal.textContent = `Bs ${parseFloat(data.total_general).toFixed(2)}`;
                if (summaryTotal) summaryTotal.textContent = `Bs ${parseFloat(data.total_general).toFixed(2)}`;

                
                const cartBadge = document.querySelector('.cart-count, #cart-badge');
                if (cartBadge) {
                    cartBadge.textContent = data.total_prendas;
                    cartBadge.style.display = data.total_prendas > 0 ? 'inline-block' : 'none';
                }

            } else {
                alert(data.message || 'No se pudo actualizar el carrito.');
            }

        } catch (error) {
            console.error('Error al actualizar:', error);
            alert('Hubo un problema de conexión con el servidor.');
        } finally {
            buttonsInRow.forEach(b => b.disabled = false);
        }
    });
});