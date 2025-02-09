<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  
  <title>@yield('title')</title>
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
  @endif

  {{-- แสดงเนื้อหาในแต่ละหน้า --}}
  <div id="content" class="content">
    @yield('content')
  </div>
  @if ($select)
    @include('Popup.quotation')
    @include('Popup.invoice')
  @endif
</body>
</html>