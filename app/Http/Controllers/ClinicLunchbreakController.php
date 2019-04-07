<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ClinicLunchbreak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicLunchbreakController extends Controller
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
        if ($user->clinic->clinicLunchbreak->exists) {
            $base = array('base' => $user->clinic->clinicLunchbreak->base);
            $display_flag = array('display_flag' => $user->clinic->clinicLunchbreak->display_flag);
            $output = array_merge($base, $display_flag, $user->clinic->clinicLunchbreak->times);
        }

        return $output;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ClinicLunchbreak  $clinicLunchbreak
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicLunchbreak $clinicLunchbreak)
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
        if ($user->clinic->clinicLunchbreak->exists) {
            // Update
            $model = $user->clinic->clinicLunchbreak;
        } else {
            // Create
            $model = new ClinicLunchbreak();
            $model->clinic_id = $user->clinic->id;
        }

        $model->base = $request->base;
        $model->display_flag = $request->display_flag;
        $model->times = $request->except(['base', 'display_flag']);

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\ClinicLunchbreak  $clinicLunchbreak
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicLunchbreak $clinicLunchbreak)
    {
        //
    }
}
