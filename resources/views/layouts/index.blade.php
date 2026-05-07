<html>
  <head>
    <title>GradeSync</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
      @include('layouts.navbar')

      <main>
        @yield('content')
      </main>
  </body>
</html>