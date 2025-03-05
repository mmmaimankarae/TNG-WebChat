<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ $title }}</title>

  @vite('resources/css/app.css')
  
  <script src="{{ asset('/js/auth/showHideIcon.js') }}"></script>
  <script src="{{ asset('/js/auth/popup.js') }}"></script>
</head>
<body class="flex flex-col items-center justify-center h-screen">
  @if($isReset)
  {{-- breadcrumb navigation --}}
  <nav class="absolute top-0 left-0 pt-10 ml-10" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li class="inline-flex items-center">
        <a onclick="history.back()" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
          <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
          </svg>
          กลับไปหน้าที่แล้ว
        </a>
      </li>
      <li>
        <div class="flex items-center">
          <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <span class="ml-2 text-red-600">เปลี่ยนรหัสผ่าน</span>
        </div>
      </li>
    </ol>
  </nav>
  @endif

  {{-- logo --}}
  <div class="sm:w-full sm:max-w-md sm:mx-auto">
    <img class="w-auto h-auto mx-auto " src="{{ asset('images/logo2.png') }}" alt="TANGNAM_LOGO">
  </div>
  {{-- form --}}
  <div class="sm:w-full sm:max-w-lg sm:mx-auto mt-10">
    <form class="space-y-6" method="POST" action="/authen/">
      @csrf
      <div>
        <label for="accName" class="block font-medium">ชื่อผู้ใช้        
          @error('accName')
            <span class="ml-2 text-xs text-red-500"> {{$message}} </span>
          @enderror
        </label>
        <div class="mt-2">
          <input type="text" name="accName" id="accName" autocomplete="accName" 
          class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 @if(!$accName) focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000] @endif
          placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อผู้ใช้"
          value="{{ old('accName', $accName->AccName ?? '') }}" @if($accName) readonly @endif>
        </div>
      </div>

      <div>
        <label for="password" class="block font-medium">รหัสผ่าน @if($isReset) ใหม่ @endif
          <span id="passError" class="hidden ml-2 text-xs text-red-500"> * กรุณากรอกรหัสผ่านใหม่ </span>
          @error('password')
            <span class="ml-2 text-xs text-red-500"> {{$message}} </span>
          @enderror
        </label>
        <div class="mt-2 relative">
          <input type="password" name="password" id="password"
          class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
          placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกรหัสผ่าน">
          {{-- Icon To Show and Hide Password --}}
          <div class="absolute top-1/2 right-2 text-2xl transform -translate-y-1/2 cursor-pointer">
            {{-- Show Password Icon --}}
            <svg id="showPass" xmlns="http://www.w3.org/2000/svg" class="block text-black" style="width: 24px; height: 24px;" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
            </svg>

            {{-- Hide Password Icon --}}
            <svg id="hidePass" xmlns="http://www.w3.org/2000/svg" class="block text-black" style="width: 24px; height: 24px; display: none;" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
            </svg>
          </div>
        </div>
        @if(!($isReset))
        <div class="flex justify-end mt-2">
          <button type="button" id="showForgotPopup" class="text-sm font-semibold text-blue-600 hover:text-blue-900">
            ลืมรหัสผ่าน?
          </button>
        </div>
        @endif
      </div>
      
      @error('errorInput')
        <p class="flex justify-center text-sm text-red-500"> {{$message}} </p>
      @enderror

      @if ($isReset)
        <div class="flex justify-center">
          <button type="button" id="showResetPassPopup" class="px-10 py-1.5 text-base font-semibold text-white bg-[#FF0000] shadow-sm rounded-full hover:text-black hover:bg-slate-300">เปลี่ยนรหัสผ่าน</button>
        </div>          
      @else
        <div class="flex justify-center">
          <button type="submit" class="px-10 py-1.5 text-base font-semibold text-white bg-[#FF0000] shadow-sm rounded-full hover:text-black hover:bg-slate-300">เข้าสู่ระบบ</button>
        </div>
      @endif
    </form>
    @include('Popup.forgotPassword')
    @include('Popup.resetPassword')
  </div>
</body>
</html>