<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Muestra los productos activos en la página principal.
     */
    public function index(): View
    {
        /*
         * active() utiliza el scope definido en Product.
         * with('categories') carga las categorías del producto.
         */
        $products = Product::active()
            ->with('categories')
            ->latest()
            ->paginate(8);

        return view('welcome', compact('products'));
    }
}
