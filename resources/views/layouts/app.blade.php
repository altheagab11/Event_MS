<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Event Management System' }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/css/app.css')
    @else
        {{-- Hostinger / shared hosting: use committed CSS when Vite build was not uploaded --}}
        <link rel="stylesheet" href="{{ asset('css/admin-app.css') }}">
    @endif
</head>
<body>
    @yield('content')
</body>
</html>
