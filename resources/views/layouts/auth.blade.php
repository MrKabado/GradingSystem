<html>

<head>
    <title>GradeSync</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script src="https://unpkg.com/lucide@latest"></script>

<script>
    lucide.createIcons();
</script>

<body>
    <main>
        @yield('content')
    </main>
</body>

</html>
