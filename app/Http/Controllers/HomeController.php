<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        // $contact = Contact::first();
        $products = Product::where('online_category_id', '<>', '4')->orderBy('name', 'asc')->get();
        // return view('landing-page', compact('contact', 'products'));
        return view('layouts/home', compact('products'));
    }
}
