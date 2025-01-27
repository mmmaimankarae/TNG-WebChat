@php
$rolePath = '';
if (request()->attributes->get('decoded')->roleCode == '3') {
  $rolePath = 'branch-manager.';
} elseif (request()->attributes->get('decoded')->roleCode == '4') {
  $rolePath = 'office-chief.';
}
@endphp

{{-- HEADER --}}
<nav class="sticky top-0 z-20 py-0.5 text-sm font-medium tracking-wider bg-white shadow-md ">
  <div class="flex items-center mx-auto w-full h-12 px-2 sm:px-3 lg:px-5">
    {{-- LOGO --}}
    <a href="/" class="flex-shrink-0"><img class="w-auto h-10" src="{{ asset('images/logo.png') }}" alt="logo"></a>
    {{-- Navigation Menu --}}
    <div class="ml-6 space-x-6 lg:space-x-10"> 
      <a href="{{ route($rolePath . 'tasks') }}" class="nav-style" aria-current="false">
        รายการงานใหม่ <span class="notiHead-style" noti="false">4</span>
      </a>
    </div>
    
    {{-- Right Icons --}}
    <div class="flex items-center ml-auto space-x-2 sm:space-x-1 md:space-x-2">
      {{-- Notification Icon --}}
      <div class="relative">
        <button id="noti-button" class="flex items-center justify-center p-1 bg-gray-200 rounded-full hover:bg-[#FFC5C5]">
          <img class="w-7 h-7 sm:w-6 sm:h-6 md:w-7 md:h-7 rounded-full" src="{{ asset('images/noti.png') }}" alt="notification">
          {{-- Notification Badge --}}
          <span class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 text-xs font-semibold text-white bg-[#FF4343] rounded-full ">
            4
          </span>
        </button>
      </div>
        
      {{-- User Icon --}}
      <div class="relative">
        <button id="head-user" class="sm:block relative">
          <img class="w-7 h-7 sm:w-6 sm:h-6 md:w-7 md:h-7 rounded-full" src="{{ asset('images/user.png') }}" alt="user-account">
        </button>
        {{-- Dropdown User --}}
        <div id="menu-user" class="hidden absolute right-0 z-10 w-40 mt-1 bg-white shadow-lg 
        shadow-gray-400 border-gray-300 border rounded-md" role="menu">
          <a href="/authen/reset" class="block px-4 py-3 text-xs hover:bg-gray-200 hover:rounded-md">เปลี่ยนรหัสผ่าน</a>
          <a href="/signout" class="block px-4 py-3 text-xs hover:bg-gray-200 hover:rounded-md">ออกจากระบบ</a>
        </div>
      </div>
    </div>
  </div>
</nav>