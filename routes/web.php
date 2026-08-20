<?php

/*
|--------------------------------------------------------------------------
| LENGUAJE: PHP con Laravel
|--------------------------------------------------------------------------
| Este archivo registra las direcciones disponibles en el sistema:
| catálogo, administración, carrito, checkout y Stripe.
*/

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Página pública de productos
|--------------------------------------------------------------------------
| HomeController obtiene los productos activos y sus categorías.
*/
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard de Laravel Breeze
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Panel administrativo y perfil
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    /*
     * CRUD de categorías y productos.
     */
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::resource(
                'categories',
                CategoryController::class
            );

            Route::resource(
                'products',
                ProductController::class
            );
        });

    /*
     * Administración del perfil.
     */
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Carrito de compras
|--------------------------------------------------------------------------
| El carrito se guarda temporalmente en la sesión de Laravel.
*/

/*
 * Muestra el contenido del carrito.
 */
Route::get(
    '/carrito',
    [CartController::class, 'index']
)->name('cart.index');

/*
 * Agrega un producto o suma una unidad si ya existe.
 */
Route::post(
    '/carrito/agregar/{product}',
    [CartController::class, 'add']
)->name('cart.add');

/*
 * Actualiza manualmente la cantidad.
 */
Route::patch(
    '/carrito/actualizar/{product}',
    [CartController::class, 'update']
)->name('cart.update');

/*
 * Aumenta una unidad.
 */
Route::patch(
    '/carrito/aumentar/{product}',
    [CartController::class, 'increase']
)->name('cart.increase');

/*
 * Disminuye una unidad.
 */
Route::patch(
    '/carrito/disminuir/{product}',
    [CartController::class, 'decrease']
)->name('cart.decrease');

/*
 * Elimina completamente un producto del carrito.
 */
Route::delete(
    '/carrito/eliminar/{product}',
    [CartController::class, 'remove']
)->name('cart.remove');

/*
|--------------------------------------------------------------------------
| Resumen del checkout
|--------------------------------------------------------------------------
| Muestra los productos y el total antes de enviarlos a Stripe.
*/
Route::get(
    '/checkout',
    [CartController::class, 'checkout']
)->name('checkout.index');

/*
|--------------------------------------------------------------------------
| Integración real con Stripe
|--------------------------------------------------------------------------
*/

/*
 * Crea una sesión de pago y redirige al checkout oficial de Stripe.
 */
Route::post(
    '/stripe/pagar',
    [StripeController::class, 'checkout']
)->name('stripe.checkout');

/*
 * Recibe al usuario después de un pago exitoso.
 */
Route::get(
    '/stripe/exito',
    [StripeController::class, 'success']
)->name('stripe.success');

/*
 * Recibe al usuario si cancela el pago.
 */
Route::get(
    '/stripe/cancelado',
    [StripeController::class, 'cancel']
)->name('stripe.cancel');

/*
|--------------------------------------------------------------------------
| Rutas de autenticación de Laravel Breeze
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
