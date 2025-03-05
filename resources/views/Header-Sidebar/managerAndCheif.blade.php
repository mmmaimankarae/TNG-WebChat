@php
$rolePath = '';
if (request()->attributes->get('decoded')->roleCode == '3') {
  $rolePath = 'branch-manager.';
} elseif (request()->attributes->get('decoded')->roleCode == '4') {
  $rolePath = 'office-chief.';
}
@endphp

{{-- HEADER --}}
<nav class="sticky top-0 z-30 py-0.5 text-sm font-medium tracking-wider bg-white shadow-md ">
  <div class="flex items-center mx-auto w-full h-12 px-2 sm:px-3 lg:px-5">
    {{-- LOGO --}}
    <a href="{{ route($rolePath . 'new-tasks') }}" class="flex-shrink-0"><img class="w-auto h-10" src="{{ asset('images/logo.png') }}" alt="logo"></a>
    {{-- Navigation Menu --}}
    <div class="ml-6 space-x-6 lg:space-x-10"> 
      <a href="{{ route($rolePath . 'new-tasks') }}" class="nav-style" aria-current="false">
        รายการงานใหม่ <span class="notiHead-style" noti="false">4</span>
      </a>
      <a href="{{ route($rolePath . 'current-tasks') }}" class="nav-style" aria-current="false">
        งานที่ดำเนินอยู่ <span class="notiHead-style" noti="false">4</span>
      </a>
    </div>
    
    {{-- Right Icons --}}
    <div class="flex items-center ml-auto space-x-2 sm:space-x-1 md:space-x-2">
      {{-- Notification Icon --}}
      <div class="relative">
        <button id="noti-button" class="flex items-center justify-center p-1 bg-gray-200 rounded-full hover:bg-[#FFC5C5]">
          <svg class="w-7 h-7 sm:w-6 sm:h-6 md:w-7 md:h-7 rounded-full" viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.93 6 11v5l-2 2v1h16v-1l-2-2z"/>
          </svg>
          {{-- Notification Badge --}}
          <span class="absolute -top-1 -right-1 flex items-center justify-center h-5 w-5 text-xs font-semibold text-white bg-[#FF4343] rounded-full ">
            4
          </span>
        </button>
      </div>
        
      {{-- User Icon --}}
      <div class="relative">
        <button id="head-user" class="sm:block relative">
          <svg class="w-10 h-10 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 
            7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 
            13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" />
          </svg>
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