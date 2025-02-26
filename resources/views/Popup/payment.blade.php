<div id="paymentPopup" tabindex="-1" class="hidden fixed z-50 inset-0 justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-4xl p-4">
    <div class="relative bg-white rounded-lg shadow max-h-[80vh] overflow-y-auto">
      <div class="p-4 md:p-5 text-center">
        <p class="text-lg font-semibold">แจ้งยอดการชำระเงิน</p>
        @if ($amountInfo != null)
          <div class="mt-4">
            @php 
              $quoteShow = false;
              $quotaCode = '';
            @endphp
            @foreach ($amountInfo as $item)
              @if ($quoteShow == false)
              <label for="quotaCode" class="block text-left font-medium">เลขที่ใบเสนอราคาที่พบ</label>
              <input type="text" name="quotaCode" id="quotaCodeInput" value="{{ $item['quotaCode'] }}" class="block w-full px-3 py-2 mt-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder="เลขที่ใบเสนอราคา" required>
                @php 
                  $quoteShow = true; 
                  $quotaCode = $item['quotaCode'];
                @endphp
                <label class="block mt-4 text-left font-medium">เลือกใบเสนอราคาที่จะใช้แจ้งยอดชำระ</label>
              @endif
              @if ($quotaCode != $item['quotaCode'])
                @php $quotaCode = $item['quotaCode']; @endphp
                <p class="mt-4 text-left text-red-500 font-medium">ใบเสนอราคาอื่นที่พบ</p>
                <label for="quotaCode" class="block mt-4 -mb-2 text-left font-medium">{{ $item['quotaCode'] }}</label>
              @endif
              <label class="inline-flex mt-4 ml-4">
                <input type="radio" name="quotaVersion" value="{{ $item['amount'] }}" class="form-radio" required onchange="updateTotalPrice(this)" 
                  data-version="{{ $item['version'] }}" data-quota-code="{{ $item['quotaCode'] }}">
                <span class="ml-2 font-medium">version: {{ $item['version'] }},</span>
                <span class="ml-2">{{ $item['itemsQty'] }} รายการสินค้า,</span>
                <span class="ml-2">ราคารวม: {{ $item['amount'] }} บาท</span>
                
                <form id="imageForm" method="POST" action="{{ route('view.image') }}" target="_blank">
                  @csrf
                  <input type="hidden" name="messageId" value="{{ $item['quotaPath'] }}">
                  <button type="submit" class="ml-2 text-sm text-blue-500">
                    ดูรูปภาพ
                  </button>
                </form>
              </label>
            @endforeach
          </div>
          <form method="POST" action="" >
            @csrf
            <label for="totalPrice" class="block text-left font-medium">ยอดชำระ</label>
            <div class="mt-2">
              <input type="text" name="totalPrice" id="totalPrice" value=""
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกยอดชำระทั้งหมด" required>
            </div>
            <input type="text" name="quotaCode" id="quotaCode" class="hidden">
            <input type="text" name="version" id="version" class="hidden">

            <input type="hidden" name="replyId" value="{{ old('replyId', $taskLineID) }}">
            <input type="hidden" name="replyName" value="{{ old('replyName', $cusName) }}">
            <input type="hidden" name="cusCode" value="{{ old('cusCode', $cusCode) }}">
            <input type="hidden" name="taskCode" id="taskcodeQuota" value="{{ old('taskCode', $taskCode) }}">
            <input type="hidden" name="userId" value="bot">
            <input type="hidden" name="userName" value="tangbot">
            <input type="hidden" name="taskStatus" value="4">
            <input type="hidden" name="updateStatus" value="true">
            <input type="hidden" name="showchat" value="true">
            <input type="hidden" name="branchCode" value="{{ old('branchCode', $branchCode) }}">
            <button type="submit" class="px-10 py-1.5 mt-4 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
            <button id="cancelPaymentPopup" type="button" class="px-10 py-1.5 mt-4 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
          </form>
      @else
        <p class="mt-4 text-center text-red-500 font-medium">ไม่พบใบเสนอราคา</p>
        <button id="cancelPaymentPopup" type="button" class="px-10 py-1.5 mt-4 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ยกเลิก</button>
      @endif 
      </div>
    </div>
  </div>
</div>