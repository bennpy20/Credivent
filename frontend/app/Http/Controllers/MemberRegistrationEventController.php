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
        // dd($user);
        $user_id = $user['id'];
        dd(session('user'));

        $response = Http::get('http://localhost:3000/member/member-registration-index', [
            'id' => $user_id
        ]);

        dd($response->json());

        if ($response->successful()) {
            $registrations = $response->json();
            
            // $registrations = array_map(function ($item) {
            //     $start = Carbon::parse($item['event']['start_date'])->locale('id');
            //     $end = Carbon::parse($item['event']['end_date'])->locale('id');

            //     if ($start->isSameDay($end)) {
            //         $item['event']['date_display'] = $start->translatedFormat('d F Y');
            //     } else {
            //         if ($start->format('Y') == $end->format('Y')) {
            //             if ($start->format('F') == $end->format('F')) {
            //                 $item['event']['date_display'] = $start->format('d') . '-' . $end->translatedFormat('d F Y');
            //             } else {
            //                 $item['event']['date_display'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
            //             }
            //         } else {
            //             $item['event']['date_display'] = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
            //         }
            //     }

            //     return $item;
            // }, $registrations);

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
            return redirect()->route('member.registration.index')->with('success', 'Berhasil mendaftar pada sesi yang dipilih!');
        }

        return back()->withErrors(['error' => 'Gagal menyimpan data']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
