<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> เพิ่มข้อมูลใบกำกับภาษี </title>

  @vite('resources/css/app.css')

  <style>
    /* ซ่อนปุ่มเลื่อนตัวเลขขึ้นลงใน input type="number" */
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type=number] {
      -moz-appearance: textfield;
    }
  </style>

  {{-- JQUERY --}}
  <link rel="stylesheet" href="{{ asset('css/jquery.Thailand.min.css') }}">
  <script src="{{ asset('js/addressThailand/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('js/addressThailand/JQL.min.js') }}"></script>
  <script src="{{ asset('js/addressThailand/typeahead.bundle.js') }}"></script>
  <script src="{{ asset('js/addressThailand/jquery.Thailand.js') }}"></script> <!-- jQuery -->
  <script src="{{ asset('js/addressThailand/jquery.Thailand.min.js') }}"></script> <!-- Plugin -->

  <script>
    /* Address Thailand */
    $(document).ready(function() {
      $.Thailand({
        database: '{{ asset('js/addressThailand/db.json') }}',
        $district: $('#district'),
        $amphoe: $('#amphoe'),
        $province: $('#province'),
        $zipcode: $('#zipcode'),
      });
    });

    /* Date Picker */
    document.addEventListener('DOMContentLoaded', function() {
      var today = new Date().toISOString().split('T')[0];
      var invoiceShipDateInput = document.getElementById('invoiceShipDate');
      invoiceShipDateInput.setAttribute('min', today);
      invoiceShipDateInput.value = today;
    });

    /* Update Invoice Value */
    function updateInvoiceValue() {
      var select = document.getElementById('invoiceQuotaCode');
      var selectedOption = select.options[select.selectedIndex];
      var amount = selectedOption.getAttribute('data-amount').replace(/,/g, '');
      document.getElementById('invoiceValue').value = amount;
      var version = selectedOption.getAttribute('data-version');
      document.getElementById('quotaVersion').value = version;
    }
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.querySelector('form');
      const errorMessage = document.getElementById('error-message');

      console.log(localStorage);
      form.addEventListener('submit', function (event) {
        event.preventDefault();
        const formData = new FormData(form);

        fetch(form.action, {
          method: 'POST',
          body: formData,
          headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            window.close();
          } else {
              errorMessage.textContent = 'เกิดข้อผิดพลาด โปรดตรวจสอบข้อมูลอีกครั้ง';
              errorMessage.classList.remove('hidden');
            }
          })
          .catch(error => {
            errorMessage.textContent = 'เกิดข้อผิดพลาด โปรดตรวจสอบข้อมูลอีกครั้ง';
            errorMessage.classList.remove('hidden');
          });
      });
    });
  </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
