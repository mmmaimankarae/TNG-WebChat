document.addEventListener('DOMContentLoaded', function() {
  const openDesc = document.getElementById('openDesc');
  if (openDesc) {
    openDesc.addEventListener('click', function() {
      const payment = document.getElementById('payment');
      payment.classList.toggle('hidden');
    });
  }

  var desc = document.getElementById('desc');
  if (desc) {
    desc.placeholder = 
    "กรอกข้อความ (ไม่เกิน 800 ตัวอักษร)\n ** จำนวนเงิน ** แทนการระบุจำนวนเงิน \n \t"
    + "ยอดชำระ คือ ** จำนวนเงิน ** จะได้ ยอดชำระ คือ 3,500.00 บาท \n \t\t\t"
    + "สามารถเว้นวรรค ขึ้นบรรทัดใหม่ tab หรือ ใส่ 😊 ได้ "
  }

  var currentDesc = document.getElementById('currentDesc');
  if (currentDesc) {
    currentDesc.addEventListener('click', function() {
      var dataDesc = document.getElementById('dataDesc');
      var desc = document.getElementById('desc');
      if (desc.value === dataDesc.value) {
        desc.value = '';
      } else {
        desc.value = dataDesc.value;
      }
    });
  }
});