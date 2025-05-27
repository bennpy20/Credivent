<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    // Tampilkan form buat event baru
    public function showCreateEventForm()
    {
        return view('committee.event.create');
    }

    // Simpan data event baru ke API Node.js
    public function storeEvent(Request $request)
    {
        $response = Http::post('http://localhost:3000/api/committee-event', [
            'name' => $request->name,
            'location' => $request->location,
            'max_participants' => $request->max_participants,
            'transaction_fee' => $request->transaction_fee,
            'event_status' => 0, // draft
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);

        if ($response->successful()) {
            $event = $response->json();
            return redirect()->route('committee.session.create', $event['id']);
        } else {
            return back()->withErrors(['error' => 'Gagal membuat event']);
        }
    }

    // Tampilkan form tambah sesi untuk event tertentu
    public function showAddSessionForm($eventId)
    {
        $eventResponse = Http::get("http://localhost:3000/api/committee-event/{$eventId}");
        $event = $eventResponse->successful() ? $eventResponse->json() : null;

        $speakersResponse = Http::get("http://localhost:3000/api/committee-event/{$eventId}/speaker");
        $speakers = $speakersResponse->successful() ? $speakersResponse->json() : [];

        if (!$event) {
            return redirect()->route('committee.event.create')->withErrors(['error' => 'Event tidak ditemukan']);
        } else {
            return view('committee.session.create', compact('event', 'speakers'));
        }
    }

    // Simpan sesi baru untuk event
    public function storeSession(Request $request, $eventId)
    {
        $response = Http::post("http://localhost:3000/api/committee-event/{$eventId}/session", [
            'title' => $request->title,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'speaker_id' => $request->speaker_id,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Sesi berhasil ditambahkan');
        }

        return back()->withErrors(['error' => 'Gagal menambahkan sesi']);
    }

    // Tampilkan form tambah pembicara untuk event tertentu
    public function showAddSpeakerForm($eventId)
    {
        $eventResponse = Http::get("http://localhost:3000/api/events/{$eventId}");
        $event = $eventResponse->successful() ? $eventResponse->json() : null;

        if (!$event) {
            return redirect()->route('events.create')->withErrors(['error' => 'Event tidak ditemukan']);
        }

        return view('speaker.create', compact('event'));
    }

    // Simpan pembicara baru untuk event
    public function storeSpeaker(Request $request, $eventId)
    {
        $response = Http::post("http://localhost:3000/api/events/{$eventId}/speakers", [
            'name' => $request->name,
            'organization' => $request->organization,
            'email' => $request->email,
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Pembicara berhasil ditambahkan');
        }

        return back()->withErrors(['error' => 'Gagal menambahkan pembicara']);
    }

    // Publikasikan event (ubah status)
    public function publishEvent($eventId)
    {
        $response = Http::put("http://localhost:3000/api/events/{$eventId}", [
            'status' => 1, // published
        ]);

        if ($response->successful()) {
            return redirect()->route('events.create')->with('success', 'Event berhasil dipublikasikan');
        }

        return back()->withErrors(['error' => 'Gagal mempublikasikan event']);
    }

    // Batalkan event draft (hapus)
    public function cancelEvent($eventId)
    {
        $response = Http::delete("http://localhost:3000/api/events/{$eventId}");

        if ($response->successful()) {
            return redirect()->route('events.create')->with('info', 'Event draft dibatalkan');
        }

        return back()->withErrors(['error' => 'Gagal membatalkan event']);
    }
}