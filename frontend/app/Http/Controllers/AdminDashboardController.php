<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/admin/admin-dashboard-index');

        if ($response->successful()) {
            $data = $response->json();

            $panitiaCount = $data['panitia'];
            $keuanganCount = $data['keuangan'];

            // Batasi hanya 5 user pertama
            $users = array_slice($data['users'], 0, 5);

            // Ubah status akun 1 → Aktif, 2 → Nonaktif
            foreach ($users as &$user) {
                $user['status_label'] = $user['acc_status'] == 1 ? 'Aktif' : 'Nonaktif';
                $user['status_color'] = $user['acc_status'] == 1 ? 'green' : 'red';
            }

            return view('admin.index', compact('panitiaCount', 'keuanganCount', 'users'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data user']);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'certificate_link' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $certificateUrl = null;

        if ($request->hasFile('certificate_link')) {
            $file = $request->file('certificate_link');
            $filename = 'certificate_' . $id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('certificates'), $filename);
            $certificateUrl = url('certificates/' . $filename);
        }

        $response = Http::put("http://localhost:3000/api/committee/committee-certificate-update/{$id}", [
            'certificate_link' => $certificateUrl
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Sertifikat berhasil diunggah');
        } else {
            return back()->with('error', 'Gagal mengunggah sertifikat');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
