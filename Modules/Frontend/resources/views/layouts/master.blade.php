<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $description ?? 'LensLegacy Photography - Profesionalna fotografija za venčanja, porodične portrete, pejzaže i događaje | Professzionális esküvői, családi, tájkép és eseményfotózás' }}">
        <meta name="keywords" content="{{ $keywords ?? 'fotografija, fotograf, foto studio, portreti, venčanja, događaji, pejzaži, umetnička fotografija, fényképészet, fényképész, fotó stúdió, portrék, esküvők, események, tájképek, művészi fotózás, Serbia, Srbija, Magyarország' }}">
        <meta name="author" content="{{ $author ?? 'LensLegacy Photography' }}">
        
        <!-- Open Graph / Social Media Meta Tags -->
        <meta property="og:title" content="LensLegacyPhotography">
        <meta property="og:description" content="{{ $description ?? 'Profesionalna fotografija za sve vaše potrebe | Professzionális fotózás minden igényre' }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

        <title>LensLegacyPhotography</title>

        <!-- Favicon -->
        <link rel="icon" href="/favicon.ico">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('build-frontend/css/app.css') }}">

        <!-- JS -->
        @vite(['resources/js/app.js'])
        {{-- {{ module_vite('build-frontend', 'resources/assets/js/app.js') }} --}}
    
    </head>

    <body>
        <div id="app"></div>
    </body>
</html>