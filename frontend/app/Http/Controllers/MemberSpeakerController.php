<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MemberSpeakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $response = Http::get('http://localhost:3000/api/member/member-speaker-index');

        if ($response->successful()) {
            $speakers = $response->json();
            return view('member.speaker.index', compact('speakers'));
        } else {
            return back()->withErrors(['error' => 'Gagal memuat data pembicara']);
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
