<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PT Asa Pangan Bangsa</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-900 bg-white">
    <!-- Navbar -->
    <nav class="fixed z-10 w-full p-4 shadow-md bg-cream">
        <div class="container flex items-center justify-between mx-auto">
            <!-- Logo on the left -->
            <a href="#" class="text-lg font-bold text-green">Sagansa</a>

            <!-- Centered navigation links -->
            <div class="justify-center flex-grow hidden space-x-6 lg:flex">
                <a href="#home" class="text-green">Home</a>
                <a href="#profil" class="text-green">Tentang Kami</a>
                <a href="#products" class="text-green">Produk Kami</a>
                <a href="#contact" class="text-green">Hubungi Kami</a>
            </div>

            <!-- Login and Register on the right -->
            @auth
                <div class="hidden space-x-4 lg:flex">
                    <a href="https://www.sagansa.id/admin" class="px-4 py-2 text-white rounded bg-green">Shop</a>
                </div>
            @else
                <div class="hidden space-x-4 lg:flex">
                    <a href="https://www.sagansa.id/admin/login" class="px-4 py-2 text-white rounded bg-green">Login</a>
                    <a href="https://www.sagansa.id/admin/register"
                        class="px-4 py-2 bg-white border-2 rounded text-green border-green">Register</a>
                </div>
            @endauth


            <!-- Hamburger Menu (visible on small screens) -->
            <div class="lg:hidden">
                <button id="nav-toggle" class="text-green focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation (Hidden by default) -->
        <div id="nav-content" class="hidden lg:hidden bg-cream">
            <a href="#home" class="block px-4 py-2 text-green">Home</a>
            <a href="#profil" class="block px-4 py-2 text-green">Tentang Kami</a>
            <a href="#products" class="block px-4 py-2 text-green">Produk Kami</a>
            <a href="#contact" class="block px-4 py-2 text-green">Hubungi Kami</a>
            @auth
                <a href="https://www.sagansa.id/admin" class="block px-4 py-2 text-white bg-green">Login</a>
            @else
                <a href="https://www.sagansa.id/admin/login" class="block px-4 py-2 text-white bg-green">Login</a>
                <a href="https://www.sagansa.id/admin/register"
                    class="block px-4 py-2 bg-white border-2 text-green border-green">Register</a>
            @endauth

        </div>
    </nav>

    <!-- Home Section with Background Image -->
    <section id="home" class="relative flex items-center justify-center min-h-screen bg-center bg-cover"
        style="background-image: url('{{ asset('images/home-background.jpg') }}');">
        <!-- Optional overlay for darkening the background -->
        <div class="absolute inset-0 bg-black opacity-40"></div>

        <!-- Centered content inside the home section -->
        <div class="container relative z-10 text-center text-white">
            <h1 class="mb-6 text-5xl font-bold">Welcome to Sagansa</h1>
            <p class="mb-6 text-xl">Supplier of Cashew Nuts and Sesame Oil for Restaurants, Hotels, and Food Stalls</p>
            <a href="#products"
                class="inline-block px-6 py-3 mt-8 text-lg text-white rounded-lg bg-green hover:bg-dark-green">
                Explore Our Products
            </a>
        </div>
    </section>

    <!-- Tentang Kami Section -->
    <section id="profil" class="min-h-screen py-20 bg-white">
        <div class="container mx-auto">
            <h2 class="mb-6 text-3xl font-bold text-green">Profil Perusahaan</h2>
            <p>
                PT Asa Pangan Bangsa (Sagansa) adalah perusahaan yang bergerak di bidang makanan, terutama sebagai
                pemasok kacang mete dan minyak wijen. Kami memasok restoran, hotel, dan gerai makanan dengan bahan
                berkualitas tinggi.
            </p>
        </div>
        <div class="container mx-auto">
            <h2 class="mb-6 text-3xl font-bold text-green">Visi dan Misi</h2>
            <p><strong>Visi:</strong> Menjadi pemasok makanan premium yang terpercaya di Indonesia.</p>
            <p><strong>Misi:</strong> Menyediakan bahan makanan berkualitas tinggi kepada pelanggan kami dengan layanan
                yang unggul.</p>
        </div>
    </section>

    <!-- Product Section -->
    <section id="products" class="min-h-screen py-24 bg-white">
        <div class="container mx-auto">
            <h2 class="mb-12 text-4xl font-bold text-center text-green">Our Products</h2>

            <div class="grid grid-cols-2 gap-6 sm:grid-cols-4 lg:grid-cols-5">
                @foreach ($products as $product)
                    <div
                        class="p-4 overflow-hidden transition-transform transform bg-white rounded-lg shadow-lg hover:scale-105">
                        {{-- <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="object-cover w-full h-40 mb-4 rounded-t-lg"> --}}
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/default-image.jpg') }}"
                            alt="{{ $product->name }}" class="object-cover w-full h-32">
                        <h3 class="text-lg font-bold text-green">{{ $product->name }}</h3>
                        <p class="text-gray-600">{{ $product->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    <!-- Kontak Section -->
    <section id="contact" class="py-16 bg-cream">
        <div class="container grid grid-cols-1 gap-8 mx-auto sm:grid-cols-2 lg:grid-cols-4">
            <!-- Sagansa and Home link -->
            <div>
                <a href="#home" class="text-green hover:underline">
                    <h2 class="mb-4 text-2xl font-bold text-green">Sagansa</h2>
                </a>
            </div>

            <!-- Media Sosial -->
            <div>
                <h3 class="mb-4 text-xl font-semibold text-green">Media Sosial</h3>

                <div class="flex space-x-4">
                    <a href="https://www.youtube.com/dityoenggar" target="_blank"
                        class="text-gray-700 hover:text-green">
                        <img src="svg/youtube.svg" alt="Youtube" class="h-8">
                    </a>
                    <a href="https://www.instagram.com/sabiowear" target="_blank"
                        class="text-gray-700 hover:text-green">
                        <img src="svg/instagram.svg" alt="Instagram" class="h-8">
                    </a>
                    <a href="https://www.facebook.com" target="_blank" class="text-gray-700 hover:text-green">
                        <img src="svg/facebook.svg" alt="Facebook" class="h-8">
                    </a>
                    <a href="https://www.x.com" target="_blank" class="text-gray-700 hover:text-green">
                        <img src="svg/twitter.svg" alt="Twitter" class="h-8">
                    </a>
                </div>

            </div>

            <!-- Kontak -->
            <div>
                <h3 class="mb-4 text-xl font-semibold text-green">Kontak</h3>
                <ul class="space-y-2">
                    <li class="flex items-center">
                        <a href="https://wa.me/6285782004645" target="_blank"
                            class="flex items-center text-gray-700 hover:text-green">
                            <img src="svg/whatsapp.svg" alt="YouTube" class="h-8">
                            <div class="mx-2">+62 8578 200 4645</div>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <a href="mailto:info@sagansa.com" class="flex items-center text-gray-700 hover:text-green">
                            <img src="svg/email.svg" alt="YouTube" class="h-8">
                            <div class="mx-2">admin@sagansa.id</div>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <a href="#" class="flex items-center text-gray-700 hover:text-green">
                            <img src="svg/address.svg" alt="YouTube" class="h-8">
                            <div class="mx-2">Apartement Mediterania Garden Residence I tower B, Jakarta Barat</div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Online Shop -->
            <div>
                <h3 class="mb-4 text-xl font-semibold text-green">Online Shop</h3>
                <div class="flex space-x-4">
                    <a href="https://www.tokopedia.com/daehwa-1" target="_blank"
                        class="text-gray-700 hover:text-green">
                        <img src="svg/tokopedia.svg" alt="Tokopedia" class="h-8">
                    </a>
                    <a href="https://shopee.co.id/sabiowear10" target="_blank"
                        class="text-gray-700 hover:text-green">
                        <img src="svg/shopee.svg" alt="Shopee" class="h-8">
                    </a>
                    <a href="https://www.bukalapak.com" target="_blank" class="text-gray-700 hover:text-green">
                        <img src="svg/bukalapak.svg" alt="Bukalapak" class="h-8">
                    </a>
                    <a href="https://www.lazada.co.id" target="_blank" class="text-gray-700 hover:text-green">
                        <img src="svg/lazada.svg" alt="Lazada" class="h-8">
                    </a>
                </div>
            </div>
        </div>
    </section>
</body>

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
