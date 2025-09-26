<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Sistem Iuran Warga</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">
    <div class="min-h-screen flex flex-col lg:flex-row items-center justify-center px-4 py-8 lg:space-x-8">
        <!-- Kiri: Header -->
        <div class="bg-white p-8 rounded-2xl shadow-lg text-center lg:text-left max-w-md w-full mb-6 lg:mb-0">
            <h1 class="text-3xl font-extrabold text-blue-700">Wellcome to Dashboard</h1>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">Antara Residence</h2>
        </div>

        <!-- Kanan: Slot -->
        <div class="bg-white p-6 rounded-xl shadow-md w-full max-w-md">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
