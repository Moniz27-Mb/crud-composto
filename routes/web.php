<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\AdminController;

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

    // Rotas de produtos
    Route::resource('produtos', ProdutoController::class)->except(['show', 'create', 'edit']);
    Route::get('produtos/lixeira', [ProdutoController::class, 'lixeira'])->name('produtos.lixeira');
    Route::post('produtos/{id}/restaurar', [ProdutoController::class, 'restaurar'])->name('produtos.restaurar');
    Route::delete('produtos/{id}/force-apagar', [ProdutoController::class, 'forceApagar'])->name('produtos.forceApagar');

    // Rotas do admin
    Route::middleware('admin')->group(function () {
        Route::get('/admin/utilizadores', [AdminController::class, 'utilizadores'])->name('admin.utilizadores');
        Route::post('/admin/utilizadores', [AdminController::class, 'criarUtilizador'])->name('admin.criarUtilizador');
        Route::put('/admin/utilizadores/{user}/role', [AdminController::class, 'alterarRole'])->name('admin.alterarRole');
        Route::delete('/admin/utilizadores/{user}', [AdminController::class, 'eliminarUtilizador'])->name('admin.eliminarUtilizador');
    });
});
