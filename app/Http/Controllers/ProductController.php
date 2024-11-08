<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Store;
use App\Models\VariantType;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $contact = Product::all();
        // $products = Product::where('online_category_id', '<>', '4')
        //     ->orderBy('name', 'asc')
        //     ->get();
        // return view('landing-page', compact('contact', 'products'));
        return view('layouts/home', compact('products'));
    }

    public function create()
    {
        $categories = ProductCategory::where('status', 1)->get();
        $stores = Store::where('status', 1)->get();
        $variantTypes = VariantType::with('values')
            ->where('status', 1)
            ->get();

        $products = Product::all(); // Ambil produk dari database
        if ($products->isEmpty()) {
            $products = []; // Atau bisa menggunakan collect() untuk memastikan ini adalah koleksi
        }

        return view('products.create', compact(
            'categories',
            'stores',
            'variantTypes',
            'products',
        ));
    }
}
