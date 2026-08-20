<?php

/*
|--------------------------------------------------------------------------
| LENGUAJE: PHP con Laravel y Stripe
|--------------------------------------------------------------------------
| Este controlador crea el checkout real de Stripe, verifica el pago
| y descuenta el stock solamente cuando Stripe confirma que fue pagado.
*/

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Stripe\StripeClient;
use Throwable;

class StripeController extends Controller
{
    /**
     * Crea una sesión de pago y redirige al checkout oficial de Stripe.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        $lineItems = [];

        foreach ($cart as $item) {
            $product = Product::find($item['id']);

            if (! $product || ! $product->is_active) {
                return redirect()
                    ->route('cart.index')
                    ->with(
                        'error',
                        "El producto {$item['name']} ya no está disponible."
                    );
            }

            if ($item['quantity'] > $product->stock) {
                return redirect()
                    ->route('cart.index')
                    ->with(
                        'error',
                        "No hay stock suficiente de {$product->name}."
                    );
            }

            /*
             * Stripe trabaja con centavos.
             * Por ejemplo: $399.99 se envía como 39999.
             */
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => (int) round(
                        (float) $product->price * 100
                    ),
                ],
                'quantity' => (int) $item['quantity'],
            ];
        }

        try {
            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $checkoutSession = $stripe->checkout->sessions->create([
                'mode' => 'payment',
                'line_items' => $lineItems,

                /*
                 * Stripe colocará automáticamente el identificador
                 * de la sesión dentro de esta dirección.
                 */
                'success_url' => route('stripe.success')
                    . '?session_id={CHECKOUT_SESSION_ID}',

                'cancel_url' => route('stripe.cancel'),
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('checkout.index')
                ->with(
                    'error',
                    'No fue posible conectarse con Stripe. '
                    . 'Revisa la configuración e intenta nuevamente.'
                );
        }

        return redirect()->away($checkoutSession->url);
    }

    /**
     * Verifica que Stripe realmente haya recibido el pago.
     */
    public function success(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');

        if (! is_string($sessionId) || $sessionId === '') {
            return redirect()
                ->route('cart.index')
                ->with('error', 'No se recibió la confirmación de Stripe.');
        }

        /*
         * Evita descontar el stock dos veces si se recarga
         * accidentalmente la página de confirmación.
         */
        if (
            $request->session()->get('stripe_completed_session')
            === $sessionId
        ) {
            return redirect()
                ->route('home')
                ->with('success', 'Este pago ya fue procesado.');
        }

        try {
            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $checkoutSession = $stripe
                ->checkout
                ->sessions
                ->retrieve($sessionId);
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'No fue posible verificar el pago con Stripe.'
                );
        }

        if ($checkoutSession->payment_status !== 'paid') {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Stripe todavía no confirma el pago.'
                );
        }

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('home')
                ->with('success', 'El pago ya fue confirmado.');
        }

        try {
            DB::transaction(function () use ($cart): void {
                foreach ($cart as $item) {
                    $product = Product::query()
                        ->lockForUpdate()
                        ->find($item['id']);

                    if (! $product) {
                        throw new RuntimeException(
                            "El producto {$item['name']} ya no existe."
                        );
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new RuntimeException(
                            "No hay stock suficiente de {$product->name}."
                        );
                    }

                    $product->decrement(
                        'stock',
                        (int) $item['quantity']
                    );
                }
            });
        } catch (RuntimeException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        }

        /*
         * Marca la sesión como procesada y vacía el carrito.
         */
        $request->session()->put(
            'stripe_completed_session',
            $sessionId
        );

        $request->session()->forget('cart');

        return redirect()
            ->route('home')
            ->with(
                'success',
                'Pago confirmado por Stripe. ¡Compra realizada correctamente!'
            );
    }

    /**
     * Regresa al checkout cuando el usuario cancela el pago.
     */
    public function cancel(): RedirectResponse
    {
        return redirect()
            ->route('checkout.index')
            ->with(
                'error',
                'El pago fue cancelado. No se descontó el stock.'
            );
    }
}
