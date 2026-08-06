<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Comercio electrónico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800">
    {{-- Encabezado público --}}
    <header class="border-b border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between
                    px-4 py-5 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">
                Mi Ecommerce
            </a>

            <nav class="flex items-center gap-3">
                @auth
                    <a
                        href="{{ route('admin.products.index') }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2
                               font-semibold text-white hover:bg-indigo-700"
                    >
                        Panel administrativo
                    </a>
                @else
                    <a
                        href="{{ route('login') }}"
                        class="rounded-lg px-4 py-2 font-semibold
                               text-gray-700 hover:bg-gray-100"
                    >
                        Iniciar sesión
                    </a>

                    <a
                        href="{{ route('register') }}"
                        class="rounded-lg bg-indigo-600 px-4 py-2
                               font-semibold text-white hover:bg-indigo-700"
                    >
                        Registrarse
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    {{-- Presentación de la tienda --}}
    <section class="bg-gradient-to-r from-indigo-700 to-purple-600 text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <p class="mb-3 text-sm font-bold uppercase tracking-widest
                      text-indigo-200">
                Catálogo en línea
            </p>

            <h1 class="max-w-3xl text-4xl font-bold sm:text-5xl">
                Encuentra nuestros productos disponibles
            </h1>

            <p class="mt-5 max-w-2xl text-lg text-indigo-100">
                Conoce los productos activos, sus categorías,
                precios y existencias.
            </p>
        </div>
    </section>

    {{-- Cards de productos registrados --}}
    <main class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                Productos
            </h2>

            <p class="mt-2 text-gray-600">
                Catálogo de productos actualmente disponibles.
            </p>
        </div>

        <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse ($products as $product)
                <article class="overflow-hidden rounded-2xl bg-white shadow
                                transition hover:-translate-y-1 hover:shadow-xl">
                    {{-- Área visual de la card --}}
                    <div class="flex h-48 items-center justify-center
                                bg-gradient-to-br from-indigo-100 to-purple-100">
                        <div class="flex h-24 w-24 items-center justify-center
                                    rounded-full bg-white text-5xl shadow">
                            🛍️
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- Categorías relacionadas --}}
                        <div class="mb-3 flex flex-wrap gap-2">
                            @foreach ($product->categories as $category)
                                <span class="rounded-full bg-indigo-100
                                             px-2.5 py-1 text-xs font-semibold
                                             text-indigo-700">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>

                        <h3 class="text-xl font-bold text-gray-900">
                            {{ $product->name }}
                        </h3>

                        <p class="mt-3 line-clamp-3 text-sm leading-6 text-gray-600">
                            {{ $product->description ?: 'Producto disponible en nuestra tienda.' }}
                        </p>

                        <div class="mt-6 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-gray-500">
                                    Precio
                                </p>

                                <p class="text-2xl font-bold text-indigo-600">
                                    {{ $product->price_formatted }}
                                </p>
                            </div>

                            <span class="rounded-lg bg-green-100 px-3 py-2
                                         text-sm font-semibold text-green-700">
                                Stock: {{ $product->stock }}
                            </span>
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl bg-white p-12 text-center shadow
                            sm:col-span-2 lg:col-span-3 xl:col-span-4">
                    <div class="text-5xl">🛒</div>

                    <h3 class="mt-4 text-xl font-bold text-gray-800">
                        No hay productos disponibles
                    </h3>

                    <p class="mt-2 text-gray-500">
                        Próximamente agregaremos productos al catálogo.
                    </p>
                </div>
            @endforelse
        </div>

        @if ($products->hasPages())
            <div class="mt-10">
                {{ $products->links() }}
            </div>
        @endif
    </main>

    <footer class="mt-12 bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 py-8 text-center
                    text-sm text-gray-400 sm:px-6 lg:px-8">
            © {{ date('Y') }} Mi Ecommerce. Proyecto académico.
        </div>
    </footer>
</body>
</html>
