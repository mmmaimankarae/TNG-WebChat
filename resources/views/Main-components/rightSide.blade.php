<div class="flex flex-col right-0 w-80 h-screen bg-white border-l border-gray-200">
    {{-- ส่วนข้อมูลลูกค้า --}}
    <div class="flex-none p-3 mr-3 text-sm  bg-white border-b border-gray-200">
      <div class="relative my-3">
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