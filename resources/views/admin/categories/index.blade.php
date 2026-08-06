{{-- LENGUAJE: Blade de Laravel con clases de Tailwind CSS --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Administración de categorías
                </h2>

                <p class="mt-1 text-sm text-gray-500">
                    Registra, edita y elimina las categorías de la tienda.
                </p>
            </div>

            <a
                href="{{ route('admin.categories.create') }}"
                class="rounded-lg bg-indigo-600 px-5 py-2.5 text-center
                       font-semibold text-white shadow hover:bg-indigo-700"
            >
                + Nueva categoría
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Navegación del panel administrativo --}}
            <div class="mb-6 flex flex-wrap gap-3">
                <a
                    href="{{ route('admin.categories.index') }}"
                    class="rounded-lg bg-indigo-600 px-4 py-2
                           font-medium text-white"
                >
                    Categorías
                </a>

                <a
                    href="{{ route('admin.products.index') }}"
                    class="rounded-lg bg-white px-4 py-2 font-medium
                           text-gray-700 shadow hover:bg-gray-100"
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

            {{-- Mensaje mostrado después de registrar, editar o eliminar --}}
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
                                           uppercase tracking-wider text-gray-600">
                                    Nombre
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-bold
                                           uppercase tracking-wider text-gray-600">
                                    Slug
                                </th>

                                <th class="px-6 py-4 text-center text-xs font-bold
                                           uppercase tracking-wider text-gray-600">
                                    Productos
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-bold
                                           uppercase tracking-wider text-gray-600">
                                    Acciones
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($categories as $category)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 font-semibold text-gray-800">
                                        {{ $category->name }}
                                    </td>

                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $category->slug }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="rounded-full bg-indigo-100
                                                     px-3 py-1 text-sm font-semibold
                                                     text-indigo-700">
                                            {{ $category->products_count }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            <a
                                                href="{{ route(
                                                    'admin.categories.edit',
                                                    $category
                                                ) }}"
                                                class="rounded-lg bg-amber-500
                                                       px-3 py-2 text-sm font-semibold
                                                       text-white hover:bg-amber-600"
                                            >
                                                Editar
                                            </a>

                                            <form
                                                action="{{ route(
                                                    'admin.categories.destroy',
                                                    $category
                                                ) }}"
                                                method="POST"
                                                onsubmit="return confirm(
                                                    '¿Deseas eliminar esta categoría?'
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
                                        colspan="4"
                                        class="px-6 py-12 text-center text-gray-500"
                                    >
                                        Todavía no hay categorías registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
