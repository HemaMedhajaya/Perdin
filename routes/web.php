<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\CategoryproductController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\DetailApproverControler;
use App\Http\Controllers\HistoryRealisasiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PerdinController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\SubMenuController;
use App\Http\Controllers\TravelRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/tabel', function () {
    return view('tabel');
});

//Login
Route::get('/login', [AuthController::class, 'showlogin'])->name('login');
Route::post('/login', [AuthController::class, 'loginpost']);
Route::get('/verrify-otp', [AuthController::class, 'showotp'])->name('show.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('otp.verify');
Route::get('/logout', function () {
Auth::logout(); return redirect()->route('login'); })->name('logout');
// End

Route::middleware(['check.expired', 'role:admin'])->group(function () {
    Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
    // User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/data', [UserController::class, 'getData'])->name('users.data');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    // End User

    // Karyawan
    Route::get('/karyawans', [KaryawanController::class, 'index'])->name('karyawans.index');
    Route::get('/karyawan/data', [KaryawanController::class, 'getData'])->name('karyawans.data');
    Route::post('/karyawans', [KaryawanController::class, 'store'])->name('karyawans.store');
    Route::put('/karyawans/{id}', [KaryawanController::class, 'update'])->name('karyawans.update');
    Route::delete('/karyawans/{id}', [KaryawanController::class, 'destroy'])->name('karyawans.destroy');
    Route::get('/karyawans/{id}/edit', [KaryawanController::class, 'edit'])->name('karyawans.edit');
    // End Karyawan

    // Jabatan
    Route::get('/jabatan', [JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('/jabatan/data', [JabatanController::class, 'getData'])->name('jabatan.data');
    Route::post('/jabatan', [JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/jabatan/{id}/edit', [JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('/jabatan/{id}', [JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [JabatanController::class, 'destroy'])->name('jabatan.destroy');
    // End Jabatan

    // Departement
    Route::get('/departement', [DepartementController::class, 'index'])->name('departement.index');
    Route::get('/departement/data', [DepartementController::class, 'getData'])->name('departement.data');
    Route::post('/departement', [DepartementController::class, 'store'])->name('departement.store');
    Route::get('/departement/{id}/edit', [DepartementController::class, 'edit'])->name('departement.edit');
    Route::put('/departement/{id}', [DepartementController::class, 'update'])->name('departement.update');
    Route::delete('/departement/{id}', [DepartementController::class, 'destroy'])->name('departement.destroy');
    // End Departement

    // Category Product
    Route::get('/categorypd', [CategoryproductController::class, 'index'])->name('categorypd.index');
    Route::get('/categorypd/data', [CategoryproductController::class, 'getData'])->name('categorypd.data');
    Route::post('/categorypd', [CategoryproductController::class, 'store'])->name('categorypd.store');
    Route::get('/categorypd/{id}/edit', [CategoryproductController::class, 'edit'])->name('categorypd.edit');
    Route::put('/categorypd/{id}', [CategoryproductController::class, 'update'])->name('categorypd.update');
    Route::delete('/categorypd/{id}', [CategoryproductController::class, 'destroy'])->name('categorypd.destroy');
    // End Category Product

    // Menu
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/data', [MenuController::class, 'getData'])->name('menus.data');
    Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{id}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{id}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/menus/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');
    // End Menu

    // Sub Menu
    Route::get('/submenus', [SubMenuController::class, 'index'])->name('submenus.index');
    Route::get('/submenus/data', [SubMenuController::class, 'getData'])->name('submenus.data');
    Route::get('/submenus/menus', [SubMenuController::class, 'getMenu'])->name('submenus.menus');
    Route::post('/submenus', [SubMenuController::class, 'store'])->name('submenus.store');
    Route::get('/submenus/{id}/edit', [SubMenuController::class, 'edit'])->name('submenus.edit');
    Route::put('/submenus/{id}', [SubMenuController::class, 'update'])->name('submenus.update');
    Route::delete('/submenus/{id}', [SubMenuController::class, 'destroy'])->name('submenus.destroy');
    // End Sub Menu

    // Biaya
    Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');
    Route::get('/biaya/data', [BiayaController::class, 'getData'])->name('biaya.data');
    Route::post('/biaya', [BiayaController::class, 'store'])->name('biaya.store');
    Route::get('/biaya/{id}/edit', [BiayaController::class, 'edit'])->name('biaya.edit');
    Route::put('/biaya/{id}', [BiayaController::class, 'update'])->name('biaya.update');
    Route::delete('/biaya/{id}', [BiayaController::class, 'destroy'])->name('biaya.destroy');

});

Route::middleware(['check.expired', 'role:user'])->group( function() {
    Route::get('/dashboard', [PerdinController::class, 'index'])->name('index.dashboard');

    // Perjalan Dinas
    Route::get('/perdin', action: [TravelRequestController::class, 'index'])->name('perdin.index');
    Route::get('/perdin/data', [TravelRequestController::class, 'getData'])->name('perdin.data');
    Route::get('/perdin/datauser', [TravelRequestController::class, 'getUser'])->name('perdin.datauser');
    Route::get('/perdin/getcategoryproduct', [TravelRequestController::class, 'getCategoryProduct'])->name('perdin.getcategoryproduct');
    Route::get('/perdin/userpj', [TravelRequestController::class, 'getUserpj'])->name('perdin.userpj');
    Route::post('/perdin', [TravelRequestController::class, 'store'])->name('perdin.store');
    Route::get('/perdin/{id}/edit', [TravelRequestController::class, 'edit'])->name('perdin.edit');
    Route::put('/perdin/{id}', [TravelRequestController::class, 'update'])->name('perdin.update');
    Route::delete('/perdin/{id}', [TravelRequestController::class, 'destroy'])->name('perdin.destroy');
    // End Perjalan Dinas

    // Detail Perjalan Dinas
    Route::get('/perdin/details/{id}', [TravelRequestController::class, 'detail'])->name('perdin.detail');
    Route::get('/perdin/{id}/detail', [TravelRequestController::class, 'getDataDetail'])->name('perdin.datadetail');
    Route::post('/detail', [TravelRequestController::class, 'saveBiaya'])->name('perdin.savaebiaya');
    Route::put('/detail/{id}', [TravelRequestController::class, 'saveBiaya'])->name('perdin.updatebiaya');
    Route::get('/detail/{id}/edit', [TravelRequestController::class, 'editdetail'])->name('perdin.detail.edit');
    Route::delete('/detail/{id}', [TravelRequestController::class, 'destroydetail'])->name('perdin.destroydetail');
    Route::put('/submitrequest/{id}', [TravelRequestController::class, 'submitRequest'])->name('detail.submitrequest');
    Route::get('/statusapprove/{id}', [TravelRequestController::class, 'cekStatusApprove'])->name('detail.statusapprove');
    // End Detail Perjalan Dinas

    // Realisasi Perjalan Dinas
    Route::get('/perdin/realisasi/{id}', [RealisasiController::class, 'index'])->name('perdin.realisasi');
    Route::get('/perdin/realisasi/{id}/detail', [RealisasiController::class, 'getDataRealisasi'])->name('perdin.realisasi.detail');
    Route::post('/realisasi', [RealisasiController::class, 'store'])->name('realisasi.store');
    Route::get('/realisasi/{id}/edit', [RealisasiController::class, 'edit'])->name('realisasi.edit');
    Route::put('/realisasi/{id}', [RealisasiController::class, 'update'])->name('realisasi.update');
    Route::delete('/realisasi/{id}', [RealisasiController::class, 'destroy'])->name('realisasi.destroy');
    Route::get('/perdin/{id}/detail-sebelum', [RealisasiController::class, 'getDataSebelum']);
    Route::get('/perdin/realisasi/{id}/detail-sesudah', [RealisasiController::class, 'getDatasesudah']);
    Route::get('/perdin/{id}/detail-combined', [RealisasiController::class, 'getDataCombined']);
    // End Realisasi Perjalan Dinas
    
    Route::get('/export/perjalanan', [RealisasiController::class, 'export'])->name('export.excel');

    // Export PDF & Excel Perdin Sebelum Realisasi
    Route::get('/export-kasbon-pdf/{id}', [TravelRequestController::class, 'exportPDF'])->name('export.kasbon.pdf');
    Route::get('/export-kasbon-excel/{id}', [TravelRequestController::class, 'ExportEcxelKasbon'])->name('export.kasbon.excel');
    // End PDF & Excel Perdin Sebelum Realisasi
    Route::get('/historyrealisasi', [HistoryRealisasiController::class, 'index'])->name('historyrealisasi.index');
    Route::get('/historyrealisasi/data', [HistoryRealisasiController::class, 'getData'])->name('historyrealisasi.data');
    Route::get('/historyrealisasi/realisasi/{id}', [HistoryRealisasiController::class, 'sudahRealisasi'])->name('historyrealisasi.realisasi');
    Route::get('/historyrealisasi/realisasi/data/{id}', [HistoryRealisasiController::class, 'getDataCombined'])->name('historyrealisasi.Combined');
});

Route::middleware(['check.expired', 'role:adminapprover'])->group(function() {
    Route::get('/dashboardadmin', [ApprovalController::class, 'dashboard'])->name('dashboard.approver');

    Route::get('/approver', [ApprovalController::class, 'index'])->name('approver.index');
    Route::get('/approver/data', [ApprovalController::class, 'getData'])->name('approver.data');
    Route::get('/approver/getcategoryproduct', [ApprovalController::class, 'getCategoryProduct'])->name('approver.getcategoryproduct');
    Route::get('/approver/userpj', [ApprovalController::class, 'getUserpj'])->name('approver.userpj');
    Route::get('/approver/{id}/edit', [ApprovalController::class, 'edit'])->name('approver.edit');
    // Route::post('/approver')

    // Detail
    Route::get('/approver/detail/{id}', [DetailApproverControler::class, 'index'])->name('approver.detail');
    Route::get('/approver/detail/{id}/data', [DetailApproverControler::class, 'getDataDetailApprover'])->name('approver.detail.data');
    Route::get('/approver/detail/{id}/edit', [DetailApproverControler::class, 'editdetail'])->name('approver.detail.edit');
    Route::put('/approver/detail/{id}/update', [DetailApproverControler::class, 'updateTravelRequest'])->name('approver.detail.approve');

});

