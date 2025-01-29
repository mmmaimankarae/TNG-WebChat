<script src="{{ asset('/js/listTodetail.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  รายการงานใหม่
@endsection
@section('content')
  <div>
    <form class="flex items-center max-w-5xl mx-auto px-20 pt-10">
      <input type="text" id="simple-search" class="block w-full px-3 py-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-[#6ebf9d]
      placeholder:text-sm placeholder:text-gray-400" placeholder="ค้นหาชื่อลูกค้า, หมายเลขโทรศัพท์" required />
      <button type="submit" class="flex items-center px-3.5 py-2.5 ms-2.5 bg-[#6ebf9d] rounded-lg hover:bg-[#3d9571]">
        <img src="{{ asset('images/Search.png') }}" alt="search" class="h-5 w-auto"/>
      </button>
    </form>

    <div class="overflow-x-auto px-20 py-10">
      <table class="min-w-full text-center text-sm">
        <thead class="tracking-widest border-b-1 border-gray-300">
          <tr>
            <th class="px-6 py-3">ชื่อลูกค้า</th>
            <th class="px-6 py-3">ประเภทข้อความ</th>
            <th class="px-6 py-3">เวลา</th>
            <th class="px-6 py-3">พนักงานที่ดูแล</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white ">
          @foreach ($results as $row)
            @php $status = trim($row->TasksStatusCode); @endphp
            <tr class="rounded-lg shadow-md shadow-gray-200 clickable-row">
              <td class="px-6 py-4">{{ $row->CusName }}</td>
              <td class="px-6 py-4">
                @if (in_array($status, [1, 4, 5]))
                  <div class="block py-2 my-2 bg-blue-300 rounded-lg"> สอบถาม/ขอใบเสนอราคา </div>
                @elseif ($status == 7)
                  <div class="block py-2 my-2 bg-red-300 rounded-lg"> สั่งซื้อสินค้า </div>
                @elseif ($status == 10)
                  <div class="block py-2 my-2 bg-amber-300 rounded-lg"> ถึงกำหนดวันนัดรับสินค้า </div>
                @elseif ($status == 9)
                  <div class="block py-2 my-2 bg-[#6ebf9d] rounded-lg"> ชำระเงินแล้ว </div>
                @elseif ($status == 14)
                  <div class="block py-2 my-2 bg-amber-400 rounded-lg"> ดำเนินเรื่องการคืนเงิน </div>
                @endif
              </td>
              <td class="px-6 py-4">{{ $row->TasksUpdate }}</td>
              <td class="px-6 py-4">{{ $row->participant ?? '' }}</td>
            </tr>
            <form id="taskForm" method="POST" action="">
              @csrf
              <input type="hidden" name="taskCode" id="taskCode" value="{{ $row->TasksCode }}">
              <input type="hidden" name="cusCode" id="cusCode" value="{{ $row->CusCode }}">
              <input type="hidden" name="cusName" id="cusName" value="{{ $row->CusName }}">
            </form>        
          @endforeach
        </tbody>
    </table>
  </div>
</div>
@endsection