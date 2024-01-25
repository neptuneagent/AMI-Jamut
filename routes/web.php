<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
Route::put('/admin/settings/update-password', [SettingController::class, 'updatePassword'])->name('admin.update-password');

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/user', [SettingController::class, 'viewUsers'])->name('admin.view-users');
    Route::post('/admin/user', [SettingController::class, 'addUser'])->name('admin.add-user');
    Route::delete('/admin/user/{id}', [SettingController::class, 'deleteUser'])->name('admin.delete-user');
    Route::put('/admin/user/roles/{id}', [SettingController::class, 'updateRoles'])->name('admin.update-roles');
});