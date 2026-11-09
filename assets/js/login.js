function initLoginModal() {
    const modal = document.getElementById('modalLogin');
    const btnOpen = document.getElementById('btnOpenLogin');
    const btnClose = document.getElementById('btnCloseModal');
    const cardFlip = document.getElementById('cardFlip');
    const cardFront = document.getElementById('cardFront');
    const cardBack = document.getElementById('cardBack');
    const btnToRegister = document.getElementById('btnToRegister');
    const btnToLogin = document.getElementById('btnToLogin');
    const formLogin = document.getElementById('formLogin');
    const formRegister = document.getElementById('formRegister');

    if (!modal) return;

    function openModal(e) {
        if (e) e.preventDefault();
        modal.classList.remove('hidden');
        requestAnimationFrame(() => {
            modal.classList.add('active');
        });
        const firstInput = modal.querySelector('input[name="usuario"]');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 150);
        }
    }

    function closeModal() {
        modal.classList.remove('active');
        setTimeout(() => {
            if (!modal.classList.contains('active')) {
                modal.classList.add('hidden');
                // Al cerrar el modal, restaurar vista al frente (login)
                if (cardFlip) {
                    cardFlip.classList.remove('flipped');
                }
            }
        }, 300);
    }

    if (btnOpen) {
        btnOpen.onclick = openModal;
    }

    if (btnClose) {
        btnClose.onclick = closeModal;
    }

    // Rotar a Registro
    if (btnToRegister && cardFlip) {
        btnToRegister.onclick = (e) => {
            e.preventDefault();
            cardFlip.classList.add('flipped');
            const firstRegInput = cardBack ? cardBack.querySelector('input[name="nombre"]') : null;
            if (firstRegInput) {
                setTimeout(() => firstRegInput.focus(), 350);
            }
        };
    }

    // Rotar de regreso a Login
    if (btnToLogin && cardFlip) {
        btnToLogin.onclick = (e) => {
            e.preventDefault();
            cardFlip.classList.remove('flipped');
            const firstLogInput = cardFront ? cardFront.querySelector('input[name="usuario"]') : null;
            if (firstLogInput) {
                setTimeout(() => firstLogInput.focus(), 350);
            }
        };
    }

    // cierre modal oscurozzz
    modal.onclick = (e) => {
        if (e.target === modal) {
            closeModal();
        }
    };

    // el escp
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // Envío del formulario de Login elimanao
     //if (formLogin) {
       // formLogin.addEventListener('submit', (e) => {
         //   e.preventDefault();
        //});
    //}

    // Envío del formulario de Registro
    if (formRegister) {
        formRegister.addEventListener('submit', async (e) => {
            e.preventDefault();

            let feedback = document.getElementById('registerFeedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.id = 'registerFeedback';
                feedback.style.fontSize = '0.85em';
                feedback.style.textAlign = 'center';
                feedback.style.marginTop = '8px';
                formRegister.appendChild(feedback);
            }

            feedback.style.display = 'block';
            feedback.style.color = '#38bdf8';
            feedback.textContent = 'Procesando registro...';

            const submitBtn = formRegister.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(formRegister);

            // Determinar la ruta correcta al controlador sin importar la página
            let controllerUrl = '../controllers/controlador.php?accion=registrar';
            const match = window.location.pathname.match(/^(.*?\/baggy_culture)\//i);
            if (match) {
                controllerUrl = match[1] + '/controllers/controlador.php?accion=registrar';
            }

            try {
                const response = await fetch(controllerUrl, {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    feedback.style.color = '#4ade80'; // Verde
                    feedback.textContent = data.message;
                    formRegister.reset();

                    // Regresar la tarjeta al Login después de 1.8 segundos
                    setTimeout(() => {
                        feedback.textContent = '';
                        feedback.style.display = 'none';
                        if (cardFlip) {
                            cardFlip.classList.remove('flipped');
                        }
                    }, 1800);
                } else {
                    feedback.style.color = '#f87171'; // Rojo
                    feedback.textContent = data.message;
                }
            } catch (error) {
                console.error('Error en el registro:', error);
                feedback.style.color = '#f87171';
                feedback.textContent = 'Error al comunicarse con el servidor. Revisa los datos ingresados.';
            } finally {
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLoginModal);
} else {
    initLoginModal();
}
