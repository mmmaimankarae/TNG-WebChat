<div id="forgotPopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-full max-w-md max-h-full p-4">
    <div class="relative bg-white rounded-lg shadow">
      <button id="closeForgotPopup" type="button" class="absolute top-3 end-2.5 inline-flex justify-center items-center w-8 h-8 ms-auto text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
      </button>
      <div class="p-4 md:p-5 text-center">
        <svg class="w-12 h-12 mx-auto mb-4 text-amber-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
        <p class="mb-3 font-normal text-gray-500"> กรุณากรอกชื่อผู้ใช้งานของคุณ แล้วกดปุ่ม "ตกลง" เพื่อเข้าสู่การตั้งรหัสผ่านใหม่</p>
        @error('successReset')
          <p id="successResetError" class="mb-2 text-sm text-green-600"> {{$message}} </p>
        @enderror

        @error('forgotAcc')
          <p id="forgotAccError"class="mb-2 text-sm text-red-500"> {{$message}} </p>
        @enderror
        <form method="POST" action="/authen/reset-forgot">
          @csrf
          <input type="text" name="forgotAcc" id="forgotAcc" autocomplete="accName" 
            class="block w-full px-3 py-2 mb-3 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
            placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อผู้ใช้">
          <input type="hidden" name="actionFor" value="forgotPass">
          <button type="submit" class="px-10 py-1.5 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
          <button id="cancelForgotPopup" type="button" class="px-10 py-1.5 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const hasErrors = @json($errors->has('forgotAcc') || $errors->has('successReset'));
    if (hasErrors) {
      document.getElementById('forgotPopup').classList.remove('hidden');
    }
  });
</script>