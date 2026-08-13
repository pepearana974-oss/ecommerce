{{-- LENGUAJE: Blade de Laravel y Tailwind CSS --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row
                    sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Mi carrito de compras
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Revisa los productos antes de continuar al pago.
                </p>
            </div>

            <a
                href="{{ route('home') }}"
                class="rounded-lg bg-white px-5 py-2.5 text-center
                       font-semibold text-gray-700 shadow
                       hover:bg-gray-100"
            >
                ← Seguir comprando
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Mensaje de operación correcta --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200
                            bg-green-50 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Mensaje de error --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200
                            bg-red-50 p-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200
                            bg-red-50 p-4">
                    <p class="font-semibold text-red-700">
                        Revisa los siguientes datos:
                    </p>

                    <ul class="mt-2 list-inside list-disc text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (empty($cart))
                {{-- Carrito vacío --}}
                <div class="rounded-2xl bg-white p-12 text-center shadow">
                    <div class="text-6xl">
                        🛒
                    </div>

                    <h3 class="mt-5 text-2xl font-bold text-gray-800">
                        Tu carrito está vacío
                    </h3>

                    <p class="mt-2 text-gray-500">
                        Agrega productos desde nuestro catálogo.
                    </p>

                    <a
                        href="{{ route('home') }}"
                        class="mt-7 inline-block rounded-lg bg-indigo-600
                               px-6 py-3 font-semibold text-white
                               hover:bg-indigo-700"
                    >
                        Ver productos
                    </a>
                </div>
            @else
                <div class="grid gap-8 lg:grid-cols-3">

                    {{-- Productos guardados en session --}}
                    <div class="space-y-5 lg:col-span-2">
                        @foreach ($cart as $item)
                            @php
                                $itemSubtotal =
                                    (float) $item['price']
                                    * (int) $item['quantity'];
                            @endphp

                            <article class="overflow-hidden rounded-2xl
                                            bg-white shadow">
                                <div class="grid gap-5 p-5 sm:grid-cols-[140px_1fr]">

                                    {{-- Imagen predeterminada --}}
                                    <div class="flex h-36 items-center
                                                justify-center rounded-xl
                                                bg-gradient-to-br
                                                from-indigo-100
                                                to-purple-100">
                                        <div class="flex h-20 w-20
                                                    items-center justify-center
                                                    rounded-full bg-white
                                                    text-4xl shadow">
                                            🛍️
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex flex-col gap-3
                                                    sm:flex-row
                                                    sm:items-start
                                                    sm:justify-between">
                                            <div>
                                                <h3 class="text-xl font-bold
                                                           text-gray-900">
                                                    {{ $item['name'] }}
                                                </h3>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Precio unitario:
                                                    <span class="font-semibold
                                                                 text-gray-700">
                                                        ${{ number_format(
                                                            $item['price'],
                                                            2
                                                        ) }}
                                                    </span>
                                                </p>

                                                <p class="mt-1 text-sm text-gray-500">
                                                    Stock disponible:
                                                    {{ $item['stock'] }}
                                                </p>
                                            </div>

                                            {{-- Eliminar producto completo --}}
                                            <form
                                                action="{{ route(
                                                    'cart.remove',
                                                    $item['id']
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    '¿Deseas eliminar este producto?'
                                                )"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-100
                                                           px-3 py-2 text-sm
                                                           font-semibold
                                                           text-red-700
                                                           hover:bg-red-200"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>

                                        <div class="mt-6 flex flex-col gap-4
                                                    sm:flex-row
                                                    sm:items-end
                                                    sm:justify-between">

                                            {{-- Controles para disminuir y aumentar --}}
                                            <div>
                                                <p class="mb-2 text-sm
                                                          font-semibold
                                                          text-gray-700">
                                                    Cantidad
                                                </p>

                                                <div class="flex items-center gap-2">
                                                    <form
                                                        action="{{ route(
                                                            'cart.decrease',
                                                            $item['id']
                                                        ) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="h-10 w-10
                                                                   rounded-lg
                                                                   border
                                                                   border-gray-300
                                                                   font-bold
                                                                   text-gray-700
                                                                   hover:bg-gray-100"
                                                        >
                                                            −
                                                        </button>
                                                    </form>

                                                    {{-- Actualización manual --}}
                                                    <form
                                                        action="{{ route(
                                                            'cart.update',
                                                            $item['id']
                                                        ) }}"
                                                        method="POST"
                                                        class="flex items-center
                                                               gap-2"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <input
                                                            name="quantity"
                                                            type="number"
                                                            value="{{ $item['quantity'] }}"
                                                            min="1"
                                                            max="{{ $item['stock'] }}"
                                                            class="h-10 w-20
                                                                   rounded-lg
                                                                   border-gray-300
                                                                   text-center
                                                                   shadow-sm
                                                                   focus:border-indigo-500
                                                                   focus:ring-indigo-500"
                                                        >

                                                        <button
                                                            type="submit"
                                                            class="rounded-lg
                                                                   bg-gray-800
                                                                   px-3 py-2
                                                                   text-sm
                                                                   font-semibold
                                                                   text-white
                                                                   hover:bg-gray-900"
                                                        >
                                                            Actualizar
                                                        </button>
                                                    </form>

                                                    <form
                                                        action="{{ route(
                                                            'cart.increase',
                                                            $item['id']
                                                        ) }}"
                                                        method="POST"
                                                    >
                                                        @csrf
                                                        @method('PATCH')

                                                        <button
                                                            type="submit"
                                                            class="h-10 w-10
                                                                   rounded-lg
                                                                   border
                                                                   border-gray-300
                                                                   font-bold
                                                                   text-gray-700
                                                                   hover:bg-gray-100"
                                                        >
                                                            +
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            {{-- Subtotal por producto --}}
                                            <div class="text-left sm:text-right">
                                                <p class="text-xs font-semibold
                                                          uppercase
                                                          tracking-wide
                                                          text-gray-500">
                                                    Subtotal
                                                </p>

                                                <p class="text-2xl font-bold
                                                          text-indigo-600">
                                                    ${{ number_format(
                                                        $itemSubtotal,
                                                        2
                                                    ) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Resumen general del carrito --}}
                    <aside class="h-fit rounded-2xl bg-white p-6 shadow">
                        <h3 class="text-xl font-bold text-gray-900">
                            Resumen del pedido
                        </h3>

                        <div class="mt-6 space-y-4">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal general</span>

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

                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-900">
                                        Total general
                                    </span>

                                    <span class="text-2xl font-bold
                                                 text-indigo-600">
                                        ${{ number_format($total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a
                            href="{{ route('checkout.index') }}"
                            class="mt-7 block rounded-lg bg-indigo-600
                                   px-5 py-3 text-center font-semibold
                                   text-white shadow hover:bg-indigo-700"
                        >
                            Proceder al checkout
                        </a>

                        <a
                            href="{{ route('home') }}"
                            class="mt-3 block rounded-lg border
                                   border-gray-300 px-5 py-3 text-center
                                   font-semibold text-gray-700
                                   hover:bg-gray-100"
                        >
                            Seguir comprando
                        </a>

                        <p class="mt-5 text-center text-xs text-gray-500">
                            El stock se descontará únicamente al confirmar
                            la compra.
                        </p>
                    </aside>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
