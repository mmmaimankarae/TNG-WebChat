{{-- ประวัติแชท --}}
<div id="chat-history" class="ml-28 pb-5 flex-1 overflow-y-auto">
  @foreach ($messages as $msg)
  {{-- ส่วนของ พนง. --}}

    @if (strpos($msg['userId'], 'TNG') === 0)
      <div class="flex items-start justify-end gap-2.5 mt-5 mr-2">
        <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-3 bg-red-100 rounded-xl">
    {{-- ส่วนของ bot --}}
    @elseif ($msg['userId'] === 'bot')
      <div class="flex items-start justify-end gap-2.5 mt-5 mr-2">
        <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 bg-green-100 rounded-xl">
    {{-- ส่วนของ ลค. --}}
    @else
      <div class="flex items-start justify-start gap-2.5 mt-5">
        <div class="flex flex-col w-full max-w-[320px] leading-1.5 px-4 py-2 bg-blue-100 rounded-xl">
    @endif
          {{-- detail --}}
          @if (isset($msg['quoteType']) && $msg['quoteType'] === 'text')
            <div class="flex items-center space-x-2 rtl:space-x-reverse text-sm">
              <span class="font-semibold w-2/5 truncate">{{ $msg['quoteContent'] }}</span>
            </div>
          <hr class="my-2">
          @endif
          <div class="flex items-center space-x-2 rtl:space-x-reverse text-xs">
            <span id="userName" class="font-semibold w-2/5 truncate">{{ $msg['userName'] }}</span>
            <span class="text-gray-700">{{ $msg['messageDate'] . ' ' . $msg['messagetime'] }}</span>
          </div>
          @switch($msg['messageType'])
            @case('text')
              <p id="content" class="py-2 mt-1 text-sm text-gray-900 break-words whitespace-pre-wrap">{{ $msg['messageContent'] }}</p>
              @break
            @case('image')
              <form id="imageForm" method="POST" action="{{ route('view.image') }}" target="_blank">
                @csrf
                <input type="hidden" name="messageId" value="{{ $msg['messageId'] }}">
                <button type="submit" class="mt-2">
                  <img id="messageImage" class="rounded-md" src="{{ route('preview.image', ['messageId' => $msg['messageId']]) }}" 
                    onerror="handleImageError()">
                </button>
              </form>
              <a id="downloadLink" class="flex justify-end mt-2 text-xs underline text-blue-950" href="{{ route('download.image', ['messageId' => $msg['messageId']]) }}">
                ดาวน์โหลดรูปภาพ
              </a>
              @break
            @case('sticker')
              <img src="{{ $msg['messageContent'] }}" alt="image" class="w-2/3 h-auto mt-2"/>
              @break
            @default
            @endswitch
        </div>
        {{-- reply of msg ลค. --}}
        @if (!(strpos($msg['userId'], 'TNG') === 0))
          <div class="relative inline-block text-left">
            <button id="actionMsg" type="button" data-quoteData="{{ $msg['quoteToken']}}" data-userName="{{ $msg['userName'] }}"
              data-msgType="{{ $msg['messageType'] }}" data-msgContent="{{ $msg['messageContent'] }}"
              class="inline-flex items-center p-2 text-sm text-center rounded-lg hover:bg-gray-100">
              <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
              </svg>
            </button>
            <div class="actionDots hidden absolute left-0 mt-2 w-48 border border-gray-300 rounded-md shadow-lg">
              <ul class="py-1 text-xs text-gray-700">
                <li>
                  <button onclick="quoteMsg()" id="quoteMsg" class="block w-full text-left px-4 py-2 hover:bg-gray-100">ตอบกลับข้อความนี้</button>
                </li>
              </ul>
            </div>
          </div>
        @endif
    </div>
  @endforeach
</div>

{{-- ช่องส่งข้อมูล --}}
<div class="sticky bottom-0 bg-white border-t border-gray-300">
  {{-- ข้อความที่ Quote ถึง --}}
  <div id="quoteBox" class="hidden p-2 mb-2 bg-blue-200 bg-opacity-60 rounded-lg">
    <div class="flex justify-between items-center">
      <span id="quoteUser" class="ml-28 font-semibold w-8/12 truncate"></span>
      <button id="removeQuote" class="text-lg">&times;</button>
    </div>
    <div id="quoteContent" class="text-sm ml-28 w-8/12 truncate"></div>
  </div>
  <div class="flex items-center py-4 pr-2 ml-28 space-x-2">
    <form id="lineMessageForm" method="POST" action="{{ route('send-message') }}" enctype="multipart/form-data" class="flex w-full">
      @csrf
      {{-- ปุ่มอัพโหลดไฟล์ --}}
      <input type="file" id="fileInput" name="file" style="display: none;" accept=".JPEG, .PNG" multiple>
      <svg id="fileButton" class="w-9 h-9 text-sky-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
        <path fill-rule="evenodd" d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm11-4.243a1 1 0 1 0-2 0V11H7.757a1 1 0 1 0 0 2H11v3.243a1 1 0 1 0 2 0V13h3.243a1 1 0 1 0 0-2H13V7.757Z" clip-rule="evenodd"/>
      </svg>
    
      {{-- ช่องกรอกข้อความ --}}
      <input type="hidden" name="quoteToken" id="quoteToken">
      <input type="hidden" name="quoteContentInput" id="quoteContentInput">
      <input type="hidden" name="replyId" value="{{ old('replyId', $taskLineID) }}">
      <input type="hidden" name="replyName" value="{{ old('replyName', $cusName) }}">
      <input type="hidden" name="cusCode" value="{{ old('cusCode', $cusCode) }}">
      <input type="hidden" name="taskCode" value="{{ old('taskCode', $taskCode) }}">
      <input type="hidden" name="userId" value="{{ old('userId', $accCode) }}">
      <input type="hidden" name="userName" value="{{ old('userName', $accName->AccName) }}">
      <input type="hidden" name="select" value="true">
      <input type="hidden" name="taskStatus" value="{{ old('taskStatus', $taskStatus) }}">
      <input type="hidden" name="empCode" value="{{ old('empCode', $empCode) }}">
      <input type="text" name="message" autocomplete="off" class="flex-1 px-3 py-2 border border-gray-500 rounded-full focus:outline-none focus:border-blue-500 text-sm" placeholder="พิมพ์ข้อความ..." required/>
      <button type="submit" class="ml-2 mr-2 text-blue-500 rounded-full hover:bg-blue-100">
        <svg class="w-7 h-7 text-sky-600 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24" transform="rotate(90)">
          <path fill-rule="evenodd" d="M12 2a1 1 0 0 1 .932.638l7 18a1 1 0 0 1-1.326 1.281L13 19.517V13a1 1 0 1 0-2 0v6.517l-5.606 2.402a1 1 0 0 1-1.326-1.281l7-18A1 1 0 0 1 12 2Z" clip-rule="evenodd"/>
        </svg>
      </button>
    </form>
  </div>
</div>