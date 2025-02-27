document.addEventListener('DOMContentLoaded', () => {
    const rows = document.querySelectorAll('.clickable-row');
    rows.forEach(row => {
      row.addEventListener('click', () => {
        rows.forEach(r => r.classList.remove('bg-red-200'));
        row.classList.add('bg-red-200');
        document.getElementById('branchCode').value = row.getAttribute('data-brch');
        console.log(row.getAttribute('data-brch'));
        document.getElementById('confirmButton').classList.remove('hidden');
      });
    });
});