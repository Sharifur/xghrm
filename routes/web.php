<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
    return redirect()->route('admin.login');
});

/*==============================================
            MEDIA UPLOAD ROUTES
==============================================*/
Route::prefix('media-upload')->middleware(['auth:admin,web'])->group(function () {
    Route::post('/delete', [\App\Http\Controllers\Admin\MediaUploadController::class,'delete_upload_media_file'])->name('admin.upload.media.file.delete');
    Route::get('/page', [\App\Http\Controllers\Admin\MediaUploadController::class,'all_upload_media_images_for_page'])->name('admin.upload.media.images.page');
    Route::post('/alt', [\App\Http\Controllers\Admin\MediaUploadController::class,'alt_change_upload_media_file'])->name('admin.upload.media.file.alt.change');
    Route::post('/all', [\App\Http\Controllers\Admin\MediaUploadController::class,'all_upload_media_file'])->name('admin.upload.media.file.all');
    Route::post('/', [\App\Http\Controllers\Admin\MediaUploadController::class,'upload_media_file'])->name('admin.upload.media.file');
    Route::post('/loadmore', [\App\Http\Controllers\Admin\MediaUploadController::class,'get_image_for_loadmore'])->name('admin.upload.media.file.loadmore');
    Route::post('/single', [\App\Http\Controllers\Admin\MediaUploadController::class,'fetch_single_image'])->name('admin.upload.media.file.single');
});


require __DIR__.'/admin.php';
require __DIR__.'/user.php';
require __DIR__.'/auth.php';

