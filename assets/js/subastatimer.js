document.addEventListener('DOMContentLoaded', () => {
    function actualizarContadores() {
        const barrasTiempo = document.querySelectorAll('.barra-tiempo-restante');

        barrasTiempo.forEach(barra => {
            const fechaFinStr = barra.getAttribute('data-fin');
            if (!fechaFinStr) return;

            
            const fechaFin = new Date(fechaFinStr.replace(' ', 'T')).getTime();
            const ahora = new Date().getTime();
            const distancia = fechaFin - ahora;
            const elementoReloj = barra.querySelector('.cronometro-tiempo');

            if (distancia <= 0) {
                elementoReloj.innerHTML = "FINALIZADA";
                elementoReloj.style.color = "#ff4d4d";
                return;
            }

            
            const horas = Math.floor(distancia / (1000 * 60 * 60));
            const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

            
            const h = String(horas).padStart(2, '0');
            const m = String(minutos).padStart(2, '0');
            const s = String(segundos).padStart(2, '0');

            elementoReloj.innerHTML = `${h}h ${m}m ${s}s`;
        });
    }

    // Ejecutar inmediatamente al cargar y actualizar cada 1 segundo
    actualizarContadores();
    setInterval(actualizarContadores, 1000);
});