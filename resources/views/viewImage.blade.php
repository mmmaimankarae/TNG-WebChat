<!-- resources/views/viewImage.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite('resources/css/app.css')
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  
  <title>รูปภาพ</title>
</head>
<body>
  <div class="flex justify-center items-center h-screen">
    <button type="button" onclick="window.close()" class="absolute top-5 left-20 px-10 py-1.5 text-sm font-semibold text-white bg-[#FF0000] rounded-full shadow-sm hover:bg-slate-400"> ย้อนกลับ </button>
    <a href="{{ route('download.image', ['messageId' => request()->input('messageId')]) }}" 
      class="absolute top-5 right-20 px-10 py-1.5 text-sm font-semibold text-white bg-blue-500 rounded-full shadow-sm hover:bg-slate-400">
      ดาวน์โหลดรูปภาพ
    </a>
    <div class="relative top-5  p-4 bg-white shadow-lg rounded-lg">
      <img src="{{ $imageUrl }}" alt="Image" class="w-auto h-screen rounded-lg">
    </div>
  </div>
</body>
</html>