<?php

use App\Http\Controllers\Admin\ContaController;
use App\Http\Controllers\Admin\HistoricoController;
use App\Http\Controllers\Admin\PessoaController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', 'home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function(){
    Route::controller(PessoaController::class)->prefix('pessoa')->name('pessoa.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/listar', 'listarPessoa')->name('listar');
        Route::post('/cadastrar', 'store')->name('cadastrar');
        Route::get('/editar/{id}', 'edit')->name('editar');
        Route::put('/atualizar/{id}', 'update')->name('atualizar');
        Route::delete('/deletar', 'destroy')->name('deletar');
    });

    Route::controller(ContaController::class)->prefix('conta')->name('conta.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/listar', 'listarConta')->name('listar');
        Route::get('/listar-conta-pessoa/{id}', 'listarContaPessoa')->name('listar.pessoa');
        Route::post('/cadastrar', 'store')->name('cadastrar');
        Route::get('/editar/{id}', 'edit')->name('editar');
        Route::put('/atualizar/{id}', 'update')->name('atualizar');
        Route::delete('/deletar', 'destroy')->name('deletar');
    });

    Route::controller(HistoricoController::class)->prefix('historico')->name('historico.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/listar/{id}', 'listarHistorico')->name('listar');
        Route::post('/cadastrar', 'store')->name('cadastrar');
        Route::get('/editar/{id}', 'edit')->name('editar');
        Route::put('/atualizar/{id}', 'update')->name('atualizar');
        Route::delete('/deletar', 'destroy')->name('deletar');
    });
});
