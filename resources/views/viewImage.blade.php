<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title> รูปภาพ </title>

  @vite('resources/css/app.css')
</head>
<body>
  <button type="button" onclick="window.close()" class="absolute top-5 left-20 px-10 py-1.5 text-sm font-semibold text-white bg-[#FF0000] rounded-full shadow-sm hover:bg-slate-400">
    ย้อนกลับ
  </button>

  <div class="flex flex-col items-center space-y-4 p-4">
    @foreach ($imageUrls as $image)
      <div class="relative p-4 bg-white shadow-lg rounded-lg">
        <img src="{{ $image['url'] }}" alt="Image" class="w-auto h-screen rounded-lg">
        
        @if ($image['downloadable'])
          <a href="{{ $image['url'] }}" download class="absolute top-5 right-20 px-10 py-1.5 text-sm font-semibold text-white bg-blue-500 rounded-full shadow-sm hover:bg-slate-400">
            ดาวน์โหลดรูปภาพ
          </a>
        @endif
      </div>
    @endforeach
  </div>
</body>
</html>
