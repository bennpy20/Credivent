<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CommitteeEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $response = Http::get('http://localhost:3000/api/committee/committee-event-index');

        if ($response->successful()) {
            $events = $response->json();

            $events = array_map(function ($event) {
                $start = Carbon::parse($event['start_date'])->locale('id');
                $end = Carbon::parse($event['end_date'])->locale('id');

                if ($start->isSameDay($end)) {
                    $event['date_display'] = $start->translatedFormat('d F Y');  // ex: 27 Mei 2025
                } else {
                    if ($start->format('Y') == $end->format('Y')) {
                        if ($start->format('F') == $end->format('F')) {
                            $event['date_display'] = $start->format('d') . '-' . $end->translatedFormat('d F Y'); // ex: 27-28 Mei 2025
                        } else {
                            $event['date_display'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y'); // ex: 27 Mei - 02 Juni 2025
                        }
                    } else {
                        $event['date_display'] = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y'); // ex: 27 Mei 2025 - 02 Juni 2026
                    }
                }

                return $event;
            }, $events);

            return view('committee.event.index', compact('events'));
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data']);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('committee.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:200',
            'poster_link' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'max_participants' => 'required|numeric',
            'transaction_fee' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            // Validasi session
            'sessions' => 'required|array|min:1',
            // Validasi setiap field dalam sessions
            'sessions.*.title' => 'required|string|max:150',
            'sessions.*.session_start' => 'required|date_format:Y-m-d\TH:i',
            'sessions.*.session_end' => 'required|date_format:Y-m-d\TH:i|after:sessions.*.session_start',
            'sessions.*.description' => 'nullable|string|max:500',
            'sessions.*.name' => 'required|string|max:100',
            'sessions.*.speaker_image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Simpan file poster
        $posterPath = null;

        if ($request->hasFile('poster_link')) {
            $posterFile = $request->file('poster_link');
            // Ambil nama event dari request
            $eventName = $request->input('name');
            // Bersihkan nama event agar aman untuk nama file
            $safeEventName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $eventName);
            // Ambil ekstensi file
            $extension = $posterFile->getClientOriginalExtension();
            // Buat nama file final
            $posterFilename = 'event_' . $safeEventName . '.' . $extension;
            // Pindahkan file ke folder public/posters
            $posterFile->move(public_path('posters'), $posterFilename);
            // Simpan path relatif (untuk frontend / API)
            $posterPath = url('posters/' . $posterFilename); // full URL, cocok untuk API
        }

        // Siapkan array sessions
        $sessionsInput = $request->input('sessions', []);
        $eventSessions = [];

        foreach ($sessionsInput as $index => $session) {
            $sessionData = [
                'title' => $session['title'],
                'session_start' => Carbon::createFromFormat('Y-m-d\TH:i', $session['session_start'], 'Asia/Jakarta')->format('Y-m-d H:i:s'),
                'session_end' => Carbon::createFromFormat('Y-m-d\TH:i', $session['session_end'], 'Asia/Jakarta')->format('Y-m-d H:i:s'),
                'description' => $session['description'],
                'name' => $session['name'],
                'speaker_image' => null // akan diisi di bawah jika file tersedia
            ];

            // Proses foto pembicara jika ada
            if ($request->hasFile("sessions.$index.speaker_image")) {
                $speakerFile = $request->file("sessions.$index.speaker_image");
                // Bersihkan nama event agar aman untuk nama file
                $safeSpeakerName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $eventName);
                // Ambil ekstensi file
                $speakerExtension = $speakerFile->getClientOriginalExtension();
                // Buat nama file final
                $speakerFilename = 'speaker_' . $index . '_' . $safeSpeakerName . '.' . $speakerExtension;
                // Pindahkan file ke folder public/posters
                $speakerFile->move(public_path('speakers'), $speakerFilename);
                // Simpan path relatif (untuk frontend / API)
                $sessionData['speaker_image'] = url('speakers/' . $speakerFilename); // full URL, cocok untuk API
            }

            $eventSessions[] = $sessionData;
        }

        // Kirim ke backend Node.js
        $user = session('user'); // Ambil data user dari session
        $user_id = $user['id'];

        // $eventSessions = $request->input('sessions');

        $response = Http::post('http://localhost:3000/api/committee/committee-event-store', [
            'name' => $request->name,
            'location' => $request->location,
            'poster_link' => $posterPath, // dikirim sebagai URL/path
            'max_participants' => $request->max_participants,
            'transaction_fee' => $request->transaction_fee,
            'event_status' => 1,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'user_id' => $user_id,
            // 'session' => $request->session,
            'event_sessions' => $eventSessions
        ]);

        if ($response->successful()) {
            return redirect()->route('committee.event.index')->with('success', 'Event berhasil dibuat');
        } else {
            return back()->withErrors(['error' => 'Gagal menambahkan user'])->withInput();
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
        return view('committee.event.edit');
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
