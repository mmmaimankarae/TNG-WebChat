<div id="invoicePopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-4xl p-4">
    <div class="relative bg-white rounded-lg shadow">
      <div class="p-4 md:p-5 text-center">
        <p class="text-lg font-semibold">แนบใบกำกับภาษี และกรอกข้อมูลของลูกค้า</p>
        <div class="my-4 grid grid-cols-2 gap-4">
          <div>
            <label for="invoiceNo" class="block text-left text-sm font-medium">เลขที่ใบกำกับภาษี</label>
            <div class="mt-2">
              <input type="text" name="invoiceNo" id="invoiceNo"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกเลขที่ใบกำกับภาษี">
            </div>
          </div>
          <div>
            <label for="cusName" class="block text-left text-sm font-medium">ชื่อลูกค้า</label>
            <div class="mt-2">
              <input type="text" name="cusName" id="cusName"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อลูกค้าที่ระบุในใบกำกับภาษี">
            </div>
          </div>
        </div>

        <div>
          <label for="addr" class="block text-left text-sm font-medium">สถานที่จัดส่ง</label>
          <div class="mt-2">
            <input type="text" name="addr" id="addr"
              class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกสถานที่จัดส่ง">
          </div>
        </div>

        <div class="my-4 grid grid-cols-2 gap-4">
          <div>
            <label for="contactName" class="block text-left text-sm font-medium">ชื่อผู้รับของ</label>
            <div class="mt-2">
              <input type="text" name="contactName" id="contactName"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อผู้รับของ">
            </div>
          </div>
          <div>
            <label for="contactPhone" class="block text-left text-sm font-medium">เบอร์โทรศัพท์ผู้รับของ</label>
            <div class="mt-2">
              <input type="text" name="contactPhone" id="contactPhone"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกเบอร์โทรศัพท์ผู้รับของ">
            </div>
          </div>
        </div>

        <div class="my-4 grid grid-cols-2 gap-4">
          <div>
            <label for="deliDate" class="block text-left text-sm font-medium">วันที่ส่งของ</label>
            <div class="mt-2">
              <input type="text" name="deliDate" id="deliDate"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกวันที่ส่งของ">
            </div>
          </div>
          <div>
            <label for="deliWeight" class="block text-left text-sm font-medium">น้ำหนักสินค้า</label>
            <div class="mt-2">
              <input type="text" name="deliWeight" id="deliWeight"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-sm placeholder:text-gray-400" placeholder=" โปรดกรอกน้ำหนักสินค้าที่จะขนส่ง">
            </div>
          </div>
        </div>

        <label class="block text-sm font-medium" for="file_input">อัปโหลดใบเสนอราคาที่ลูกค้าตกลงสั่งซื้อ</label>
        <div class="relative text-sm">
          <input class="block absolute top-0 left-0 z-10 w-full px-3 py-2 mt-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 opacity-0" id="file_input" type="file" accept=".JPEG, .PNG">
          <div class="block w-full px-3 py-2 mt-2 text-gray-400 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300" id="file_input_label">
            เลือกไฟล์ที่มีนามสกุล .JPEG หรือ .PNG
          </div>
        </div>
        {{-- พื้นที่สำหรับแสดงรูปที่เลือก --}}
        <div id="preview-container" class="mt-4 hidden">
          <p class="text-sm font-medium text-gray-900 dark:text-white">รูปภาพตัวอย่าง:</p>
          <img id="preview-image" class="w-full h-auto rounded-lg" src="" alt="Image Preview">
        </div>

        <button type="button" id="submitStatus" class="px-10 py-1.5 mt-4 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
        <button id="cancelInvoicePopup" type="button" class="px-10 py-1.5 mt-4 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
      </div>
    </div>
  </div>
</div>