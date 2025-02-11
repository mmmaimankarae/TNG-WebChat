@extends('Header-Sidebar.layout')
@section('title')
  {{ $title }}
@endsection

@section('content')
  <div class="container mx-auto p-4">
    <h2 class="text-xl font-semibold mb-4">{{ $title }}</h2>
    <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex justify-between items-center mb-4 w-full">
        @csrf
        <input type="file" id="employee_csv" accept=".csv" class="block absolute w-full px-3 py-2 mt-2 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-500 opacity-0">
        <div class="block w-full px-3 py-2 mt-2 text-gray-400 bg-white rounded-md outline-1 -outline-offset-1 outline-gray-500" id="file_input_label">
            เลือกไฟล์ที่มีนามสกุล .CSV
        </div>
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
        @if ($title != 'เพิ่มข้อมูลสาขา') 
          @php
            $count = count($table);
          @endphp
          @foreach ($data as $item)
            <tr>
              @php
                $itemArray = get_object_vars($item); // แปลง object เป็น array
              @endphp
              @for ($i = 0; $i < $count-1; $i++)
                <td class="px-6 py-4"></td>
              @endfor
              <td class="px-6 py-4">
                <span class="text-lg font-semibold text-red-500"> {{ $itemArray[array_keys($itemArray)[0]] }} </span>
                {{ '-' .$itemArray[array_keys($itemArray)[1]] }}
              </td>
            </tr>
          @endforeach
        @else
          <tr>
            <td class="px-6 py-4">HO</td>
            <td class="px-6 py-4">สำนักงานใหญ่</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
@endsection