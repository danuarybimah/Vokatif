<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} - Vokatif</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: radial-gradient(circle at 10% 20%, rgba(220, 38, 38, 0.07), transparent 35%),
                        radial-gradient(circle at 90% 80%, rgba(244, 63, 94, 0.05), transparent 35%),
                        #09090b;
            color: #f4f4f5;
            min-height: 100vh;
            background-attachment: fixed;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col justify-between">

    <div>
        <!-- HEADER -->
        @include('partials.header')

        <!-- CONTENT -->
        <main class="max-w-7xl mx-auto px-6 py-12 w-full">
            @yield('content')
        </main>
    </div>

    <!-- FOOTER -->
    @php
        $role = auth()->user()?->role?->slug;
    @endphp
    @if (!$role || $role === 'user')
        @include('partials.footer')
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
</body>

</html>
