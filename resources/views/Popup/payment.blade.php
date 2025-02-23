<script src="{{ asset('/js/priceCal.js') }}"></script>
<div id="paymentPopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-4xl p-4">
    <div class="relative bg-white rounded-lg shadow">
      <div class="p-4 md:p-5 text-center">
        <p class="text-lg font-semibold">แจ้งยอดการชำระเงิน</p>
        @if ($amountInfo != null)
          <div class="mt-4">
            @php $quoteShow = false; @endphp
            @foreach ($amountInfo as $item)
              @if ($quoteShow == false)
              <label for="quoteCode" class="block text-left font-medium">เลขที่ใบเสนอราคา</label>
              <input type="text" name="quoteCode" value="{{ $item['quoteCode'] }}" class="block w-full px-3 py-2 mt-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder="เลขที่ใบเสนอราคา" required>
                @php $quoteShow = true; @endphp
                <label class="block mt-4 text-left font-medium">เลือกใบเสนอราคาที่จะใช้แจ้งยอดชำระ</label>
              @endif
              <label class="inline-flex mt-4 ml-4">
                <input type="radio" name="quotaVersion" value="{{ $item['amount'] }}" class="form-radio" required onchange="updateTotalPrice(this)">
                <span class="ml-2 font-medium">version: {{ $item['version'] }},</span>
                <span class="ml-2">{{ $item['itemsQty'] }} รายการสินค้า,</span>
                <span class="ml-2">ราคารวม: {{ $item['amount'] }} บาท</span>

                <input type="text" name="version" value="{{ $item['version'] }}" class="hidden">
                <input type="text" name="itemsQty" value="{{ $item['itemsQty'] }}" class="hidden">
                <input type="text" name="amount" value="{{ $item['amount'] }}" class="hidden">
                
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
        @endif  
        <div>
          <label for="totalPrice" class="block text-left font-medium">ยอดชำระ</label>
          <div class="mt-2">
            <input type="text" name="totalPrice" id="totalPrice"
              class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกยอดชำระทั้งหมด">
          </div>
        </div>

        <button type="button" id="submitStatus" class="px-10 py-1.5 mt-4 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
        <button id="cancelPaymentPopup" type="button" class="px-10 py-1.5 mt-4 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
      </div>
    </div>
  </div>
</div>