<?php

use App\Http\Controllers\Admin\DownloadInvoiceController;
use App\Http\Controllers\Admin\DownloadZipController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\PrintInvoiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewInvoiceController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PayPalController;

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

// Route::get('/', [App\Http\Controllers\FrontendController::class, 'homepage'])->name('homepage');
Route::get('/', function(){
    return redirect()->route('login');
});

Auth::routes();
Auth::routes(['verify' => true]);

// Admin routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {

    Route::resource('/invoices',InvoiceController::class)->names(['index'=>'invoices.index']);

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

    Route::resource('/users',UserController::class)->names(['index'=>'users.index']);

    Route::get('/invoice_download/{id}/content',[DownloadInvoiceController::class,'download_pdf']); // on the table level download invoice

    Route::get('/invoice_print/{id}/content',[PrintInvoiceController::class, 'print_invoice']);// on the table level invoice print

    Route::resource('/settings',SettingController::class)->names(['index'=>'settings.index']);

    Route::post('/download_zip_invoice',[DownloadZipController::class, 'downloadZip']); // multiple checkbox selected then that invoice download in the zip.

    // Route::get('/invoices', [App\Http\Controllers\Admin\InvoiceController::class, 'index'])->name('invoices');
    // Route::get('/invoices/{id}', [::class, 'view_invoices'])->name('invoices.view');
    // Route::get('/invoices/create', [App\Http\Controllers\Admin\InvoiceController::class, 'create_invoice']);
    // Route::post('/invoices/store', [App\Http\Controllers\Admin\InvoiceController::class, 'store']);
    // Route::get('/invoices/edit/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'edit']);
    // Route::post('/invoices/update', [App\Http\Controllers\Admin\InvoiceController::class, 'update']);
    // Route::get('/invoices/delete/{id}', [App\Http\Controllers\Admin\InvoiceController::class, 'delete']);

    // Change Password
    Route::get('/change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'change_password']);
    Route::post('/change-password', [App\Http\Controllers\Admin\ChangePasswordController::class, 'update_password'])->name('update_password_admin');
   
});