@php
    use Filament\Facades\Filament;
@endphp

<nav class="fixed z-10 p-4 w-full shadow-md bg-cream">
    <div class="container flex justify-between items-center mx-auto">
        <!-- Logo on the left -->
        <a href="#" class="text-2xl font-bold text-green">
            {{-- <img src="{{ asset('images/logo-apb.png') }}" alt="Sagansa Logo" class="h-10"> --}}
            SAGANSA</a>

        <!-- Centered navigation links -->
        <div class="hidden flex-grow justify-center space-x-6 lg:flex">
            {{-- <a href="#home" class="font-bold text-green">Home</a> --}}
            <a href="#profil" class="font-bold text-green">Tentang kami</a>
            <a href="#products" class="font-bold text-green">Produk</a>
            <a href="#blog" class="font-bold text-green">Blog</a>
            <a href="#contact" class="font-bold text-green">Hubungi Kami</a>
            <a href="https://ev.sagansa.id" class="font-bold text-green">EV</a>
            {{-- <a href="https://dityoenggar.my.id" class="font-bold text-green">About me</a> --}}
        </div>

        <!-- Login and Register on the right -->
        @guest
            <div class="hidden space-x-4 lg:flex">
                <a href="https://www.sagansa.id/admin/login" class="px-4 py-2 text-white rounded-lg bg-green">Login</a>
                <a href="https://www.sagansa.id/admin/register"
                    class="px-4 py-2 text-white rounded-lg bg-green">Register</a>
            </div>
        @else
            <div class="hidden space-x-4 lg:flex">
                <a href="{{ Filament::getUrl() }}" class="flex items-center px-4 py-2 text-white rounded-lg bg-green">
                    <!-- SVG Cart Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    <div class="mx-2">Shop</div>
                </a>
            </div>
        @endguest


        <!-- Hamburger Menu (visible on small screens) -->
        <div class="lg:hidden">
            <button id="nav-toggle" class="text-green focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation (Hidden by default) -->
    <div id="nav-content" class="hidden lg:hidden bg-cream">

        <a href="#profil" class="block px-4 py-2 text-green">Tentang Kami</a>
        <a href="#products" class="block px-4 py-2 text-green">Produk Kami</a>
        <a href="#blog" class="block px-4 py-2 text-green">Blog</a>
        <a href="#contact" class="block px-4 py-2 text-green">Hubungi Kami</a>
        <a href="https://ev.sagansa.id" class="block px-4 py-2 font-bold text-green">EV</a>
        @guest
            <a href="https://www.sagansa.id/admin/login" class="block px-4 py-2 text-white rounded-t-lg bg-green">Login</a>
            <a href="https://www.sagansa.id/admin/register"
                class="block px-4 py-2 text-white rounded-b-lg bg-green">Register</a>
        @else
            <a href="{{ Filament::getUrl() }}" class="flex items-center px-4 py-2 text-white rounded-lg bg-green">
                <!-- SVG Cart Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <div class="mx-2">Shop</div>
            </a>
        @endguest
    </div>
</nav>
