document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('file_input');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label').textContent = fileName;
        });
    }
});