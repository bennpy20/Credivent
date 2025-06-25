<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CommitteeSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $response = Http::get('http://localhost:3000/api/committee/committee-session-index');

        if ($response->successful()) {
            $events = $response->json();

            // Pastikan semua event memiliki key 'event_sessions', bahkan jika kosong
            foreach ($events as &$event) {
                if (!array_key_exists('event_sessions', $event)) {
                    $event['event_sessions'] = []; // isi dengan array kosong untuk menghindari error
                }
            }

            return view('committee.session.index', compact('events'));
        }

        return back()->withErrors(['error' => 'Gagal mengambil data event']);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('committee.session.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'session' => 'required|integer',
            'event_id' => 'required|string',
            'title' => 'required|string|max:150',
            'session_start' => 'required|date_format:Y-m-d H:i',
            'session_end' => 'required|date_format:Y-m-d H:i',
            'description' => 'nullable|string|max:500',
            'name' => 'required|string|max:100',
            'speaker_image' => 'nullable|image|max:2048',
        ]);

        $speakerImage = null;
        if ($request->hasFile('speaker_image')) {
            $file = $request->file('speaker_image');
            $ext = $file->getClientOriginalExtension();
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $request->name);
            $filename = 'speaker_' . time() . '_' . $safeName . '.' . $ext;
            $file->move(public_path('speakers'), $filename);
            $speakerImage = url('speakers/' . $filename);
        }

        // // Proses foto pembicara jika ada
        //     if ($request->hasFile("sessions.$index.speaker_image")) {
        //         $speakerFile = $request->file("sessions.$index.speaker_image");
        //         // Bersihkan nama event agar aman untuk nama file
        //         $safeSpeakerName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $eventName);
        //         // Ambil ekstensi file
        //         $speakerExtension = $speakerFile->getClientOriginalExtension();
        //         // Buat nama file final
        //         $speakerFilename = 'speaker_' . $index . '_' . $safeSpeakerName . '.' . $speakerExtension;
        //         // Pindahkan file ke folder public/posters
        //         $speakerFile->move(public_path('speakers'), $speakerFilename);
        //         // Simpan path relatif (untuk frontend / API)
        //         $sessionData['speaker_image'] = url('speakers/' . $speakerFilename); // full URL, cocok untuk API
        //     }

        $user = session('user');

        $response = Http::post('http://localhost:3000/api/committee/committee-session-store', [
            'event_id' => $request->event_id,
            'session' => $request->session,
            'title' => $request->title,
            'session_start' => $request->session_start,
            'session_end' => $request->session_end,
            'description' => $request->description,
            'name' => $request->name,
            'speaker_image' => $speakerImage,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Sesi event berhasil ditambahkan');
        } else {
            return back()->with(['error' => 'Gagal menambahkan sesi event']);
        }
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
        $response = Http::get("http://localhost:3000/api/committee/committee-session-edit", [
            'event_id' => $id,
        ]);

        if ($response->successful()) {
            $event = $response->json();

            // Jaga-jaga kalau event_sessions belum ada
            if (!array_key_exists('event_sessions', $event)) {
                $event['event_sessions'] = [];
            }

            return view('committee.session.edit', compact('event'));
        }

        return back()->withErrors(['error' => 'Gagal mengambil data sesi']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $sessions = $request->input('sessions', []);

        foreach ($sessions as $index => $sessionData) {
            $payload = [
                'title' => $sessionData['title'],
                'session_start' => $sessionData['session_start'],
                'session_end' => $sessionData['session_end'],
                'description' => $sessionData['description'],
                'name' => $sessionData['name']
            ];

            // Ambil file image dengan cara yang benar (file nested array)
            if (isset($sessionData['speaker_image']) && $sessionData['speaker_image'] instanceof \Illuminate\Http\UploadedFile) {
                $image = $sessionData['speaker_image'];
                if ($image->isValid()) {
                    $filename = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/speakers'), $filename);
                    $payload['speaker_image'] = url('uploads/speakers/' . $filename);
                }
            } elseif (isset($sessionData['old_speaker_image'])) {
                $payload['speaker_image'] = $sessionData['old_speaker_image'];
            }

            // Kirim ke backend Node.js
            Http::put("http://localhost:3000/api/committee/committee-session-update/{$sessionData['id']}", $payload);
        }

        return back()->with('success', 'Sesi berhasil diperbarui');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $response = Http::delete("http://localhost:3000/api/committee/committee-session-destroy/$id");

        if ($response->successful()) {
            return back()->with('success', 'Sesi berhasil dihapus');
        }

        return back()->withErrors(['error' => 'Gagal menghapus sesi']);
    }
}
