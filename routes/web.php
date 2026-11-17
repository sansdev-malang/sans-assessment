<?php

use App\Http\Controllers\SPMBController;
use Illuminate\Support\Facades\Route;

// Login Page as Root Landing Page
Route::get('/', [SPMBController::class, 'showLogin'])->name('login');
Route::post('/login', [SPMBController::class, 'login'])->name('login.post');

// Halaman Observasi untuk Guru
Route::get('/observasi', [SPMBController::class, 'observasi'])->name('guru.observasi');

// Dashboard Observasi untuk Admin SPMB
Route::get('/admin/dashboard', [SPMBController::class, 'dashboard'])->name('admin.dashboard');
Route::get('/admin/guru', [SPMBController::class, 'guruList'])->name('admin.guru');
Route::get('/admin/siswa', [SPMBController::class, 'siswaList'])->name('admin.siswa');
Route::get('/admin/indikator', [SPMBController::class, 'indikatorList'])->name('admin.indikator');

// Logout
Route::match(['get', 'post'], '/logout', [SPMBController::class, 'logout'])->name('logout');
