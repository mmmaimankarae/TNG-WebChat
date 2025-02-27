document.addEventListener('DOMContentLoaded', function() {
    var data_csv = document.getElementById('data_csv');
    if (data_csv) {
        data_csv.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label').textContent = fileName;
        });
    }

    var data_csv2 = document.getElementById('data_csv2');
    if (data_csv2) {
        data_csv2.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label2').textContent = fileName;
        });
    }

    var file_input = document.getElementById('file_input');
    if (file_input) {
        file_input.addEventListener('change', function() {
            var fileName = this.files[0].name;
            document.getElementById('file_input_label').textContent = fileName;
        });
    }
});

function showFileName(input) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (input.files[0].size > maxSize) {
        input.value = '';
        return;
    }

    var fileName = input.files[0].name;
    var fileLabel = document.getElementById('fileName_' + input.id.split('_')[1]);
    fileLabel.textContent = fileName;
}