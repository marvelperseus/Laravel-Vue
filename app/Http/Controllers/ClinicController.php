<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        return $user->clinic;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\Clinic $clinic
     * @return \Illuminate\Http\Response
     */
    public function edit(Clinic $clinic)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'director' => ['required', 'string', 'max:191'],
            'tel' => ['required', 'string', 'max:191'],
            'zip' => ['nullable', 'string', 'max:191'],
            'url' => ['required', 'url'],
        ]);

        $user = Auth::user();
        $clinic = $user->clinic;

        $clinic->name = $request->name;
        $clinic->director = $request->director;
        $clinic->tel = $request->tel;
        $clinic->zip = $request->zip;
        $clinic->address = $request->address;
        $clinic->url = $request->url;

        $clinic->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\Clinic $clinic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clinic $clinic)
    {
        //
    }
}
