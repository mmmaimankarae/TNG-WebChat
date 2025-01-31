<script src="{{ asset('/js/detailMsg/handleNoImage.js') }}"></script>
<script src="{{ asset('/js/detailMsg/handleCopyData.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  ข้อความ คุณ{{ $cusName }}
@endsection
@section('content')
  {{-- สถานะงาน
    1,4,5 สอบถาม/ขอใบเสนอราคา
    2 สอบถามเพิ่มเติม/ให้ข้อมูล 
    3 แจ้งใบเสนอราคา -> 6 ยืนยันสต๊อก (ระบุจำนวนเงิน)
    9 สั่งสินค้าและชำระเงินแล้ว
    10/11 แนบใบรับเงินมัดจำ/ใบกำกับภาษี "แนบเลขที่ใบเสร็จ/กดสินค้าPreorder+ใบเสร็จ+ระบุวันที่"
    13 ดำเนินเรื่องการคืนเงิน
    14 คืนเงินเสร็จสิ้น
    12 แจ้งให้ชำระเงินเพิ่มเติม 
    10 ถึงกำหนดวันนัดรับสินค้า -> 8 แจ้งยอดการชำระรอบสอง -> 9 ชำระเงินแล้ว + ใส่เลขที่ใบเสร็จ
    7,8,15 processinline 
    
    2, 3, 11
    --}}
  @php 
    $statusMap = [
          1 => 'สอบถาม/ขอใบเสนอราคา',
          2 => 'สอบถามเพิ่มเติม/ให้ข้อมูล',
          3 => 'แจ้งใบเสนอราคา',
          4 => 'สอบถาม/ขอใบเสนอราคา',
          5 => 'สอบถาม/ขอใบเสนอราคา',
          8 => 'แจ้งยอดการชำระเพิ่มเติม',
          9 => 'ชำระเงินแล้ว',
          10 => 'ถึงกำหนดวันนัดรับสินค้า',
          11 => 'แนบใบรับเงินมัดจำ/ใบกำกับภาษี',
          12 => 'แจ้งชำระเงินเพิ่มเติม',
          13 => 'ดำเนินเรื่องการคืนเงิน',
          14 => 'คืนเงินเสร็จสิ้น'
        ];
    $statusThai = $statusMap[$status];
  @endphp
  <div class="flex flex-1 h-screen">
    <div class="flex-1 flex flex-col">
      {{-- ส่วนข้อมูลลูกค้า --}}
      <div class="sticky top-12 p-4 tracking-wider bg-white shadow-md">
        <div class="grid grid-cols-2 divide-x text-sm">
          {{-- Column 1: รูป + ข้อมูล --}}
          <div class="flex items-center ml-20 space-x-4">
            <div>
              {{-- ชื่อลูกค้า --}}
              <div class="relative">
                <span class="font-semibold">ชื่อลูกค้า:</span>
                <input id="cusName" type="text" value="{{ $cusName }}" class="w-8/12 truncate" disabled readonly>
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

              {{-- รหัสลูกค้า --}}
              <div class="relative mt-2.5">
                <label for="cusCode" class="sr-only">Label</label>
                <span class="font-semibold">รหัสลูกค้า:</span>
                <input id="cusCode" type="text" value="{{ $cusCode }}" class="w-8/12 truncate" disabled readonly>
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

              {{-- ปุ่มส่งต่อให้สาขา --}}
              @if ($roleCode == 2)
              <div class="mt-3">
                <form method="POST" action="">
                  @csrf
                  <input type="hidden" name="taskCode" value="{{ $taskCode }}">
                  <button type="submit" class="text-xs py-1 px-3 border border-gray-500 rounded-2xl hover:bg-gray-300">มอบหมายให้สาขา</button>
                </form>
              </div>
              @endif
            </div>
          </div>
          
          <!-- Column 2: สถานะ -->
          <div class="pl-5">
            <div>
              <span class="font-semibold">สถานะงานปัจจุบัน:</span>
              <span>{{ $statusThai }}</span>
            </div>

            <!-- อัปเดทสถานะ -->
            <div class="mt-2.5">
              <span class="font-semibold">อัปเดทสถานะ:</span>
              <!-- ปุ่มเลือกสถานะ -->
              <div class="relative inline-block text-left">
                <div>
                  <button type="button" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]" id="upStatus">
                    โปรดเลือกสถานะ
                    <svg class="-mr-1 size-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
                      <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                <!-- menu สถานะ -->
                <div id="list-status" class="hidden absolute right-0 z-10 w-72 mt-2 divide-y divide-gray-300 bg-white shadow-lg rounded-md
                    border border-gray-300 ring-1 ring-black ring-opacity-5" role="menu">
                  @foreach ($statusMap as $key => $value)
                    @if (in_array($key, [2, 3, 11]))
                      <div class="py-1" role="none">
                        <a href="" class="block px-4 py-3 hover:bg-gray-200 hover:rounded-md">{{ $value }}</a>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ประวัติแชท --}}
      <div class="ml-20 pb-5 flex-1 overflow-y-auto">
        @foreach ($messages as $msg)
          @if (strpos($msg['userId'], 'TNG') === 0)
            <div class="flex items-start justify-end gap-2.5 mt-5 mr-2">
              <div class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 bg-red-100 rounded-xl">
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
                      <p id="content" class="py-2.5 mt-2 text-sm text-gray-900">{{ $msg['messageContent'] }}</p>
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
            <span id="quoteUser" class="ml-20 font-semibold"></span>
            <button id="removeQuote" class="text-lg">&times;</button>
          </div>
          <div id="quoteContent" class="text-sm ml-20 w-8/12 truncate"></div>
        </div>
        <div class="flex items-center py-4 pr-2 ml-20 space-x-2">
          {{-- ปุ่มอัพโหลดไฟล์ --}}
          <input type="file" id="fileInput" style="display: none;" accept=".JPEG, .PNG" multiple>
          <img src="{{ asset('images/addFile.png') }}" alt="file" class="w-8 h-8 cursor-pointer hover:w-7 hover:h-7" id="fileButton"/>
          {{-- ช่องกรอกข้อความ --}}
          <input type="text" class="flex-1 px-3 py-2 border border-gray-500 rounded-full focus:outline-none focus:border-blue-500 text-sm" 
            placeholder="พิมพ์ข้อความ..." />
          <button class="text-blue-500 rounded-full hover:bg-blue-100">
            <img src="{{ asset('images/send.png') }}" alt="file" class="w-8 h-8 cursor-pointer"/>
          </button>
        </div>
      </div>
    </div>

    <!-- รายการสินค้า -->
    <div>
      <div id="side-right" class="right-0 w-80 h-screen overflow-y-auto pl-3 bg-white border-l border-gray-200">
        <p class="my-5 text-center tracking-wider underline underline-offset-4 decoration-dashed"> รายการรหัสสินค้าที่เกี่ยวข้อง </p>
        <!-- รหัสสินค้า -->
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
        <!-- ชื่อสินค้า -->
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

@endsection