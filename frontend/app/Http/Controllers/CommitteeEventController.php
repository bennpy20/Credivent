<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            'poster_link' => 'required|image|mimes:jpg,png|max:2048',
            'max_participants' => 'required|integer',
            'transaction_fee' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
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

        $response = Http::post('http://localhost:3000/api/committee/committee-event-store', [
            'name' => $request->name,
            'location' => $request->location,
            'poster_link' => $posterPath, // dikirim sebagai URL/path
            'max_participants' => $request->max_participants,
            'transaction_fee' => $request->transaction_fee,
            'event_status' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        if ($response->successful()) {
            return redirect()->route('committee.event.index')->with('success', 'Event berhasil dibuat');
        } else {
            dd($response->body());
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
