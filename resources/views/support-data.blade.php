<script src="{{ asset('/js/dataCSV.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  {{ $title }}
@endsection

@section('content')
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">{{ 'เพิ่ม' . $title }}</h2>
    <h5 class="text-sm text-red-500 mb-4">
      @if (session('messageInsert')) 
        {{ session('messageInsert') }} 
      @endif
    </h5>
    <form action="{{ route('add-data') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="flex justify-between items-center mb-4 w-full">
        <input type="file" name="data_csv" id="data_csv" accept=".csv" class="hidden" required>
        <label for="data_csv" class="block w-full px-3 py-2 mt-2 text-gray-400 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-500 cursor-pointer" id="file_input_label">
          เลือกไฟล์ที่มีนามสกุล .CSV
        </label>
        <button type="submit" class="ml-5 mt-2 px-4 py-2 text-white bg-amber-500 rounded-md">อัพโหลด</button>
      </div>
    </form>
    <table class="w-full text-left">
      <thead class="uppercase bg-amber-100 border border-amber-200">
        <tr>
          @foreach ($table as $item)
            <th scope="col" class="px-6 py-3 rounded-s-lg">{{ $item }}</th>
          @endforeach
        </tr>
      </thead>
      <tbody class="text-sm">
        @php
          $count = count($table);
          $round = 0;
        @endphp
        @foreach ($data as $item)
          <tr>
            @php
              $itemArray = get_object_vars($item);
              $round++;
            @endphp
            @for ($i = 0; $i < $count-1; $i++)
              @if ($title == 'ข้อมูลสาขา' && $i == 0 && $round == 1)
                <td class="px-6 py-4"><span class="text-lg font-semibold text-red-500">HO</span></td>
              @else
                <td class="px-6 py-4"></td>
              @endif
            @endfor
            <td class="px-6 py-4">
              <span class="text-lg font-semibold text-red-500"> {{ $itemArray[array_keys($itemArray)[0]] }} </span>
              {{ '-' .$itemArray[array_keys($itemArray)[1]] }}
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  @if ($title == 'ข้อมูลสินค้า')
    <div class="container mx-auto p-4 mb-12">
      <h2 class="text-xl font-semibold mb-4">เพิ่มข้อมูลประเภท</h2>
      <h5 class="text-sm text-red-500 mb-4">
        @if (session('messageInsertPd')) 
          {{ session('messageInsertPd') }} 
        @endif
      </h5>
      <form action="{{ route('add-data-type') }}" method="POST">
        @csrf
        <input type='text' name='typeCode' class="w-1/3 px-3 py-2 mt-2 text-gray-500 border rounded-md" placeholder="กรุณาระบุรหัสประเภทสินค้า" required>
        <input type='text' name='typeName' class="w-1/3 px-3 py-2 mt-2 ml-5 text-gray-500 border rounded-md" placeholder="กรุณาระบุประเภทสินค้า" required>
        <button type="submit" class="ml-5 mt-2 px-4 py-2 text-white bg-amber-500 rounded-md">เพิ่มประเภท</button>
      </form>
  @endif
@endsection