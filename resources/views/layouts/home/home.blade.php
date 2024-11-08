<section id="home" class="relative flex items-center justify-center min-h-screen bg-center bg-cover home-background">
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

        <!-- Tombol Create Produk -->
        <div class="mt-4">
            <a href="{{ route('products.create') }}"
                class="inline-block px-6 py-3 text-lg text-white transition duration-300 bg-blue-600 rounded-lg hover:bg-blue-700">
                Buat Produk Baru
            </a>
        </div>
    </div>
</section>

<style>
    .home-background {
        background-image: url('{{ asset('images/home-background.jpg') }}');
    }
</style>
