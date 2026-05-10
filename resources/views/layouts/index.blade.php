<html>

<head>
  <title>GradeSync</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen bg-gray-400">

  {{-- Sidebar --}}
  @include('layouts.sidebar')

  {{-- Main Content --}}
  <main class="flex-1 ml-64 pt-15">
    @include('layouts.header')
    @yield('content')
  </main>

  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    lucide.createIcons();
  </script>

</body>

</html>
