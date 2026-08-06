{{-- LENGUAJE: Blade de Laravel con Tailwind CSS --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center
                    sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Administración de productos
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Registra, edita y elimina los productos de la tienda.
                </p>
            </div>

            <a
                href="{{ route('admin.products.create') }}"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-center
                       font-semibold text-white shadow hover:bg-indigo-700"
            >
                + Nuevo producto
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Navegación del panel --}}
            <div class="mb-6 flex flex-wrap gap-3">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-lg bg-white px-4 py-2 font-medium
                           text-gray-700 shadow hover:bg-gray-100"
                >
                    Categorías
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2
                           font-medium text-white"
                >
                    Productos
                </a>

                <a
                    href="{{ route('home') }}"
                    class="rounded-lg bg-white px-4 py-2 font-medium
                           text-gray-700 shadow hover:bg-gray-100"
                >
                    Ver tienda
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200
                            bg-green-50 p-4 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden rounded-xl bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold
                                           uppercase text-gray-600">
                                    Producto
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold
                                           uppercase text-gray-600">
                                    Categorías
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold
                                           uppercase text-gray-600">
                                    Precio
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-bold
                                           uppercase text-gray-600">
                                    Stock
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-bold
                                           uppercase text-gray-600">
                                    Estado
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold
                                           uppercase text-gray-600">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-gray-800">
                                            {{ $product->name }}
                                        </p>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{ $product->slug }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex max-w-xs flex-wrap gap-1">
                                            @foreach ($product->categories as $category)
                                                <span class="rounded-full bg-indigo-100
                                                             px-2.5 py-1 text-xs
                                                             font-semibold text-indigo-700">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $product->price_formatted }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-gray-700">
                                        {{ $product->stock }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if ($product->is_active)
                                            <span class="rounded-full bg-green-100
                                                         px-3 py-1 text-sm
                                                         font-semibold text-green-700">
                                                Activo
                                            </span>
                                        @else
                                            <span class="rounded-full bg-gray-200
                                                         px-3 py-1 text-sm
                                                         font-semibold text-gray-600">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route(
                                                    'admin.products.edit',
                                                    $product
                                                ) }}"
                                                class="rounded-lg bg-amber-500
                                                       px-3 py-2 text-sm font-semibold
                                                       text-white hover:bg-amber-600"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route(
                                                    'admin.products.destroy',
                                                    $product
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
                                                    class="rounded-lg bg-red-600
                                                           px-3 py-2 text-sm
                                                           font-semibold text-white
                                                           hover:bg-red-700"
                                                >
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td
                                        colspan="6"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        Todavía no hay productos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
