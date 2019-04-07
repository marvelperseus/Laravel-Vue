<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\RecallMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class RecallMasterController extends Controller
{

    /**
     * The attributes that Validation Rules.
     *
     * @var array
     */
    private $validationRules = [
        'name' => ['required', 'string', 'max:191'],
        'patient_status' => ['required'],
        'timing_number' => ['required', 'integer'],
        'timing_unit' => ['required'],
        'time' => ['required'],
        'title' => ['nullable', 'string', 'max:191'],
        'body' => ['required', 'string'],
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
        return $user->clinic->recallMasters;
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
        $user = Auth::user();

        $request->validate($this->validationRules);

        $recallMaster = new RecallMaster();
        $recallMaster->name = $request->name;
        $recallMaster->draft_flag = $request->draft_flag;
        $recallMaster->patient_status = $request->patient_status;
        $recallMaster->timing_number = $request->timing_number;
        $recallMaster->timing_unit = $request->timing_unit;
        $recallMaster->time = $request->time;
        $recallMaster->title = $request->title;
        $recallMaster->body = $request->body;
        $recallMaster->clinic_id = $user->clinic->id;

        $recallMaster->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\RecallMaster  $recallMaster
     * @return \Illuminate\Http\Response
     */
    public function show(RecallMaster $recallMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\RecallMaster  $recallMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(RecallMaster $recallMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eloquent\RecallMaster  $recallMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RecallMaster $recallMaster)
    {
        $request->validate($this->validationRules);

        $recallMaster->name = $request->name;
        $recallMaster->draft_flag = $request->draft_flag;
        $recallMaster->patient_status = $request->patient_status;
        $recallMaster->timing_number = $request->timing_number;
        $recallMaster->timing_unit = $request->timing_unit;
        $recallMaster->time = $request->time;
        $recallMaster->title = $request->title;
        $recallMaster->body = $request->body;

        $recallMaster->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\RecallMaster  $recallMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(RecallMaster $recallMaster)
    {

        Log::debug('call destory method.');
        Log::debug($recallMaster);

        $recallMaster->delete();
        return response()->json();
    }

}
