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
  document.getElementById('cusNameCopy').addEventListener('click', () => {
    copyToClipboard('cusName', 'icon-cusNamecopy', 'suc-cusNamecopy');
  });

  document.getElementById('cusCodeCopy').addEventListener('click', () => {
    copyToClipboard('cusCode', 'icon-cusCodecopy', 'suc-cusCodecopy');
  });

  document.getElementById('prodCodecopy').addEventListener('click', () => {
    copyToClipboard('prodCode', 'icon-prodCodecopy', 'suc-prodCodecopy');
  });

  document.getElementById('prodNamecopy').addEventListener('click', () => {
    copyToClipboard('prodName', 'icon-prodNamecopy', 'suc-prodNamecopy');
  });
});
