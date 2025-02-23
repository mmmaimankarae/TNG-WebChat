document.addEventListener("DOMContentLoaded", () => {
    /* Show and Hide Quotation Popup */
    const showQuotationPopup = document.getElementById('showQuotationPopup');
    const quotationPopup = document.getElementById('quotationPopup');
    const cancelQuotaPopup = document.getElementById('cancelQuotaPopup');
    if (showQuotationPopup) {
        showQuotationPopup.addEventListener('click', () => {
            quotationPopup.classList.remove('hidden');
        });

        cancelQuotaPopup.addEventListener('click', () => {
            quotationPopup.classList.add('hidden');
        });
    }

    const showInvoicePopup = document.getElementById('showInvoicePopup');
    const invoicePopup = document.getElementById('invoicePopup');
    const cancelInvoicePopup = document.getElementById('cancelInvoicePopup');
    if (showInvoicePopup) {
        showInvoicePopup.addEventListener('click', () => {
            invoicePopup.classList.remove('hidden');
        });

        cancelInvoicePopup.addEventListener('click', () => {
            invoicePopup.classList.add('hidden');
        });
    }

    const showPaymentPopup = document.getElementById('showPaymentPopup');
    const paymentPopup = document.getElementById('paymentPopup');
    const cancelPaymentPopup = document.getElementById('cancelPaymentPopup');
    if (showPaymentPopup) {
        showPaymentPopup.addEventListener('click', () => {
            paymentPopup.classList.remove('hidden');
        });

        cancelPaymentPopup.addEventListener('click', () => {
            paymentPopup.classList.add('hidden');
        });
    }

    const quotaCodeInput = document.getElementById('quotaCodeInput');
    if (quotaCodeInput) {
        quotaCodeInput.addEventListener('input', () => {
            document.getElementById('quotaCode').value = quotaCodeInput.value;
        });
    }
});

function updateTotalPrice(radio) {
    document.getElementById('totalPrice').value = radio.value;
    document.getElementById('version').value = radio.getAttribute('data-version');
    const quotaCodeInput = document.getElementById('quotaCodeInput');
    if (quotaCodeInput != radio.getAttribute('data-quota-code')) {
        quotaCodeInput.value = radio.getAttribute('data-quota-code');
    }
    document.getElementById('quotaCode').value = quotaCodeInput.value;
}