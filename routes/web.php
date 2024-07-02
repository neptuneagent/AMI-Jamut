<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\ResponseController;

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

Auth::routes();

// Route::get('/', [HomeController::class, 'index'])->name('index');
// Route::get('/home', [HomeController::class, 'index'])->name('home');



Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [ResponseController::class, 'index'])->name('index');
    Route::get('/home', [ResponseController::class, 'index'])->name('home');
    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::put('/admin/settings/update-password', [SettingController::class, 'updatePassword'])->name('admin.update-password');
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/user', [SettingController::class, 'viewUsers'])->name('admin.view-users');
    Route::post('/admin/user', [SettingController::class, 'addUser'])->name('admin.add-user');
    Route::delete('/admin/user/{id}', [SettingController::class, 'deleteUser'])->name('admin.delete-user');
    Route::put('/admin/user/roles/{id}', [SettingController::class, 'updateRoles'])->name('admin.update-roles');
    Route::put('/admin/user/passwordReset/{id}', [SettingController::class, 'resetUserPassword'])->name('admin.user-reset');
});

Route::group(['middleware' => ['role:prodi']], function () {
    Route::get('/forms/available', [FormController::class, 'show_available'])->name('forms.available-forms');
    Route::get('/forms/{form}/fill', [FormController::class, 'fill'])->name('forms.fill');
    Route::post('/forms/{form}/submit', [ResponseController::class, 'store'])->name('forms.submit');
    Route::put('/responses/{response}/mark-done', [ResponseController::class, 'markAsDone'])->name('responses.markDone');
    Route::get('/responses/{response}/resubmit', [ResponseController::class, 'edit'])->name('responses.edit');
    Route::put('/responses/{response}/resubmit', [ResponseController::class, 'update'])->name('responses.resubmit');
});

Route::group(['middleware' => ['role:jamut']], function () {
    Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('/forms/create', [FormController::class, 'create'])->name('forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('forms.store');
    Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
    Route::put('/forms/{form}', [FormController::class, 'update'])->name('forms.update');
    Route::delete('/forms/{form}', [FormController::class, 'destroy'])->name('forms.destroy');

    Route::post('/forms/{form}/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    Route::post('/questions/{question}/standards', [StandardController::class, 'store'])->name('standards.store');
    Route::put('/standards/{standard}', [StandardController::class, 'update'])->name('standards.update');
    Route::delete('/standards/{standard}', [StandardController::class, 'destroy'])->name('standards.destroy');

    Route::post('/standards/{standard}/criterias', [CriteriaController::class, 'store'])->name('criterias.store');
    Route::put('/criterias/{criteria}', [CriteriaController::class, 'update'])->name('criterias.update');
    Route::delete('/criterias/{criteria}', [CriteriaController::class, 'destroy'])->name('criterias.destroy');
});


Route::group(['middleware' => ['role:gkm']], function () {
    Route::post('/responses/{response}/upload-evidence', [ResponseController::class, 'uploadEvidence'])->name('responses.uploadEvidence');
    Route::put('/evidences/{evidence}', [ResponseController::class, 'updateEvidence'])->name('responses.updateEvidence');
    Route::delete('/evidences/{evidence}', [ResponseController::class, 'deleteEvidence'])->name('responses.deleteEvidence');
    Route::put('/responses/{response}/mark-complete', [ResponseController::class, 'markComplete'])->name('responses.markComplete');
});

Route::group(['middleware' => ['role:prodi|gkm|jamut|auditor']], function () {
    Route::get('/responses', [ResponseController::class, 'index'])->name('responses.index');
    Route::get('/responses/{response}', [ResponseController::class, 'show'])->name('responses.show');
});

Route::group(['middleware' => ['role:auditor']], function () {
    Route::post('/responses/{response}/add-finding', [ResponseController::class, 'addFinding'])->name('findings.store');
    Route::put('/findings/{finding}', [ResponseController::class, 'updateFinding'])->name('findings.update');
    Route::delete('/findings/{finding}', [ResponseController::class, 'deleteFinding'])->name('findings.destroy');
    Route::put('/responses/{response}/mark-audited', [ResponseController::class, 'markAudited'])->name('responses.markAudited');
});
