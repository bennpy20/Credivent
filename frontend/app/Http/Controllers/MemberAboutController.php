<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MemberAboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member.about.index');
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
