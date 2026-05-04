<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('produtos.index');
    })->name('dashboard');

    Route::resource('produtos', ProdutoController::class)->except(['show', 'create', 'edit']);
    Route::get('produtos/lixeira', [ProdutoController::class, 'lixeira'])->name('produtos.lixeira');
    Route::post('produtos/{id}/restaurar', [ProdutoController::class, 'restaurar'])->name('produtos.restaurar');
    Route::delete('produtos/{id}/force-apagar', [ProdutoController::class, 'forceApagar'])->name('produtos.forceApagar');
});
