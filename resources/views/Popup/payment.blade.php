<script src="{{ asset('/js/priceCal.js') }}"></script>
<div id="paymentPopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-4xl p-4">
    <div class="relative bg-white rounded-lg shadow">
      <div class="p-4 md:p-5 text-center">
        <p class="text-lg font-semibold">แจ้งยอดการชำระเงิน</p>
        <div class="my-4 grid grid-cols-2 gap-4">
          <div>
            <label for="prodsPrice" class="block text-left text-sm font-medium">ค่าสินค้า</label>
            <div class="mt-2">
              <input type="text" name="prodsPrice" id="prodsPrice" autocomplete="off"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกเลขที่ใบกำกับภาษี" required>
            </div>
          </div>
          <div>
            <label for="deliPrice" class="block text-left text-sm font-medium">ค่าจัดส่ง</label>
            <div class="mt-2">
              <input type="text" name="deliPrice" id="deliPrice" autocomplete="off"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกค่าจัดส่ง">
            </div>
          </div>
        </div>
  
        <div>
          <label for="totalPrice" class="block text-left text-sm font-medium">ยอดชำระ</label>
          <div class="mt-2">
            <input type="text" name="totalPrice" id="totalPrice" readonly
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