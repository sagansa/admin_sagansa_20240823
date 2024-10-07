<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sagansa - Supplier Makanan</title>
    @vite('resources/css/landing-page.css')
</head>

<body class="text-gray-900 bg-gray-100">

    <!-- Navbar -->
    <nav class="fixed top-0 z-50 w-full p-4 bg-green-800 shadow-lg">
        <div class="container flex items-center justify-between mx-auto">
            <div class="text-2xl font-bold text-white">
                <a href="#">Sagansa</a>
            </div>

            <!-- Menu Links -->
            <div class="hidden md:flex">
                <a href="#profile" class="mx-2 text-white hover:text-gray-300">Profil</a>
                <a href="#vision-mission" class="mx-2 text-white hover:text-gray-300">Visi & Misi</a>
                <a href="#products" class="mx-2 text-white hover:text-gray-300">Produk</a>
                <a href="#contact" class="mx-2 text-white hover:text-gray-300">Kontak</a>
            </div>

            <!-- Authentication Links & Cart -->
            <div class="items-center hidden md:flex">
                @auth
                    <!-- Admin Link with Cart Icon -->
                    <a href="https://www.sagansa.id/admin" class="flex items-center mx-2 text-white hover:text-gray-300">
                        <span>Order</span>
                        {{-- <img src="svg/cart.svg" alt="Cart" class="h-6 ml-1"> --}}
                    </a>
                @else
                    <a href="https://www.sagansa.id/admin/login" class="mx-2 text-white hover:text-gray-300">Login</a>
                    <a href="https://www.sagansa.id/admin/register" class="mx-2 text-white hover:text-gray-300">Register</a>
                @endauth
            </div>

            <!-- Dark Mode Toggle -->
            <button id="dark-mode-toggle" class="ml-4 text-white md:ml-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m8.49-8.49l-.7-.7m-13.56 0l-.7.7m11.49 5.65l-.7-.7m-9.19 0l-.7.7M16 12h1m-13 0H3m16.95 4.49l-.7-.7m-13.56 0l-.7.7m11.49-5.65l-.7-.7m-9.19 0l-.7.7M12 3v1m0 16v1">
                    </path>
                </svg>
            </button>

            <!-- Mobile Menu Toggle -->
            <button id="mobile-menu-toggle" class="text-white md:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu (Hidden by Default) -->
        <div id="mobile-menu" class="hidden">
            <a href="#profile" class="flex items-center mx-2 my-2 text-white hover:text-gray-300">Profil</a>
            <a href="#vision-mission" class="flex items-center mx-2 my-2 text-white hover:text-gray-300">Visi & Misi</a>
            <a href="#products" class="flex items-center mx-2 my-2 text-white hover:text-gray-300">Produk</a>
            <a href="#contact" class="flex items-center mx-2 my-2 text-white hover:text-gray-300">Kontak</a>
            @auth
                <!-- Admin Link with Cart Icon -->
                <a href="https://www.sagansa.id/admin" class="flex items-center mx-2 my-2 text-white hover:text-gray-300">
                    <span>Order</span>
                    {{-- <img src="svg/cart.svg" alt="Cart" class="h-6 ml-1"> --}}
                </a>
            @else
                <a href="https://www.sagansa.id/admin/login" class="mx-2 my-2 text-white hover:text-gray-300">Login</a>
                <a href="https://www.sagansa.id/admin/register" class="mx-2 text-white hover:text-gray-300">Register</a>
            @endauth
        </div>
    </nav>
    </nav>

    <!-- Add padding top so content doesn't overlap with sticky navbar -->
    < class="pt-20">
        <!-- Hero Section -->
        <section class="flex items-center justify-center h-screen bg-center bg-cover"
            style="background-image: url('/images/hero-image.jpg');">
            <div class="text-center">
                <h1 class="text-5xl font-bold text-white">Sagansa
                </h1>
                <h2 class="mt-4 text-3xl font-bold text-white">Supplier Makanan Untuk Restaurant & Warung Makan</h2>
                <p class="mt-4 text-xl text-white">Kualitas Terbaik dari Alam Indonesia</p>
            </div>
        </section>

        <!-- Profil Section -->
        <section id="profile" class="flex items-center justify-center min-h-screen bg-cream text-green-dark">
            <div class="max-w-4xl text-center">
                <h2 class="mb-8 text-4xl font-bold">Profil Kami</h2>
                <p class="text-lg leading-relaxed">PT Asa Pangan Bangsa (Sagansa) adalah perusahaan yang bergerak dalam
                    industri
                    makanan,
                    khususnya sebagai supplier kacang mete dan minyak wijen berkualitas tinggi dari Indonesia.</p>
            </div>
        </section>

        <!-- Visi dan Misi Section -->
        <section id="vision-mission" class="flex items-center justify-center min-h-screen bg-green-soft text-cream">
            <div class="max-w-4xl text-center">
                <h2 class="mb-8 text-4xl font-bold">Visi & Misi</h2>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                    <div>
                        <h3 class="mb-2 text-2xl font-semibold">Visi</h3>
                        <p class="text-lg leading-relaxed">Menjadi supplier makanan terpercaya di seluruh dunia dengan
                            produk-produk berkualitas tinggi
                            dari
                            Indonesia.</p>
                    </div>
                    <div>
                        <h3 class="mb-2 text-2xl font-semibold">Misi</h3>
                        <ul class="list-disc list-inside">
                            <li>Menyediakan produk makanan berkualitas tinggi dan sehat</li>
                            <li>Memberdayakan petani lokal untuk meningkatkan kesejahteraan mereka</li>
                            <li>Mengutamakan keberlanjutan dan lingkungan dalam setiap aspek bisnis</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="py-24 bg-white dark:bg-gray-900">
            <div class="container px-4 mx-auto">
                <h2 class="mb-8 text-3xl font-bold text-center text-gray-800 dark:text-white">Produk Kami</h2>
                <div class="grid grid-cols-2 gap-6 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach ($products as $product)
                        <div class="p-4 bg-white rounded-lg shadow-md dark:bg-gray-800">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/image_not_available.png') }}"
                                alt="{{ $product->name }}" class="object-cover w-full h-40 mb-4 rounded-lg">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white">{{ $product->name }}</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ $product->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="p-8 bg-green-100">
            <div class="container mx-auto">
                <h2 class="mb-6 text-3xl font-bold text-center">Kontak Kami</h2>
                <p class="mb-4 text-lg text-center">Hubungi kami melalui email dan WA atau ikuti media sosial kami.</p>

                <!-- Social Media Links -->
                <div class="flex justify-center mb-4 space-x-4">
                    <a href="https://www.instagram.com/sabiowear" title="Instagram">
                        <img src="svg/instagram.svg" alt="Instagram" class="h-8">
                    </a>
                    <a href="https://www.youtube.com/dityoenggar" title="YouTube">
                        <img src="svg/youtube.svg" alt="YouTube" class="h-8">
                    </a>
                    <a href="https://x.com/8632dit" title="Twitter">
                        <img src="svg/twitter.svg" alt="Twitter" class="h-8">
                    </a>
                </div>

                <p class="text-center">
                    Email: <a href="mailto:admin@sagansa.id"
                        class="text-green-700 hover:text-green-500">admin@sagansa.id</a>
                </p>
                <p class="text-center">
                    WA: <a href="https://wa.me/6285782004645" class="text-green-700 hover:text-green-500">+62 8578 200
                        4645</a>
                </p>
            </div>
            <div class="mt-6">
                <h3 class="mb-4 text-2xl font-bold text-center">Online Shop Kami</h3>
                <div class="flex justify-center space-x-4">
                    <a href="https://www.tokopedia.com/daehwa" target="_blank"
                        class="text-green-dark hover:text-green-light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4.5A1.5 1.5 0 014.5 3h15a1.5 1.5 0 011.5 1.5V21a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 21V4.5z" />
                        </svg>
                    </a>
                    <a href="https://shopee.co.id/yourshop" target="_blank"
                        class="text-green-dark hover:text-green-light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7a4 4 0 004-4h10a4 4 0 004 4M3 11v10a1 1 0 001 1h16a1 1 0 001-1V11M3 8h18" />
                        </svg>
                    </a>
                    <a href="https://www.tiktok.com/@yourprofile" target="_blank"
                        class="text-green-dark hover:text-green-light">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5a3 3 0 01-3-3 3 3 0 013 3v6a3 3 0 01-3 3 3 3 0 01-3-3V7a3 3 0 013-3 3 3 0 01-3 3v3a6 6 0 0012 0V5h-1z" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="p-4 text-white bg-green-100">
            <p class="text-center">© 2024 PT Asa Pangan Bangsa (Sagansa). All Rights Reserved.</p>
        </footer>

        <script>
            // Dark Mode Toggle Script
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            darkModeToggle.addEventListener('click', () => {
                document.body.classList.toggle('dark-mode');
            });

            // Mobile Menu Toggle Script
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenuToggle.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        </script>
        </div>

        @vite('resources/js/landing-page.js')
</body>

</html>
