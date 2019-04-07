<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ClinicHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicHolidayController extends Controller
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
        return $user->clinic->clinicHolidays;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mini()
    {
        $user = Auth::user();

        $holidays = array();
        foreach ($user->clinic->clinicHolidays as $holiday) {
            $holidays[$holiday->start] = $holiday->color;
        }

        return $holidays;
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
     * @param  \App\Models\Eloquent\ClinicHoliday  $clinicHoliday
     * @return \Illuminate\Http\Response
     */
    public function show(ClinicHoliday $clinicHoliday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ClinicHoliday  $clinicHoliday
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicHoliday $clinicHoliday)
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
        $user = Auth::user();
        $model = null;
        if (!empty($request->id)) {
            // Update
            $model = $user->clinic->clinicHolidays->find($request->id);

            if ($request->type === 'none') {
                // Delete
                $model->delete();
                return response()->json();
            }
        } else {
            // Create
            $model = new ClinicHoliday();
            $model->clinic_id = $user->clinic->id;
        }
        $model->start = $request->start;
        $model->type = $request->type;

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\ClinicHoliday  $clinicHoliday
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicHoliday $clinicHoliday)
    {
        //
    }
}
