<script src="{{ asset('/js/assignTask.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  มอบหมายงานให้สาขา
@endsection
@section('content')
  <div class="ml-96 pr-20 py-8 overflow-x-auto">
    <p class="text-center font-medium tracking-wider mb-3 break-words whitespace-pre-wrap"> หมอบหมายงานคุณ ' {{ $cusName}} ' ให้สาขา </p>
    <p class="font-medium tracking-wider mb-3"> เลือกภูมิภาคของสาขา </p>
    <form id="regionForm" method="POST" action="{{ route('sale-admin.assign-task') }}">
      @csrf
      <div class="flex flex-wrap items-center justify-center space-x-8">
      @foreach ($regionThai as $key => $region)
        <div class="flex items-center px-4 mb-3 border border-gray-300 rounded-lg shadow">
          <input type="radio" value="{{ $key }}" name="region" class="w-4 h-4 text-blue-300 border-gray-300 rounded-md" {{ $key == request()->input('region') ? 'checked' : '' }}
            onchange="document.getElementById('regionForm').submit();">
          <label class="w-full py-4 ms-2 text-sm"> {{ $region }} </label>
          <input type="hidden" name="taskCode" value="{{ $taskCode }}">
          <input type="hidden" name="cusName" value="{{ $cusName }}">
        </div>
      @endforeach
      </div>
    </form>
    <form  method="POST" action="{{ route('sale-admin.confirm-assign') }}">
      @csrf
      <div class="flex justify-between my-4">
        <button type="button" onclick="window.history.back()" class="px-10 py-1.5 text-sm font-semibold text-white bg-[#FF0000] rounded-full shadow-sm hover:bg-slate-400"> ย้อนกลับ </button>
        <input type="hidden" name="taskCode" value="{{ $taskCode }}">
        <input type="hidden" id="branchCode" name="branchCode" value="">
        <div class="flex justify-end">
          <button id="confirmButton" type="submit" class="hidden px-10 py-1.5 text-sm font-semibold text-white bg-[#6ebf9d] rounded-full shadow-sm hover:bg-[#3d9571]"> ยืนยัน </button>
        </div>
      </div>
      <hr class="mt-8">
      <table class="min-w-full text-center text-sm">
        <thead class="text-gray-700 border-b-2">
          <tr>
          <th class="px-6 py-3">สาขา</th>
          <th class="px-6 py-3">เบอร์โทรศัพท์</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          @foreach ($branchs as $row)
            <tr id="branchCode" class="pb-4 rounded-lg shadow-md shadow-gray-200 clickable-row" data-brch="{{ $row->BrchCode }}">
              <td class="px-6 py-4">{{ $row->BrchName }}</td>
              <td class="px-6 py-4">{{ $row->BrchPhone }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </form>
  </div>
@endsection