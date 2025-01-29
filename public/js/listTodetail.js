document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".clickable-row").forEach(row => {
    row.addEventListener("click", function () {
      let taskCode = document.getElementById("taskCode").value;
      let cusCode = document.getElementById("cusCode").value;
      let cusName = document.getElementById("cusName").value;

      /* ตรวจสอบว่าอยู่ที่ sale-admin หรือ internal-sale */
      let currentPath = window.location.pathname;
      let newPath = currentPath.includes("sale-admin") ? 
        "/sale-admin/current-tasks/detail-message" : 
        "/internal-sale/current-tasks/detail-message";

      /* กำหนดค่าให้กับฟอร์ม */
      document.getElementById("taskForm").action = newPath;
      document.getElementById("taskCode").value = taskCode;
      document.getElementById("cusCode").value = cusCode;
      document.getElementById("cusName").value = cusName;

      /* ส่งฟอร์ม */
      if (currentPath.includes("internal-sale") || currentPath.includes("sale-admin")) {
        document.getElementById("taskForm").submit();
      }
    });
  });
});
