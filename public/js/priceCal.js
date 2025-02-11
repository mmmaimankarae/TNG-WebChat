document.addEventListener('DOMContentLoaded', function() {
    const prodsPrice = document.getElementById('prodsPrice');
    const deliPrice = document.getElementById('deliPrice');
    const totalPrice = document.getElementById('totalPrice');

    function calculateTotal() {
        const value1 = parseFloat(prodsPrice.value) || 0;
        const value2 = parseFloat(deliPrice.value) || 0;
        totalPrice.value = (value1 + value2).toFixed(2);
    }

    function validateInput(event) {
        const input = event.target;
        input.value = input.value.replace(/[^0-9.]/g, '');
    }

    prodsPrice.addEventListener('input', calculateTotal);
    deliPrice.addEventListener('input', calculateTotal);

    prodsPrice.addEventListener('input', validateInput);
    deliPrice.addEventListener('input', validateInput);
});