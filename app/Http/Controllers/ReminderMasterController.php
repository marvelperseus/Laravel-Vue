<?php

namespace App\Http\Controllers;

use App\Models\Eloquent\ReminderMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReminderMasterController extends Controller
{

    /**
     * The attributes that Validation Rules.
     *
     * @var array
     */
    private $validationRules = [
        'name' => ['required', 'string', 'max:191'],
        'timing' => ['required', 'integer'],
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
        return $user->clinic->reminderMasters;
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

        $reminderMaster = new ReminderMaster();
        $reminderMaster->name = $request->name;
        $reminderMaster->draft_flag = $request->draft_flag;
        $reminderMaster->timing = $request->timing;
//        $reminderMaster->time = Carbon::createFromTime($request->time, 0, 0);
        $reminderMaster->time = $request->time;
        $reminderMaster->title = $request->title;
        $reminderMaster->body = $request->body;
        $reminderMaster->clinic_id = $user->clinic->id;

        $reminderMaster->save();

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Eloquent\ReminderMaster  $reminderMaster
     * @return \Illuminate\Http\Response
     */
    public function show(ReminderMaster $reminderMaster)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Eloquent\ReminderMaster  $reminderMaster
     * @return \Illuminate\Http\Response
     */
    public function edit(ReminderMaster $reminderMaster)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Eloquent\ReminderMaster  $reminderMaster
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReminderMaster $reminderMaster)
    {
        $request->validate($this->validationRules);

        $reminderMaster->name = $request->name;
        $reminderMaster->draft_flag = $request->draft_flag;
        $reminderMaster->timing = $request->timing;
//        $reminderMaster->time = Carbon::createFromTime($request->time, 0, 0);
        $reminderMaster->time = $request->time;
        $reminderMaster->title = $request->title;
        $reminderMaster->body = $request->body;

        $reminderMaster->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Eloquent\ReminderMaster  $reminderMaster
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReminderMaster $reminderMaster)
    {
        $reminderMaster->delete();
        return response()->json();
    }
}
