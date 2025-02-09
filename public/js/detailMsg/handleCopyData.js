document.addEventListener('DOMContentLoaded', (event) => {
  function copyToClipboard(elementId, iconId, successId) {
    /* เลือก Element by ID */
    const copyText = document.getElementById(elementId);
    copyText.select();

    /* คัดลอกข้อความ */
    navigator.clipboard.writeText(copyText.value).then(() => {
      /* แสดงข้อความแจ้ง */
      document.getElementById(iconId).classList.add('hidden');
      document.getElementById(successId).classList.remove('hidden');

      // Revert back to original icon after 2 seconds
      setTimeout(() => {
        document.getElementById(iconId).classList.remove('hidden');
        document.getElementById(successId).classList.add('hidden');
      }, 2000);
    }).catch(err => {
      console.error('Failed to copy: ', err);
    });
  }

  // Add event listeners to copy buttons
  const cusNameCopy = document.getElementById('cusNameCopy');
  if (cusNameCopy) {
    cusNameCopy.addEventListener('click', () => {
      copyToClipboard('cusName', 'icon-cusNamecopy', 'suc-cusNamecopy');
    });
  }

  const cusCodeCopy = document.getElementById('cusCodeCopy');
  if (cusCodeCopy) {
    cusCodeCopy.addEventListener('click', () => {
      copyToClipboard('cusCode', 'icon-cusCodecopy', 'suc-cusCodecopy');
    });
  }

  const prodCodeCopy = document.getElementById('prodCodecopy');
  if (prodCodeCopy) {
    prodCodeCopy.addEventListener('click', () => {
      copyToClipboard('prodCode', 'icon-prodCodecopy', 'suc-prodCodecopy');
    });
  }

  const prodNameCopy = document.getElementById('prodNamecopy');
  if (prodNameCopy) {
    prodNameCopy.addEventListener('click', () => {
      copyToClipboard('prodName', 'icon-prodNamecopy', 'suc-prodNamecopy');
    });
  }
});
