<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ClinicTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicTimeController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
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

        $output = null;
        if ($user->clinic->clinicTime->exists) {
            $base = array('base' => $user->clinic->clinicTime->base);
            $output = array_merge($base, $user->clinic->clinicTime->times);
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ClinicTime  $clinicTime
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicTime $clinicTime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'base.start' => ['required'],
            'base.end' => ['required', 'after:base.start'],
        ]);

        $user = Auth::user();
        $model = null;
        if ($user->clinic->clinicTime->exists) {
            // Update
            $model = $user->clinic->clinicTime;
        } else {
            // Create
            $model = new ClinicTime();
            $model->clinic_id = $user->clinic->id;
        }

        $model->base = $request->base;
        $model->times = $request->except('base');

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\ClinicTime  $clinicTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicTime $clinicTime)
    {
        //
    }
}
