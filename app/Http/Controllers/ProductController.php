<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
}
