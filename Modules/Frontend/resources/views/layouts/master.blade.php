<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>LensLegacy Photography</title>

    <meta name="description" content="{{ $description ?? '' }}">
    <meta name="keywords" content="{{ $keywords ?? '' }}">
    <meta name="author" content="{{ $author ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('build-frontend/css/app.css') }}">
    {{-- Vite CSS --}}
    @vite('public/css/tailwind.css')
    
</head>

<body>
    <div id="app"></div>

    {{-- Vite JS --}}
    @vite(['resources/js/app.js'])
    {{-- {{ module_vite('build-frontend', 'resources/assets/js/app.js') }} --}}
</body>


