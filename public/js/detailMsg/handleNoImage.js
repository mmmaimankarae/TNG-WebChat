function handleImageError(image) {
    /* ซ่อนภาพที่เกิดปัญหา */
    image.style.display = 'none';

    /* ถ้าหาไฟล์ภาพใน storage ไม่เจอ */
    if (image.src.includes('/storage')) {
        let errorElement = document.createElement('div');
        errorElement.className = 'flex justify-center mt-2 text-xs text-red-500 font-medium';
        errorElement.textContent = 'รูปภาพหมดอายุ';
        let imageButton = image.closest('button');
        imageButton.insertAdjacentElement('afterend', errorElement);
        console.clear();
    } else {
        let downloadLink = document.getElementById('downloadLink');
        if (downloadLink) {
            downloadLink.textContent = "รูปภาพหมดอายุ ไม่สามารถดาวน์โหลดได้";
            downloadLink.removeAttribute('href');
            downloadLink.classList.remove('text-blue-950', 'underline', 'justify-end');
            downloadLink.classList.add('text-red-500', 'justify-center', 'font-medium');
        }
        let form = image.closest('form');
        if (form) {
            let submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.disabled = true;
            }
        }
    }
}