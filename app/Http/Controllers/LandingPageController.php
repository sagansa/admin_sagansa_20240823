<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        // $contact = Contact::first();
        $products = Product::where('online_category_id', '<>', '4')->get();
        // return view('landing-page', compact('contact', 'products'));
        return view('landing-page', compact('products'));
    }
}
