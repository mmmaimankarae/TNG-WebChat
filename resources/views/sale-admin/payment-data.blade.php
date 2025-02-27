<script src="{{ asset('/js/detailMsg/handleNoimage.js') }}"></script>
<script src="{{ asset('/js/showFileName.js') }}"></script>
<script src="{{ asset('/js/sale-admin/showDesc.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  {{ $title }}
@endsection

@section('content')
  <div class="container mx-auto p-10 pl-80">
    <input type="hidden" id="dataDesc" value="{{ e($dataDesc) }}">
    <div class="flex items-center mb-4">
      <h2 class="text-xl font-semibold">{{ 'เพิ่ม/แก้ไข' . $title }}</h2>
      <button type="button" id="openDesc" class="ml-5 p-1 text-xs bg-amber-200 rounded-md hover:bg-amber-300">แก้ไขข้อความแจ้งการชำระเงิน</button>
    </div>
    @if ($errors->has('desc'))
      <div class="text-sm text-red-500 my-2">{{ $errors->first('desc') }}</div>
    @endif
    <form method="POST" action="{{ route('sale-admin.payment-desc') }}" id="payment" class="hidden my-4">
      @csrf
      <input type='text' name='accCode' value='{{ $accCode }}' class="hidden">
      <div class="flex items-center mb-2">
        <label for="desc" class="text-left font-medium">ข้อความแจ้งการชำระเงิน</label>
        <button type="button" id="currentDesc" class="ml-5 p-1 text-xs bg-amber-200 rounded-md hover:bg-amber-300">ดูข้อความปัจจุบัน</button>
      </div>
      <div class="flex items-center space-x-4">
        <textarea name="desc" id="desc" rows="4" maxlength="800"
          class="block w-full px-3 py-2 text-sm bg-white rounded-md outline-1 -outline-offset-1 outline-gray-300
          placeholder:text-gray-400" required></textarea>
        <button type="submit" class="p-2 text-xs bg-red-300 rounded-md hover:bg-red-400">ยืนยัน</button>
      </div>
    </form>
    <form id="regionForm" method="POST" action="{{ route('sale-admin.payment') }}">
        @csrf
        <div class="flex flex-wrap items-center justify-center space-x-8">
        @foreach ($regionThai as $key => $region)
          <div class="flex items-center px-4 mb-3 border border-gray-300 rounded-lg shadow">
            <input type="radio" value="{{ $key }}" name="region" class="w-4 h-4 text-blue-300 border-gray-300 rounded-md" {{ $key == request()->input('region') ? 'checked' : '' }}
              onchange="document.getElementById('regionForm').submit();">
            <label class="w-full py-4 ms-2 text-sm"> {{ $region }} </label>
          </div>
        @endforeach
        </div>
      </form>
    <table class="w-full text-left">
      <thead class="uppercase bg-blue-100 border border-blue-200">
        <tr>
          @foreach ($table as $item)
            <th scope="col" class="px-6 py-3 rounded-s-lg">{{ $item }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody class="text-sm">
        @foreach ($data as $item)
          <tr>
            @php
              $imagePath = basename($item->PayAccPath);
              $imageUrl = $imagePath !== 'NO IMAGE' ? asset('storage/payments/' . $imagePath) : null;
            @endphp
            <td scope="col" class="px-6 py-3 rounded-s-lg font-semibold">{{ $item->BrchCode }}</td>
            <td scope="col" class="pl-6 py-3 rounded-s-lg">
              @if ($imageUrl)
                <img id="messageImage" class="rounded-md w-3/5" src="{{ $imageUrl }}" onerror="handleImageError()">
              @else
                <span>No Image Available</span>
              @endif
            </td>
            <form action="{{ route('add-data') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <td scope="col" class="px-3 py-3 rounded-s-lg">
                <div class="flex justify-between items-center mb-4 w-full">
                  <input type='text' name='table' value='payment' class="hidden">
                  <input type='text' name='accCode' value='{{ $accCode }}' class="hidden">
                  <input type='text' name='brchCode' value='{{ $item->BrchCode }}' class="hidden">
                  <input type="file" name="file" id="file_{{ $item->BrchCode }}" accept=".JPEG" class="hidden" required onchange="showFileName(this)">
                  <label for="file_{{ $item->BrchCode }}" id="fileName_{{ $item->BrchCode }}" 
                    class="block w-full px-3 py-2 mt-2 text-center text-gray-400 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-500 cursor-pointer">
                    เลือกไฟล์ที่มีนามสกุล .JPEG ไม่เกิน 10MB
                  </label>
              </td>
              <td scope="col" class="px-3 py-3 rounded-s-lg">
                <button type="submit" class="ml-5 mt-2 px-4 py-2 bg-red-300 rounded-md hover:bg-red-400">ยืนยัน</button>
               </td>
                </div>
            </form>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
