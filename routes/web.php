<?php

use App\Http\Controllers\SPMBController;
use Illuminate\Support\Facades\Route;

// Login Page as Root Landing Page
Route::get('/', [SPMBController::class, 'showLogin'])->name('login');
Route::post('/login', [SPMBController::class, 'login'])->name('login.post');

// Halaman Observasi untuk Guru
Route::get('/observasi', [SPMBController::class, 'observasi'])->name('guru.observasi');
Route::post('/observasi/simpan', [SPMBController::class, 'simpanObservasi'])->name('guru.observasi.simpan');
Route::post('/observasi/klaim', [SPMBController::class, 'klaimSiswa'])->name('guru.observasi.klaim');
Route::post('/observasi/lepas', [SPMBController::class, 'lepasSiswa'])->name('guru.observasi.lepas');

// Dashboard Observasi untuk Admin SPMB
Route::get('/admin/dashboard', [SPMBController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/guru', [SPMBController::class, 'guruList'])->name('admin.guru');
Route::post('/admin/guru/store', [SPMBController::class, 'guruStore'])->name('admin.guru.store');
Route::post('/admin/guru/update', [SPMBController::class, 'guruUpdate'])->name('admin.guru.update');
Route::post('/admin/guru/delete', [SPMBController::class, 'guruDelete'])->name('admin.guru.delete');
Route::get('/admin/guru/template', [SPMBController::class, 'guruTemplate'])->name('admin.guru.template');
Route::post('/admin/guru/import', [SPMBController::class, 'guruImport'])->name('admin.guru.import');

Route::get('/admin/siswa', [SPMBController::class, 'siswaList'])->name('admin.siswa');
Route::post('/admin/siswa/store', [SPMBController::class, 'siswaStore'])->name('admin.siswa.store');
Route::post('/admin/siswa/update', [SPMBController::class, 'siswaUpdate'])->name('admin.siswa.update');
Route::post('/admin/siswa/delete', [SPMBController::class, 'siswaDelete'])->name('admin.siswa.delete');
Route::get('/admin/siswa/template', [SPMBController::class, 'siswaTemplate'])->name('admin.siswa.template');
Route::post('/admin/siswa/import', [SPMBController::class, 'siswaImport'])->name('admin.siswa.import');
Route::get('/admin/siswa/export', [SPMBController::class, 'siswaExport'])->name('admin.siswa.export');

Route::get('/admin/indikator', [SPMBController::class, 'indikatorList'])->name('admin.indikator');

// Logout
Route::match(['get', 'post'], '/logout', [SPMBController::class, 'logout'])->name('logout');
