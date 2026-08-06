{{-- Formulario reutilizable para registrar y editar categorías --}}

{{-- Muestra todos los errores de validación --}}
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

<div class="space-y-6">
    {{-- Nombre de la categoría --}}
    <div>
        <label
            for="name"
            class="mb-2 block text-sm font-semibold text-gray-700"
        >
            Nombre de la categoría
        </label>

        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $category->name ?? '') }}"
            required
            maxlength="255"
            placeholder="Ejemplo: Computadoras"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >

        <p class="mt-2 text-sm text-gray-500">
            Escribe un nombre claro para identificar la categoría.
        </p>
    </div>

    {{-- Slug opcional --}}
    <div>
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
            value="{{ old('slug', $category->slug ?? '') }}"
            maxlength="255"
            placeholder="Se genera automáticamente si lo dejas vacío"
            class="w-full rounded-lg border-gray-300 shadow-sm
                   focus:border-indigo-500 focus:ring-indigo-500"
        >

        <p class="mt-2 text-sm text-gray-500">
            Ejemplo: computadoras-y-accesorios.
        </p>
    </div>
</div>

<div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
    <a
        href="{{ route('admin.categories.index') }}"
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
