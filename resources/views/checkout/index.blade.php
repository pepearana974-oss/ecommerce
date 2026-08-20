{{--
    LENGUAJE: Blade de Laravel y Tailwind CSS.

    Esta vista muestra el resumen del pedido y permite enviar
    el carrito al checkout oficial de Stripe.
--}}

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Checkout con Stripe
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Revisa tu pedido antes de realizar el pago.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

            {{-- Muestra los errores enviados por los controladores --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200
                            bg-red-50 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-8 lg:grid-cols-2">

                {{-- Resumen de los productos del carrito --}}
                <section class="rounded-2xl bg-white p-6 shadow">
                    <h3 class="text-xl font-bold text-gray-900">
                        Resumen del pedido
                    </h3>

                    <div class="mt-6 space-y-4">
                        @foreach ($cart as $item)
                            @php
                                /*
                                 * Calcula el subtotal de cada producto.
                                 */
                                $itemSubtotal =
                                    (float) $item['price']
                                    * (int) $item['quantity'];
                            @endphp

                            <div class="flex items-center gap-4
                                        rounded-xl border
                                        border-gray-200 p-4">

                                {{-- Imagen predeterminada --}}
                                <div class="flex h-16 w-16 flex-none
                                            items-center justify-center
                                            rounded-lg bg-indigo-100
                                            text-3xl">
                                    🛍️
                                </div>

                                <div class="min-w-0 flex-1">
                                    <p class="font-semibold text-gray-900">
                                        {{ $item['name'] }}
                                    </p>

                                    <p class="text-sm text-gray-500">
                                        Cantidad:
                                        {{ $item['quantity'] }}
                                        ×
                                        ${{ number_format(
                                            $item['price'],
                                            2
                                        ) }}
                                    </p>
                                </div>

                                <p class="font-bold text-indigo-600">
                                    ${{ number_format(
                                        $itemSubtotal,
                                        2
                                    ) }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Totales generales --}}
                    <div class="mt-6 space-y-3 border-t
                                border-gray-200 pt-5">

                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal</span>

                            <span class="font-semibold text-gray-900">
                                ${{ number_format($subtotal, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between text-gray-600">
                            <span>IVA</span>

                            <span class="font-semibold text-gray-900">
                                ${{ number_format($iva, 2) }}
                            </span>
                        </div>

                        <div class="flex justify-between text-xl
                                    font-bold text-gray-900">
                            <span>Total a pagar</span>

                            <span class="text-indigo-600">
                                ${{ number_format($total, 2) }}
                            </span>
                        </div>
                    </div>

                    {{-- Regresa al carrito sin realizar el pago --}}
                    <a
                        href="{{ route('cart.index') }}"
                        class="mt-6 inline-block font-semibold
                               text-indigo-600 hover:text-indigo-800"
                    >
                        ← Regresar al carrito
                    </a>
                </section>

                {{-- Integración con la pasarela oficial de Stripe --}}
                <section class="h-fit rounded-2xl border-2
                                border-dashed border-indigo-300
                                bg-indigo-50 p-6 shadow-sm">

                    <div class="flex h-14 w-14 items-center
                                justify-center rounded-full
                                bg-indigo-600 text-2xl
                                text-white shadow">
                        💳
                    </div>

                    <h3 class="mt-5 text-2xl font-bold text-gray-900">
                        Pasarela de pagos Stripe
                    </h3>

                    <p class="mt-3 leading-7 text-gray-600">
                        Serás redirigido al checkout oficial de Stripe
                        para realizar el pago en modo de prueba.
                    </p>

                    <div class="mt-6 rounded-xl bg-white p-5 shadow-sm">
                        <p class="text-sm font-semibold uppercase
                                  tracking-wide text-gray-500">
                            Total a pagar
                        </p>

                        <p class="mt-1 text-3xl font-bold text-indigo-600">
                            ${{ number_format($total, 2) }}
                        </p>

                        <div class="mt-5">
                            <p class="text-sm font-semibold text-gray-700">
                                Método de pago seleccionado
                            </p>

                            <div class="mt-2 flex items-center gap-3
                                        rounded-lg border
                                        border-gray-200 p-3">
                                <span class="text-2xl">
                                    💳
                                </span>

                                <span class="font-medium text-gray-700">
                                    Stripe — modo de prueba
                                </span>
                            </div>
                        </div>
                    </div>

                    {{--
                        Este formulario crea una sesión real de Stripe.
                        Ya no utiliza la confirmación simulada anterior.
                    --}}
                    <form
                        action="{{ route('stripe.checkout') }}"
                        method="POST"
                        class="mt-6"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-indigo-600
                                   px-6 py-3.5 text-lg font-semibold
                                   text-white shadow
                                   hover:bg-indigo-700"
                        >
                            Pagar con Stripe
                        </button>
                    </form>

                    <p class="mt-4 text-sm leading-6 text-gray-500">
                        Stripe mostrará los productos y el total real
                        del carrito. El stock se descontará solamente
                        después de confirmar el pago.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
