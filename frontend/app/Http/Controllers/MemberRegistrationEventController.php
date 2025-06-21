<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MemberRegistrationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kirim ke backend Node.js
        $user = session('user'); // Ambil data user dari session
        $user_id = $user['id'];

        $response = Http::get('http://localhost:3000/api/member/member-registration-index', [
            'id' => $user_id
        ]);

        if ($response->successful()) {
            $registrations = $response->json();

            $registrations = array_map(function ($item) {
                if (!isset($item['session']) || !isset($item['event'])) {
                    return $item; // Skip jika tidak lengkap
                }
                $start = Carbon::parse($item['session']['session_start'])->locale('id');
                $end = Carbon::parse($item['session']['session_end'])->locale('id');

                // Format dinamis tergantung panjang range tanggal
                if ($start->isSameDay($end)) {
                    $formatted = $start->translatedFormat('d F Y H:i');
                } elseif ($start->isSameMonth($end)) {
                    $formatted = $start->format('d') . '-' . $end->translatedFormat('d F Y H:i');
                } elseif ($start->year === $end->year) {
                    $formatted = $start->translatedFormat('d F H:i') . ' - ' . $end->translatedFormat('d F Y H:i');
                } else {
                    $formatted = $start->translatedFormat('d F Y H:i') . ' - ' . $end->translatedFormat('d F Y H:i');
                }

                $item['event']['date_display'] = $formatted;

                // Tambahkan logika status pembayaran
                if ($item['event']['event_status'] === 1 && $item['payment_status'] === 1 && $item['payment_proof'] === "") {
                    $item['registration_status'] = 'bayar';
                } elseif ($item['event']['event_status'] === 1 && $item['payment_status'] === 1) {
                    $item['registration_status'] = 'diproses';
                } elseif ($item['payment_status'] === 2) {
                    $item['registration_status'] = 'sukses';
                } else {
                    $item['registration_status'] = 'gagal';
                }

                return $item;
            }, $registrations);

            return view('member.registration.index', compact('registrations'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data']);
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
        $validated = $request->validate([
            'user_id' => 'required|string',
            'session_ids' => 'required|array',
            'session_ids.*' => 'string'
        ]);

        // Kirim ke Node.js API untuk simpan banyak data registration
        $response = Http::post('http://localhost:3000/api/member/member-registration-store', [
            'user_id' => $validated['user_id'],
            'session_ids' => $validated['session_ids'],
        ]);

        if ($response->successful()) {
            return redirect()->route('member.registration.index')->with('success', 'Berhasil mendaftar event');
        } else {
            $errorMsg = $response->json('message') ?? 'Gagal mendaftar event';
            return back()->with('error', $errorMsg);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = session('user'); // Ambil data user dari session

        $userId = $user['id']; // ambil ID user yang sedang login

        $response = Http::get("http://localhost:3000/api/member/member-registration-show/{$id}", [
            'id' => $userId
        ]);

        if ($response->successful()) {
            // Ambil data JSON dari API
            $data = $response->json();

            // Format jam mulai dan jam selesai menjadi jam:menit
            $event_sessions = collect($data['event_sessions'])->map(function ($session) {
                $session['session_start'] = Carbon::parse($session['session_start'])->format('H:i');
                $session['session_end'] = Carbon::parse($session['session_end'])->format('H:i');
                return $session;
            });

            // Kirim data event dan event_sessions dengan speakers ke view
            return view('member.registration.show', [
                'event' => $data['event'],
                'event_sessions' => $event_sessions,
                'user' => $data['user']
            ]);
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('member.registration.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $paymentProofUrl = null;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');

            // Nama file berdasarkan ID registrasi
            $filename = 'payment_' . $id . '.' . $file->getClientOriginalExtension();

            // Simpan ke folder public/payment_proofs
            $file->move(public_path('payment_proofs'), $filename);

            // Simpan full URL
            $paymentProofUrl = url('payment_proofs/' . $filename);
        }

        // Kirim PUT request ke API Node.js
        $response = Http::put("http://localhost:3000/api/member/member-registration-update/{$id}", [
            'payment_proof' => $paymentProofUrl
        ]);

        if ($response->successful()) {
            return redirect()->route('member.registration.index')->with('success', 'Bukti pembayaran berhasil diupload');
        } else {
            return back()->with(['error' => 'Gagal mengupload bukti pembayaran']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = Http::delete("http://localhost:3000/api/member/member-registration-destroy/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'Pendaftaran event berhasil dibatalkan');
        }

        return back()->withErrors(['error' => 'Gagal membatalkan pendaftaran']);
    }
}
