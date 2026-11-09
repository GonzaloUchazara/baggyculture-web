// Función para cambiar la foto principal desde las miniaturas
function cambiarFoto(nuevaRuta) {
    const mainImg = document.getElementById('mainImage');
    if (mainImg) {
        mainImg.src = nuevaRuta;
    }
    document.querySelectorAll('.thumb, .miniatura').forEach(el => el.classList.remove('active'));
    if (window.event && window.event.target) {
        window.event.target.classList.add('active');
    }
}

// Botones de incremento rápido (+20, +50, +100)
function sumarIncremento(cantidad) {
    const input = document.getElementById('input-monto');
    const spanPrecio = document.getElementById('span-precio-actual');
    if (!input || !spanPrecio) return;
    
    const valorBase = parseFloat(spanPrecio.innerText.replace(',', '')) || 0;
    input.value = (valorBase + cantidad).toFixed(2);
}

// Temporizador de cuenta regresiva
const badgeReloj = document.getElementById('reloj-subasta');
if (badgeReloj) {
    let fechaFin = parseInt(badgeReloj.getAttribute('data-fin-ms'), 10);
    if (!fechaFin || isNaN(fechaFin)) {
        const fechaFinAttr = badgeReloj.getAttribute('data-fin');
        if (fechaFinAttr) {
            fechaFin = new Date(fechaFinAttr.replace(' ', 'T')).getTime();
        }
    }

    if (fechaFin) {
        function actualizarReloj() {
            const ahora = new Date().getTime();
            const distancia = fechaFin - ahora;

            if (distancia <= 0) {
                if (timer) clearInterval(timer);
                badgeReloj.innerText = "SUBASTA FINALIZADA";
                badgeReloj.classList.add('finalizada');
                
                const btn = document.getElementById('btn-enviar-puja');
                if (btn) {
                    btn.disabled = true;
                    btn.innerText = "Subasta Concluida";
                }
                const inputMonto = document.getElementById('input-monto');
                if (inputMonto) inputMonto.disabled = true;
                return;
            }

            const horas = Math.floor(distancia / (1000 * 60 * 60));
            const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

            const h = String(horas).padStart(2, '0');
            const m = String(minutos).padStart(2, '0');
            const s = String(segundos).padStart(2, '0');

            badgeReloj.innerText = `TIEMPO RESTANTE: ${h}h ${m}m ${s}s`;
        }

        actualizarReloj();
        const timer = setInterval(actualizarReloj, 1000);
    }
}

// Envío de puja por AJAX
async function enviarPuja(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('btn-enviar-puja');
    const feedback = document.getElementById('feedback-msg');
    const formData = new FormData(form);

    if (btn) {
        btn.disabled = true;
        btn.innerText = "Procesando oferta...";
    }
    if (feedback) {
        feedback.className = "feedback-alerta";
        feedback.style.display = "none";
    }

    try {
        const response = await fetch('../controllers/subastacontroler.php?accion=pujar', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            if (feedback) {
                feedback.className = "feedback-alerta exito";
                feedback.innerText = data.mensaje;
            }

            // Actualizar precio en pantalla
            const spanPrecio = document.getElementById('span-precio-actual');
            if (spanPrecio) spanPrecio.innerText = data.nuevo_precio;
            
            // Recalcular mínimo (+20 Bs por defecto de incremento)
            const nuevoMinimo = (parseFloat(data.nuevo_precio) + 20).toFixed(2);
            const labelMin = document.getElementById('label-minimo');
            const inputMonto = document.getElementById('input-monto');
            const spanGanador = document.getElementById('span-ganador');

            if (labelMin) labelMin.innerText = nuevoMinimo;
            if (inputMonto) {
                inputMonto.min = nuevoMinimo;
                inputMonto.value = nuevoMinimo;
            }
            if (spanGanador) spanGanador.innerText = "Tú (Líder actual)";
        } else {
            if (feedback) {
                feedback.className = "feedback-alerta error";
                feedback.innerText = data.mensaje;
            }
        }
    } catch (err) {
        if (feedback) {
            feedback.className = "feedback-alerta error";
            feedback.innerText = "Error de conexión al procesar la puja.";
        }
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerText = "Confirmar y enviar puja";
        }
    }
}
