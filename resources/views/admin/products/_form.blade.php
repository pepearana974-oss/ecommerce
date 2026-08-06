{{-- Formulario reutilizable para registrar y editar productos --}}

@if ($errors->any())
    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4">
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

@php
    /*
     * Conserva las categorías seleccionadas si existe un error.
     * Durante la edición carga las categorías actuales del producto.
     */
    $selectedCategories = old(
        'categories',
        isset($product)
            ? $product->categories->pluck('id')->all()
            : []
    );
@endphp

<div class="grid gap-6 md:grid-cols-2">
    {{-- Nombre --}}
    <div class="md:col-span-2">
        <label
            for="name"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Nombre del producto
        </label>

        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $product->name ?? '') }}"
            required
            maxlength="255"
            placeholder="Ejemplo: Gorra deportiva negra"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    {{-- Descripción --}}
    <div class="md:col-span-2">
        <label
            for="description"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Descripción
        </label>

        <textarea
            id="description"
            name="description"
            rows="4"
            placeholder="Describe las características del producto"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    {{-- Precio --}}
    <div>
        <label
            for="price"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Precio
        </label>

        <input
            id="price"
            name="price"
            type="number"
            value="{{ old('price', $product->price ?? '') }}"
            required
            min="0"
            step="0.01"
            placeholder="0.00"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    {{-- Existencias --}}
    <div>
        <label
            for="stock"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Existencias
        </label>

        <input
            id="stock"
            name="stock"
            type="number"
            value="{{ old('stock', $product->stock ?? 0) }}"
            required
            min="0"
            step="1"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    {{-- Slug --}}
    <div class="md:col-span-2">
        <label
            for="slug"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Slug
            <span class="font-normal text-gray-500">(opcional)</span>
        </label>

        <input
            id="slug"
            name="slug"
            type="text"
            value="{{ old('slug', $product->slug ?? '') }}"
            maxlength="255"
            placeholder="Se genera automáticamente si lo dejas vacío"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >
    </div>

    {{-- Relación muchos a muchos con categorías --}}
    <div class="md:col-span-2">
        <p class="mb-2 block text-sm font-semibold text-gray-700">
            Categorías
        </p>

        <p class="mb-3 text-sm text-gray-500">
            Selecciona una o varias categorías para el producto.
        </p>

        <div class="grid gap-3 rounded-lg border border-gray-200
                    bg-gray-50 p-4 sm:grid-cols-2">
            @forelse ($categories as $category)
                <label class="flex cursor-pointer items-center gap-3
                              rounded-lg bg-white p-3 shadow-sm
                              hover:bg-indigo-50">
                    <input
                        name="categories[]"
                        type="checkbox"
                        value="{{ $category->id }}"
                        @checked(
                            in_array(
                                $category->id,
                                $selectedCategories
                            )
                        )
                        class="rounded border-gray-300 text-indigo-600
                               focus:ring-indigo-500"
                    >

                    <span class="font-medium text-gray-700">
                        {{ $category->name }}
                    </span>
                </label>
            @empty
                <p class="text-sm text-red-600 sm:col-span-2">
                    No existen categorías. Primero registra una categoría.
                </p>
            @endforelse
        </div>
    </div>

    {{-- Estado del producto --}}
    <div class="md:col-span-2">
        <label class="flex items-center gap-3 rounded-lg border
                      border-gray-200 p-4">
            <input
                name="is_active"
                type="checkbox"
                value="1"
                @checked(old(
                    'is_active',
                    $product->is_active ?? true
                ))
                class="rounded border-gray-300 text-indigo-600
                       focus:ring-indigo-500"
            >

            <span>
                <span class="block font-semibold text-gray-700">
                    Producto activo
                </span>

                <span class="text-sm text-gray-500">
                    Los productos activos aparecen en la tienda.
                </span>
            </span>
        </label>
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
    <a
        href="{{ route('admin.products.index') }}"
        class="rounded-lg border border-gray-300 px-5 py-2.5
               text-center font-semibold text-gray-700 hover:bg-gray-100"
    >
        Cancelar
    </a>

    <button
        type="submit"
        class="rounded-lg bg-indigo-600 px-5 py-2.5 font-semibold
               text-white shadow hover:bg-indigo-700"
    >
        {{ $buttonText }}
    </button>
</div>
