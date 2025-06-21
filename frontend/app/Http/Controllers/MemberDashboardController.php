<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class MemberDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/member/member-dashboard-index');

        if ($response->successful()) {
            $data = $response->json();

            $events = array_map(function ($event) {
                $start = Carbon::parse($event['start_date'])->locale('id');
                $end = Carbon::parse($event['end_date'])->locale('id');

                if ($start->isSameDay($end)) {
                    $event['date_display'] = $start->translatedFormat('d F Y');
                } elseif ($start->format('Y') === $end->format('Y')) {
                    if ($start->format('F') === $end->format('F')) {
                        $event['date_display'] = $start->format('d') . '-' . $end->translatedFormat('d F Y');
                    } else {
                        $event['date_display'] = $start->translatedFormat('d F') . ' - ' . $end->translatedFormat('d F Y');
                    }
                } else {
                    $event['date_display'] = $start->translatedFormat('d F Y') . ' - ' . $end->translatedFormat('d F Y');
                }

                // Mapping status event
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
            }, $data['events']);

            $speakers = $data['speakers'];

            return view('member.index', compact('events', 'speakers'));
        }

        return back()->withErrors(['error' => 'Gagal mengambil data dashboard']);
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
