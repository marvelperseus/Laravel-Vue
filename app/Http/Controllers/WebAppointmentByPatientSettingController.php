<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\WebAppointmentByPatientSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebAppointmentByPatientSettingController extends Controller
{

    /**
     * The attributes that Validation Rules.
     *
     * @var array
     */
    private $validationRules = [
        'patient_id' => ['required', 'integer'],
        'treatment_id' => ['required', 'integer'],
        'time' => ['required', 'integer'],
    ];

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

        $result = DB::table('web_appointment_by_patient_settings')
            ->join('patients', 'web_appointment_by_patient_settings.patient_id', '=', 'patients.id')
            ->join('treatments', 'web_appointment_by_patient_settings.treatment_id', '=', 'treatments.id')
            ->select('patients.karte_number', 'patients.name', 'treatments.treatment', 'web_appointment_by_patient_settings.*')
            ->where('web_appointment_by_patient_settings.clinic_id', '=', $user->clinic->id)
            ->get();

        return $result;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function units()
    {
        $user = Auth::user();

        $unitNumber = $user->clinic->unit_number;
        $unitNames = explode(',', $user->clinic->appointmentTableSetting->unit_names);
        $unitItems = array();
        if (count($unitNames) >= $unitNumber) {
            $unitItems = array_slice($unitNames, 0, $unitNumber);
        } else {
            $unitItems = $unitNames;
            for ($i = count($unitNames) + 1; $i <= $unitNumber; $i++) {
                $unitItems[] = 'ユニット' . $i;
            }
        }

        return $unitItems;
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
        $request->validate($this->validationRules);

        $user = Auth::user();
        $webAppointmentByPatientSetting = new WebAppointmentByPatientSetting();
        $webAppointmentByPatientSetting->patient_id = $request->patient_id;
        $webAppointmentByPatientSetting->treatment_id = $request->treatment_id;
        $webAppointmentByPatientSetting->time = $request->time;
        $webAppointmentByPatientSetting->units = $request->units;
        $webAppointmentByPatientSetting->clinic_id = $user->clinic->id;

        $webAppointmentByPatientSetting->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentByPatientSetting $webAppointmentByPatientSetting
     * @return \Illuminate\Http\Response
     */
    public function show(WebAppointmentByPatientSetting $webAppointmentByPatientSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentByPatientSetting $webAppointmentByPatientSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(WebAppointmentByPatientSetting $webAppointmentByPatientSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Eloquent\WebAppointmentByPatientSetting $webAppointmentByPatientSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebAppointmentByPatientSetting $webAppointmentByPatientSetting)
    {
        $request->validate($this->validationRules);

        $webAppointmentByPatientSetting->patient_id = $request->patient_id;
        $webAppointmentByPatientSetting->treatment_id = $request->treatment_id;
        $webAppointmentByPatientSetting->time = $request->time;
        $webAppointmentByPatientSetting->units = $request->units;

        $webAppointmentByPatientSetting->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\WebAppointmentByPatientSetting $webAppointmentByPatientSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebAppointmentByPatientSetting $webAppointmentByPatientSetting)
    {
        $webAppointmentByPatientSetting->delete();
        return response()->json();
    }
}
