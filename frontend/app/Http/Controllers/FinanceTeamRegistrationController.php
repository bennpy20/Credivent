<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FinanceTeamRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/financeteam/financeteam-registration-index');

        if ($response->successful()) {
            $registrations = $response->json();

            $registrations = array_map(function ($item) {
                $item['status_display'] = match ($item['payment_status']) {
                    1 => 'Menunggu Verifikasi',
                    2 => 'Disetujui',
                    3 => 'Ditolak',
                    default => 'Belum Bayar',
                };

                return $item;
            }, $registrations);

            return view('financeteam.registration.index', compact('registrations'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data pembayaran']);
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
            'payment_status' => 'required|integer|in:2,3'
        ]);

        $response = Http::put("http://localhost:3000/api/financeteam/financeteam-registration-update/{$id}", [
            'payment_status' => (int) $request->payment_status,
        ]);

        if ($response->successful()) {
            $message = $request->payment_status == 2 ? 'Pembayaran berhasil disetujui' : 'Pembayaran berhasil ditolak';
            return redirect()->route('financeteam.registration.index')->with('success', $message);
        } else {
            return back()->with(['error' => 'Gagal memperbarui status pembayaran']);
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
