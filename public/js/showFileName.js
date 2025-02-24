document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('data_csv');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label').textContent = fileName;
        });
    }

    var fileInput2 = document.getElementById('data_csv2');
    if (fileInput2) {
        fileInput2.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label2').textContent = fileName;
        });
    }
});

function showFileName(input) {
    var fileName = input.files[0].name;
    var fileLabel = document.getElementById('fileName_' + input.id.split('_')[1]);
    fileLabel.textContent = fileName;
}