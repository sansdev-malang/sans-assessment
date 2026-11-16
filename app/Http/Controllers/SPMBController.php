<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SPMBController extends Controller
{
    /**
     * Show the Login Page (Portals Guru & Admin).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Handle Login authentication and role-based redirect.
     */
    public function login(Request $request): RedirectResponse
    {
        $role = $request->input('role', 'guru');
        $email = $request->input('email', '');

        // Auto-detect role if entered directly via email
        if (str_contains(strtolower($email), 'admin')) {
            $role = 'admin';
        } elseif (str_contains(strtolower($email), 'guru') || str_contains(strtolower($email), 'ustadzah')) {
            $role = 'guru';
        }

        if ($role === 'admin') {
            session([
                'auth_user' => [
                    'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
                    'email' => $email ?: 'admin@anaksaleh.sch.id',
                    'role' => 'admin',
                    'role_label' => 'Kepala Panitia SPMB & Admin',
                ],
            ]);

            return redirect()->route('admin.dashboard')
                ->with('status', 'Selamat datang di Dashboard Observasi SPMB Anak Saleh!');
        }

        // Default: Guru / Pengamat
        session([
            'auth_user' => [
                'name' => 'Ustadzah Fatimah Azzahra, S.Pd.',
                'email' => $email ?: 'guru@anaksaleh.sch.id',
                'role' => 'guru',
                'role_label' => 'Tim Pengamat & Psikolog Anak',
            ],
        ]);

        return redirect()->route('guru.observasi')
            ->with('status', 'Selamat bertugas di Halaman Observasi Siswa Baru!');
    }

    /**
     * Halaman Observasi Siswa Baru untuk Guru.
     */
    public function observasi(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadzah Fatimah Azzahra, S.Pd.',
            'email' => 'guru@anaksaleh.sch.id',
            'role' => 'guru',
            'role_label' => 'Tim Pengamat & Psikolog Anak',
        ]);

        return view('guru.observasi', compact('user'));
    }

    /**
     * Dashboard Observasi & Rekapitulasi untuk Admin.
     */
    public function dashboard(): View
    {
        $user = session('auth_user', [
            'name' => 'Ustadz H. Ahmad Fauzi, M.Pd.',
            'email' => 'admin@anaksaleh.sch.id',
            'role' => 'admin',
            'role_label' => 'Kepala Panitia SPMB & Admin',
        ]);

        return view('admin.dashboard', compact('user'));
    }

    /**
     * Logout and redirect to login page.
     */
    public function logout(): RedirectResponse
    {
        session()->forget('auth_user');

        return redirect()->route('login')
            ->with('status', 'Anda telah berhasil keluar dari sistem.');
    }
}
