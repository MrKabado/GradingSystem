<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GradeSync</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen overflow-x-hidden gs-primary-bg">

  <div id="sidebar-overlay"
    class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden hidden"
    aria-hidden="true"></div>

  @include('layouts.sidebar')

  <main class="flex-1 w-full min-w-0 lg:ml-64 pt-16">
    @include('layouts.header')
    @yield('content')
  </main>

  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      const openBtn = document.getElementById('sidebar-open');
      const closeBtn = document.getElementById('sidebar-close');

      function refreshIcons() {
        if (typeof lucide !== 'undefined') {
          lucide.createIcons();
        }
      }

      function openSidebar() {
        sidebar?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden', 'lg:overflow-auto');
        refreshIcons();
      }

      function closeSidebar() {
        sidebar?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden', 'lg:overflow-auto');
      }

      openBtn?.addEventListener('click', openSidebar);
      closeBtn?.addEventListener('click', closeSidebar);
      overlay?.addEventListener('click', closeSidebar);

      sidebar?.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          if (window.innerWidth < 1024) {
            closeSidebar();
          }
        });
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
          closeSidebar();
        }
      });

      window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
          overlay?.classList.add('hidden');
          document.body.classList.remove('overflow-hidden');
          sidebar?.classList.remove('-translate-x-full');
        } else if (overlay?.classList.contains('hidden')) {
          sidebar?.classList.add('-translate-x-full');
        }
      });

      refreshIcons();
    });
  </script>

</body>

</html>
