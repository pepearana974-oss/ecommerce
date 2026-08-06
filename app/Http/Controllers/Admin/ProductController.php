<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Muestra el listado de productos del panel administrativo.
     */
    public function index(): View
    {
        $products = Product::with('categories')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Muestra el formulario para registrar un producto.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    /**
     * Guarda el producto y sus categorías.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('table_products', 'slug'),
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'categories' => [
                'required',
                'array',
                'min:1',
            ],
            'categories.*' => [
                'integer',
                Rule::exists('table_categories', 'id'),
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated): void {
            $productData = collect($validated)
                ->except('categories')
                ->all();

            $product = Product::create($productData);

            /*
             * Guarda las relaciones en table_category_product.
             */
            $product->categories()->sync($validated['categories']);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    /**
     * Redirige a la edición del producto.
     */
    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    /**
     * Muestra el formulario para editar un producto.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        $product->load('categories');

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    /**
     * Actualiza el producto y sus categorías.
     */
    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('table_products', 'slug')
                    ->ignore($product->id),
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
            'categories' => [
                'required',
                'array',
                'min:1',
            ],
            'categories.*' => [
                'integer',
                Rule::exists('table_categories', 'id'),
            ],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use (
            $validated,
            $product
        ): void {
            $productData = collect($validated)
                ->except('categories')
                ->all();

            $product->update($productData);

            /*
             * Actualiza los registros de table_category_product.
             */
            $product->categories()->sync($validated['categories']);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Elimina lógicamente un producto.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }
}
