@php
  use Carbon\Carbon;
  Carbon::setLocale('th'); 
@endphp

{{-- SIDEBAR --}}
<div id="sidebar" class="fixed left-0 z-20 w-72 h-screen overflow-y-auto bg-white shadow-md">
  {{-- Close Button --}}
  <button id="close-side" class="flex ml-auto mx-4 my-2 text-xl text-black hover:text-[#FF0000]">&times;</button>
  {{-- Search --}}
  <form class="px-4 mb-5" action="{{ route('sale-admin.new-tasks') }}" method="POST">
    <div class="relative">
      <input type="text" name="search" placeholder="ค้นหาชื่อ, เบอร์โทรศัพท์" required 
        class="w-full py-2.5 px-2.5 text-xs font-medium rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#FF0000]"/>
        <button type="submit" class="absolute right-0 top-0 bottom-0 flex items-center justify-center px-3 py-2.5 bg-red-200 rounded-r-md hover:bg-red-300">
          <svg class="w-5 h-5 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
          </svg>
      </button>
    </div>
  </form>

  {{-- Task Section --}}
  <div class="mx-4">
    <div class="mt-4 mr-1 space-y-5">
      @if (count($sidebarChat) > 0)
        @foreach ($sidebarChat as $chat)
          @php
            $tasksUpdate = Carbon::parse($chat->TasksUpdate);
            $timeAgo = $tasksUpdate->diffForHumans();
            $prevEmpAccName = $chat->PrevEmpAccName;
            $path = request()->segment(1);
          @endphp
          <form action="{{ route( $path . '.current-tasks') }}" method="POST" id="chat-form-{{ $chat->TasksLineID }}">
            @csrf
            <input type="hidden" name="TasksLineID" value="{{ $chat->TasksLineID }}">
            <input type="hidden" name="TasksCode" value="{{ $chat->TasksCode }}">
            <input type="hidden" name="cusCode" value="{{ $chat->CusCode }}">
            <input type="hidden" name="cusName" value="{{ $chat->CusName }}">
            <input type="hidden" name="taskStatus" value="{{ $chat->TasksStatusCode }}">
            @if ($chat->TasksStatusCode == '1')
              <input type="hidden" name="update" value="true">
              <input type="hidden" name="taskStatus" value="2">
            @endif
            <input type="hidden" name="select" value="true">
            <div class="grid grid-cols-4 items-center p-2 rounded-lg cursor-pointer hover:bg-gray-200" onclick="document.getElementById('chat-form-{{ $chat->TasksLineID }}').submit();">
              <div class="flex justify-center -ml-5">
                <svg class="w-10 h-10 text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                  <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 
                  7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 
                  13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" />
                </svg>
              </div>
              <div class="col-span-2 -ml-4">
                @if ($chat->cusLineType == 'G')
                  <p class="text-sm font-medium w-8/12 truncate"><span class="text-gray-500">(กลุ่ม) </span>{{ $chat->CusName }}</p>
                @else
                  <p class="text-sm font-medium w-8/12 truncate">{{ $chat->CusName }}</p>
                @endif

                @if ($chat->TasksStatusCode == '5')
                  <p class="inline-block px-2 text-xs border border-red-700 rounded-2xl">เสร็จสิ้น</p>
                @elseif ($prevEmpAccName != null)
                  <p class="inline-block px-2 text-xs border border-green-700 rounded-2xl">{{ $prevEmpAccName }}</p>
                @endif
              </div>
              <div class="flex justify-end -ml-5 overflow-y-auto text-xs font-semibold text-blue-500">{{ $timeAgo }}</div>
            </div>
          </form>
        @endforeach
      @endif
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