<form method="POST" action="{{ route('add-invoice') }}">
    @csrf
  <input type="hidden" name="invoiceEmpCode" value="{{ $empCode }}">
  <div class="container mx-auto p-4 md:p-5 bg-white shadow-md rounded-lg">
    <p class="text-2xl font-semibold mb-4">กรอกข้อมูลใบกำกับภาษี และข้อมูลของลูกค้า</p>
    <div class="flex items-center mb-4">
      <p class="font-semibold mr-4">เลือกรูปแบบการจัดส่ง</p>
      <div class="flex items-center space-x-4">
        @if ($info)
          <label class="inline-flex items-center">
            <input type="radio" name="shipOption" value="1" class="form-radio" required>
            <span class="ml-2">มารับที่สาขา</span>
          </label>
        @endif
        <label class="inline-flex items-center">
          <input type="radio" name="shipOption" value="2" class="form-radio" required>
          <span class="ml-2">จัดส่งรอบเช้า</span>
        </label>
        <label class="inline-flex items-center">
          <input type="radio" name="shipOption" value="3" class="form-radio" required>
          <span class="ml-2">จัดส่งรอบบ่าย</span>
        </label>
      </div>
      <p class="font-semibold ml-48 mr-4">วันที่ส่ง</p>
      <div>
        <div class="mt-5">
          <input type="date" name="invoiceShipDate" id="invoiceShipDate"
            class="block w-full px-3 py-2 bg-red-50 border border-red-500 text-red-900 rounded-md
          placeholder:text-gray-400" required>
        </div>
      </div>
    </div>

    <div class="my-4 grid grid-cols-2 gap-4">
      @if ($info)
        <input type="hidden" name="taskCode" value="{{ $invoiceInfo['cusInfo']['TaskCode'] }}">
        <input type="hidden" name="invoiceCusCode" value="{{ $invoiceInfo['cusInfo']['CusPhoneCode'] }}">
        <div>
          <label for="invoiceQuotaCode" class="block text-left font-medium">เลขที่ใบเสนอราคา</label>
          <div class="mt-5">
            <select name="invoiceQuotaCode" id="invoiceQuotaCode"
              class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300text-gray-400"
              onchange="updateInvoiceValue()">>
              <option value="" class="text-gray-400" disabled selected>เลือกเลขที่ใบเสนอราคา</option>
              @foreach ($invoiceInfo['quotations'] as $quotation)
                <option value="{{ $quotation['quotaCode'] }}"  data-amount="{{ $quotation['amount'] }}" data-version="{{ $quotation['version'] }}">
                  {{ $quotation['quotaCode'] }}
                </option>
              @endforeach
            </select>
            <input type="hidden" name="quotaVersion" id="quotaVersion">
          </div>
        </div>
      @else
        <div>
          <label for="invoiceCusCode" class="block text-left font-medium">รหัสลูกค้า</label>
            <div class="mt-5">
              <input type="text" name="invoiceCusCode" id="invoiceCusCode"
                class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
                placeholder:text-gray-400"  placeholder=" โปรดกรอกเลขที่ใบเสนอราคา" required>
            </div>
        </div>
      @endif

      <div>
        <label for="invoiceCode" class="block text-left font-medium">เลขที่ใบกำกับภาษี</label>
        <div class="mt-5">
          <input type="text" name="invoiceCode" id="invoiceCode"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกเลขที่ใบกำกับภาษี" required>
        </div>
      </div>
    </div>

    <div class="my-4 grid grid-cols-3 gap-4">
      <div>
        <label for="invoiceValue" class="block text-left font-medium">ยอดราคารวม</label>
          <div class="mt-5">
            <input type="number" name="invoiceValue" id="invoiceValue"
              class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400"  placeholder=" โปรดกรอกยอดราคารวม" required>
          </div>
      </div>

      <div>
        <label for="invoiceWeight" class="block text-left font-medium">น้ำหนักสินค้า</label>
        <div class="mt-5">
          <input type="number" name="invoiceWeight" id="invoiceWeight"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกน้ำหนักสินค้าที่จะขนส่ง (กิโลกรัม)" required>
        </div>
      </div>

      @if (!($info))
        <div>
          <label for="invoiceWeight" class="block text-left font-medium">ต้องการรถโฟร์คลิฟต์</label>
          <div class="flex items-center space-x-4 mt-5">
            <label class="inline-flex items-center">
              <input type="radio" name="forkLiftOption" value="Y" class="form-radio" required>
              <span class="ml-2">ใช่</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" name="forkLiftOption" value="N" class="form-radio" required>
              <span class="ml-2">ไม่</span>
            </label>
          </div>
        </div>
      @endif
    </div>

    <div class="mt-4">
      <label for="addr" class="block text-left font-medium">สถานที่จัดส่ง</label>
      <div class="mt-5">
        <input type="text" name="addr" id="addr"
          class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
           placeholder:text-gray-400" placeholder=" โปรดกรอกสถานที่จัดส่ง" required>
      </div>
    </div>

    <div class="mt-4 grid grid-cols-4 gap-4">
      <div>
        <label for="district" class="block text-left font-medium">แขวง / ตำบล</label>
        <div class="mt-5">
          <input type="text" name="district" id="district"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกแขวง / ตำบล" required>
        </div>
      </div>
      <div>
        <label for="amphoe" class="block text-left font-medium">เขต / อำเภอ</label>
        <div class="mt-5">
          <input type="text" name="amphoe" id="amphoe"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกเขต / อำเภอ" required>
        </div>
      </div>

      <div>
        <label for="province" class="block text-left font-medium">จังหวัด</label>
        <div class="mt-5">
          <input type="text" name="province" id="province"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกจังหวัด" required>
        </div>
      </div>

     <div>
        <label for="zipcode" class="block text-left font-medium">ไปรษณีย์</label>
        <div class="mt-5">
          <input type="text" name="zipcode" id="zipcode"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกไปรษณีย์" required>
        </div>
      </div>
    </div>
    <div class="my-4 grid grid-cols-2 gap-4">
      <div>
        <label for="invoiceReceiverName" class="block text-left font-medium">ชื่อผู้รับของ</label>
        <div class="mt-5">
          <input type="text" name="invoiceReceiverName" id="invoiceReceiverName" value="@if ($info) {{ $invoiceInfo['cusInfo']['CusName'] }} @endif"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
             placeholder:text-gray-400" placeholder=" โปรดกรอกชื่อผู้รับของ" required>
        </div>
      </div>
      <div>
        <label for="invoiceReceiverPhone" class="block text-left font-medium">เบอร์โทรศัพท์ผู้รับของ</label>
        <div class="mt-5">
          <input type="text" name="invoiceReceiverPhone" id="invoiceReceiverPhone" value="@if ($info) {{ $invoiceInfo['cusInfo']['CusPhone'] }} @endif"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
              placeholder:text-gray-400" placeholder=" โปรดกรอกเบอร์โทรศัพท์ผู้รับของ" required>
        </div>
      </div>
    </div>

    <div class="mt-4">
      <label for="invoiceNote" class="block text-left font-medium">ข้อมูลเพิ่มเติม</label>
      <div class="mt-5">
        <textarea name="invoiceNote" id="invoiceNote" rows="4" maxlength="500"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder="กรอกข้อมูลเพิ่มเติม (ไม่เกิน 500 ตัวอักษร)"></textarea>
      </div>
    </div>
    <div id="error-message" class="text-sm text-red-500 my-4 hidden"></div>
    <div class="flex justify-end space-x-4 mt-4">
      <button type="submit" class="px-10 py-1.5 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
      <button type="button" onclick="window.close()" class="px-10 py-1.5 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
    </div>
  </div>
</form>
</body>
</html>
