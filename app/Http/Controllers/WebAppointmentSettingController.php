<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\WebAppointmentSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppointmentSettingController extends Controller
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
        $webAppointmentSetting = $user->clinic->webAppointmentSetting;

        $unitNumber = $user->clinic->unit_number;
        $unitNames = explode(',', $user->clinic->appointmentTableSetting->unit_names);
        if (empty($user->clinic->appointmentTableSetting->unit_names)) {
            $unitNames = array();
        }

        $unitItems = array();
        if (count($unitNames) >= $unitNumber) {
            $unitItems = array_slice($unitNames, 0, $unitNumber);
        } else {
            $unitItems = $unitNames;
            for ($i = count($unitNames) + 1; $i <= $unitNumber; $i++) {
                $unitItems[] = 'ユニット' . $i;
            }
        }
        $webAppointmentSetting->unit_items = $unitItems;

        return $webAppointmentSetting;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentSetting $webAppointmentSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(WebAppointmentSetting $webAppointmentSetting)
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
//        $request->validate([]);

        $user = Auth::user();
        $model = null;
        if ($user->clinic->webAppointmentSetting->exists) {
            // Update
            $model = $user->clinic->webAppointmentSetting;
        } else {
            // Create
            $model = new WebAppointmentSetting();
            $model->clinic_id = $user->clinic->id;
        }

        $model->status = $request->status;
        $model->auto_regist = $request->auto_regist;
        $model->cancel_auto_regist = $request->cancel_auto_regist;
        $model->units = $request->units;
        $model->target = $request->target;
        $model->start_day = $request->start_day;
        $model->term = $request->term;
        $model->time_delimiter = $request->time_delimiter;
//        $model->day_limit_count = $request->day_limit_count;
        $model->day_limit_count = 10;
//        $model->max_limit_count = $request->max_limit_count;
        $model->max_limit_count = 10;
        $model->web_cancel_flag = $request->web_cancel_flag;
        $model->permit_cancel_day = $request->permit_cancel_day;

        $model->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\WebAppointmentSetting $webAppointmentSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebAppointmentSetting $webAppointmentSetting)
    {
        //
    }
}
