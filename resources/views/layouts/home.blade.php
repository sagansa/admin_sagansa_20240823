<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    {{-- @if (app()->environment('local')) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- @else
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script src="{{ asset('build/assets/app.js') }}" defer></script>
    @endif --}}

    <style>
        html {
            scroll-behavior: smooth;
        }

        /* Fixed Navbar */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            /* Ensures navbar stays above other content */
            background-color: white;
            /* Adjust the background color of the navbar */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Add some padding-top to the sections so they aren't hidden behind the navbar */
        section {
            padding-top: 5rem;
            /* Adjust based on the height of your navbar */
        }

        /* Home Section Padding for Full-Screen Background Image */
        #home {
            padding-top: 6rem;
            /* Additional top padding to ensure content is visible */
        }

        .content-below-nav {
            padding-top: 64px;
            /* Sesuaikan dengan tinggi navbar Anda */
        }
    </style>
</head>

<body class="text-gray-900 bg-white">
    <!-- Navbar -->
    @include('layouts.home.nav')

    <div class="content-below-nav">
        <!-- Home Section with Background Image -->
        @include('layouts.home.home')

        <!-- Tentang Kami Section -->
        @include('layouts.home.about')

        <!-- Product Section -->
        @include('layouts.home.product')

        <!-- Artikel Blog Section -->
        @include('layouts.home.blog')

        <!-- Kontak Section -->
        @include('layouts.home.contact')
    </div>

    <!-- Footer Section -->
    <footer class="py-4 text-white bg-green">
        <div class="container mx-auto text-center">
            <p>&copy; {{ date('Y') }} PT Asa Pangan Bangsa (Sagansa). All Rights Reserved.</p>
        </div>
    </footer>

    <!-- JS for toggling the mobile menu -->
    <script>
        const navToggle = document.getElementById('nav-toggle');
        const navContent = document.getElementById('nav-content');

        navToggle.addEventListener('click', () => {
            navContent.classList.toggle('hidden');
        });
    </script>

</html>
