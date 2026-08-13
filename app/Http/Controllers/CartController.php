<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use RuntimeException;

class CartController extends Controller
{
    /**
     * Muestra los productos guardados en el carrito de sesión.
     */
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        $subtotal = $this->calculateSubtotal($cart);
        $iva = 0;
        $total = $subtotal + $iva;

        return view(
            'cart.index',
            compact('cart', 'subtotal', 'iva', 'total')
        );
    }

    /**
     * Agrega un producto al carrito.
     *
     * Si ya existe, aumenta su cantidad sin superar el stock.
     */
    public function add(
        Request $request,
        Product $product
    ): RedirectResponse {
        if (! $product->is_active) {
            return back()->with(
                'error',
                'Este producto no está disponible.'
            );
        }

        if ($product->stock < 1) {
            return back()->with(
                'error',
                'El producto está agotado.'
            );
        }

        $cart = $request->session()->get('cart', []);

        $productId = (string) $product->id;
        $currentQuantity = $cart[$productId]['quantity'] ?? 0;

        if ($currentQuantity >= $product->stock) {
            return back()->with(
                'error',
                'No puedes agregar más unidades que el stock disponible.'
            );
        }

        $newQuantity = $currentQuantity + 1;

        $cart[$productId] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'quantity' => $newQuantity,
            'stock' => $product->stock,

            /*
             * Por ahora se utilizará una imagen predeterminada.
             */
            'image' => null,
        ];

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Producto agregado al carrito correctamente.'
        );
    }

    /**
     * Actualiza manualmente la cantidad de un producto.
     */
    public function update(
        Request $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        if (! isset($cart[$productId])) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El producto no está en el carrito.');
        }

        if ($validated['quantity'] > $product->stock) {
            return back()->with(
                'error',
                'La cantidad solicitada supera el stock disponible.'
            );
        }

        $cart[$productId]['quantity'] = $validated['quantity'];
        $cart[$productId]['stock'] = $product->stock;
        $cart[$productId]['price'] = (float) $product->price;

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Cantidad actualizada correctamente.'
        );
    }

    /**
     * Aumenta una unidad sin superar el stock disponible.
     */
    public function increase(
        Request $request,
        Product $product
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        if (! isset($cart[$productId])) {
            return back()->with(
                'error',
                'El producto no está en el carrito.'
            );
        }

        if ($cart[$productId]['quantity'] >= $product->stock) {
            return back()->with(
                'error',
                'No hay más existencias disponibles.'
            );
        }

        $cart[$productId]['quantity']++;
        $cart[$productId]['stock'] = $product->stock;

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Cantidad aumentada correctamente.'
        );
    }

    /**
     * Disminuye una unidad sin permitir cantidades menores a uno.
     */
    public function decrease(
        Request $request,
        Product $product
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        if (! isset($cart[$productId])) {
            return back()->with(
                'error',
                'El producto no está en el carrito.'
            );
        }

        if ($cart[$productId]['quantity'] <= 1) {
            return back()->with(
                'error',
                'La cantidad mínima permitida es 1.'
            );
        }

        $cart[$productId]['quantity']--;

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Cantidad disminuida correctamente.'
        );
    }

    /**
     * Elimina completamente un producto del carrito.
     */
    public function remove(
        Request $request,
        Product $product
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);
        $productId = (string) $product->id;

        unset($cart[$productId]);

        $request->session()->put('cart', $cart);

        return back()->with(
            'success',
            'Producto eliminado del carrito.'
        );
    }

    /**
     * Muestra el checkout y la pasarela simulada.
     */
    public function checkout(Request $request): View|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        $subtotal = $this->calculateSubtotal($cart);
        $iva = 0;
        $total = $subtotal + $iva;

        return view(
            'checkout.index',
            compact('cart', 'subtotal', 'iva', 'total')
        );
    }

    /**
     * Confirma la compra y descuenta el stock.
     *
     * El stock no se descuenta al agregar productos al carrito.
     */
    public function confirm(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        try {
            DB::transaction(function () use ($cart): void {
                foreach ($cart as $item) {
                    /*
                     * Bloquea temporalmente la fila mientras se procesa
                     * la compra para evitar inconsistencias de stock.
                     */
                    $product = Product::query()
                        ->lockForUpdate()
                        ->find($item['id']);

                    if (! $product) {
                        throw new RuntimeException(
                            "El producto {$item['name']} ya no está disponible."
                        );
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new RuntimeException(
                            "No hay stock suficiente de {$product->name}."
                        );
                    }

                    $product->decrement(
                        'stock',
                        $item['quantity']
                    );
                }
            });
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        }

        /*
         * El carrito se vacía solamente después de completar
         * correctamente toda la compra.
         */
        $request->session()->forget('cart');

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Compra procesada correctamente. ¡Gracias por tu compra!'
            );
    }

    /**
     * Calcula el subtotal general del carrito.
     */
    private function calculateSubtotal(array $cart): float
    {
        return collect($cart)->sum(
            fn (array $item): float =>
                (float) $item['price'] * (int) $item['quantity']
        );
    }
}
