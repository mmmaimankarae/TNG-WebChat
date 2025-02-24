@php
  if (!function_exists('getPopupId')) {
    function getPopupId($key) {
      switch ($key) {
        case '3':
          return 'showQuotationPopup';
        case '4':
          return 'showPaymentPopup';
        case '5':
          return 'showInvoicePopup';
        default:
          return '';
      }
    }
  }

  if (!function_exists('getHoverClass')) {
    function getHoverClass($key) {
      return in_array($key, ['3', '4', '5']) ? 'hover:text-black hover:bg-red-300' : '';
    }
  }
@endphp


<div class="sticky top-12 z-10 p-4 tracking-wider bg-white shadow-md">
  <div class="grid grid-cols-5 text-sm">
    @foreach ($statusThai as $key => $status)
      <div class="px-3">
        @if (old('taskStatus', $taskStatus) == $key)
          <span id="{{ getPopupId($key) }}" type="button" class="inline-flex justify-center w-full px-3 py-1.5 bg-[#FF4343] shadow-sm rounded-md text-white {{ getHoverClass($key) }}">
            {{ $status }}
          </span>
        @else
          <form method="POST" action="">
            @csrf
            <input type="hidden" name="taskLineID" value="{{ old('replyId', $taskLineID) }}">
            <input type="hidden" name="taskCode" value="{{ old('taskCode', $taskCode) }}">
            <input type="hidden" name="cusCode" value="{{ old('cusCode', $cusCode) }}">
            <input type="hidden" name="cusName" value="{{ old('replyName', $cusName) }}">
            <input type="hidden" name="showchat" value="true">
            <input type="hidden" name="updateStatus" value="true">
            <input type="hidden" name="taskStatus" value="{{ $key }}">
            @if(in_array($key, ['3', '4', '5']))
              <button id="{{ getPopupId($key) }}" type="button" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                {{ $status }}
              </button>
            @elseif($key == '6')
              <button type="submit" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                {{ $status }}
              </button>
            @else
              <input type="hidden" name="userId" value="bot">
              <input type="hidden" name="userName" value="tangbot">
              <button type="submit" class="inline-flex justify-center w-full px-3 py-1.5 bg-white shadow-sm rounded-md ring-1 ring-inset ring-gray-300 hover:bg-gray-50 hover:ring-[#FF4343]">
                {{ $status }}
              </button>
            @endif
          </form>
        @endif
        {{-- ปุ่มส่งต่อให้สาขา --}}
        @if ($roleCode == '2' && $key == '6' && $taskStatus != '6')
          <div class="mt-5 flex justify-end">
            <form method="POST" action="{{ route('sale-admin.assign-task') }}">
              @csrf
              <input type="hidden" name="taskCode" value="{{ old('taskCode', $taskCode) }}">
              <input type="hidden" name="cusName" value="{{ old('replyName', $cusName) }}">
              <button type="submit" class="text-xs py-1 px-3 border border-gray-500 rounded-2xl hover:bg-gray-300">มอบหมายให้สาขา</button>
            </form>
          </div>
        @endif  
      </div>
    @endforeach
  </div>
</div>