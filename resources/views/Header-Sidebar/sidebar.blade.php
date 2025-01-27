{{-- SIDEBAR --}}
<div id="sidebar" class="fixed left-0 z-10 w-64 h-screen overflow-y-auto bg-white shadow-md">
  {{-- Close Button --}}
  <button id="close-side" class="flex ml-auto mx-4 my-2 text-xl text-black hover:text-[#FF0000]">&times;</button>
  {{-- Search --}}
  <form class="px-4 mb-5" action="" method="post">
    <div class="relative">
      <input type="text" name="search" placeholder="ค้นหาชื่อ, เบอร์โทรศัพท์" required 
        class="w-full py-2.5 px-2.5 text-xs font-medium rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]"/>
      <button type="submit" class="absolute right-0 top-0 bottom-0 px-3 py-2.5 bg-red-200 rounded-r-md hover:bg-red-300">
        <img class="h-4 w-auto" src="{{ asset('images/search.png') }}" alt="logo">
      </button>
    </div>
  </form>

  @php
    $rolePath = '';
    if (request()->attributes->get('decoded')->roleCode == '1') {
      $rolePath = 'internal-sale.';
    } elseif (request()->attributes->get('decoded')->roleCode == '2') {
      $rolePath = 'sale-admin.';
    }
  @endphp
  {{-- Task Section --}}
  <div class="mx-4">
    {{-- current tasks --}}
    <a href="{{ route($rolePath . 'current-tasks') }}" class="flex justify-center items-center text-sm font-normal text-red-500 underline underline-offset-2 mb-2">
      <span class="flex items-center justify-center w-5 h-5 mr-2 text-white bg-[#FF4343] rounded-full">4</span> 
      รายการดำเนินอยู่
    </a>
    {{-- task deatil --}}
    <div class="mt-4 mr-1 space-y-5">
      <div class="grid grid-cols-4 items-center gap-2">
        <div class="flex justify-center">
          <img src="{{ asset('images/user.png') }}" alt="User" class="h-8 w-8 rounded-full">
        </div>
        <div class="col-span-2">
          <p class="text-sm font-medium">mmaimankarae</p>
          <p class="text-xs text-gray-500">ส่งรูปภาพ</p>
        </div>
        <div class="flex justify-end text-xs font-semibold text-blue-500">1 ชั่วโมง</div>
      </div>

      <div class="grid grid-cols-4 items-center gap-2">
        <div class="flex justify-center">
          <img src="{{ asset('images/user.png') }}" alt="User" class="h-8 w-8 rounded-full">
        </div>
        <div class="col-span-2">
          <p class="text-sm font-medium">mmaimankarae</p>
          <p class="text-xs text-gray-500">ส่งรูปภาพ</p>
        </div>
        <div class="flex justify-end text-xs font-semibold text-blue-500">1 ชั่วโมง</div>
      </div>
    </div>
  </div>

  {{-- new tasks --}}
  <div class="mx-4 mt-10">
    <a href="{{ route($rolePath . 'current-tasks') }}" class="flex justify-center items-center text-sm font-normal text-red-500 underline underline-offset-2 mb-2">
      <span class="flex items-center justify-center w-5 h-5 mr-2 text-white bg-[#FF4343] rounded-full">4</span> 
      รายการงานใหม่
    </a>
    {{-- task deatil --}}
    <div class="mt-4 mr-1" space-y-3>
      <div class="grid grid-cols-4 items-center gap-2">
        <div class="flex justify-center">
          <img src="{{ asset('images/user.png') }}" alt="User" class="h-8 w-8 rounded-full">
        </div>
        <div class="col-span-2">
          <p class="text-sm font-medium">mmaimankarae</p>
          <p class="text-xs text-gray-500">ส่งรูปภาพ</p>
        </div>
        <div class="flex justify-end text-xs font-semibold text-blue-500">1 ชั่วโมง</div>
      </div>
    </div>
  </div>
</div>

{{-- SIDEBAR TOGGLE TO TAB --}}
<div id="side-tab" class="hidden fixed left-0 z-10 w-8 h-screen flex items-center justify-center bg-white shadow-md">
{{-- Hamburger Icon for Sidebar --}}
  <button id="open-side" class="p-2 bg-white rounded-md translate-x-1 -translate-y-8" style="box-shadow: 1px 0 1px rgba(0, 0, 0, 0.2);">
    <svg class="h-7 w-6" fill="none" stroke-width="2" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" d="M13 4.5v20 M18 4.5v20 M23 4.5v20" />
    </svg>
  </button>
</div>