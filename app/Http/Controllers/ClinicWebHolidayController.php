<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ClinicWebHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClinicWebHolidayController extends Controller
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
        return $user->clinic->clinicWebHolidays;
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
     * @param  \App\Models\Eloquent\ClinicWebHoliday  $clinicWebHoliday
     * @return \Illuminate\Http\Response
     */
    public function show(ClinicWebHoliday $clinicWebHoliday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ClinicWebHoliday  $clinicWebHoliday
     * @return \Illuminate\Http\Response
     */
    public function edit(ClinicWebHoliday $clinicWebHoliday)
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
            $model = $user->clinic->clinicWebHolidays->find($request->id);

            if ($request->type === 'none') {
                // Delete
                $model->delete();
                return response()->json();
            }
        } else {
            // Create
            $model = new ClinicWebHoliday();
            $model->clinic_id = $user->clinic->id;
        }
        $model->start = $request->start;
        $model->type = $request->type;

        $model->save();

        return response()->json();
    }

    /**
     * Copy from \App\Models\Eloquent\ClinicHoliday the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function copy()
    {
        $user = Auth::user();
        $clinicHolidays = $user->clinic->clinicHolidays;

        foreach ($clinicHolidays as $holiday) {
            $day = $holiday->start;
            $webHoliday = ClinicWebHoliday::where('clinic_id', $user->clinic->id)->where('start', $day)->first();

            // 重複レコードは削除
            if (!empty($webHoliday)) {
                $webHoliday->delete();
            }

            $copy = new ClinicWebHoliday();
            $copy->start = $holiday->start;
            $copy->type = $holiday->type;
            $copy->clinic_id = $user->clinic->id;

            $copy->save();
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\ClinicWebHoliday  $clinicWebHoliday
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClinicWebHoliday $clinicWebHoliday)
    {
        //
    }
}
