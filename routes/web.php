<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterKelasSiswaController;
use App\Http\Controllers\MasterGuruController;
use App\Http\Controllers\MasterMatkulController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriController;
use App\Http\Controllers\MateriSiswaController;
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
Route::get('hash_make/{string}', function($string){
    return Hash::make($string);
});

Route::group(['prefix' => "auth"], function(){
    Route::get('/', [AuthController::class, 'index'])->name('auth');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
Route::group(['middleware' => "auth"], function(){
    Route::get('/', [DashboardController::class, 'index']);

    // Master Data
    Route::group(['prefix' => 'master-data'], function(){
        // -- Kelas
        Route::group(['prefix' => "kelas"], function(){
            Route::get('/', [MasterKelasSiswaController::class, 'index']);
            Route::get('/add', [MasterKelasSiswaController::class, 'view_add']);
            Route::post('/save_add', [MasterKelasSiswaController::class, 'save_add']);
            Route::get('/detail/{id}', [MasterKelasSiswaController::class, 'view_detail']);
            Route::post('/save_edit/{id}', [MasterKelasSiswaController::class, 'save_edit']);
            Route::post('/add_siswa/{id}', [MasterKelasSiswaController::class, 'add_siswa']);
            Route::post('/edit_siswa/{id_kelas}/{id}', [MasterKelasSiswaController::class, 'edit_siswa']);
            Route::get('/ajax_cek_absen', [MasterKelasSiswaController::class, 'ajax_cek_absen']);
        });    
        // -- Guru
        Route::group(['prefix' => "guru"], function(){
            Route::get('/', [MasterGuruController::class, 'index']);
            Route::post('/save_add', [MasterGuruController::class, 'save_add']);
            Route::post('/save_edit/{id}', [MasterGuruController::class, 'save_edit']);
        });
        // Matkul
        Route::group(['prefix' => "matkul"], function(){
            Route::get('/', [MasterMatkulController::class, 'index']);
            Route::post('/save_add', [MasterMatkulController::class, 'save_add']);
        });
    });

    // Materi
    Route::group(['prefix' => "guru/materi"], function(){
        Route::get('/', [MateriController::class, 'index']);        
        Route::get('/buat-materi', [MateriController::class, 'buat_materi']);
        Route::post('/simpan_materi', [MateriController::class, 'simpan_materi']);

        Route::get('/{id}', [MateriController::class, 'detail_materi']);
        Route::get('/{id}/buat-sub-materi', [MateriController::class, 'create_submateri']);
        Route::post('/{id}/buat-sub-materi', [MateriController::class, 'save_create_submateri']);

        Route::group(['prefix' => 'submateri'], function(){            
            Route::get('/{submateri_id}/set/{state}', [MateriController::class, 'set_materi_status']);
            Route::get('/edit/{submateri_id}', [MateriController::class, 'edit_submateri']);
            Route::post('/edit/{submateri_id}', [MateriController::class, 'save_edit_submateri']);
        });        
    });

    Route::group(['prefix' => "siswa/materi"], function(){
        Route::get('/', [MateriSiswaController::class, 'index']);
        Route::get('/{id}', [MateriSiswaController::class, 'detail_materi']);

        Route::group(['prefix' => 'submateri'], function(){
            Route::get('/{submateri_id}/test', [MateriSiswaController::class, 'do_test']);
            Route::post('/{submateri_id}/test', [MateriSiswaController::class, 'submit_test']);
            Route::get('/{submateri_id}/jobsheet', [MateriSiswaController::class, 'view_jobsheet']);
        });
    });
});