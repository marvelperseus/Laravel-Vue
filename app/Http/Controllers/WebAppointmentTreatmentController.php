<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\WebAppointmentTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAppointmentTreatmentController extends Controller
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
        $webAppointmentSetting = $user->clinic->webAppointmentTreatments;

        $result = array();
        foreach ($webAppointmentSetting as $item) {
            $treatment = $user->clinic->treatments->find($item->treatment_id);
            $result[] = array(
                'id' => $item->id,
                'treatment' => $treatment->treatment,
                'time' => $treatment->time,
                'color' => $treatment->color
            );
        }

        return $result;
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
        $request->validate([
            'treatment_id' => ['required'],
        ]);

        $user = Auth::user();

        $treatment = new WebAppointmentTreatment();
        $treatment->treatment_id = $request->treatment_id;
        $treatment->clinic_id = $user->clinic->id;

        $treatment->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentTreatment  $webAppointmentTreatment
     * @return \Illuminate\Http\Response
     */
    public function show(WebAppointmentTreatment $webAppointmentTreatment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\WebAppointmentTreatment  $webAppointmentTreatment
     * @return \Illuminate\Http\Response
     */
    public function edit(WebAppointmentTreatment $webAppointmentTreatment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eloquent\WebAppointmentTreatment  $webAppointmentTreatment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WebAppointmentTreatment $webAppointmentTreatment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\WebAppointmentTreatment  $webAppointmentTreatment
     * @return \Illuminate\Http\Response
     */
    public function destroy(WebAppointmentTreatment $webAppointmentTreatment)
    {
        $webAppointmentTreatment->delete();
        return response()->json();
    }
}
