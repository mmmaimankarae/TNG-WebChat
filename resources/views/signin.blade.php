<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
@vite('resources/css/app.css')

  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>เข้าสู่ระบบ</title>
</head>
<body class="font-[Kodchasan]  h-screen flex flex-col items-center justify-center">
  <div class="sm:mx-auto sm:w-full sm:max-w-md">
    <img class="mx-auto h-auto w-auto" src="../../Pictures/logo2.png" alt="TANGNAM_LOGO">
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-lg">
    <form class="space-y-6">
      <div>
        <label for="accName" class="block font-xl font-medium">ชื่อผู้ใช้</label>
        <div class="mt-2">
          <input type="text" name="accName" id="accName" autocomplete="accName" required 
          class="block w-full rounded-md bg-white px-3 py-2 text-base outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-none focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
          placeholder:text-gray-400 placeholder:text-sm" placeholder=" โปรดกรอกชื่อผู้ใช้">
        </div>
      </div>

      <div>
        <label for="password" class="block font-xl font-medium">รหัสผ่าน</label>
        <div class="mt-2 relative">
          <input type="password" name="password" id="password" required 
          class="block w-full rounded-md bg-white px-3 py-2 text-base outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-none focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
          placeholder:text-gray-400 placeholder:text-sm" placeholder=" โปรดกรอกรหัสผ่าน">
          <!-- Icon To Show and Hide Password -->
          <div class="absolute text-2xl right-2 top-1/2 transform -translate-y-1/2 cursor-pointer">
            <i id="showPass" class='bx bxs-show block' style='color: black;'></i>
            <i id="hidePass" class='bx bxs-hide' style="color: black; display: none;"></i>
          </div>
        </div>
        <div class="flex justify-end mt-2">
          <button id="showForgotPopup" class="text-sm font-semibold text-blue-600 hover:text-blue-900" type="button">
            ลืมรหัสผ่าน?
          </button>
        </div>
      </div>
      <p id="errorInput" class="flex justify-center text-sm text-red-500"></p>
      <div class="flex justify-center">
        <button onclick="validation()" type="button" class="rounded-full bg-[#FF0000] px-10 py-1.5 text-base font-semibold text-white shadow-sm hover:bg-slate-400">เข้าสู่ระบบ</button>
      </div>
    </form>
    
    <div id="forgotPopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-black bg-opacity-50">
      <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-lg shadow">
          <button id="closeForgotPopup" type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center">
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
          </button>
          <div class="p-4 md:p-5 text-center">
            <svg class="mx-auto mb-4 text-amber-300 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
            </svg>
            <p class="mb-3 font-normal text-gray-500"> กรุณากรอกชื่อผู้ใช้งานของคุณ แล้วกดปุ่ม "ตกลง" เพื่อเข้าสู่การตั้งรหัสผ่านใหม่</p>
            <p id="errorReset" class="mb-2 text-sm"></p>
            <form>
              <input type="text" name="forgotAcc" id="forgotAcc"  autocomplete="accName"required 
                class="block w-full rounded-md bg-white px-3 py-2 mb-3 text-base outline outline-1 -outline-offset-1 outline-gray-300 focus:outline-none focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
                placeholder:text-gray-400 placeholder:text-sm" placeholder=" โปรดกรอกชื่อผู้ใช้">
              <button onclick="checkInfo()" type="button" class="rounded-lg bg-[#FF0000] px-10 py-1.5 text-white shadow-sm hover:bg-slate-400">ตกลง</button>
              <button id="cancelForgotPopup" type="button" class="rounded-lg bg-white px-10 py-1.5 shadow-sm border broder-gray-200 hover:bg-slate-200">ยกเลิก</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    /* Show and Hide Password */
    const passwordInput = document.getElementById('password');
    const showIcon = document.getElementById('showPass');
    const hideIcon = document.getElementById('hidePass');

    showIcon.addEventListener('click', () => {
      passwordInput.setAttribute('type', 'text');
      showIcon.style.display = 'none';
      hideIcon.style.display = 'block';
    });

    hideIcon.addEventListener('click', () => {
      passwordInput.setAttribute('type', 'password');
      showIcon.style.display = 'block';
      hideIcon.style.display = 'none';
    });

    /* Show and Hide Forgot Password Popup */
    const showForgotPopup = document.getElementById('showForgotPopup');
    const closeForgotPopup = document.getElementById('closeForgotPopup');
    const cancelForgotPopup = document.getElementById('cancelForgotPopup');

    showForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.remove('hidden');
    });

    closeForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.add('hidden');
      errorReset.innerHTML = '';
    });

    cancelForgotPopup.addEventListener('click', () => {
      forgotPopup.classList.add('hidden');
      errorReset.innerHTML = '';
    });

    /* Validation */
    function validation() {
      const accName = document.getElementById('accName');
      const pass = document.getElementById('password');

      if (accName.value == '' || pass.value == '') {
        errorInput.innerHTML = 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง';
      } else {
        errorInput.innerHTML = '';
        $.ajax({
          type: 'POST',
          url: '../../Backend/Authen/CheckUser.php',
          data: {
            acc: accName.value,
            pass: pass.value
          },
          success: function(response) {
            if (response) {
              window.location.href = '../../Frontend/IsPages/listOfNewTask.php';
            } else {
              errorInput.innerHTML = 'ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง';
            }
          }
        });
      }
    }

    /* Check Account for Reset Password*/
    function checkInfo() {
      event.preventDefault();
      const forgotAcc = document.getElementById('forgotAcc').value;

      if (forgotAcc != '') {
        $.ajax({
          type: 'POST',
          url: '../../Backend/Authen/CheckUser.php',
          data: {
            acc: forgotAcc
          },
          success: function(response) {
            if (response) {
              $.ajax({
                type: 'POST',
                url: '../../Backend/Authen/UpdatePassword.php',
                data: {
                  acc: forgotAcc
                },
                success: function(response) {
                  if (response) {
                    errorReset.classList.remove('hidden');
                    errorReset.classList.remove('text-red-500');
                    errorReset.classList.add('text-green-600');
                    errorReset.innerHTML = 'ระบบทำการตั้งรหัสผ่านใหม่ให้คุณแล้ว กดปุ่ม "ปิด" หรือ "ยกเลิก" และใส่รหัสที่ตกลงกันไว้';
                  }
                }
              });
            } else {
              errorReset.classList.remove('text-green-600');
              errorReset.classList.add('text-red-500');
              forgotPopup.classList.remove('hidden');
              errorReset.innerHTML = 'ชื่อผู้ใช้งานไม่ถูกต้อง';
            }
          }
        });
      } else {
        errorReset.innerHTML = 'โปรดกรอกชื่อผู้ใช้งาน';
      }
    }
  </script>
</body>
</html>