<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MemberScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/member/member-event-index');

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

                // Mapping status
                switch ($event['event_status']) {
                    case 1:
                        $event['event_status_text'] = 'Mendatang';
                        break;
                    case 2:
                        $event['event_status_text'] = 'Berlangsung';
                        break;
                    case 3:
                        $event['event_status_text'] = 'Selesai';
                        break;
                    default:
                        $event['event_status_text'] = 'Tidak diketahui';
                }

                return $event;
            }, $events);

            return view('member.schedule.index', compact('events'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Mengambil data event dari API Node.js
        $response = Http::get("http://localhost:3000/api/member/member-event-show/{$id}");

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
            return view('member.schedule.show', [
                'event' => $data['event'],
                'event_sessions' => $event_sessions,
            ]);
        } else {
            return back()->withErrors(['error' => 'Gagal mengambil data event dan speaker']);
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
