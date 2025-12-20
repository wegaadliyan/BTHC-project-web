<?php

namespace App\Http\Controllers;

use App\Models\CustomProduct;
use Illuminate\Http\Request;

class CustomController extends Controller
{
    public function index()
    {
        $customProducts = CustomProduct::all();
        return view('custom', compact('customProducts'));
    }
}
