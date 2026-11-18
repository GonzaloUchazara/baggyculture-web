const avatarTrigger = document.getElementById('avatarTrigger');
const inputFileFoto = document.getElementById('inputFileFoto');
const imgPerfil = document.getElementById('imgPerfil');
const savePhotoBox = document.getElementById('savePhotoBox');
const photoNameText = document.getElementById('photoNameText');
const btnCancelPhoto = document.getElementById('btnCancelPhoto');


const fotoOriginal = imgPerfil.src;


avatarTrigger.addEventListener('click', () => {
    inputFileFoto.click();
});

// previsualizacion guardar
inputFileFoto.addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('La imagen no puede pesar más de 2MB.');
        this.value = '';
        return;
    }

    const reader = new FileReader();
    reader.onload = (e) => {
        imgPerfil.src = e.target.result;
        photoNameText.textContent = `Archivo: ${file.name}`;
        savePhotoBox.style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// 3. Cancelar la selección
btnCancelPhoto.addEventListener('click', () => {
    inputFileFoto.value = '';
    imgPerfil.src = fotoOriginal;
    savePhotoBox.style.display = 'none';
});