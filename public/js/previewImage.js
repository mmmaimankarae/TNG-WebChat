document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('file_input');
    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('preview-container');
                    const previewImage = document.getElementById('preview-image');
                    previewImage.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    var submitButton = document.getElementById('submitQuotation');
    if (submitButton) {
        submitButton.addEventListener('click', function() {
            document.getElementById('quotationForm').submit();
        });
    }
});