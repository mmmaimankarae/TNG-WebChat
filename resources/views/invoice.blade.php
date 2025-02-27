<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700&display=swap"
        rel="stylesheet">
    <title>เพิ่มข้อมูลใบกำกับภาษี</title>

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
    </script>
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
  <form method="POST" action="">
    @csrf
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

    <div class="my-4 grid grid-cols-3 gap-4">
      @if ($info)
        <div>
          <label for="invoiceQuotaCode" class="block text-left font-medium">เลขที่ใบเสนอราคา</label>
          <div class="mt-5">
            <select name="invoiceQuotaCode" id="invoiceQuotaCode"
              class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300text-gray-400">
              <option value="" class="text-gray-400" disabled selected>เลือกเลขที่ใบเสนอราคา</option>
              @foreach ($invoiceInfo['quotations'] as $quotation)
                <option value="{{ $quotation['quotaCode'] }}">{{ $quotation['quotaCode'] }}</option>
              @endforeach
            </select>
          </div>
        </div>
      @else
        <div>
          <label for="invoiceCusCode" class="block text-left font-medium">รหัสลูกค้า</label>
            <div class="mt-5">
              <input type="text" name="invoiceCusCode" id="invoiceCusCode" value="@if ($info) {{ $invoiceInfo['cusInfo']['CusCode'] }} @endif"
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
      <div>
        <label for="invoiceWeight" class="block text-left font-medium">น้ำหนักสินค้า</label>
        <div class="mt-5">
          <input type="number" name="invoiceWeight" id="invoiceWeight"
            class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
            placeholder:text-gray-400" placeholder=" โปรดกรอกน้ำหนักสินค้าที่จะขนส่ง" required>
        </div>
      </div>
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
          <input type="text" name="invoiceReceiverPhone" id="invoiceReceiverPhone" value="@if ($info) {{ $invoiceInfo['cusInfo']['CusPhoneCode'] }} @endif"
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

    <div class="flex justify-end space-x-4 mt-4">
      <button type="submit" class="px-10 py-1.5 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
      <button type="button" onclick="window.close()" class="px-10 py-1.5 bg-white shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
    </div>
  </div>
  </form>
</body>
</html>
