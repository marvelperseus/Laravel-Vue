<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\Treatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
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
        $user = Auth::user();
        return $user->clinic->treatments;
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
        $user = Auth::user();

        $request->validate([
            'treatment' => ['required', 'string', 'max:191'],
            'time' => ['required', 'integer'],
            'color' => ['nullable', 'string', 'max:191'],
        ]);

        $treatment = new Treatment();
        $treatment->treatment = $request->treatment;
        $treatment->time = $request->time;
        $treatment->color = $request->color;
        $treatment->clinic_id = $user->clinic->id;

        $treatment->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function show(Treatment $treatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function edit(Treatment $treatment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Eloquent\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Treatment $treatment)
    {
        $request->validate([
            'treatment' => ['required', 'string', 'max:191'],
            'time' => ['required', 'integer'],
            'color' => ['nullable', 'string', 'max:191'],
        ]);

        $treatment->treatment = $request->treatment;
        $treatment->time = $request->time;
        $treatment->color = $request->color;

        $treatment->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\Treatment $treatment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return response()->json();
    }
}
