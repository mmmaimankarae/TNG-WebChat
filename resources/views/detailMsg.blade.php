@extends('Header-Sidebar.layout')
@section('title')
  รายการงานใหม่
@endsection
<script src="{{ asset('/js/listTodetail.js') }}"></script>
@section('content')
  <div class="px-20 py-10">
    <p>Task Code: {{ $taskCode }}</p>
    <p>Customer Code: {{ $cusCode }}</p>
    <p>Customer Name: {{ $cusName }}</p>
  </div>
@endsection