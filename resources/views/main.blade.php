<script src="{{ asset('/js/detailMsg/handleNoImage.js') }}"></script>
<script src="{{ asset('/js/detailMsg/handleCopyData.js') }}"></script>
<script src="{{ asset('/js/main.js') }}"></script>
<script src="{{ asset('/js/popup.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  ทดสอบ
@endsection

@section('content')
  @if ($select)
  <div class="flex flex-1 h-screen">
    <div class="flex-1 flex flex-col">
      {{-- ส่วนของสถานะ --}}
      <div class="sticky top-12 p-4 tracking-wider bg-white shadow-md">
        <div class="grid grid-cols-4 ml-24 text-sm">
          @foreach ($statusThai as $key => $status)
            <div class="pl-5">
              @if ($taskStatus == $key)
                <span class="inline-flex justify-center w-full px-3 py-1.5 bg-[#FF4343] shadow-sm rounded-md text-white">
                  {{ $status }}
                </span>
              @else
                <form method="POST" action="">
                  @csrf
                  <input type="hidden" name="TasksLineID" value="{{ $taskLineID }}">
                  <input type="hidden" name="TasksCode" value="{{ $taskCode }}">
                  <input type="hidden" name="cusCode" value="{{ $cusCode }}">
                  <input type="hidden" name="cusName" value="{{ $cusName }}">
                  <input type="hidden" name="select" value="true">
                  <input type="hidden" name="update" value="true">
                  <input type="hidden" name="taskStatus" value="{{ $key }}">
                  @if($key == '3')
                    <button id="showQuotationPopup" type="button" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                      {{ $status }}
                    </button>
                  @elseif($key == '4')
                    <button id="showInvoicePopup" type="button" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                      {{ $status }}
                    </button>
                  @else
                    <button type="submit" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                      {{ $status }}
                    </button>
                  @endif
                </form>
              @endif
              {{-- ปุ่มส่งต่อให้สาขา --}}
              @if ($roleCode == '2' && $key == '5')
              <div class="mt-5 flex justify-end">
                <form method="POST" action="{{ route('sale-admin.assign-task') }}">
                  @csrf
                  <input type="hidden" name="taskCode" value="{{ $taskCode }}">
                  <input type="hidden" name="cusName" value="{{ $cusName }}">
                  <button type="submit" class="text-xs py-1 px-3 border border-gray-500 rounded-2xl hover:bg-gray-300">มอบหมายให้สาขา</button>
                </form>
              </div>
              @endif  
            </div>
          @endforeach
        </div>
      </div>

      {{-- ประวัติแชท --}}
      <div id="chat-history" class="ml-28 pb-5 flex-1 overflow-y-auto">
        @foreach ($messages as $msg)
          @if (strpos($msg['userId'], 'TNG') === 0)
            <div class="flex items-start justify-end gap-2.5 mt-5 mr-2">
              <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 bg-red-100 rounded-xl">
          @elseif ($msg['userId'] === 'bot')
            <div class="flex items-start justify-end gap-2.5 mt-5 mr-2">
              <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 bg-green-100 rounded-xl">
          @else
            <div class="flex items-start justify-start gap-2.5 mt-5">
              <div class="flex flex-col w-full max-w-[320px] leading-1.5 px-4 py-2 bg-blue-100 rounded-xl">
          @endif
                <div class="flex items-center space-x-2 rtl:space-x-reverse text-xs">
                  <span id="userName" class="font-semibold">{{ $msg['userName'] }}</span>
                  <span class="text-gray-700">{{ $msg['messageDate'] . ' ' . $msg['messagetime'] }}</span>
                </div>
                @switch($msg['messageType'])
                  @case('text')
                    <p id="content" class="py-2.5 mt-2 text-sm text-gray-900 break-words whitespace-pre-wrap">{{ $msg['messageContent'] }}</p>
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
            </div>
        @endforeach
      </div>

      {{-- ช่องส่งข้อมูล --}}
      <div class="sticky bottom-0 bg-white border-t border-gray-300">
        {{-- ข้อความที่ Quote ถึง --}}
        <div id="quoteBox" class="hidden p-2 mb-2 bg-blue-200 bg-opacity-60 rounded-lg">
          <div class="flex justify-between items-center">
            <span id="quoteUser" class="ml-28 font-semibold"></span>
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
            <input type="hidden" name="replyId" value="{{ old('replyId', $taskLineID) }}">
            <input type="hidden" name="replyName" value="{{ old('replyName', $cusName) }}">
            <input type="hidden" name="cusCode" value="{{ old('cusCode', $cusCode) }}">
            <input type="hidden" name="taskCode" value="{{ old('taskCode', $taskCode) }}">
            <input type="hidden" name="messageType" value="text">
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
    </div>

    <div class="flex flex-col right-0 w-80 h-screen bg-white border-l border-gray-200">
      {{-- ส่วนข้อมูลลูกค้า --}}
      <div class="flex-none p-3 mr-3 text-sm  bg-white border-b border-gray-200">
        <div class="relative my-3">
          <span class="font-semibold">ชื่อลูกค้า:</span>
          <input id="cusName" type="text" value="mine" class="w-8/12 truncate" disabled readonly>
          <button data-copy="cusName" id="cusNameCopy" class="absolute inline-flex items-center justify-center py-1.5 px-2 text-blue-600 border border-gray-300 rounded-md hover:bg-gray-100">
            <span id="icon-cusNamecopy" class="inline-flex items-center">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
              </svg>
            </span>
            <span id="suc-cusNamecopy" class="hidden items-center">
              <svg class="w-3 h-3 mx-1.5 text-blue-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
              </svg> 
            </span>
          </button>
        </div>

        <div class="relative mt-2.5">
          <span class="font-semibold">รหัสลูกค้า:</span>
          <input id="cusCode" type="text" value="{{ $cusCode }}" disabled readonly>
          <button data-copy="cusCode" id="cusCodeCopy" class="absolute inline-flex items-center justify-center py-1.5 px-2 text-red-600 border border-gray-300 rounded-md hover:bg-gray-100">
            <span id="icon-cusCodecopy" class="inline-flex items-center">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
              </svg>
            </span>
            <span id="suc-cusCodecopy" class="hidden items-center">
              <svg class="w-3 h-3 mx-1.5 text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
              </svg>
            </span>
          </button>
        </div>
      </div>
  
      {{-- รายการสินค้า --}}
      <div class="flex-1 overflow-y-auto p-3">
        <p class="my-5 text-center tracking-wider underline underline-offset-4 decoration-dashed"> รายการรหัสสินค้าที่เกี่ยวข้อง </p>
        <div class="relative text-sm space-y-3 -pb-3">
          <span>1.</span>
          <input id="prodCode" type="text" value="G01020020205013056" disabled readonly>
          <button data-copy="prodCode" id="prodCodecopy" class="absolute end-2.5 top-1/4 -translate-y-1/2 inline-flex items-center justify-center py-1.5 px-2 text-blue-600 border border-gray-300 rounded-md hover:bg-gray-100">
            <span id="icon-prodCodecopy" class="inline-flex items-center">
              <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
              </svg>
            </span>
            <span id="suc-prodCodecopy" class="hidden items-center">
              <svg class="w-3 h-3 mx-1.5 text-blue-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
              </svg>
            </span>
          </button>
          </div>
          <div class="relative text-xs space-y-3">
            <input id="prodName" type="text" value="กระจกเงาใส 5 มม. 13x56 AGC" class="w-4/5 truncate" disabled readonly>
            <button data-copy="prodName" id="prodNamecopy" class="absolute end-2.5 top-1/4 -translate-y-1/2 inline-flex items-center justify-center py-1.5 px-2 text-red-600 border border-gray-300 rounded-md hover:bg-gray-100">
              <span id="icon-prodNamecopy" class="inline-flex items-center">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                </svg>
              </span>
              <span id="suc-prodNamecopy" class="hidden items-center">
                <svg class="w-3 h-3 mx-1.5 text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 12">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5.917 5.724 10.5 15 1.5"/>
                </svg>
              </span>
            </button>
          </div>
        </div>
      </div>
    </div>
  @else
    <div class="flex flex-col justify-center items-center h-screen -mt-12">
      <img src="{{ asset('images/logo2.png') }}" alt="notfound" class="w-1/3 h-auto mb-4 opacity-75 shadow-md shadow-red-200"/>
      <p class="text-xl text-gray-600">
        เริ่มต้นส่งข้อความเลย!
      </p>
    </div>
  @endif
@endsection