<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <script src="{{ asset('/js/auth/showHideIcon.js') }}"></script>
  <script src="{{ asset('/js/auth/popup.js') }}"></script>
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <title>{{ $title }}</title>
</head>
<body class="flex flex-col items-center justify-center h-screen font-[Kodchasan] ">
  @if($isReset)
  {{-- breadcrumb navigation --}}
  <nav class="flex pt-10 ml-10" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
      <li class="inline-flex items-center">
        <a onclick="history.back()" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
          <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
          </svg>
          กลับไปหน้าที่แล้ว
        </a>
      </li>
      <li aria-current="page">
        <div class="flex items-center">
          <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
          </svg>
          <span class="ms-2 text-red-600">เปลี่ยนรหัสผ่าน</span>
        </div>
      </li>
    </ol>
  </nav>
  @endif

  {{-- logo --}}
  <div class="sm:w-full sm:max-w-md sm:mx-auto">
    <img class="w-auto h-auto mx-auto " src="{{'images/logo2.png'}}" alt="TANGNAM_LOGO">
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
          class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
          placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อผู้ใช้"
          value="{{ old('accName', $accName) }}" @if($accName) readonly @endif>
        </div>
      </div>

      <div>
        <label for="password" class="block font-medium">รหัสผ่าน
          @error('password')
            <span class="ml-2 text-xs text-red-500"> {{$message}} </span>
          @enderror
        </label>
        <div class="mt-2 relative">
          <input type="password" name="password" id="password"
          class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]
          placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกรหัสผ่าน">
          <!-- Icon To Show and Hide Password -->
          <div class="absolute top-1/2 right-2 text-2xl transform -translate-y-1/2 cursor-pointer">
            <i id="showPass" class='block text-black bx bxs-show'></i>
            <i id="hidePass" class='block text-black bx bxs-hide' style="display: none;"></i>
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
          <button type="button" id="showResetPassPopup" class="px-10 py-1.5 text-base font-semibold text-white bg-[#FF0000] shadow-sm rounded-full hover:text-black hover:bg-slate-300">เข้าสู่ระบบ</button>
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