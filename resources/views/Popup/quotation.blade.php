<script src="{{ asset('/js/pdf.js') }}"></script>
<div id="quotationPopup" tabindex="-1" class="hidden fixed z-50 inset-0 flex justify-center items-center w-full h-full bg-gray-500/60">
  <div class="relative w-4xl p-4">
    <div class="relative bg-white rounded-lg shadow">
      <div class="p-4 md:p-5 text-center">
        <label class="block text-lg font-semibold" for="file_input">อัปโหลดใบเสนอราคา</label>
        <form id="quotationForm" method="POST" action="{{ route('upload-pdf') }}" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="replyId" value="{{ old('replyId', $taskLineID) }}">
          <input type="hidden" name="replyName" value="{{ old('replyName', $cusName) }}">
          <input type="hidden" name="cusCode" value="{{ old('cusCode', $cusCode) }}">
          <input type="hidden" name="taskCode" value="{{ old('taskCode', $taskCode) }}">
          <input type="hidden" name="userId" value="{{ old('userId', $accCode) }}">
          <input type="hidden" name="userName" value="{{ old('userName', $accName->AccName) }}">
          <input type="hidden" name="taskStatus" value="{{ old('taskStatus', $taskStatus) }}">
          <input type="hidden" name="empCode" value="{{ old('empCode', $empCode) }}">
          <div class="mt-4">
            <label class="inline-flex items-center">
              <input type="radio" name="quotaOption" value="AI" class="form-radio" required>
              <span class="ml-2">อัปเซลล์ด้วย AI</span>
            </label>
            <label class="inline-flex items-center ml-4">
              <input type="radio" name="quotaOption" value="image" class="form-radio" required>
              <span class="ml-2">ส่งภาพใบเสนอราคา</span>
            </label>
          </div>

          <div class="relative text-sm">
            <input type="file" id="file_input" name="file" accept=".PDF" class="block absolute top-0 left-0 z-10 w-full px-3 py-2 mt-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 opacity-0" required>
            <div class="block w-full px-3 py-2 mt-2 text-gray-400 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300" id="file_input_label">
              เลือกไฟล์ที่มีนามสกุล .PDF
            </div>
          </div>
          <button type="submit" id="submitStatus" class="px-10 py-1.5 mt-4 text-white bg-[#FF0000] shadow-sm rounded-lg hover:text-black hover:bg-slate-300">ตกลง</button>
          <button id="cancelQuotaPopup" type="button" class="px-10 py-1.5 mt-4 bg-white border border-gray-300 shadow-sm rounded-lg hover:bg-slate-200">ยกเลิก</button>
        </form>
      </div>
    </div>
  </div>
</div>