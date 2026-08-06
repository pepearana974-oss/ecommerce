{{-- Vista para editar un producto existente --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Editar producto
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Modifica los datos y las categorías de
                {{ $product->name }}.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow sm:p-8">
                <form
                    action="{{ route(
                        'admin.products.update',
                        $product
                    ) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    @include('admin.products._form', [
                        'buttonText' => 'Guardar cambios',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
