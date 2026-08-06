{{-- Vista para registrar un producto nuevo --}}
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Registrar producto
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Completa los datos y selecciona una o varias categorías.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow sm:p-8">
                <form
                    action="{{ route('admin.products.store') }}"
                    method="POST"
                >
                    @csrf

                    @include('admin.products._form', [
                        'buttonText' => 'Registrar producto',
                    ])
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
