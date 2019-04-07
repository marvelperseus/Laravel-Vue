<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ClinicWebTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClinicWebTimeController extends Controller
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
        if ($user->clinic->clinicWebTime->exists) {
            $base = array('base' => $user->clinic->clinicWebTime->base);
            $output = array_merge($base, $user->clinic->clinicWebTime->times);
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ClinicWebTime  $clinicWebTime
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicWebTime $clinicWebTime)
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
        if ($user->clinic->clinicWebTime->exists) {
            // Update
            $model = $user->clinic->clinicWebTime;
        } else {
            // Create
            $model = new ClinicWebTime();
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
     * @param  \App\Models\Eloquent\ClinicWebTime  $clinicWebTime
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicWebTime $clinicWebTime)
    {
        //
    }
}
