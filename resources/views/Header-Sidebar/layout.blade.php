<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> @yield('title') </title>

  @vite('resources/css/app.css')
  
  <script src="{{ asset('/js/header-sidebar.js') }}"></script>
</head>
<body>
  {{-- แสดง Header ตาม Role --}}
  @if (request()->attributes->get('decoded')->roleCode == '1')
    @include('Header-Sidebar.internalSale')
    @include('Header-Sidebar.sidebar')
  @elseif (request()->attributes->get('decoded')->roleCode == '2')
    @include('Header-Sidebar.saleAdmin')
    @include('Header-Sidebar.sidebar')
  @elseif (in_array(request()->attributes->get('decoded')->roleCode, ['3', '4']))
    @include('Header-Sidebar.managerAndCheif')
    @include('Header-Sidebar.sidebar')
  @elseif(request()->attributes->get('decoded')->roleCode == '5')
    @include('Header-Sidebar.itSupport')
  @endif

  {{-- แสดงเนื้อหาในแต่ละหน้า --}}
  <div id="content" class="content">
    @yield('content')
  </div>
</body>
</html>