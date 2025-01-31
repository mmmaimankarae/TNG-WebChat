function handleImageError() {
    let downloadLink = document.getElementById('downloadLink');
    let form = document.getElementById('imageForm');

    if (downloadLink) {
        downloadLink.textContent = "รูปภาพหมดอายุ ไม่สามารถแสดงได้";
        downloadLink.removeAttribute('href');
        downloadLink.classList.remove('text-blue-950');
        downloadLink.classList.remove('underline');
        downloadLink.classList.remove('justify-end');
        downloadLink.classList.add('text-red-500');
        downloadLink.classList.add('justify-center');
    }

    if (form) {
        form.remove();
    }
}