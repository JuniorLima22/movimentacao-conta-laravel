<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function(){
    Route::controller(PessoaController::class)->prefix('pessoa')->name('pessoa.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/listar', 'listarPessoa')->name('listar');
        Route::post('/cadastrar', 'store')->name('cadastrar');
        Route::get('/editar/{id}', 'edit')->name('editar');
        Route::put('/atualizar/{id}', 'update')->name('atualizar');
        Route::delete('/deletar', 'destroy')->name('deletar');
    });
});
