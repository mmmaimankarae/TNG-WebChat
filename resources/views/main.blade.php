<script src="{{ asset('/js/detailMsg/handleNoImage.js') }}"></script>
<script src="{{ asset('/js/detailMsg/handleCopyData.js') }}"></script>
<script src="{{ asset('/js/main.js') }}"></script>
<script src="{{ asset('/js/popup.js') }}"></script>
@extends('Header-Sidebar.layout')
@section('title')
  @if (request()->segment(2) === 'new-tasks')
    รายการงานใหม่ 
  @else
    งานที่ดำเนินอยู่
  @endif
@endsection

@section('content')
  @if ($select)
  <div class="flex flex-1 h-screen">
    <div class="flex-1 flex flex-col">
      {{-- ส่วนของสถานะ --}}
      @include('Main-Components.status')

      {{-- ประวัติแชท & ช่องส่งข้อมูล --}}
      @include('Main-Components.chat')
    </div>

    {{-- ส่วนข้อมูลลูกค้า & รายการสินค้า --}}
    @include('Main-Components.rightSide')
  @else
    <div class="flex flex-col justify-center items-center h-screen -mt-12">
      <img src="{{ asset('images/logo2.png') }}" alt="notfound" class="w-1/3 h-auto mb-4 opacity-75 shadow-md shadow-red-200"/>
      <p class="text-xl text-gray-600">
        เริ่มต้นส่งข้อความเลย!
      </p>
    </div>
  @endif
@endsection