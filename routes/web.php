<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FiadoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Facades\Route;

// Entrada pública redireciona para a tela de login customizada.
Route::redirect('/', '/login');

Route::middleware('auth')->group(function () {
    // Painel principal e dashboards.
    Route::get('/dashboard', [DashboardController::class, 'menu'])->name('dashboard');

    // Relatórios analíticos do negócio.
    Route::get('/relatorio/fiados', [RelatorioController::class, 'fiados'])->name('relatorio.fiados');
    Route::get('/relatorio/fiados/{cliente}', [RelatorioController::class, 'fiadosPorCliente'])->name('relatorio.fiados.cliente');
    Route::get('/relatorio/vendas', [RelatorioController::class, 'vendas'])->name('relatorio.vendas');

    // Controle de vendas em aberto (contas a receber).
    Route::get('/fiados', [FiadoController::class, 'index'])->name('fiados.index');
    Route::post('/fiados/{id}/receber', [FiadoController::class, 'receber'])->name('fiados.receber');

    // Catálogo, clientes e vendas são sempre restritos ao usuário logado.
    Route::resource('produtos', ProductController::class)->except(['show']);
    Route::resource('clientes', ClientController::class);
    Route::resource('vendas', SaleController::class);

    // Perfil do usuário autenticado.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Área administrativa com middleware personalizado.
Route::middleware(['auth', EnsureUserIsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', AdminUserController::class)->except(['show']);
});

require __DIR__.'/auth.php';
